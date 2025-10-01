<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $dados = $request->validate([
            'email' => 'required|email',
            'senha' => 'required'
        ]);

        // o Laravel espera que a chave seja "password"
        $credentials = [
            'email' => $dados['email'],
            'password' => $dados['senha']
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth::user()->tipo === 'admin') {
                return redirect()->route('inicio'); // nome da rota /admin/
            } elseif (Auth::user()->tipo === 'candidato') {
                return redirect()->route('candidato.editais.index');
            } else {
                Auth::logout();
                return back()->with('failure', 'Usuário sem permissão válida.');
            }
        }
        
        return back()->with('failure', 'E-mail ou senha incorretos.');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
