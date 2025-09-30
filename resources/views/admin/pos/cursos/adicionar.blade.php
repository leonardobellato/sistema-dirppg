@extends('layouts.app')

@section('title', 'Cursos')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
@endpush

@section('content')
    <h1>Adicionar curso</h1>

    <div class="container-form">
		<form action="{{ route('pos.cursos.salvar') }}" method="POST">
            @csrf

			<label for="input-programa">Programa:<span class="required-content">*</span></label>
			<select id="input-programa" name="programa" required>
				<option value="">Selecione...</option>
				
                @foreach($programas as $programa)
					<option value="{{ $programa->id_programa }}">{{ $programa->nome }}</option>
				@endforeach
				
			</select>

            <label for="input-tipo">Tipo do curso:<span class="required-content">*</span></label>
			<select id="input-tipo" name="tipo" required>
				<option value="">Selecione...</option>
				<option value="Mestrado">Mestrado</option>
				<option value="Doutorado">Doutorado</option>
				<option value="PAPOS">PAPOS</option>
                <option value="Aluno Externo">Aluno Externo</option>
			</select>

			<div class="pos-form">
				<p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
			</div>

			<div class="btn-grp-form">
				<a href={{ route('pos.cursos.index') }}>Cancelar</a>
				<button type="submit">Cadastrar</button>
			</div>
		</form>
    </div>
@endsection