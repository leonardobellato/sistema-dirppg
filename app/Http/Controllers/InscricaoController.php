<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Inscricao;
use App\Models\Edital;
use App\Models\Documento;
use App\Models\Auditoria;

class InscricaoController extends Controller
{

    // Método para listar todos os objetos
    public function listar()
    {
        $inscricoes = Inscricao::with('edital.curso.programa')->where('id_candidato', Auth::id())->get();

        return view('candidato.inscricoes.index', compact('inscricoes'));
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

        \DB::beginTransaction();

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
            $tipos = [
                'ficha_inscricao',
                'documento_identificacao',
                'cpf',
                'diploma',
                'curriculo',
                'historico',
                'documentacao',
                'dissertacao_mestrado',
                'projeto_pesquisa',
                'carta_aceite',
                'outro',
            ];

            foreach ($tipos as $tipo) {
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
                        'tipo' => $tipo,
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

            \DB::commit();

            return redirect()
                ->route('candidato.editais.index')
                ->with('success', 'Inscrição enviada com sucesso!');
        } catch (\Exception $e) {
            \DB::rollBack();

            Auditoria::create([
                'id_usuario' => Auth::id(),
                'tipo' => 'inscricao',
                'operacao' => 'salvar',
                'sucesso' => false,
                'detalhes' => $e->getMessage(),
                'ip' => $request->ip(),
                'navegador' => $request->header('User-Agent'),
            ]);

            return back()
                ->withErrors(['erro' => 'Ocorreu um erro ao enviar sua inscrição.'])
                ->withInput();
        }
    }
}
