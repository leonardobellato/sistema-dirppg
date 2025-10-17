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

/* AUTENTICAÇÃO */
Route::get('/', function () {
    return redirect('/login');
});
Route::get('/login', function () { return view('autenticacao.login.index');})->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('autenticacao.login');
Route::get('/logout', [LoginController::class, 'logout'])->name('autenticacao.logout');

Route::get('/cadastro', function () { return view('autenticacao.cadastro.index');})->name('autenticacao.cadastro.index');
Route::post('/cadastro', [UsuarioController::class, 'store'])->name('autenticacao.cadastro.salvar');


/* ACESSO RESTRITO - Qualquer usuário logado */
Route::middleware('auth')->group(function () {
    Route::get('/usuario', [UsuarioController::class, 'edit'])->name('usuarios.alterar');
    Route::put('/usuario', [UsuarioController::class, 'update'])->name('usuarios.atualizar');
});


/* ACESSO RESTRITO - Admin */
Route::prefix('admin')->middleware(['auth', 'permissao:admin'])->group(function () {

    Route::get('/', function () {
        return view('admin.inicio.index');
    })->name('inicio');

    Route::get('/analise-inscricoes', function () {
        return view('admin.analise-inscricoes.index');
    })->name('analise-inscricoes.index');
    
    Route::get('/editais', [EditalController::class, 'index'])->name('admin.editais.index');
    Route::get('/editais/adicionar', [EditalController::class, 'create'])->name('admin.editais.adicionar');
    Route::get('/editais/alterar/{id}', [EditalController::class, 'edit'])->name('admin.editais.alterar');
    Route::post('/editais', [EditalController::class, 'store'])->name('admin.editais.salvar');
    Route::put('/editais/{id}', [EditalController::class, 'update'])->name('admin.editais.atualizar');
    Route::delete('/editais/{id}', [EditalController::class, 'destroy'])->name('admin.editais.excluir');

    Route::get('/programas', [ProgramaController::class, 'index'])->name('pos.programas.index');
    Route::get('/programas/adicionar', [ProgramaController::class, 'create'])->name('pos.programas.adicionar');
    Route::get('/programas/alterar/{id}', [ProgramaController::class, 'edit'])->name('pos.programas.alterar');
    Route::get('/programas/{id}/cursos', [CursoController::class, 'getCursosByPrograma']);
    Route::post('/programas', [ProgramaController::class, 'store'])->name('pos.programas.salvar');
    Route::put('/programas/{id}', [ProgramaController::class, 'update'])->name('pos.programas.atualizar');
    Route::delete('/programas/{id}', [ProgramaController::class, 'destroy'])->name('pos.programas.excluir');

    Route::get('/cursos', [CursoController::class, 'index'])->name('pos.cursos.index');
    Route::get('/cursos/adicionar', [CursoController::class, 'create'])->name('pos.cursos.adicionar');
    Route::get('/cursos/{id}/areas-concentracao', [AreaConcentracaoController::class, 'getAreasByCurso']);
    Route::get('/cursos/{id}/disciplinas-aluno-externo', [DisciplinaController::class, 'getDisciplinasByCurso']);
    Route::get('/cursos/{id}/editais', [EditalController::class, 'getEditaisByCurso']);
    Route::post('/cursos', [CursoController::class, 'store'])->name('pos.cursos.salvar');
    Route::delete('/cursos/{id}', [CursoController::class, 'destroy'])->name('pos.cursos.excluir');

    Route::get('/areas-concentracao', [AreaConcentracaoController::class, 'index'])->name('pos.areas-concentracao.index');
    Route::get('/areas-concentracao/adicionar', [AreaConcentracaoController::class, 'create'])->name('pos.areas-concentracao.adicionar');
    Route::get('/areas-concentracao/alterar/{id}', [AreaConcentracaoController::class, 'edit'])->name('pos.areas-concentracao.alterar');
    Route::get('/areas-concentracao/{id}/linhas-pesquisa', [LinhaPesquisaController::class, 'getLinhasByArea']);
    Route::post('/areas-concentracao', [AreaConcentracaoController::class, 'store'])->name('pos.areas-concentracao.salvar');
    Route::put('/areas-concentracao/{id}', [AreaConcentracaoController::class, 'update'])->name('pos.areas-concentracao.atualizar');
    Route::delete('/areas-concentracao/{id}', [AreaConcentracaoController::class, 'destroy'])->name('pos.areas-concentracao.excluir');

    Route::get('/linhas-pesquisa', [LinhaPesquisaController::class, 'index'])->name('pos.linhas-pesquisa.index');
    Route::get('/linhas-pesquisa/adicionar', [LinhaPesquisaController::class, 'create'])->name('pos.linhas-pesquisa.adicionar');
    Route::get('/linhas-pesquisa/alterar/{id}', [LinhaPesquisaController::class, 'edit'])->name('pos.linhas-pesquisa.alterar');
    Route::get('/linhas-pesquisa/{id}/sublinhas', [SublinhaController::class, 'getSublinhasByLinha']);
    Route::post('/linhas-pesquisa', [LinhaPesquisaController::class, 'store'])->name('pos.linhas-pesquisa.salvar');
    Route::put('/linhas-pesquisa/{id}', [LinhaPesquisaController::class, 'update'])->name('pos.linhas-pesquisa.atualizar');
    Route::delete('/linhas-pesquisa/{id}', [LinhaPesquisaController::class, 'destroy'])->name('pos.linhas-pesquisa.excluir');

    Route::get('/sublinhas', [SublinhaController::class, 'index'])->name('pos.sublinhas.index');
    Route::get('/sublinhas/adicionar', [SublinhaController::class, 'create'])->name('pos.sublinhas.adicionar');
    Route::get('/sublinhas/alterar/{id}', [SublinhaController::class, 'edit'])->name('pos.sublinhas.alterar');
    Route::post('/sublinhas', [SublinhaController::class, 'store'])->name('pos.sublinhas.salvar');
    Route::put('/sublinhas/{id}', [SublinhaController::class, 'update'])->name('pos.sublinhas.atualizar');
    Route::delete('/sublinhas/{id}', [SublinhaController::class, 'destroy'])->name('pos.sublinhas.excluir');

    Route::get('/disciplinas-aluno-externo', [DisciplinaController::class, 'index'])->name('pos.disciplinas-aluno-externo.index');
    Route::get('/disciplinas-aluno-externo/adicionar', [DisciplinaController::class, 'create'])->name('pos.disciplinas-aluno-externo.adicionar');
    Route::get('/disciplinas-aluno-externo/alterar/{id}', [DisciplinaController::class, 'edit'])->name('pos.disciplinas-aluno-externo.alterar');
    Route::post('/disciplinas-aluno-externo', [DisciplinaController::class, 'store'])->name('pos.disciplinas-aluno-externo.salvar');
    Route::put('/disciplinas-aluno-externo/{id}', [DisciplinaController::class, 'update'])->name('pos.disciplinas-aluno-externo.atualizar');
    Route::delete('/disciplinas-aluno-externo/{id}', [DisciplinaController::class, 'destroy'])->name('pos.disciplinas-aluno-externo.excluir');

    Route::get('/professores', function () {
        return view('admin.pessoas.servidores.professores');
    })->name('pessoas.professores');

    Route::get('/secretarios', function () {
        return view('admin.pessoas.servidores.secretarios');
    })->name('pessoas.secretarios');

    Route::get('/entrevistas', function () {
        return view('admin.entrevistas.index');
    })->name('admin.entrevistas.index');

});


/* ACESSO RESTRITO - Candidato */
Route::prefix('candidato')->middleware(['auth', 'permissao:candidato'])->group(function () {
    
    Route::get('/', function () {
        return redirect()->route('candidato.editais.index');
    });

    Route::get('/editais', [EditalController::class, 'listVigentes'])->name('candidato.editais.index');
    Route::get('/editais/{id}', [EditalController::class, 'lookup'])->name('candidato.editais.details');

    Route::get('/inscricoes', function () {
        return view('candidato.inscricoes.index');
    })->name('candidato.inscricoes.index');
    Route::get('/inscricoes/inscrever/{id}', [InscricaoController::class, 'create'])->name('candidato.inscricoes.inscrever');

    Route::get('/entrevistas', function () {
        return view('candidato.entrevistas.index');
    })->name('candidato.entrevistas.index');

});