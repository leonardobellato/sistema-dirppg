<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;
use App\Models\LinhaPesquisa;

class LinhaPesquisaController extends Controller
{
    // Método para listar objetos
    public function index()
    {
        $linhas_pesquisa = LinhaPesquisa::with([
            'areaConcentracao:id_area_concentracao,nome,id_curso',
            'areaConcentracao.curso:id_curso,tipo,id_programa',
            'areaConcentracao.curso.programa:id_programa,nome,sigla'
        ])->get();

        return view('admin.pos.linhas-pesquisa.index', compact('linhas_pesquisa'));
    }

    // Método para mostrar o formulário de criação
    public function create()
    {
        $programas = Programa::all();
        return view('admin.pos.linhas-pesquisa.adicionar', compact('programas'));
    }

    // rota para AJAX
    public function getLinhasByArea($idArea)
    {
        $linhas_pesquisa = LinhaPesquisa::where('id_area_concentracao', $idArea)->get();
        return response()->json($linhas_pesquisa);
    }

    // Método para salvar no banco
    public function store(Request $request)
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
    public function edit($id)
    {
        $linha_pesquisa = LinhaPesquisa::with([
            'areaConcentracao:id_area_concentracao,nome,id_curso',
            'areaConcentracao.curso:id_curso,tipo,id_programa',
            'areaConcentracao.curso.programa:id_programa,nome'
        ])->where('id_linha_pesquisa', $id)->firstOrFail();

        return view('admin.pos.linhas-pesquisa.alterar', compact('linha_pesquisa'));
    }

    // Atualizar objeto
    public function update(Request $request, $id)
    {
        $linha_pesquisa = LinhaPesquisa::where('id_linha_pesquisa', $id)->firstOrFail();

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

    // Método para remover um objeto
    public function destroy($id)
    {
        $linha_pesquisa = LinhaPesquisa::where('id_linha_pesquisa', $id)->firstOrFail();

        try {
            $linha_pesquisa->delete();
            return response()->json(['success' => true, 'message' => 'Linha de pesquisa removida com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
