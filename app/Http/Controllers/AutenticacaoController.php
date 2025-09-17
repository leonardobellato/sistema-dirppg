<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AutenticacaoController extends Controller
{
    public function index()
    {
        return view('autenticacao.cadastro.index');
    }

    public function store(Request $request)
    {
        // Validação dos dados recebidos do formulário
        $request->validate([
            'email' => 'required|email|confirmed',
            'senha' => 'required|min:8|confirmed',
        ]);

        // Aqui você pode adicionar a lógica para salvar o usuário no banco de dados
        // Por exemplo:
        // User::create([
        //     'email' => $validatedData['email'],
        //     'password' => bcrypt($validatedData['senha']),
        // ]);

        // Redirecionar para uma página de sucesso ou login após o cadastro
        return redirect()->route('login.candidato')->with('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }
}