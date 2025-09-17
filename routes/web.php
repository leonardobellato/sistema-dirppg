<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EditalController;
use App\Http\Controllers\ProgramaController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AreaConcentracaoController;
use App\Http\Controllers\LinhaPesquisaController;
use App\Http\Controllers\SublinhaController;
use App\Http\Controllers\DisciplinaController;
use App\Http\Controllers\AutenticacaoController;

Route::get('/', function () {
    return view('dashboard.index');
})->name('dashboard');;

Route::get('/login', function () {
    return redirect('/login/candidato');
});

Route::get('/login/candidato', function () {
    return view('autenticacao.login.candidato');
})->name('autenticacao.login.candidato');

Route::get('/login/restrito', function () {
    return view('autenticacao.login.restrito');
})->name('autenticacao.login.restrito');


Route::get('/cadastro', [AutenticacaoController::class, 'index'])->name('autenticacao.cadastro.index');
Route::get('/cadastro/confirmacao', function () { return view('autenticacao.cadastro.confirmacao');})->name('autenticacao.cadastro.confirmacao');
Route::post('/cadastro/salvar', [AutenticacaoController::class, 'store'])->name('autenticacao.cadastro.salvar');


Route::get('/analise-inscricoes', function () {
    return view('analise-inscricoes.index');
})->name('analise-inscricoes.index');


Route::get('/editais', [EditalController::class, 'index'])->name('editais.index');
Route::get('/editais/adicionar', [EditalController::class, 'create'])->name('editais.adicionar');
Route::get('/editais/alterar/{id}', [EditalController::class, 'edit'])->name('editais.alterar');
Route::post('/editais/salvar', [EditalController::class, 'store'])->name('editais.salvar');
Route::put('/editais/{id}', [EditalController::class, 'update'])->name('editais.atualizar');
Route::delete('/editais/{id}', [EditalController::class, 'destroy'])->name('editais.excluir');

Route::get('/programas', [ProgramaController::class, 'index'])->name('pos.programas.index');
Route::get('/programas/adicionar', [ProgramaController::class, 'create'])->name('pos.programas.adicionar');
Route::get('/programas/alterar/{id}', [ProgramaController::class, 'edit'])->name('pos.programas.alterar');
Route::get('/programas/{id}/cursos', [CursoController::class, 'getCursosByPrograma']);
Route::post('/programas/salvar', [ProgramaController::class, 'store'])->name('pos.programas.salvar');
Route::put('/programas/{id}', [ProgramaController::class, 'update'])->name('pos.programas.atualizar');
Route::delete('/programas/{id}', [ProgramaController::class, 'destroy'])->name('pos.programas.excluir');

Route::get('/cursos', [CursoController::class, 'index'])->name('pos.cursos.index');
Route::get('/cursos/adicionar', [CursoController::class, 'create'])->name('pos.cursos.adicionar');
Route::get('/cursos/{id}/areas-concentracao', [AreaConcentracaoController::class, 'getAreasByCurso']);
Route::get('/cursos/{id}/disciplinas-aluno-externo', [DisciplinaController::class, 'getDisciplinasByCurso']);
Route::get('/cursos/{id}/editais', [EditalController::class, 'getEditaisByCurso']);
Route::post('/cursos/salvar', [CursoController::class, 'store'])->name('pos.cursos.salvar');
Route::delete('/cursos/{id}', [CursoController::class, 'destroy'])->name('pos.cursos.excluir');

Route::get('/areas-concentracao', [AreaConcentracaoController::class, 'index'])->name('pos.areas-concentracao.index');
Route::get('/areas-concentracao/adicionar', [AreaConcentracaoController::class, 'create'])->name('pos.areas-concentracao.adicionar');
Route::get('/areas-concentracao/alterar/{id}', [AreaConcentracaoController::class, 'edit'])->name('pos.areas-concentracao.alterar');
Route::get('/areas-concentracao/{id}/linhas-pesquisa', [LinhaPesquisaController::class, 'getLinhasByArea']);
Route::post('/areas-concentracao/salvar', [AreaConcentracaoController::class, 'store'])->name('pos.areas-concentracao.salvar');
Route::put('/areas-concentracao/{id}', [AreaConcentracaoController::class, 'update'])->name('pos.areas-concentracao.atualizar');
Route::delete('/areas-concentracao/{id}', [AreaConcentracaoController::class, 'destroy'])->name('pos.areas-concentracao.excluir');

Route::get('/linhas-pesquisa', [LinhaPesquisaController::class, 'index'])->name('pos.linhas-pesquisa.index');
Route::get('/linhas-pesquisa/adicionar', [LinhaPesquisaController::class, 'create'])->name('pos.linhas-pesquisa.adicionar');
Route::get('/linhas-pesquisa/alterar/{id}', [LinhaPesquisaController::class, 'edit'])->name('pos.linhas-pesquisa.alterar');
Route::get('/linhas-pesquisa/{id}/sublinhas', [SublinhaController::class, 'getSublinhasByLinha']);
Route::post('/linhas-pesquisa/salvar', [LinhaPesquisaController::class, 'store'])->name('pos.linhas-pesquisa.salvar');
Route::put('/linhas-pesquisa/{id}', [LinhaPesquisaController::class, 'update'])->name('pos.linhas-pesquisa.atualizar');
Route::delete('/linhas-pesquisa/{id}', [LinhaPesquisaController::class, 'destroy'])->name('pos.linhas-pesquisa.excluir');

Route::get('/sublinhas', [SublinhaController::class, 'index'])->name('pos.sublinhas.index');
Route::get('/sublinhas/adicionar', [SublinhaController::class, 'create'])->name('pos.sublinhas.adicionar');
Route::get('/sublinhas/alterar/{id}', [SublinhaController::class, 'edit'])->name('pos.sublinhas.alterar');
Route::post('/sublinhas/salvar', [SublinhaController::class, 'store'])->name('pos.sublinhas.salvar');
Route::put('/sublinhas/{id}', [SublinhaController::class, 'update'])->name('pos.sublinhas.atualizar');
Route::delete('/sublinhas/{id}', [SublinhaController::class, 'destroy'])->name('pos.sublinhas.excluir');

Route::get('/disciplinas-aluno-externo', [DisciplinaController::class, 'index'])->name('pos.disciplinas-aluno-externo.index');
Route::get('/disciplinas-aluno-externo/adicionar', [DisciplinaController::class, 'create'])->name('pos.disciplinas-aluno-externo.adicionar');
Route::get('/disciplinas-aluno-externo/alterar/{id}', [DisciplinaController::class, 'edit'])->name('pos.disciplinas-aluno-externo.alterar');
Route::post('/disciplinas-aluno-externo/salvar', [DisciplinaController::class, 'store'])->name('pos.disciplinas-aluno-externo.salvar');
Route::put('/disciplinas-aluno-externo/{id}', [DisciplinaController::class, 'update'])->name('pos.disciplinas-aluno-externo.atualizar');
Route::delete('/disciplinas-aluno-externo/{id}', [DisciplinaController::class, 'destroy'])->name('pos.disciplinas-aluno-externo.excluir');

Route::get('/professores', function () {
    return view('pessoas.professores');
})->name('pessoas.professores');

Route::get('/secretarios', function () {
    return view('pessoas.secretarios');
})->name('pessoas.secretarios');

Route::get('/entrevistas', function () {
    return view('entrevistas.index');
})->name('entrevistas.index');