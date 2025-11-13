<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaseEdital extends Model
{
    protected $table = 'fases_edital';
    protected $primaryKey = 'id_fase';
    public $timestamps = false;

    protected $fillable = [
        'id_edital',
        'tipo',
        'data_inicio',
        'data_fim',
    ];

    public function edital(){
        return $this->belongsTo(Edital::class, 'id_edital', 'id_edital');
    }
}
