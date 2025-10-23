<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;

class ProgramaController extends Controller
{
    // Método para listar todos os objetos
    public function listar()
    {
        $programas = Programa::all();

        return view('admin.pos.programas.index', compact('programas'));
    }

    // Método para mostrar o formulário de criação
    public function criar()
    {
        return view('admin.pos.programas.adicionar');
    }

    // Método para salvar no banco
    public function salvar(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100|unique:programas,nome',
            'sigla' => 'required|string|max:10|unique:programas,sigla'
        ]);

        Programa::create([
            'nome' => $request->input('nome'),
            'sigla' => $request->input('sigla')
        ]);

        return redirect()->route('pos.programas.index')->with('success', 'Programa criado com sucesso!');
    }

    // Mostrar formulário de edição
    public function alterar($id)
    {
        $programa = Programa::findOrFail($id);
        
        return view('admin.pos.programas.alterar', compact('programa'));
    }

    public function atualizar(Request $request, $id)
    {
        $programa = Programa::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:100|unique:programas,nome,' . $programa->id_programa . ',id_programa',
            'sigla' => 'required|string|max:10|unique:programas,sigla,' . $programa->id_programa . ',id_programa'
        ]);

        $programa->update([
            'nome' => $request->input('nome'),
            'sigla' => $request->input('sigla')
        ]);

        return redirect()->route('pos.programas.index')
                         ->with('success', 'Programa atualizado com sucesso!');
    }

    public function excluir($id)
    {
        $programa = Programa::findOrFail($id);

        try {
            $programa->delete();
            return response()->json(['success' => true, 'message' => 'Programa removido com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
