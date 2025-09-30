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


Route::get('/', function () {
    return view('inicio.index');
})->name('inicio')->middleware('auth');

Route::get('/login', function () { return view('autenticacao.login.index');})->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('autenticacao.login');
Route::get('/logout', [LoginController::class, 'logout'])->name('autenticacao.logout');

Route::get('/cadastro', function () { return view('autenticacao.cadastro.index');})->name('autenticacao.cadastro.index');
Route::post('/cadastro', [UsuarioController::class, 'store'])->name('autenticacao.cadastro.salvar');


Route::middleware('auth')->group(function () {
    Route::get('/usuario', [UsuarioController::class, 'edit'])->name('pessoas.usuarios.alterar');
    Route::put('/usuario', [UsuarioController::class, 'update'])->name('pessoas.usuarios.atualizar');
});

//['auth', 'role:admin']

Route::get('/admin/analise-inscricoes', function () {
    return view('analise-inscricoes.index');
})->name('analise-inscricoes.index');


Route::get('/admin/editais', [EditalController::class, 'index'])->name('editais.index');
Route::get('/admin/editais/adicionar', [EditalController::class, 'create'])->name('editais.adicionar');
Route::get('/admin/editais/alterar/{id}', [EditalController::class, 'edit'])->name('editais.alterar');
Route::post('/admin/editais', [EditalController::class, 'store'])->name('editais.salvar');
Route::put('/admin/editais/{id}', [EditalController::class, 'update'])->name('editais.atualizar');
Route::delete('/admin/editais/{id}', [EditalController::class, 'destroy'])->name('editais.excluir');

Route::get('/admin/programas', [ProgramaController::class, 'index'])->name('pos.programas.index');
Route::get('/admin/programas/adicionar', [ProgramaController::class, 'create'])->name('pos.programas.adicionar');
Route::get('/admin/programas/alterar/{id}', [ProgramaController::class, 'edit'])->name('pos.programas.alterar');
Route::get('/admin/programas/{id}/cursos', [CursoController::class, 'getCursosByPrograma']);
Route::post('/admin/programas', [ProgramaController::class, 'store'])->name('pos.programas.salvar');
Route::put('/admin/programas/{id}', [ProgramaController::class, 'update'])->name('pos.programas.atualizar');
Route::delete('/admin/programas/{id}', [ProgramaController::class, 'destroy'])->name('pos.programas.excluir');

Route::get('/admin/cursos', [CursoController::class, 'index'])->name('pos.cursos.index');
Route::get('/admin/cursos/adicionar', [CursoController::class, 'create'])->name('pos.cursos.adicionar');
Route::get('/admin/cursos/{id}/areas-concentracao', [AreaConcentracaoController::class, 'getAreasByCurso']);
Route::get('/admin/cursos/{id}/disciplinas-aluno-externo', [DisciplinaController::class, 'getDisciplinasByCurso']);
Route::get('/admin/cursos/{id}/editais', [EditalController::class, 'getEditaisByCurso']);
Route::post('/admin/cursos', [CursoController::class, 'store'])->name('pos.cursos.salvar');
Route::delete('/admin/cursos/{id}', [CursoController::class, 'destroy'])->name('pos.cursos.excluir');

Route::get('/admin/areas-concentracao', [AreaConcentracaoController::class, 'index'])->name('pos.areas-concentracao.index');
Route::get('/admin/areas-concentracao/adicionar', [AreaConcentracaoController::class, 'create'])->name('pos.areas-concentracao.adicionar');
Route::get('/admin/areas-concentracao/alterar/{id}', [AreaConcentracaoController::class, 'edit'])->name('pos.areas-concentracao.alterar');
Route::get('/admin/areas-concentracao/{id}/linhas-pesquisa', [LinhaPesquisaController::class, 'getLinhasByArea']);
Route::post('/admin/areas-concentracao', [AreaConcentracaoController::class, 'store'])->name('pos.areas-concentracao.salvar');
Route::put('/admin/areas-concentracao/{id}', [AreaConcentracaoController::class, 'update'])->name('pos.areas-concentracao.atualizar');
Route::delete('/admin/areas-concentracao/{id}', [AreaConcentracaoController::class, 'destroy'])->name('pos.areas-concentracao.excluir');

Route::get('/admin/linhas-pesquisa', [LinhaPesquisaController::class, 'index'])->name('pos.linhas-pesquisa.index');
Route::get('/admin/linhas-pesquisa/adicionar', [LinhaPesquisaController::class, 'create'])->name('pos.linhas-pesquisa.adicionar');
Route::get('/admin/linhas-pesquisa/alterar/{id}', [LinhaPesquisaController::class, 'edit'])->name('pos.linhas-pesquisa.alterar');
Route::get('/admin/linhas-pesquisa/{id}/sublinhas', [SublinhaController::class, 'getSublinhasByLinha']);
Route::post('/admin/linhas-pesquisa', [LinhaPesquisaController::class, 'store'])->name('pos.linhas-pesquisa.salvar');
Route::put('/admin/linhas-pesquisa/{id}', [LinhaPesquisaController::class, 'update'])->name('pos.linhas-pesquisa.atualizar');
Route::delete('/admin/linhas-pesquisa/{id}', [LinhaPesquisaController::class, 'destroy'])->name('pos.linhas-pesquisa.excluir');

Route::get('/admin/sublinhas', [SublinhaController::class, 'index'])->name('pos.sublinhas.index');
Route::get('/admin/sublinhas/adicionar', [SublinhaController::class, 'create'])->name('pos.sublinhas.adicionar');
Route::get('/admin/sublinhas/alterar/{id}', [SublinhaController::class, 'edit'])->name('pos.sublinhas.alterar');
Route::post('/admin/sublinhas', [SublinhaController::class, 'store'])->name('pos.sublinhas.salvar');
Route::put('/admin/sublinhas/{id}', [SublinhaController::class, 'update'])->name('pos.sublinhas.atualizar');
Route::delete('/admin/sublinhas/{id}', [SublinhaController::class, 'destroy'])->name('pos.sublinhas.excluir');

Route::get('/admin/disciplinas-aluno-externo', [DisciplinaController::class, 'index'])->name('pos.disciplinas-aluno-externo.index');
Route::get('/admin/disciplinas-aluno-externo/adicionar', [DisciplinaController::class, 'create'])->name('pos.disciplinas-aluno-externo.adicionar');
Route::get('/admin/disciplinas-aluno-externo/alterar/{id}', [DisciplinaController::class, 'edit'])->name('pos.disciplinas-aluno-externo.alterar');
Route::post('/admin/disciplinas-aluno-externo', [DisciplinaController::class, 'store'])->name('pos.disciplinas-aluno-externo.salvar');
Route::put('/admin/disciplinas-aluno-externo/{id}', [DisciplinaController::class, 'update'])->name('pos.disciplinas-aluno-externo.atualizar');
Route::delete('/admin/disciplinas-aluno-externo/{id}', [DisciplinaController::class, 'destroy'])->name('pos.disciplinas-aluno-externo.excluir');

Route::get('/admin/professores', function () {
    return view('pessoas.professores');
})->name('pessoas.professores');

Route::get('/admin/secretarios', function () {
    return view('pessoas.secretarios');
})->name('pessoas.secretarios');

Route::get('/admin/entrevistas', function () {
    return view('entrevistas.index');
})->name('entrevistas.index');