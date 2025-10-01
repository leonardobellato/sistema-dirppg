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
    public function listVigentes()
    {
        $editais = Edital::with(['curso.programa'])->where('vigente', true)->get();

        return view('candidato.editais.index', ['editais' => $editais]);
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
                'tipo' => 'recurso',
                'ordem' => 1,
                'data_inicio' => $request->input('input-dt-1rec-inicio'),
                'data_fim' => $request->input('input-dt-1rec-fim'),
            ],
            [
                'tipo' => 'homologacao',
                'ordem' => 1,
                'data_inicio' => $request->input('input-dt-1hom-inicio'),
                'data_fim' => $request->input('input-dt-1hom-fim'),
            ],
        ];

        // 2º recurso (opcional)
        if ($request->filled('input-dt-2rec-inicio') && $request->filled('input-dt-2rec-fim')) {
            $fases[] = [
                'tipo' => 'recurso',
                'ordem' => 2,
                'data_inicio' => $request->input('input-dt-2rec-inicio'),
                'data_fim' => $request->input('input-dt-2rec-fim'),
            ];
        }

        if ($request->filled('input-dt-2hom-inicio') && $request->filled('input-dt-2hom-fim')) {
            $fases[] = [
                'tipo' => 'homologacao',
                'ordem' => 2,
                'data_inicio' => $request->input('input-dt-2hom-inicio'), 
                'data_fim' => $request->input('input-dt-2hom-fim'),
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
                'tipo' => 'recurso',
                'ordem' => 1,
                'data_inicio' => $request->input('input-dt-1rec-inicio'),
                'data_fim' => $request->input('input-dt-1rec-fim'),
            ],
            [
                'tipo' => 'homologacao',
                'ordem' => 1,
                'data_inicio' => $request->input('input-dt-1hom-inicio'),
                'data_fim' => $request->input('input-dt-1hom-fim'),
            ],
        ];

        // 2º recurso (opcional)
        if ($request->filled('input-dt-2rec-inicio') && $request->filled('input-dt-2rec-fim')) {
            $fases[] = [
                'tipo' => 'recurso',
                'ordem' => 2,
                'data_inicio' => $request->input('input-dt-2rec-inicio'),
                'data_fim' => $request->input('input-dt-2rec-fim'),
            ];
        }

        // 2ª homologação (opcional)
        if ($request->filled('input-dt-2hom-inicio') && $request->filled('input-dt-2hom-fim')) {
            $fases[] = [
                'tipo' => 'homologacao',
                'ordem' => 2,
                'data_inicio' => $request->input('input-dt-2hom-inicio'),
                'data_fim' => $request->input('input-dt-2hom-fim'),
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
