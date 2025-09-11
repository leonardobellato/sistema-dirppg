<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edital extends Model
{
    protected $table = 'editais';
    protected $primaryKey = 'id_edital';
    public $timestamps = false;

    protected $fillable = ['nome', 'id_curso', 'vigente'];

    public function curso(){
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    public function fasesEdital(){
        return $this->hasMany(FaseEdital::class, 'id_edital', 'id_edital');
    }
}
