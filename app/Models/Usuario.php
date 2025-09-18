<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = ['nome', 'email', 'senha', 'tipo'];

    public function candidato(){
        return $this->hasOne(Candidato::class, 'id_usuario', 'id_usuario');
    }
}
