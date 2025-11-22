<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrevista;
use App\Models\Inscricao;

class EntrevistaController extends Controller
{
    // Método para listar todos os objetos
    public function listar()
    {
        $entrevistas = Entrevista::with([
            'candidato:id_usuario,nome',
            'agendador:id_usuario,nome'
        ])->get();

        return view('admin.entrevistas.index', compact('entrevistas'));
    }

    // Método para mostrar o formulário de criação
    public function criar($idEdital)
    {
        $candidatos = Inscricao::where('id_edital', $idEdital)
            ->join('usuarios', 'inscricoes.id_candidato', '=', 'usuarios.id_usuario')
            ->select('usuarios.id_usuario', 'usuarios.nome')
            ->groupBy('usuarios.id_usuario', 'usuarios.nome')
            ->orderBy('usuarios.nome')
            ->get();
        
        return view('admin.entrevistas.adicionar', ['id_edital' => $idEdital, 'candidatos' => $candidatos]);
    }

    // Método para salvar no banco
    public function salvar(Request $request)
    {
        $request->validate([
            'id_edital' => 'required|integer|exists:editais,id_edital',
            'candidato' => 'required|integer|exists:usuarios,id_usuario',
            'local' => 'required|string|max:200',
            'observacoes' => 'nullable|string|max:600'
        ]);

        $entrevista = Entrevista::create([
            'id_edital' => $request->input('id_edital'),
            'id_candidato' => $request->input('candidato'),
            'id_agendador' => auth()->user()->id_usuario,
            'data_hora' => $request->input('data_entrevista'),
            'local' => $request->input('local'),
            'observacoes' => $request->input('observacoes')
        ]);

        app(EmailController::class)->agendamentoEntrevista($entrevista->id_entrevista);

        return back()->with('success', 'Entrevista agendada com sucesso!');
    }

    // Mostrar formulário de edição
    public function alterar($id)
    {
        $entrevista = Entrevista::with('candidato:id_usuario,nome')->findOrFail($id);

        return view('admin.entrevistas.alterar', compact('entrevista'));
    }

    // Método para atualizar no banco
    public function atualizar(Request $request, $id)
    {
        $entrevista = Entrevista::findOrFail($id);
        
        $request->validate([
            'data_entrevista' => 'required|date',
            'local' => 'required|string|max:200',
            'observacoes' => 'nullable|string|max:600',
            'status' => 'required|in:agendada,realizada,ausente,cancelada'
        ]);

        $entrevista->update([
            'data_hora'   => $request->input('data_entrevista'),
            'local'       => $request->input('local'),
            'observacoes' => $request->input('observacoes'),
            'status'      => $request->input('status'),
        ]);

        app(EmailController::class)->atualizacaoEntrevista($entrevista->candidato->email);

        return redirect()->route('admin.entrevistas.index')->with('success', 'Entrevista atualizada com sucesso!');
    }

    public function excluir($id)
    {
        $entrevista = Entrevista::findOrFail($id);
        try {
            $entrevista->delete();
            return response()->json(['success' => true, 'message' => 'Entrevista removida com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }
}
