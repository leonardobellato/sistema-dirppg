<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrevista extends Model
{
    protected $table = 'entrevistas';
    protected $primaryKey = 'id_entrevista';
    public $timestamps = false;

    protected $fillable = ['id_edital', 'id_candidato', 'id_agendador', 'data_hora', 'local', 'status', 'observacoes'];

    public function edital()
    {
        return $this->belongsTo(Edital::class, 'id_edital', 'id_edital');
    }

    public function agendador()
    {
        return $this->belongsTo(Usuario::class, 'id_agendador', 'id_usuario');
    }

    public function candidato()
    {
        return $this->belongsTo(Usuario::class, 'id_candidato', 'id_usuario');
    }
}
