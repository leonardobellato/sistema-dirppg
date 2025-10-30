<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = ['nome', 'email', 'senha', 'tipo'];

    protected $hidden = ['senha'];

    // Dizer ao Laravel que a coluna de senha é "senha"
    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function candidato(){
        return $this->hasOne(Candidato::class, 'id_usuario', 'id_usuario');
    }

    public function eAdmin()
    {
        return $this->tipo === 'admin';
    }

    public function eCandidato()
    {
        return $this->tipo === 'candidato';
    }

    public function eProfessor()
    {
        return $this->tipo === 'professor';
    }

    public function programas()
    {
        return $this->belongsToMany(Programa::class, 'professor_programa', 'id_usuario', 'id_programa');
    }

    public function entrevistasAgendadas()
    {
        return $this->hasMany(Entrevista::class, 'id_agendador', 'id_usuario');
    }
}
