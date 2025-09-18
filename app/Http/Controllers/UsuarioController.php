<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Candidato;

class UsuarioController extends Controller
{
    // Método para listar objetos
    /*public function index()
    {
        $usuarios = Edital::with(['curso.programa', 'fasesEdital'])->paginate(20);

        return view('editais.index', [
            'editais' => $editais->items(), // apenas os registros da página atual
            'pagination' => $editais        // mantém os links de paginação
        ]);
    }*/


    // Método para salvar no banco
    public function store(Request $request)
    {
        // Campos que realmente precisa validar no backend
        $request->validate([
            'nome' => 'required|string|max:100',
            'email' => 'required|string|max:100',
            'cpf' => 'required|string|max:14|unique:candidatos,cpf',
            'senha' => 'required|string|min:8',
            'telefone' => 'nullable|string|max:20'
        ]);

        $usuario = Usuario::create([
            'nome' => $request->input('nome'),
            'email' => $request->input('email'),
            'senha' => bcrypt($request->input('senha'))
        ]);

        if($request->filled('cpf')) {
            Candidato::create([
                'id_usuario' => $usuario->id_usuario,
                'cpf' => $request->input('cpf'),
                'telefone' => $request->input('telefone'),
                'brasileiro' => $request->input('nacionalidade') === 'brasileiro' ? 1 : 0
            ]);
        }

        return view('autenticacao.cadastro.confirmacao');
    }
}
