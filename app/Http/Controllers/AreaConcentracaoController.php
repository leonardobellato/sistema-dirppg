<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
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
    public function getCursosByPrograma($idPrograma)
    {
        $cursos = Curso::where('programa_id', $programaId)->get();
        return response()->json($cursos);
    }

    // Método para salvar no banco
    public function store(Request $request)
    {
        AreaConcentracao::create([
            'tipo' => $request->input('tipo'),
            'id_programa' => $request->input('programa')
        ]);

        return redirect()->route('pos.areas-concentracao.index')->with('success', 'Curso criado com sucesso!');
    }

    // Método para remover um objeto
    public function destroy($id)
    {
        $curso = AreaConcentracao::where('id_curso', $id)->firstOrFail();
        $curso->delete();

        try {
            $curso->delete();
            return response()->json(['success' => true, 'message' => 'Curso removido com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
