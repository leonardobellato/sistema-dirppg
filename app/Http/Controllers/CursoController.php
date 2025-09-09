<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Programa;

class CursoController extends Controller
{
    // Método para listar objetos
    public function index()
    {
        $cursos = Curso::with('programa')->get();

        return view('pos.cursos.index', compact('cursos'));
    }

    // Método para mostrar o formulário de criação
    public function create()
    {
        $programas = Programa::all();
        return view('pos.cursos.adicionar', ['programas' => $programas]);
    }

    // Método para salvar no banco
    public function store(Request $request)
    {
        Curso::create([
            'tipo' => $request->input('tipo'),
            'id_programa' => $request->input('programa')
        ]);

        return redirect()->route('pos.cursos.index')->with('success', 'Curso criado com sucesso!');
    }

    // Método para remover um objeto
    public function destroy($id)
    {
        $curso = Curso::where('id_curso', $id)->firstOrFail();
        $curso->delete();

        try {
            $curso->delete();
            return response()->json(['success' => true, 'message' => 'Curso removido com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
