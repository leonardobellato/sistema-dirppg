<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaConcentracao extends Model
{
    protected $table = 'areas_concentracao';
    protected $primaryKey = 'id_area_concentracao';
    public $timestamps = false;

    protected $fillable = ['nome', 'id_curso', 'inativo'];

    public function curso(){
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    public function linhasPesquisa(){
        return $this->hasMany(LinhaPesquisa::class, 'id_area_concentracao', 'id_area_concentracao');
    }
}
