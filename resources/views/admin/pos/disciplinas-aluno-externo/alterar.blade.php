@extends('layouts.app')

@section('title', 'Disciplinas de Aluno Externo')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/formularios.css') }}">
@endpush

@section('content')
    <h1>Alterar disciplina</h1>

    <div class="container-form">
		<form action="{{ route('pos.disciplinas-aluno-externo.atualizar', ['id' => $disciplina->id_disciplina]) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="input-programa">Programa:<span class="required-content">*</span></label>
			<input id="input-programa" name="programa" type="text" value="{{ $disciplina->curso->programa->nome }}" disabled>


            <label for="input-curso">Curso:<span class="required-content">*</span></label>
			<input id="input-curso" name="curso" type="text" value="{{ $disciplina->curso->tipo }}" disabled>

			<label for="input-nome">Nome da disciplina:<span class="required-content">*</span></label>
			<input type="text" id="input-nome" name="nome" placeholder="Digite o nome aqui" required autofocus
                value="{{ old('nome', $disciplina->nome) }}" {{-- mantém o valor se der erro --}}
            >

            {{-- erro específico do campo nome --}}
            @error('nome')
                <span class="campo-invalido">O nome deve ter até 150 caracteres.</span>
            @enderror

            <label for="input-ativo">Ativo:</label>
            <label class="toggle">
                <input type="checkbox" id="input-ativo" name="ativo" value="1"
                    {{ isset($disciplina) && !$disciplina->inativo ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>

			<div class="pos-form">
				<p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
			</div>

			<div class="btn-grp-form">
				<a href={{ route('pos.disciplinas-aluno-externo.index') }}>Cancelar</a>
				<button type="submit">Atualizar</button>
			</div>
		</form>
    </div>
@endsection