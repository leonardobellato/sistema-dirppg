<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    protected $table = 'disciplinas';
    protected $primaryKey = 'id_disciplina';
    public $timestamps = false;

    protected $fillable = ['nome', 'id_curso', 'visivel'];

    public function curso(){
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    public function inscricoes(){
        return $this->hasMany(Inscricao::class, 'id_disciplina', 'id_disciplina');
    }
}
