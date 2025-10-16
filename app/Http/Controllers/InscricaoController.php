<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscricao;

class InscricaoController extends Controller
{
    // Método para listar objetos
    public function index()
    {
        $editais = Inscricao::with(['curso.programa', 'fasesEdital'])->get();

        return view('admin.editais.index', ['editais' => $editais]);
    }
}
