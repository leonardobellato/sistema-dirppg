<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\AreaConcentracao;
use App\Models\Curso;

class AreaConcentracaoController extends Controller
{
    // Método para listar todos os objetos
    public function listar()
    {
        $areas_concentracao = AreaConcentracao::with('curso.programa')->get();

        return view('admin.pos.areas-concentracao.index', compact('areas_concentracao'));
    }

    // Método para mostrar o formulário de criação
    public function criar()
    {
        // Buscar todos os programas para popular o select
        $programas = Programa::all();

        return view('admin.pos.areas-concentracao.adicionar', compact('programas'));
    }

    // Rota para AJAX
    public function filtrarAreasPorCurso($idCurso)
    {
        return response()->json( Curso::findOrFail($idCurso)->areasConcentracao );
    }

    // Método para salvar no banco
    public function salvar(Request $request)
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
    public function alterar($id)
    {
        $area_concentracao = AreaConcentracao::with('curso.programa')->findOrFail($id);

        return view('admin.pos.areas-concentracao.alterar', compact('area_concentracao'));
    }

    public function atualizar(Request $request, $id)
    {
        $area_concentracao = AreaConcentracao::findOrFail($id);

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

    public function excluir($id)
    {
        $area_concentracao = AreaConcentracao::findOrFail($id);

        try {
            $area_concentracao->delete();
            return response()->json(['success' => true, 'message' => 'Área de concentração removida com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
