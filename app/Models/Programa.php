<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $table = 'programas';
    protected $primaryKey = 'id_programa';
    public $timestamps = false;

    protected $fillable = ['nome'];

    public function cursos(){
        return $this->hasMany(Curso::class);
    }
}
