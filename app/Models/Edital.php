<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Edital extends Model
{
    protected $table = 'editais';
    protected $primaryKey = 'id_edital';
    public $timestamps = false;

    protected $fillable = ['nome', 'link', 'id_curso', 'vigente'];

    public function curso(){
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    public function fasesEdital(){
        return $this->hasMany(FaseEdital::class, 'id_edital', 'id_edital');
    }

    public function faseAtual()
    {
        $agora = Carbon::now();

        return $this->fasesEdital
            ->sortByDesc('data_inicio')
            ->first(function ($fase) use ($agora) {
                // Fases com início e fim (ex: inscrição ou recurso)
                if ($fase->data_inicio && $fase->data_fim) {
                    return $fase->data_inicio <= $agora && $fase->data_fim >= $agora;
                }

                // Fases com apenas data (ex: resultadoInsc, resultadoRec)
                if ($fase->data_inicio && !$fase->data_fim) {
                    return $fase->data_inicio->isSameDay($agora) || $fase->data_inicio <= $agora;
                }

                return false;
            });
    }
}
