<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    protected $table = 'inscricoes';
    protected $primaryKey = 'id_inscricao';
    public $timestamps = false;

    protected $fillable = [
        'id_candidato', 
        'id_avaliador', 
        'id_edital', 
        'id_linha_pesquisa', 
        'id_sublinha', 
        'deferido',
        'motivo_indeferimento',
        'nome_orientador',
        'comentarios'
    ];

    public function edital(){
        return $this->belongsTo(Edital::class, 'id_edital', 'id_edital');
    }

    public function candidato(){
        return $this->belongsTo(Usuario::class, 'id_candidato', 'id_usuario');
    }

    public function avaliador(){
        return $this->belongsTo(Usuario::class, 'id_avaliador', 'id_usuario');
    }

    public function linhaPesquisa(){
        return $this->belongsTo(LinhaPesquisa::class, 'id_linha_pesquisa', 'id_linha_pesquisa');
    }

    public function sublinha(){
        return $this->belongsTo(Sublinha::class, 'id_sublinha', 'id_sublinha');
    }

    public function documentos(){
        return $this->hasMany(Documento::class, 'id_inscricao', 'id_inscricao');
    }
}
