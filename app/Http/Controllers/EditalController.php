<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Edital;

class EditalController extends Controller
{
    // Método para listar objetos
    public function index()
    {
        $editais = Edital::orderBy('nome', 'asc')->get();

        return view('editais.index', ['editais' => $editais]);
    }

    // Método para criação de objeto
    public function create()
    {
        //
    }

    // Método para armazenar um objeto
    public function store(Request $request)
    {
        //
    }

    // Método para mostrar um objeto
    public function show(string $id)
    {
        //
    }

    // Método para editar um objeto
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
