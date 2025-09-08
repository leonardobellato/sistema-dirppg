<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programa;

class ProgramaController extends Controller
{
    // Método para listar objetos
    public function index()
    {
        $programas = Programa::orderBy('nome', 'asc')->get(['id_programa as id', 'nome']);

        return view('pos.programas.index', compact('programas'));
    }
}
