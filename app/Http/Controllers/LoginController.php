<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        $dados = $request->validate([
            'email' => 'required|email',
            'senha' => 'required'
        ]);

        // Verifica se o e-mail existe na base de dados
        $usuario = Usuario::where('email', $dados['email'])->first();

        if (!$usuario) {
            return back()->with('failure', 'E-mail não encontrado.');
        }

        // O Laravel espera que a chave seja "password"
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
            } elseif (Auth::user()->tipo === 'professor') {
                return redirect()->route('professor.editais.index');
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

    public function solicitarRedefinicaoSenha(){
        return view('autenticacao.redefinir-senha.index');
    }
}
