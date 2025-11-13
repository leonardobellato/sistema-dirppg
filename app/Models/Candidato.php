<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    protected $table = 'candidatos';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = ['cpf', 'telefone', 'brasileiro', 'id_usuario', 'permitir_emails'];

    public function usuario(){
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}