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

        $edital = Edital::with(['curso.programa', 'curso.disciplinasVisiveis'])->findOrFail($idEdital);
        $inscricoes = Inscricao::with([
            'candidato:id_usuario,nome,email',
            'candidato.candidato',
            'disciplina',
            'linhaPesquisa',
            'sublinha'
            ])->where('id_edital', $idEdital)
            ->where('deferido', 1)
            ->get();
        
        // Só para aluno externo
        if ($edital->curso->tipo === 'Aluno Externo') {

            // Pega todas as disciplinas do edital
            $disciplinas = $edital->curso->disciplinasVisiveis;

            // cria array base com todas disciplinas vazias
            $base = collect();
            foreach ($disciplinas as $disc) {
                $base[$disc->nome] = collect(); // lista vazia
            }

            // GroupBy das inscrições existentes
            $agrupadas = $inscricoes->groupBy(function ($inscricao) {
                return $inscricao->disciplina->nome;
            });

            // Mescla: garantimos que TODAS existam
            $inscricoes = $base->merge($agrupadas);
        }

        $pdf = Pdf::setOptions([
            'isRemoteEnabled' => true,
            'dpi' => 150,
        ])->loadView('admin.relatorios.index', compact('edital', 'inscricoes', 'definitiva'))
                    ->setPaper('a4', 'portrait');

        return $pdf->stream("resultado_{$edital->id_edital}.pdf");
    }
}