<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\Disciplina;
use App\Models\Curso;

class DisciplinaController extends Controller
{
    // Método para listar objetos
    public function index()
    {
        $disciplinas = Disciplina::with('curso.programa')->get();

        return view('admin.pos.disciplinas-aluno-externo.index', compact('disciplinas'));
    }

    // Método para mostrar o formulário de criação
    public function create()
    {
        $programas = Programa::all();
        return view('admin.pos.disciplinas-aluno-externo.adicionar', ['programas' => $programas]);
    }

    // rota para AJAX
    public function getDisciplinasByCurso($idCurso)
    {
        return response()->json(Curso::findOrFail($idCurso)->disciplinas);
    }

    // Método para salvar no banco
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:200'
        ]);

        Disciplina::create([
            'nome' => $request->input('nome'),
            'id_curso' => $request->input('curso')
        ]);

        return redirect()->route('pos.disciplinas-aluno-externo.index')->with('success', 'Disciplina criada com sucesso!');
    }

    // Mostrar formulário de edição
    public function edit($id)
    {
        $disciplina = Disciplina::with('curso.programa')->findOrFail($id);
        return view('admin.pos.disciplinas-aluno-externo.alterar', compact('disciplina'));
    }

    // Atualizar objeto
    public function update(Request $request, $id)
    {
        $disciplina = Disciplina::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:200'
        ]);

        $disciplina->update([
            'nome' => $request->input('nome'),
            'inativo' => $request->has('ativo') ? 0 : 1
        ]);

        return redirect()->route('pos.disciplinas-aluno-externo.index')
                         ->with('success', 'Disciplina atualizada com sucesso!');
    }

    // Método para remover um objeto
    public function destroy($id)
    {
        $disciplina = Disciplina::findOrFail($id);

        try {
            $disciplina->delete();
            return response()->json(['success' => true, 'message' => 'Disciplina removida com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
