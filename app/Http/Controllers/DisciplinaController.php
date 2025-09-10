<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\Disciplina;

class DisciplinaController extends Controller
{
    // Método para listar objetos
    public function index()
    {
        $disciplinas = Disciplina::with('curso.programa')->get();

        return view('pos.disciplinas-aluno-externo.index', compact('disciplinas'));
    }

    // Método para mostrar o formulário de criação
    public function create()
    {
        $programas = Programa::all();
        return view('pos.disciplinas-aluno-externo.adicionar', ['programas' => $programas]);
    }

    // Método para salvar no banco
    public function store(Request $request)
    {
        Disciplina::create([
            'tipo' => $request->input('tipo'),
            'id_programa' => $request->input('programa')
        ]);

        return redirect()->route('pos.disciplinas-aluno-externo.index')->with('success', 'Curso criado com sucesso!');
    }

    // Método para remover um objeto
    public function destroy($id)
    {
        $curso = Disciplina::where('id_curso', $id)->firstOrFail();

        try {
            $curso->delete();
            return response()->json(['success' => true, 'message' => 'Curso removido com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
