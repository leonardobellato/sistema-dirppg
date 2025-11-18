<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Inscricao;
use App\Models\Edital;

class RelatorioController extends Controller
{
    public function gerar($idEdital)
    {
        $tipoRelatorio = request()->query('tipo');
        if ($tipoRelatorio === 'preliminar') {
            $definitiva = false;
        } elseif ($tipoRelatorio === 'definitivo') {
            $definitiva = true;
        } else {
            abort(400, 'Tipo de relatório inválido.');
        }

        $edital = Edital::with(['curso.programa'])->findOrFail($idEdital);
        $inscricoes = Inscricao::with([
            'candidato:id_usuario,nome,email',
            'candidato.candidato',
            'disciplina',
            'linhaPesquisa',
            'sublinha'
            ])->where('id_edital', $idEdital)
            ->where('deferido', 1)
            ->get();
        
        if($edital->curso->tipo === 'Aluno externo'){
            $inscricoes = $inscricoes->groupBy('disciplina.nome');
        }

        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => true,
            'dpi' => 150,
        ])->loadView('admin.relatorios.index', compact('edital', 'inscricoes', 'definitiva'))
                    ->setPaper('a4', 'portrait');

        return $pdf->stream("resultado_{$edital->id_edital}.pdf");
    }
}