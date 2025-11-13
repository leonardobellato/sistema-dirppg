<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Candidato;
use App\Models\Programa;

class UsuarioController extends Controller
{
    // Método para listar todos os objetos
    public function listarAdministradores()
    {
        $usuarios = Usuario::where('tipo', 'admin')->get();
        return view('admin.pessoas.administradores.index', compact('usuarios'));
    }

    public function listarProfessores()
    {
        $usuarios = Usuario::with('programas')->where('tipo', 'professor')->get();

        return view('admin.pessoas.professores.index', compact('usuarios'));
    }

    // Método para mostrar o formulário de cadastro
    public function criarAdministrador()
    {
        return view('admin.pessoas.administradores.adicionar');
    }

    public function criarProfessor()
    {
        return view('admin.pessoas.professores.adicionar');
    }

    // Método para salvar no banco
    public function salvarAdministrador(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100',
            'email' => 'required|string|max:100|unique:usuarios,email',
            'senha' => 'required|string|min:8',
        ]);

        $usuario = Usuario::create([
            'nome' => $request->input('nome'),
            'email' => $request->input('email'),
            'senha' => bcrypt($request->input('senha')),
            'tipo' => 'admin'
        ]);

        return redirect()->route('pessoas.administradores.index')->with('success', 'Administrador criado com sucesso!');
    }

    public function salvarProfessor(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:100',
            'email' => 'required|string|max:100|unique:usuarios,email',
            'senha' => 'required|string|min:8',
        ]);

        $usuario = Usuario::create([
            'nome' => $request->input('nome'),
            'email' => $request->input('email'),
            'senha' => bcrypt($request->input('senha')),
            'tipo' => 'professor'
        ]);

        return redirect()->route('pessoas.professores.index')->with('success', 'Professor criado com sucesso!');
    }

    public function salvarCandidato(Request $request)
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

        
        Candidato::create([
            'id_usuario' => $usuario->id_usuario,
            'cpf' => $request->input('cpf'),
            'telefone' => $request->input('telefone'),
            'brasileiro' => $request->input('nacionalidade') === 'brasileiro' ? 1 : 0
        ]);

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
    public function alterar()
    {
        // pega o usuário logado
        $usuario = Auth::user();

        return view('usuarios.alterar', compact('usuario'));
    }

    public function atualizar(Request $request)
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
            'email' => $data['email'],
        ];

        if (!empty($data['senha'])) {
            $updateData['senha'] = bcrypt($data['senha']);
        }

        $usuario->update($updateData);

        if ($usuario->tipo === 'candidato') {
            $usuario->candidato->update([
                'telefone' => $data['telefone'],
                'permitir_emails' => $request->has('permitir-emails') ? 1 : 0
            ]);
        }

        return redirect()->route('usuarios.alterar')->with('success', 'Dados atualizados com sucesso!');
    }

    public function excluir($id)
    {
        $usuario = Usuario::findOrFail($id);

        if ($usuario->nome === 'Admin') {
            return response()->json(['success' => false, 'message' => 'O usuário Admin não pode ser removido.'], 403);
        }

        try {
            $usuario->delete();
            return response()->json(['success' => true, 'message' => 'Usuário removido com sucesso!']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir: ' . $e->getMessage()], 500);
        }
    }

    public function obterProgramasVinculados($idUsuario)
    {
        $professor = Usuario::with('programas')->findOrFail($idUsuario);
        $programas = Programa::all();

        return view('admin.pessoas.professores.vincular', compact('professor', 'programas'));
    }

    public function vincularPrograma(Request $request){
        $professor = Usuario::findOrFail($request['id_usuario']);

        // Atualiza os vínculos com base nos checkboxes marcados
        $professor->programas()->sync($request['id_programas'] ?? []);

        return redirect()->route('pessoas.professores.index')->with('success', 'Programa vinculado com sucesso!');
    }
}

