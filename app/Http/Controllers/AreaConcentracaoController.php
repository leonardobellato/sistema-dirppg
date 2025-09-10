<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\AreaConcentracao;

class AreaConcentracaoController extends Controller
{
    // Método para listar objetos
    public function index()
    {
        $areas_concentracao = AreaConcentracao::with('curso.programa')->get();

        return view('pos.areas-concentracao.index', compact('areas_concentracao'));
    }

    // Método para mostrar o formulário de criação
    public function create()
    {
        $programas = Programa::all();
        return view('pos.areas-concentracao.adicionar', compact('programas'));
    }

    // rota para AJAX
    public function getAreasByCurso($idCurso)
    {
        $areas_concentracao = AreaConcentracao::where('id_curso', $idCurso)->get();
        return response()->json($areas_concentracao);
    }

    // Método para salvar no banco
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:150'
        ]);

        AreaConcentracao::create([
            'nome' => $request->input('nome'),
            'id_curso' => $request->input('curso')
        ]);

        return redirect()->route('pos.areas-concentracao.index')->with('success', 'Área de concentração criada com sucesso!');
    }

    // Mostrar formulário de edição
    public function edit($id)
    {
        $area_concentracao = AreaConcentracao::with('curso.programa')->where('id_area_concentracao', $id)->firstOrFail();
        return view('pos.areas-concentracao.alterar', compact('area_concentracao'));
    }

    // Atualizar objeto
    public function update(Request $request, $id)
    {
        $area_concentracao = AreaConcentracao::where('id_area_concentracao', $id)->firstOrFail();

        $request->validate([
            'nome' => 'required|string|max:150'
        ]);

        $area_concentracao->update([
            'nome' => $request->input('nome'),
            'inativo' => $request->has('ativo') ? 0 : 1
        ]);

        return redirect()->route('pos.areas-concentracao.index')
                         ->with('success', 'Área de concentração atualizada com sucesso!');
    }

    // Método para remover um objeto
    public function destroy($id)
    {
        $area_concentracao = AreaConcentracao::where('id_area_concentracao', $id)->firstOrFail();

        try {
            $area_concentracao->delete();
            return response()->json(['success' => true, 'message' => 'Área de concentração removida com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
