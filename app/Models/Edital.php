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
        return $this->fasesEdital
            ->sortByDesc('data_fim')
            ->first(function ($fase) {
                $agora = Carbon::now();

                if ($fase->data_inicio && $fase->data_fim) {
                    $inicio = Carbon::parse($fase->data_inicio)->startOfDay();
                    $fim = Carbon::parse($fase->data_fim)->endOfDay();
                    return $inicio <= $agora && $fim >= $agora;
                }
                return false;
            }); 
    }

    public function resultadoDisponivel()
    {
        return $this->fasesEdital
            ->where('tipo', 'resultado')
            ->first(function ($fase) {
                return Carbon::parse($fase->data_fim)->startOfDay() <= Carbon::now();
            });
    }
}

