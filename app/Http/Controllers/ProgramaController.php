<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;

class ProgramaController extends Controller
{
    // Método para listar objetos
    public function index()
    {
        $programas = Programa::all();

        return view('pos.programas.index', compact('programas'));
    }

    // Método para mostrar o formulário de criação
    public function create()
    {
        return view('pos.programas.adicionar');
    }

    // Método para salvar no banco
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100|unique:programas,nome'
        ]);

        Programa::create([
            'nome' => $request->input('nome')
        ]);

        return redirect()->route('pos.programas.index')->with('success', 'Programa criado com sucesso!');
    }

    // Mostrar formulário de edição
    public function edit($id)
    {
        $programa = Programa::where('id_programa', $id)->firstOrFail();
        return view('pos.programas.alterar', compact('programa'));
    }

    // Atualizar objeto
    public function update(Request $request, $id)
    {
        $programa = Programa::where('id_programa', $id)->firstOrFail();

        $request->validate([
            'nome' => 'required|string|max:100|unique:programas,nome,' . $programa->id_programa . ',id_programa'
        ]);

        $programa->update([
            'nome' => $request->input('nome')
        ]);

        return redirect()->route('pos.programas.index')
                         ->with('success', 'Programa atualizado com sucesso!');
    }

    // Método para remover um objeto
    public function destroy($id)
    {
        $programa = Programa::where('id_programa', $id)->firstOrFail();
        $programa->delete();

        try {
            $programa->delete();
            return response()->json(['success' => true, 'message' => 'Programa removido com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
