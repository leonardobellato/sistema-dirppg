<?php

namespace App\Http\Controllers;

use App\Models\AreaConcentracao;
use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\LinhaPesquisa;

class LinhaPesquisaController extends Controller
{
    // Método para listar todos os objetos
    public function listar()
    {
        $linhas_pesquisa = LinhaPesquisa::with([
            'areaConcentracao:id_area_concentracao,nome,id_curso',
            'areaConcentracao.curso:id_curso,tipo,id_programa',
            'areaConcentracao.curso.programa:id_programa,nome,sigla'
        ])->get();

        return view('admin.pos.linhas-pesquisa.index', compact('linhas_pesquisa'));
    }

    // Método para mostrar o formulário de criação
    public function criar()
    {
        // Buscar os programas para popular o select
        $programas = Programa::all();

        return view('admin.pos.linhas-pesquisa.adicionar', compact('programas'));
    }

    // Rota para AJAX
    public function filtrarLinhasPorArea($idArea)
    {
        return response()->json(AreaConcentracao::findOrFail($idArea)->linhasPesquisa);
    }

    // Método para salvar no banco
    public function salvar(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:150'
        ]);

        LinhaPesquisa::create([
            'nome' => $request->input('nome'),
            'id_area_concentracao' => $request->input('area-concentracao')
        ]);

        return redirect()->route('pos.linhas-pesquisa.index')->with('success', 'Linha de pesquisa criada com sucesso!');
    }

    // Mostrar formulário de edição
    public function alterar($id)
    {
        $linha_pesquisa = LinhaPesquisa::with([
            'areaConcentracao:id_area_concentracao,nome,id_curso',
            'areaConcentracao.curso:id_curso,tipo,id_programa',
            'areaConcentracao.curso.programa:id_programa,nome'
        ])->findOrFail($id);

        return view('admin.pos.linhas-pesquisa.alterar', compact('linha_pesquisa'));
    }

    public function atualizar(Request $request, $id)
    {
        $linha_pesquisa = LinhaPesquisa::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:150'
        ]);

        $linha_pesquisa->update([
            'nome' => $request->input('nome'),
            'inativo' => $request->has('ativo') ? 0 : 1
        ]);

        return redirect()->route('pos.linhas-pesquisa.index')
                         ->with('success', 'Linha de pesquisa atualizada com sucesso!');
    }

    public function excluir($id)
    {
        $linha_pesquisa = LinhaPesquisa::findOrFail($id);

        try {
            $linha_pesquisa->delete();
            return response()->json(['success' => true, 'message' => 'Linha de pesquisa removida com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
