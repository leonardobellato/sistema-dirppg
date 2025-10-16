<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinhaPesquisa extends Model
{
    protected $table = 'linhas_pesquisa';
    protected $primaryKey = 'id_linha_pesquisa';
    public $timestamps = false;

    protected $fillable = ['nome', 'id_area_concentracao', 'inativo'];

    public function areaConcentracao(){
        return $this->belongsTo(AreaConcentracao::class, 'id_area_concentracao', 'id_area_concentracao');
    }

    public function sublinhas(){
        return $this->hasMany(Sublinha::class, 'id_linha_pesquisa', 'id_linha_pesquisa');
    }
}
