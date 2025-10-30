<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EditalController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AreaConcentracaoController;
use App\Http\Controllers\LinhaPesquisaController;
use App\Http\Controllers\SublinhaController;
use App\Http\Controllers\DisciplinaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\DashboardController;

/* AUTENTICAÇÃO */
Route::get('/', function () {
    return redirect('/login');
});
Route::get('/login', function () { return view('autenticacao.login.index');})->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('autenticacao.login');
Route::get('/logout', [LoginController::class, 'logout'])->name('autenticacao.logout');

Route::get('/cadastro', function () { return view('autenticacao.cadastro.index');})->name('autenticacao.cadastro.index');
Route::post('/cadastro', [UsuarioController::class, 'salvarCandidato'])->name('autenticacao.cadastro.salvar');


/* ACESSO RESTRITO - Qualquer usuário logado */
Route::middleware('auth')->group(function () {
    Route::get('/usuario', [UsuarioController::class, 'alterar'])->name('usuarios.alterar');
    Route::put('/usuario', [UsuarioController::class, 'atualizar'])->name('usuarios.atualizar');

    // Rotas AJAX
    Route::get('/programas/{id}/cursos', [CursoController::class, 'filtrarCursosPorPrograma']);
    Route::get('/cursos/{id}/areas-concentracao', [AreaConcentracaoController::class, 'filtrarAreasPorCurso']);
    Route::get('/cursos/{id}/disciplinas-aluno-externo', [DisciplinaController::class, 'filtrarDisciplinasPorCurso']);
    Route::get('/cursos/{id}/editais', [EditalController::class, 'filtrarEditaisPorCurso']);
    Route::get('/areas-concentracao/{id}/linhas-pesquisa', [LinhaPesquisaController::class, 'filtrarLinhasPorArea']);
    Route::get('/linhas-pesquisa/{id}/sublinhas', [SublinhaController::class, 'filtrarSublinhasPorLinha']);
});


/* ACESSO RESTRITO - Admin */
Route::prefix('admin')->middleware(['auth', 'permissao:admin'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('inicio');

    Route::get('/analise-inscricoes', [EditalController::class, 'listarVigentes'])->name('analise-inscricoes.index');
    Route::get('/analise-inscricoes/{id}', [InscricaoController::class, 'listarPorEdital'])->name('analise-inscricoes.listar');
    Route::get('/analise-inscricoes/analisar/{id}', [InscricaoController::class, 'analisar'])->name('analise-inscricoes.analisar');

    Route::get('/editais', [EditalController::class, 'listar'])->name('admin.editais.index');
    Route::get('/editais/adicionar', [EditalController::class, 'criar'])->name('admin.editais.adicionar');
    Route::get('/editais/alterar/{id}', [EditalController::class, 'alterar'])->name('admin.editais.alterar');
    Route::post('/editais', [EditalController::class, 'salvar'])->name('admin.editais.salvar');
    Route::put('/editais/{id}', [EditalController::class, 'atualizar'])->name('admin.editais.atualizar');
    Route::delete('/editais/{id}', [EditalController::class, 'excluir'])->name('admin.editais.excluir');

    Route::get('/programas', [ProgramaController::class, 'listar'])->name('pos.programas.index');
    Route::get('/programas/adicionar', [ProgramaController::class, 'criar'])->name('pos.programas.adicionar');
    Route::get('/programas/alterar/{id}', [ProgramaController::class, 'alterar'])->name('pos.programas.alterar');
    Route::post('/programas', [ProgramaController::class, 'salvar'])->name('pos.programas.salvar');
    Route::put('/programas/{id}', [ProgramaController::class, 'atualizar'])->name('pos.programas.atualizar');
    Route::delete('/programas/{id}', [ProgramaController::class, 'excluir'])->name('pos.programas.excluir');

    Route::get('/cursos', [CursoController::class, 'listar'])->name('pos.cursos.index');
    Route::get('/cursos/adicionar', [CursoController::class, 'criar'])->name('pos.cursos.adicionar');
    Route::post('/cursos', [CursoController::class, 'salvar'])->name('pos.cursos.salvar');
    Route::delete('/cursos/{id}', [CursoController::class, 'excluir'])->name('pos.cursos.excluir');

    Route::get('/areas-concentracao', [AreaConcentracaoController::class, 'listar'])->name('pos.areas-concentracao.index');
    Route::get('/areas-concentracao/adicionar', [AreaConcentracaoController::class, 'criar'])->name('pos.areas-concentracao.adicionar');
    Route::get('/areas-concentracao/alterar/{id}', [AreaConcentracaoController::class, 'alterar'])->name('pos.areas-concentracao.alterar');
    Route::post('/areas-concentracao', [AreaConcentracaoController::class, 'salvar'])->name('pos.areas-concentracao.salvar');
    Route::put('/areas-concentracao/{id}', [AreaConcentracaoController::class, 'atualizar'])->name('pos.areas-concentracao.atualizar');
    Route::delete('/areas-concentracao/{id}', [AreaConcentracaoController::class, 'excluir'])->name('pos.areas-concentracao.excluir');

    Route::get('/linhas-pesquisa', [LinhaPesquisaController::class, 'listar'])->name('pos.linhas-pesquisa.index');
    Route::get('/linhas-pesquisa/adicionar', [LinhaPesquisaController::class, 'criar'])->name('pos.linhas-pesquisa.adicionar');
    Route::get('/linhas-pesquisa/alterar/{id}', [LinhaPesquisaController::class, 'alterar'])->name('pos.linhas-pesquisa.alterar');
    Route::post('/linhas-pesquisa', [LinhaPesquisaController::class, 'salvar'])->name('pos.linhas-pesquisa.salvar');
    Route::put('/linhas-pesquisa/{id}', [LinhaPesquisaController::class, 'atualizar'])->name('pos.linhas-pesquisa.atualizar');
    Route::delete('/linhas-pesquisa/{id}', [LinhaPesquisaController::class, 'excluir'])->name('pos.linhas-pesquisa.excluir');

    Route::get('/sublinhas', [SublinhaController::class, 'listar'])->name('pos.sublinhas.index');
    Route::get('/sublinhas/adicionar', [SublinhaController::class, 'criar'])->name('pos.sublinhas.adicionar');
    Route::get('/sublinhas/alterar/{id}', [SublinhaController::class, 'alterar'])->name('pos.sublinhas.alterar');
    Route::post('/sublinhas', [SublinhaController::class, 'salvar'])->name('pos.sublinhas.salvar');
    Route::put('/sublinhas/{id}', [SublinhaController::class, 'atualizar'])->name('pos.sublinhas.atualizar');
    Route::delete('/sublinhas/{id}', [SublinhaController::class, 'excluir'])->name('pos.sublinhas.excluir');

    Route::get('/disciplinas-aluno-externo', [DisciplinaController::class, 'listar'])->name('pos.disciplinas-aluno-externo.index');
    Route::get('/disciplinas-aluno-externo/adicionar', [DisciplinaController::class, 'criar'])->name('pos.disciplinas-aluno-externo.adicionar');
    Route::get('/disciplinas-aluno-externo/alterar/{id}', [DisciplinaController::class, 'alterar'])->name('pos.disciplinas-aluno-externo.alterar');
    Route::patch('/disciplinas-aluno-externo/visibilidade/{id}', [DisciplinaController::class, 'alterarVisibilidade'])->name('pos.disciplinas-aluno-externo.visibilidade');
    Route::post('/disciplinas-aluno-externo', [DisciplinaController::class, 'salvar'])->name('pos.disciplinas-aluno-externo.salvar');
    Route::put('/disciplinas-aluno-externo/{id}', [DisciplinaController::class, 'atualizar'])->name('pos.disciplinas-aluno-externo.atualizar');
    Route::delete('/disciplinas-aluno-externo/{id}', [DisciplinaController::class, 'excluir'])->name('pos.disciplinas-aluno-externo.excluir');

    Route::get('/professores', [UsuarioController::class, 'listarProfessores'])->name('pessoas.professores.index');
    Route::get('/professores/adicionar', [UsuarioController::class, 'criarProfessor'])->name('pessoas.professores.adicionar');
    Route::get('/professores/vincular/{id}', [UsuarioController::class, 'obterProgramasVinculados'])->name('pessoas.professores.programas');
    Route::post('/professores/vincular', [UsuarioController::class, 'vincularPrograma'])->name('pessoas.professores.vincular');
    Route::post('/professores', [UsuarioController::class, 'salvarProfessor'])->name('pessoas.professores.salvar');
    Route::delete('/professores/{id}', [UsuarioController::class, 'excluir'])->name('pessoas.professores.excluir');

    Route::get('/administradores', [UsuarioController::class, 'listarAdministradores'])->name('pessoas.administradores.index');
    Route::get('/administradores/adicionar', [UsuarioController::class, 'criarAdministrador'])->name('pessoas.administradores.adicionar');
    Route::post('/administradores', [UsuarioController::class, 'salvarAdministrador'])->name('pessoas.administradores.salvar');
    Route::delete('/administradores/{id}', [UsuarioController::class, 'excluir'])->name('pessoas.administradores.excluir');

    Route::get('/entrevistas', function () {
        return view('admin.entrevistas.index');
    })->name('admin.entrevistas.index');

});


/* ACESSO RESTRITO - Candidato */
Route::prefix('candidato')->middleware(['auth', 'permissao:candidato'])->group(function () {
    
    Route::get('/', function () {
        return redirect()->route('candidato.editais.index');
    });

    Route::get('/editais', [EditalController::class, 'listarVigentes'])->name('candidato.editais.index');
    Route::get('/editais/{id}', [EditalController::class, 'detalhar'])->name('candidato.editais.details');
    Route::get('/editais/{id}/inscrever', [InscricaoController::class, 'criar'])->name('candidato.editais.inscrever');

    Route::get('/inscricoes', [InscricaoController::class, 'listarPeloCandidato'])->name('candidato.inscricoes.index');

    Route::post('/inscricoes/doutorado', [InscricaoController::class, 'salvarDoutorado'])->name('candidato.inscricao.salvarDoutorado');
    Route::post('/inscricoes/mestrado', [InscricaoController::class, 'salvarMestrado'])->name('candidato.inscricao.salvarMestrado');
    Route::post('/inscricoes/papos', [InscricaoController::class, 'salvarPapos'])->name('candidato.inscricao.salvarPapos');
    Route::post('/inscricoes/aluno-externo', [InscricaoController::class, 'salvarAlunoExterno'])->name('candidato.inscricao.salvarAlunoExterno');


    Route::get('/entrevistas', function () {
        return view('candidato.entrevistas.index');
    })->name('candidato.entrevistas.index');

});