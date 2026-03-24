<?php

namespace App\Http\Controllers;
use App\Models\Edital;
use App\Models\Inscricao;
use App\Models\Entrevista;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $ttl = 1800; // 30 minutos

        $editaisAbertos = Edital::where('vigente', true)->count();

        $analisesPendentes = Inscricao::whereNull('deferido')->count();

        $entrevistasAgendadas = Entrevista::whereDate('data_hora', '>=', now())->count();

        // Histograma: inscrições por dia nos últimos 7 dias
        $histograma = Cache::remember('dashboard.inscricoes_7dias', $ttl, function () {

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

            $dias = collect(range(0,6))
                ->map(fn($i) => Carbon::now()->subDays(6 - $i)->format('Y-m-d'))
                ->mapWithKeys(fn($d) => [$d => $inscricoesPorDia[$d] ?? 0]);

            return [
                'valores' => array_values($dias->toArray()),
                'labels' => $dias->keys()
                    ->map(fn($d) => Carbon::parse($d)->format('d/m'))
                    ->toArray()
            ];
        });

        $programas = Cache::remember('dashboard.programas', $ttl, function () {
            return DB::table('inscricoes as i')
                ->join('editais as e', 'e.id_edital', '=', 'i.id_edital')
                ->join('cursos as c', 'c.id_curso', '=', 'e.id_curso')
                ->join('programas as p', 'p.id_programa', '=', 'c.id_programa')
                ->select('p.nome', DB::raw('COUNT(i.id_inscricao) as total'))
                ->groupBy('p.id_programa', 'p.nome')
                ->orderByDesc('total')
                ->pluck('total', 'nome');
        });

        $cursos = Cache::remember('dashboard.cursos', $ttl, function () {
            return DB::table('inscricoes as i')
                ->join('editais as e', 'e.id_edital', '=', 'i.id_edital')
                ->join('cursos as c', 'c.id_curso', '=', 'e.id_curso')
                ->select('c.tipo', DB::raw('COUNT(i.id_inscricao) as total'))
                ->groupBy('c.id_curso', 'c.tipo')
                ->orderByDesc('total')
                ->pluck('total', 'tipo');
        });

        return view('admin.inicio.index', [
            'editaisAbertos' => $editaisAbertos,
            'analisesPendentes' => $analisesPendentes,
            'entrevistasAgendadas' => $entrevistasAgendadas,
            'inscricoesPorDia' => $histograma['valores'],
            'dias' => $histograma['labels'],
            'programas' => $programas,
            'cursos' => $cursos
        ]);
    }
}
