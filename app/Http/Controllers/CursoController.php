<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Programa;

class CursoController extends Controller
{
    // Método para listar todos os objetos
    public function listar()
    {
        $cursos = Curso::with('programa')->get();

        return view('admin.pos.cursos.index', compact('cursos'));
    }

    // Método para mostrar o formulário de criação
    public function criar()
    {
        // Buscar todos os programas para popular o select
        $programas = Programa::all();

        return view('admin.pos.cursos.adicionar', ['programas' => $programas]);
    }

    // Rota para AJAX
    public function filtrarCursosPorPrograma($idPrograma)
    {
        return response()->json(Programa::findOrFail($idPrograma)->cursos);
    }

    // Método para salvar no banco
    public function salvar(Request $request)
    {
        Curso::create([
            'tipo' => $request->input('tipo'),
            'id_programa' => $request->input('programa')
        ]);

        return redirect()->route('pos.cursos.index')->with('success', 'Curso criado com sucesso!');
    }

    public function excluir($id)
    {
        $curso = Curso::findOrFail($id);

        try {
            $curso->delete();
            return response()->json(['success' => true, 'message' => 'Curso removido com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
