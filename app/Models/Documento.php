<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documentos';
    protected $primaryKey = 'id_documento';
    public $timestamps = false;

    protected $fillable = [
        'id_inscricao',
        'caminho_servidor',
        'tipo',
        'versao',
        'deferido',
        'motivo_indeferimento'
    ];

    public function inscricao(){
        return $this->belongsTo(Inscricao::class, 'id_inscricao', 'id_inscricao');
    }

    public function recursos(){
        return $this->hasMany(Recurso::class, 'id_documento', 'id_documento');
    }
}
