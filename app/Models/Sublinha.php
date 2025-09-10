<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sublinha extends Model
{
    protected $table = 'sublinhas';
    protected $primaryKey = 'id_sublinha';
    public $timestamps = false;

    protected $fillable = ['nome', 'id_linha_pesquisa', 'inativo'];

    public function linhaPesquisa(){
        return $this->belongsTo(LinhaPesquisa::class, 'id_linha_pesquisa', 'id_linha_pesquisa');
    }
}
