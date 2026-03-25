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
        //$ttl = 300; // 5 minutos de duração do cache
        $ttl = 60; // 1 minuto para desenvolvimento

        $editaisAbertos = Edital::where('vigente', true)->with([
            'curso',
            'curso.programa'
        ])->get(['id_edital', 'id_curso']);
        $qntEditaisAbertos = Edital::where('vigente', true)->count();

        $analisesPendentes = Inscricao::whereNull('deferido')->count();

        $entrevistasAgendadas = Entrevista::whereDate('data_hora', '>=', now())->count();

        // Histograma: inscrições por dia nos últimos 7 dias
        $histograma = Cache::remember('dashboard.inscricoes_7dias', $ttl, function () use ($editaisAbertos) {

            $raw = Inscricao::select(
                    DB::raw('DATE(criado_em) as data'),
                    'id_edital',
                    DB::raw('COUNT(*) as total')
                )
                ->where('criado_em', '>=', Carbon::now()->subDays(6))
                ->groupBy('data', 'id_edital')
                ->orderBy('data')
                ->get();
            
            $dias = collect(range(0,6))->map(fn($i) => Carbon::now()->subDays(6 - $i)->format('Y-m-d'));

            // Inicializa todos os editais com zero
            $resultado = [];
            foreach ($editaisAbertos as $edital) {
                $resultado[$edital->id_edital] = array_fill(0, 7, 0);
            }

            foreach ($raw->groupBy('id_edital') as $idEdital => $items) {
                $map = $items->mapWithKeys(fn($item) => [
                    $item->data => $item->total
                ]);

                $valores = $dias->map(fn($d) => $map[$d] ?? 0)->values();

                $resultado[$idEdital] = $valores;
            }

            return [
                'labels' => $dias
                    ->map(fn($d) => Carbon::parse($d)->format('d/m'))
                    ->toArray(),

                'geral' => array_values(
                    collect($resultado)
                        ->reduce(fn($carry, $item) =>
                            collect($carry)->zip($item)->map(fn($v) => $v[0] + $v[1])->toArray(),
                            array_fill(0, 7, 0)
                        )
                ),

                'por_edital' => $resultado
            ];
        });
           
        // Procura por programas e cursos mais populares
        $programas = Cache::remember('dashboard.programas', $ttl, function () {
            return DB::table('programas as p')
                ->leftJoin('cursos as c', 'c.id_programa', '=', 'p.id_programa')
                ->leftJoin('editais as e', 'e.id_curso', '=', 'c.id_curso')
                ->leftJoin('inscricoes as i', 'i.id_edital', '=', 'e.id_edital')
                ->select('p.sigla', DB::raw('COUNT(i.id_inscricao) as total'))
                ->groupBy('p.sigla')
                ->orderByDesc('total')
                ->pluck('total', 'sigla');
        });

        $cursos = Cache::remember('dashboard.cursos', $ttl, function () {
            return DB::table('cursos as c')
                ->leftJoin('editais as e', 'e.id_curso', '=', 'c.id_curso')
                ->leftJoin('inscricoes as i', 'i.id_edital', '=', 'e.id_edital')
                ->select('c.tipo', DB::raw('COUNT(i.id_inscricao) as total'))
                ->groupBy('c.tipo')
                ->orderByDesc('total')
                ->pluck('total', 'tipo');
        });
             

        return view('admin.inicio.index', [
            'editaisAbertos' => $editaisAbertos,
            'qntEditaisAbertos' => $qntEditaisAbertos,
            'analisesPendentes' => $analisesPendentes,
            'entrevistasAgendadas' => $entrevistasAgendadas,
            'histograma' => $histograma,
            'programas' => $programas,
            'cursos' => $cursos
        ]);
    }
}
