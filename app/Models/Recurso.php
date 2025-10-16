<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    protected $table = 'recursos';
    protected $primaryKey = 'id_recurso';
    public $timestamps = false;

    protected $fillable = ['id_documento', 'versao_submetida', 'data_submissao'];

    public function documento(){
        return $this->belongsTo(Documento::class, 'id_documento', 'id_documento');
    }
}
