<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.index');
})->name('dashboard');;

Route::get('/login', function () {
    return view('autenticacao.autenticacao');
})->name('autenticacao');

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

Route::get('/editais/editar', function () {
    return view('editais.editar');
})->name('editais.editar');

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