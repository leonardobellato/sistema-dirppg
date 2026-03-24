<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;
use Illuminate\Http\Request;
use App\Models\Inscricao;
use App\Models\Edital;
use App\Models\Documento;
use App\Models\Auditoria;

class InscricaoController extends Controller
{

    private $tiposDocumentos = [
        'ficha_inscricao' => 'Ficha de Inscrição',
        'documento_identificacao' => 'Documento de Identificação Oficial (RG ou CNH)',
        'cpf' => 'CPF',
        'diploma' => 'Diploma ou Declaração',
        'curriculo' => 'Currículo Lattes',
        'historico' => 'Histórico Escolar',
        'outro' => 'Outros',
        'documentacao' => 'Documentação Comprobatória',
        'projeto_pesquisa' => 'Projeto de Pesquisa',
        'dissertacao_mestrado' => 'Dissertação de Mestrado',
        'carta_aceite' => 'Carta de Aceite',
        'declaracao_vinculo' => 'Declaração de Vínculo',
        'dados_poscomp' => 'Dados do PosComp',
        'resumo_intencao' => 'Resumo de Intenção',
        'formulario_indicacao' => 'Formulário de Indicação',
    ];

    // Método para listar todos os objetos
    public function listarPorEdital($idEdital)
    {
        $inscricoes = Inscricao::with([
            'edital:id_edital',
            'candidato:id_usuario,nome',
            'candidato.candidato:id_usuario,cpf',
            'linhaPesquisa:id_linha_pesquisa,nome', 
            'sublinha:id_sublinha,nome',
            'disciplina:id_disciplina,nome'
        ])->where('id_edital', $idEdital)->get();

        $edital = Edital::with('curso')->findOrFail($idEdital, ['id_edital', 'id_curso']);

        return view('analise-inscricoes.listar', compact('inscricoes', 'edital'));
    }

    public function listarPeloCandidato()
    {
        $inscricoes = Inscricao::with([
            'edital.curso',
            'edital.curso.programa:id_programa,sigla', 
            'disciplina:id_disciplina,nome'
        ])->where('id_candidato', Auth::id())->get();

        return view('candidato.inscricoes.index', compact('inscricoes'));
    }

    public function analisar($id){
        $inscricao = Inscricao::with([
            'edital.curso',
            'documentos',
            'candidato:id_usuario,nome,email',
            'candidato.candidato',
            'avaliador:id_usuario,nome',
            ])->findOrFail($id);

        return view('analise-inscricoes.analisar', compact('inscricao'));
    }

    public function salvarAnalise(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $inscricao = Inscricao::with('documentos')->findOrFail($id);

            //Atualiza documentos
            foreach ($request->input('documentos', []) as $docData) {
                $documento = Documento::find($docData['id']);
                if ($documento) {
                    $documento->deferido = ($docData['status'] === 'deferir') ? 1 : 0;
                    $documento->motivo_indeferimento = $docData['status'] === 'indeferir'
                        ? $docData['motivo']
                        : null;
                    $documento->save();
                }
            }

            //Atualiza inscrição
            $inscricao->deferido = $request->input('inscricao_status') === 'deferir' ? 1 : 0;
            $inscricao->motivo_indeferimento = $request->input('comentario-geral');

            $inscricao->id_avaliador = Auth::id();
            $inscricao->save();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Análise salva com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            Auditoria::create([
                'id_usuario' => Auth::id(),
                'tipo' => 'analise',
                'operacao' => 'salvar',
                'sucesso' => false,
                'detalhes' => $e->getMessage(),
                'ip' => $request->ip(),
                'navegador' => $request->header('User-Agent'),
            ]);

            return back()->with('failure', 'Erro ao realizar análise. Contate suporte do site.');
        }
    }


    public function filtrarInscricoesPorEdital($idEdital)
    {
        return response()->json(Edital::findOrFail($idEdital)->inscricoes);
    }

    // Método para mostrar o formulário de criação
    public function criar($idEdital)
    {
        $edital = Edital::with(['curso.programa'])
            ->findOrFail($idEdital);

        // Monta o relacionamento condicional conforme o tipo do curso
        if ($edital->curso->tipo === 'Aluno Externo') {
            // Carrega as disciplinas visíveis
            $edital->load([
                'curso.disciplinas' => function ($query) {
                    $query->where('visivel', true);
                }
            ]);
        } elseif (in_array($edital->curso->tipo, ['Mestrado', 'Doutorado'])) {
            $edital->load('curso.areasConcentracao');
        }
        
        return view('candidato.editais.inscrever', compact('edital'));
    }

    // Método para salvar no banco
    public function salvarDoutorado(Request $request)
    {
        $request->validate([
            'ficha_inscricao' => 'required|file|mimes:pdf|max:5120',
            'documento_identificacao' => 'required|file|mimes:pdf|max:5120',
            'cpf' => 'required|file|mimes:pdf|max:5120',
            'diploma' => 'required|file|mimes:pdf|max:5120',
            'curriculo' => 'required|file|mimes:pdf|max:5120',
            'historico' => 'required|file|mimes:pdf|max:5120',
            'documentacao' => 'required|file|mimes:pdf|max:5120',
            'projeto_pesquisa' => 'required|file|mimes:pdf|max:5120',
            'dissertacao_mestrado' => 'nullable|file|mimes:pdf|max:15360',
            'carta_aceite' => 'nullable|file|mimes:pdf|max:5120',
            'outro' => 'nullable|file|mimes:pdf|max:5120',
            'aceito_termos' => 'accepted',
            'linha-pesquisa' => 'required',
            'sublinha' => 'nullable'
        ]);

        DB::beginTransaction();

        try {
            // Criar a inscrição
            $inscricao = Inscricao::create([
                'id_candidato' => Auth::id(),
                'id_edital' => $request->id_edital,
                'comentarios' => $request->comentarios,
                'id_linha_pesquisa' => $request->input('linha-pesquisa'),
                'id_sublinha' => $request->input('sublinha')
            ]);

            // Salvar os documentos enviados
            foreach ($this->tiposDocumentos as $tipo => $descricao) {
                if ($request->hasFile($tipo)) {
                    $arquivo = $request->file($tipo);
                    $caminho = $arquivo->storeAs(
                        "inscricoes/{$inscricao->id_inscricao}", 
                        $tipo . '.' . $arquivo->getClientOriginalExtension(),
                        'public'
                    );

                    Documento::create([
                        'id_inscricao' => $inscricao->id_inscricao,
                        'caminho_servidor' => $caminho,
                        'tipo' => $descricao,
                    ]);
                }
            }

            // Registrar auditoria
            Auditoria::create([
                'id_usuario' => Auth::id(),
                'tipo' => 'inscricao',
                'operacao' => 'salvar',
                'sucesso' => true,
                'ip' => $request->ip(),
                'navegador' => $request->header('User-Agent'),
            ]);

            DB::commit();

            return redirect()
                ->route('candidato.inscricoes.index')
                ->with('success', 'Inscrição enviada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            Auditoria::create([
                'id_usuario' => Auth::id(),
                'tipo' => 'inscricao',
                'operacao' => 'salvar',
                'sucesso' => false,
                'detalhes' => $e->getMessage(),
                'ip' => $request->ip(),
                'navegador' => $request->header('User-Agent'),
            ]);

            return back()->with('failure', 'Erro ao realizar inscrição. Contate suporte do site.');
        }
    }

    public function salvarMestrado(Request $request)
    {
        $request->validate([
            'ficha_inscricao' => 'required|file|mimes:pdf|max:5120',
            'documento_identificacao' => 'required|file|mimes:pdf|max:5120',
            'cpf' => 'required|file|mimes:pdf|max:5120',
            'diploma' => 'required|file|mimes:pdf|max:5120',
            'curriculo' => 'required|file|mimes:pdf|max:5120',
            'historico' => 'required|file|mimes:pdf|max:5120',
            'projeto_pesquisa' => 'nullable|file|mimes:pdf|max:5120',
            'declaracao_vinculo' => 'nullable|file|mimes:pdf|max:5120',
            'dados_poscomp' => 'nullable|file|mimes:pdf|max:5120',
            'resumo_intencao' => 'nullable|file|mimes:pdf|max:5120',
            'formulario_indicacao' => 'nullable|file|mimes:pdf|max:5120',
            'outro' => 'nullable|file|mimes:pdf|max:5120',
            'aceito_termos' => 'accepted',
            'linha-pesquisa' => 'required',
            'sublinha' => 'nullable'
        ]);

        DB::beginTransaction();

        try {
            // Criar a inscrição
            $inscricao = Inscricao::create([
                'id_candidato' => Auth::id(),
                'id_edital' => $request->id_edital,
                'comentarios' => $request->comentarios,
                'id_linha_pesquisa' => $request->input('linha-pesquisa'),
                'id_sublinha' => $request->input('sublinha')
            ]);

            // Salvar os documentos enviados
            foreach ($this->tiposDocumentos as $tipo => $descricao) {
                if ($request->hasFile($tipo)) {
                    $arquivo = $request->file($tipo);
                    $caminho = $arquivo->storeAs(
                        "inscricoes/{$inscricao->id_inscricao}", 
                        $tipo . '.' . $arquivo->getClientOriginalExtension(),
                        'public'
                    );

                    Documento::create([
                        'id_inscricao' => $inscricao->id_inscricao,
                        'caminho_servidor' => $caminho,
                        'tipo' => $descricao,
                    ]);
                }
            }

            // Registrar auditoria
            Auditoria::create([
                'id_usuario' => Auth::id(),
                'tipo' => 'inscricao',
                'operacao' => 'salvar',
                'sucesso' => true,
                'ip' => $request->ip(),
                'navegador' => $request->header('User-Agent'),
            ]);

            DB::commit();

            return redirect()
                ->route('candidato.inscricoes.index')
                ->with('success', 'Inscrição enviada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            Auditoria::create([
                'id_usuario' => Auth::id(),
                'tipo' => 'inscricao',
                'operacao' => 'salvar',
                'sucesso' => false,
                'detalhes' => $e->getMessage(),
                'ip' => $request->ip(),
                'navegador' => $request->header('User-Agent'),
            ]);

            return back()->with('failure', 'Erro ao realizar inscrição. Contate suporte do site.');
        }
    }

    public function salvarPapos(Request $request)
    {
        $request->validate([
            'documento_identificacao' => 'required|file|mimes:pdf|max:5120',
            'cpf' => 'required|file|mimes:pdf|max:5120',
            'curriculo' => 'required|file|mimes:pdf|max:5120',
            'projeto_pesquisa' => 'required|file|mimes:pdf|max:5120',
            'carta_aceite' => 'nullable|file|mimes:pdf|max:5120',
            'outro' => 'nullable|file|mimes:pdf|max:5120',
            'aceito_termos' => 'accepted',
        ]);

        DB::beginTransaction();

        try {
            // Criar a inscrição
            $inscricao = Inscricao::create([
                'id_candidato' => Auth::id(),
                'id_edital' => $request->id_edital,
                'comentarios' => $request->comentarios,
            ]);

            // Salvar os documentos enviados
            foreach ($this->tiposDocumentos as $tipo => $descricao) {
                if ($request->hasFile($tipo)) {
                    $arquivo = $request->file($tipo);
                    $caminho = $arquivo->storeAs(
                        "inscricoes/{$inscricao->id_inscricao}", 
                        $tipo . '.' . $arquivo->getClientOriginalExtension(),
                        'public'
                    );

                    Documento::create([
                        'id_inscricao' => $inscricao->id_inscricao,
                        'caminho_servidor' => $caminho,
                        'tipo' => $descricao,
                    ]);
                }
            }

            // Registrar auditoria
            Auditoria::create([
                'id_usuario' => Auth::id(),
                'tipo' => 'inscricao',
                'operacao' => 'salvar',
                'sucesso' => true,
                'ip' => $request->ip(),
                'navegador' => $request->header('User-Agent'),
            ]);

            DB::commit();

            return redirect()
                ->route('candidato.inscricoes.index')
                ->with('success', 'Inscrição enviada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            Auditoria::create([
                'id_usuario' => Auth::id(),
                'tipo' => 'inscricao',
                'operacao' => 'salvar',
                'sucesso' => false,
                'detalhes' => $e->getMessage(),
                'ip' => $request->ip(),
                'navegador' => $request->header('User-Agent'),
            ]);

            return back()->with('failure', 'Erro ao realizar inscrição. Contate suporte do site.');
        }
    }

    public function salvarAlunoExterno(Request $request)
    {
        $request->validate([
            'documento_identificacao' => 'required|file|mimes:pdf|max:5120',
            'cpf' => 'required|file|mimes:pdf|max:5120',
            'diploma' => 'required|file|mimes:pdf|max:5120',
            'curriculo' => 'required|file|mimes:pdf|max:5120',
            'historico' => 'required|file|mimes:pdf|max:5120',
            'outro' => 'nullable|file|mimes:pdf|max:5120',
            'disciplinas' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Verifica se há disciplinas selecionadas
            $disciplinasSelecionadas = $request->input('disciplinas', []);

            if (empty($disciplinasSelecionadas)) {
                return back()->with('failure', 'Selecione ao menos uma disciplina.');
            }

            // Para cada disciplina selecionada, criar uma inscrição separada
            foreach ($disciplinasSelecionadas as $idDisciplina) {
                // Criar nova inscrição
                $inscricao = Inscricao::create([
                    'id_candidato' => Auth::id(),
                    'id_edital'    => $request->id_edital,
                    'id_disciplina'=> $idDisciplina,
                    'comentarios'  => $request->comentarios,
                ]);

                // Salvar os documentos enviados (duplicando os mesmos arquivos para cada inscrição)
                foreach ($this->tiposDocumentos as $tipo => $descricao) {
                    if ($request->hasFile($tipo)) {
                        $arquivo = $request->file($tipo);
                        $caminho = $arquivo->storeAs(
                            "inscricoes/{$inscricao->id_inscricao}",
                            $tipo . '.' . $arquivo->getClientOriginalExtension(),
                            'public'
                        );

                        Documento::create([
                            'id_inscricao'     => $inscricao->id_inscricao,
                            'caminho_servidor' => $caminho,
                            'tipo'             => $descricao,
                        ]);
                    }
                }
            }


            // Registrar auditoria
            Auditoria::create([
                'id_usuario' => Auth::id(),
                'tipo' => 'inscricao',
                'operacao' => 'salvar',
                'sucesso' => true,
                'ip' => $request->ip(),
                'navegador' => $request->header('User-Agent'),
            ]);

            DB::commit();

            return redirect()
                ->route('candidato.inscricoes.index')
                ->with('success', 'Inscrição enviada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            Auditoria::create([
                'id_usuario' => Auth::id(),
                'tipo' => 'inscricao',
                'operacao' => 'salvar',
                'sucesso' => false,
                'detalhes' => $e->getMessage(),
                'ip' => $request->ip(),
                'navegador' => $request->header('User-Agent'),
            ]);

            return back()->with('failure', 'Erro ao realizar inscrição. Contate suporte do site.');
        }
    }

    public function visualizar($id){
        $inscricao = Inscricao::with([
            'edital:id_edital,id_curso,nome',
            'edital.curso:id_curso,id_programa,tipo',
            'edital.curso.programa:id_programa,nome',
            'edital.fasesEdital',
            'documentos',
            'linhaPesquisa:id_linha_pesquisa,nome', 
            'sublinha:id_sublinha,nome',
            'disciplina:id_disciplina,nome'
        ])->findOrFail($id);

        $faseAtual = $inscricao->edital->faseAtual();

        return view('candidato.inscricoes.visualizar', compact('inscricao', 'faseAtual'));
    }

    public function recurso(Request $request, $id)
    {
        try{
            $inscricao = Inscricao::with('documentos')->findOrFail($id);

            // Garante que existe o array de documentos vindos do form
            if (!$request->has('documentos')) {
                return back()->with('failure', 'Nenhum documento enviado.');
            }

            DB::beginTransaction();

            foreach ($request->documentos as $docData) {
                if (isset($docData['arquivo'])) {
                    $documento = Documento::findOrFail($docData['id']);
                    $arquivo = $docData['arquivo'];

                    // Remove o arquivo antigo, se existir
                    if (Storage::disk('public')->exists($documento->caminho_servidor)) {
                        Storage::disk('public')->delete($documento->caminho_servidor);
                    }

                    // Gera novo caminho e nome do arquivo
                    $novoCaminho = $arquivo->storeAs(
                        "inscricoes/{$inscricao->id_inscricao}",
                        "{$documento->tipo}_v" . ($documento->versao + 1) . '.' . $arquivo->getClientOriginalExtension(),
                        'public'
                    );

                    // Atualiza o documento
                    $documento->update([
                        'caminho_servidor' => $novoCaminho,
                        'versao' => $documento->versao + 1,
                        'deferido' => null, // volta para pendente
                        'motivo_indeferimento' => null
                    ]);
                }
            }

            // Marca a inscrição como pendente novamente
            $inscricao->update([
                'deferido' => null,
                'motivo_indeferimento' => null,
            ]);

            DB::commit();

            return redirect()
                ->route('candidato.inscricoes.visualizar', $inscricao->id_inscricao)
                ->with('success', 'Recurso enviado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            Auditoria::create([
                'id_usuario' => Auth::id(),
                'tipo' => 'recurso',
                'operacao' => 'salvar',
                'sucesso' => false,
                'detalhes' => $e->getMessage(),
                'ip' => $request->ip(),
                'navegador' => $request->header('User-Agent'),
            ]);

            return back()->with('failure', 'Erro ao realizar recurso. Contate suporte do site.');
        }
    }

    public function comunicar($id){
        $edital = Edital::findOrFail($id, ['id_edital', 'nome']);
        $emails = Inscricao::where('id_edital', $id)
            ->with('candidato:id_usuario,nome,email')
            ->get()
            ->pluck('candidato.email')
            ->unique()
            ->values();

        return view('analise-inscricoes.comunicar', [
            'edital' => $edital,
            'emails' => $emails
        ]);
    }

    public function comunicacaoGeral(Request $request, $idEdital)
    {
        try{
            $mensagem = Str::markdown($request->input('mensagem'));
            $emails = Inscricao::where('id_edital', $idEdital)
                ->with('candidato:id_usuario,nome,email')
                ->get()
                ->pluck('candidato.email')
                ->unique()
                ->values()
                ->toArray();
            
            Mail::to($emails)->queue(new Email("DIRPPG-PG: Comunicação Geral", $mensagem));

            return redirect()
                ->route(Auth::user()->tipo . '.analise-inscricoes.comunicar', $idEdital)
                ->with('success', 'E-mails enviados com sucesso!');

        } catch (\Exception $e) {
            Auditoria::create([
                'id_usuario' => Auth::id(),
                'tipo' => 'comunicacao',
                'operacao' => 'enviar',
                'sucesso' => false,
                'detalhes' => $e->getMessage(),
                'ip' => $request->ip(),
                'navegador' => $request->header('User-Agent'),
            ]);

            return back()->with('failure', 'Erro ao realizar comunicação. Contate suporte do site.');
        }
    }
}
