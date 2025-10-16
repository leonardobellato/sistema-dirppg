<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\Edital;
use App\Models\FaseEdital;

class EditalController extends Controller
{
    // Método para listar objetos
    public function index()
    {
        $editais = Edital::with(['curso.programa', 'fasesEdital'])->get();

        return view('admin.editais.index', ['editais' => $editais]);
    }

    // Método que retorna apenas os vigentes para o candidato
    public function listVigentes(Request $request)
    {
        $editais = Edital::with(['curso.programa'])->where('vigente', true)->orderBy('data_publicacao', 'desc')->get();

        // Verifica quem está acessando
        if ($request->user()->eCandidato()) {
            return view('candidato.editais.index', compact('editais'));
        } else {
            return view('secretario.editais.index', compact('editais'));
        }
    }

    // Detalhar objeto específico
    public function lookup($id)
    {
        $edital = Edital::with(['curso.programa', 'fasesEdital'])->where('id_edital', $id)->firstOrFail();

        // separar por tipo + ordem para facilitar no Blade
        $fases = $edital->fasesEdital->groupBy(function($fase) {
            return $fase->tipo.'_'.$fase->ordem;
        });

        return view('candidato.editais.details', compact('edital', 'fases'));
    }

    // Método para mostrar o formulário de criação
    public function create()
    {
        $programas = Programa::all();
        return view('admin.editais.adicionar', compact('programas'));
    }

    // rota para AJAX
    public function getEditaisByCurso($idCurso)
    {
        $editais = Edital::where('id_curso', $idCurso)->get();
        return response()->json($editais);
    }

    // Método para salvar no banco
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:200',
            'link' => 'nullable|string|max:200'
        ]);

        $edital = Edital::create([
            'nome' => $request->input('nome'),
            'id_curso' => $request->input('curso'),
            'link' => $request->input('link')
        ]);

        // criando fases
        $fases = [
            [
                'tipo' => 'inscricao',
                'ordem' => 1,
                'data_inicio' => $request->input('input-dt-insc-inicio'),
                'data_fim' => $request->input('input-dt-insc-fim'),
            ],
            [
                'tipo' => 'resultadoInsc',
                'ordem' => 1,
                'data_inicio' => $request->input('input-dt-div-insc'),
                'data_fim' => $request->input('input-dt-div-insc'),
            ],
            [
                'tipo' => 'recurso',
                'ordem' => 1,
                'data_inicio' => $request->input('input-dt-1rec-inicio'),
                'data_fim' => $request->input('input-dt-1rec-fim'),
            ],
            [
                'tipo' => 'resultadoRec',
                'ordem' => 1,
                'data_inicio' => $request->input('input-dt-div-1rec'),
                'data_fim' => $request->input('input-dt-div-1rec'),
            ],
        ];

        // 2º recurso (opcional)
        if ($request->has('input-enable-2rec')) {
            $fases[] = [
                'tipo' => 'recurso',
                'ordem' => 2,
                'data_inicio' => $request->input('input-dt-2rec-inicio'),
                'data_fim' => $request->input('input-dt-2rec-fim'),
            ];
            $fases[] = [
                'tipo' => 'resultadoRec',
                'ordem' => 2,
                'data_inicio' => $request->input('input-dt-div-2rec'),
                'data_fim' => $request->input('input-dt-div-2rec'),
            ];
        }

        foreach ($fases as $fase) {
            FaseEdital::create([
                'id_edital' => $edital->id_edital,
                'tipo' => $fase['tipo'],
                'ordem' => $fase['ordem'],
                'data_inicio' => $fase['data_inicio'],
                'data_fim' => $fase['data_fim'],
            ]);
        }

        return redirect()->route('admin.editais.index')
            ->with('success', 'Edital cadastrado com sucesso!');
    }

    // Mostrar formulário de edição
    public function edit($id)
    {
        $edital = Edital::with(['curso.programa', 'fasesEdital'])->where('id_edital', $id)->firstOrFail();

        // separar por tipo + ordem para facilitar no Blade
        $fases = $edital->fasesEdital->groupBy(function($fase) {
            return $fase->tipo.'_'.$fase->ordem;
        });

        return view('admin.editais.alterar', compact('edital', 'fases'));
    }

    // Atualizar objeto
    public function update(Request $request, $id)
    {
        $edital = Edital::where('id_edital', $id)->firstOrFail();

        $request->validate([
            'nome' => 'required|string|max:200',
            'link' => 'nullable|string|max:200'
        ]);

        // Atualizar dados do edital
        $edital->update([
            'nome' => $request->input('nome'),
            'link' => $request->input('link'),
            'vigente' => $request->has('vigente') ? 1 : 0
        ]);

        // Atualizar/criar fases
        $fases = [
            [
                'tipo' => 'inscricao',
                'ordem' => 1,
                'data_inicio' => $request->input('input-dt-insc-inicio'),
                'data_fim' => $request->input('input-dt-insc-fim'),
            ],
            [
                'tipo' => 'resultadoInsc',
                'ordem' => 1,
                'data_inicio' => $request->input('input-dt-div-insc'),
                'data_fim' => $request->input('input-dt-div-insc'),
            ],
            [
                'tipo' => 'recurso',
                'ordem' => 1,
                'data_inicio' => $request->input('input-dt-1rec-inicio'),
                'data_fim' => $request->input('input-dt-1rec-fim'),
            ],
            [
                'tipo' => 'resultadoRec',
                'ordem' => 1,
                'data_inicio' => $request->input('input-dt-div-1rec'),
                'data_fim' => $request->input('input-dt-div-1rec'),
            ],
        ];

        // 2º recurso (opcional)
        if ($request->has('input-enable-2rec')) {
            $fases[] = [
                'tipo' => 'recurso',
                'ordem' => 2,
                'data_inicio' => $request->input('input-dt-2rec-inicio'),
                'data_fim' => $request->input('input-dt-2rec-fim'),
            ];
            $fases[] = [
                'tipo' => 'resultadoRec',
                'ordem' => 2,
                'data_inicio' => $request->input('input-dt-div-2rec'),
                'data_fim' => $request->input('input-dt-div-2rec'),
            ];
        }

        // Salvar fases: atualiza se existe, cria se não
        foreach ($fases as $fase) {
            FaseEdital::updateOrCreate(
                [
                    'id_edital' => $edital->id_edital,
                    'tipo' => $fase['tipo'],
                    'ordem' => $fase['ordem'],
                ],
                [
                    'data_inicio' => $fase['data_inicio'],
                    'data_fim' => $fase['data_fim'],
                ]
            );
        }

        // Excluir 2 fases se foram desmarcadas
        $tiposOrdensAtuais = collect($fases)->map(function($fase) {
            return $fase['tipo'].'_'.$fase['ordem'];
        })->toArray();
        FaseEdital::where('id_edital', $edital->id_edital)
            ->whereNotIn(\DB::raw("CONCAT(tipo, '_', ordem)"), $tiposOrdensAtuais)
            ->delete();


        return redirect()->route('admin.editais.index')
                        ->with('success', 'Edital atualizado com sucesso!');
    }


    // Método para remover um objeto
    public function destroy($id)
    {
        $edital = Edital::where('id_edital', $id)->firstOrFail();

        try {
            $edital->delete();
            return response()->json(['success' => true, 'message' => 'Edital removido com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
