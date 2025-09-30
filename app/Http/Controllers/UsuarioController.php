<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'email' => 'required|string|max:100|unique:usuarios,email',
            'cpf' => [
                'required',
                'string',
                'max:14',
                'unique:candidatos,cpf',
                function ($attribute, $value, $fail) {
                    if (!$this->validaCPF($value)) {
                        $fail('O CPF informado é inválido.');
                    }
                },
            ],
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

    // Função de validação CPF
    private function validaCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) != 11 || preg_match('/^(.)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    // Mostra o formulário de edição
    public function edit()
    {
        // pega o usuário logado
        $usuario = Auth::user();

        return view('pessoas.usuarios.alterar', compact('usuario'));
    }

    // Atualiza os dados
    public function update(Request $request)
    {
        $usuario = Auth::user();

        $data = $request->validate([
            'nome' => 'required|string|max:100',
            'telefone' => 'nullable|string|max:20',
            'email' => 'required|email|max:100|unique:usuarios,email,' . $usuario->id_usuario . ',id_usuario',
            'senha' => 'nullable|string|min:8',
        ]);

        // Prepara os dados para atualizar
        $updateData = [
            'nome' => $data['nome'],
            'email' => $data['email']
        ];

        if (!empty($data['senha'])) {
            $updateData['senha'] = bcrypt($data['senha']);
        }

        $usuario->update($updateData);

        if (!empty($data['telefone'])) {
            $usuario->candidato->update([
            'telefone' => $data['telefone']
        ]);
        }

        return redirect()->route('pessoas.usuarios.alterar')->with('success', 'Dados atualizados com sucesso!');
    }
}
