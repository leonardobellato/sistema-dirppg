<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'cursos';
    protected $primaryKey = 'id_curso';
    public $timestamps = false;

    protected $fillable = ['tipo', 'id_programa'];

    public function programa(){
        return $this->belongsTo(Programa::class, 'id_programa', 'id_programa');
    }

    public function areasConcentracao(){
        return $this->hasMany(AreaConcentracao::class, 'id_curso', 'id_curso');
    }

    public function disciplinas(){
        return $this->hasMany(Disciplina::class, 'id_curso', 'id_curso');
    }

    public function editais(){
        return $this->hasMany(Edital::class, 'id_curso', 'id_curso');
    }
}
