<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscricao;
use App\Models\Edital;

class InscricaoController extends Controller
{

    // Método para listar todos os objetos
    public function listar($idCandidato)
    {
        $inscricoes = Inscricao::with('edital.curso.programa')->where('id_candidato', $idCandidato)->firstOrFail();

        return view('candidato.inscricoes.index', ['inscricoes' => $inscricoes]);
    }

    public function filtrarInscricoesPorEdital($idEdital)
    {
        return response()->json(Edital::findOrFail($idEdital)->inscricoes);
    }

    // Método para mostrar o formulário de criação
    public function criar($idEdital)
    {
        $edital = Edital::with(['curso.programa', 'curso.disciplinas'])->findOrFail($idEdital);
        
        return view('candidato.editais.inscrever', compact('edital'));
    }

    // Método para salvar no banco
    public function salvar(Request $request){
        
    }
}
