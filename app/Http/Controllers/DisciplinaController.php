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
        $disciplina = Disciplina::with('curso.programa')->where('id_disciplina', $id)->firstOrFail();
        return view('pos.disciplinas-aluno-externo.alterar', compact('disciplina'));
    }

    // Atualizar objeto
    public function update(Request $request, $id)
    {
        $disciplina = Disciplina::where('id_disciplina', $id)->firstOrFail();

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
        $disciplina = Disciplina::where('id_disciplina', $id)->firstOrFail();

        try {
            $disciplina->delete();
            return response()->json(['success' => true, 'message' => 'Disciplina removida com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
