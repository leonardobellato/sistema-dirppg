<?php

namespace App\Http\Controllers;
use App\Models\Edital;
use App\Models\Inscricao;
use App\Models\Entrevista;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $editaisAbertos = Edital::where('vigente', true)->count();

        $analisesPendentes = Inscricao::whereNull('deferido')->count();

        $entrevistasAgendadas = Entrevista::whereDate('data_hora', '>=', now())->count();

        // Histograma: inscrições por dia nos últimos 7 dias
        $inscricoesPorDia = Inscricao::select(
            DB::raw('DATE(criado_em) as data'),
            DB::raw('COUNT(*) as total')
        )
        ->where('criado_em', '>=', Carbon::now()->subDays(6))
        ->groupBy('data')
        ->orderBy('data')
        ->get()
        ->mapWithKeys(fn($item) => [$item->data => $item->total])
        ->toArray();

        // Preenche dias sem inscrição com 0
        $dias = collect(range(0,6))
            ->map(fn($i) => Carbon::now()->subDays(6 - $i)->format('Y-m-d'))
            ->mapWithKeys(fn($d) => [$d => $inscricoesPorDia[$d] ?? 0]);

        return view('admin.inicio.index', [
            'editaisAbertos' => $editaisAbertos,
            'analisesPendentes' => $analisesPendentes,
            'entrevistasAgendadas' => $entrevistasAgendadas,
            'inscricoesPorDia' => array_values($dias->toArray()),
            'dias' => $dias->keys()->map(fn($d) => Carbon::parse($d)->format('d/m'))->toArray(),
        ]);
    }
}
