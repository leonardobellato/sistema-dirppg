<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditorias';
    protected $primaryKey = 'id_auditoria';
    public $timestamps = false;

    protected $fillable = ['id_usuario', 'tipo', 'operacao', 'sucesso', 'detalhes', 'ip', 'navegador', 'criado_em'];

    public function usuario(){
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
