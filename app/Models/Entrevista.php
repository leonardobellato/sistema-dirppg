<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrevista extends Model
{
    protected $table = 'entrevistas';
    protected $primaryKey = 'id_entrevista';
    public $timestamps = false;

    protected $fillable = ['id_inscricao', 'id_agendador', 'data_hora', 'local', 'status', 'observacoes'];

    public function inscricao(){
        return $this->belongsTo(Inscricao::class, 'id_inscricao', 'id_inscricao');
    }

    public function agendador()
    {
        return $this->belongsTo(Usuario::class, 'id_agendador', 'id_usuario');
    }
}
