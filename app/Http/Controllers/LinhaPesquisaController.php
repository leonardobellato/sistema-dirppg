<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\LinhaPesquisa;

class LinhaPesquisaController extends Controller
{
    // Método para listar objetos
    public function index()
    {
        $cursos = LinhaPesquisa::with('programa')->get();

        return view('pos.linhas-pesquisa.index', compact('cursos'));
    }

    // Método para mostrar o formulário de criação
    public function create()
    {
        $programas = Programa::all();
        return view('pos.linhas-pesquisa.adicionar', ['programas' => $programas]);
    }

    // Método para salvar no banco
    public function store(Request $request)
    {
        LinhaPesquisa::create([
            'tipo' => $request->input('tipo'),
            'id_programa' => $request->input('programa')
        ]);

        return redirect()->route('pos.linhas-pesquisa.index')->with('success', 'Curso criado com sucesso!');
    }

    // Método para remover um objeto
    public function destroy($id)
    {
        $curso = LinhaPesquisa::where('id_curso', $id)->firstOrFail();
        $curso->delete();

        try {
            $curso->delete();
            return response()->json(['success' => true, 'message' => 'Curso removido com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
