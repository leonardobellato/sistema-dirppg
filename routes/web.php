<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.index');
})->name('dashboard');;

Route::get('/login', function () {
    return redirect('/login/candidato');
});

Route::get('/login/candidato', function () {
    return view('autenticacao.login.candidato');
})->name('login.candidato');

Route::get('/login/restrito', function () {
    return view('autenticacao.login.restrito');
})->name('login.restrito');

Route::get('/cadastro', function () {
    return view('autenticacao.cadastro');
})->name('cadastro');

Route::get('/editais', function () {
    $editais = [
        ['id'=> 1, 'nome' => 'Selecao 11/2025', 'data_publicacao' => '01/01/2025', 'vigencia' => false],
        ['id'=> 2, 'nome' => 'Selecao 30/2025', 'data_publicacao' => '04/08/2025', 'vigencia' => true]
    ];
    return view('editais.index', ['editais' => $editais]);
})->name('editais.index');

Route::get('/editais/adicionar', function () {
    return view('editais.adicionar');
})->name('editais.adicionar');

Route::get('/editais/alterar/{id}', function ($id) {
    return view('editais.alterar', ['id' => $id]);
})->name('editais.alterar');

Route::get('/editais/excluir', function () {
    return view('editais.excluir');
})->name('editais.excluir');

Route::get('/analise-inscricoes', function () {
    return view('analise-inscricoes.index');
})->name('analise-inscricoes.index');

Route::get('/programas', function () {
    return view('pos.programas.index');
})->name('pos.programas.index');

Route::get('/cursos', function () {
    return view('pos.cursos.index');
})->name('pos.cursos.index');

Route::get('/areas-concentracao', function () {
    return view('pos.areas-concentracao.index');
})->name('pos.areas-concentracao.index');

Route::get('/linhas-pesquisa', function () {
    return view('pos.linhas-pesquisa.index');
})->name('pos.linhas-pesquisa.index');

Route::get('/sublinhas', function () {
    return view('pos.sublinhas.index');
})->name('pos.sublinhas.index');

Route::get('/disciplinas-aluno-externo', function () {
    return view('pos.disciplinas-aluno-externo.index');
})->name('pos.disciplinas-aluno-externo.index');

Route::get('/professores', function () {
    return view('professores.index');
})->name('professores.index');