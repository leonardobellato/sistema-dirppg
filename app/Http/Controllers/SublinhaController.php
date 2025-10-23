<?php

namespace App\Http\Controllers;

use App\Models\LinhaPesquisa;
use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\Sublinha;

class SublinhaController extends Controller
{
    // Método para listar todos os objetos
    public function listar()
    {
        $sublinhas = Sublinha::with([
            'linhaPesquisa:id_linha_pesquisa,nome,id_area_concentracao',
            'linhaPesquisa.areaConcentracao:id_area_concentracao,nome,id_curso',
            'linhaPesquisa.areaConcentracao.curso:id_curso,tipo,id_programa',
            'linhaPesquisa.areaConcentracao.curso.programa:id_programa,nome,sigla'
        ])->get();

        return view('admin.pos.sublinhas.index', compact('sublinhas'));
    }

    // Método para mostrar o formulário de criação
    public function criar()
    {
        // Buscar todos os programas para popular o select
        $programas = Programa::all();

        return view('admin.pos.sublinhas.adicionar', compact('programas'));
    }

    // Rota para AJAX
    public function filtrarSublinhasPorLinha($idLinha)
    {
        return response()->json(LinhaPesquisa::findOrFail($idLinha)->sublinhas);
    }

    // Método para salvar no banco
    public function salvar(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:150'
        ]);

        Sublinha::create([
            'nome' => $request->input('nome'),
            'id_linha_pesquisa' => $request->input('linha-pesquisa')
        ]);

        return redirect()->route('pos.sublinhas.index')->with('success', 'Sublinha criada com sucesso!');
    }

    // Mostrar formulário de edição
    public function alterar($id)
    {
        $sublinha = Sublinha::with([
            'linhaPesquisa:id_linha_pesquisa,nome,id_area_concentracao',
            'linhaPesquisa.areaConcentracao:id_area_concentracao,nome,id_curso',
            'linhaPesquisa.areaConcentracao.curso:id_curso,tipo,id_programa',
            'linhaPesquisa.areaConcentracao.curso.programa:id_programa,nome'
        ])->findOrFail($id);

        return view('admin.pos.sublinhas.alterar', compact('sublinha'));
    }

    public function atualizar(Request $request, $id)
    {
        $sublinha = Sublinha::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:150'
        ]);

        $sublinha->update([
            'nome' => $request->input('nome'),
            'inativo' => $request->has('ativo') ? 0 : 1
        ]);

        return redirect()->route('pos.sublinhas.index')
                         ->with('success', 'Sublinha atualizada com sucesso!');
    }

    public function excluir($id)
    {
        $sublinha = Sublinha::findOrFail($id);

        try {
            $sublinha->delete();
            return response()->json(['success' => true, 'message' => 'Sublinha removida com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
