@extends('layouts.app')

@section('title', 'Áreas de Concentração')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/formularios.css') }}">
@endpush

@section('content')
    <h1>Alterar área de concentração</h1>

    <div class="container-form">
		<form action="{{ route('pos.areas-concentracao.atualizar', ['id' => $area_concentracao->id_area_concentracao]) }}" method="POST">
            @csrf
            @method('PUT')            

            <label for="input-programa">Programa:<span class="required-content">*</span></label>
			<input id="input-programa" name="programa" type="text" value="{{ $area_concentracao->curso->programa->nome }}" disabled>


            <label for="input-curso">Curso:<span class="required-content">*</span></label>
			<input id="input-curso" name="curso" type="text" value="{{ $area_concentracao->curso->tipo }}" disabled>

			<label for="input-nome">Nome do área:<span class="required-content">*</span></label>
			<input type="text" id="input-nome" name="nome" placeholder="Digite o nome aqui" required autofocus
                value="{{ old('nome', $area_concentracao->nome) }}" {{-- mantém o valor se der erro --}}
            >

            {{-- erro específico do campo nome --}}
            @error('nome')
                <span class="campo-invalido">O nome deve ter até 150 caracteres.</span>
            @enderror

            <label for="input-ativo">Ativo:</label>
            <label class="toggle">
                <input type="checkbox" id="input-ativo" name="ativo" value="1"
                    {{ isset($area_concentracao) && !$area_concentracao->inativo ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>

			<div class="pos-form">
				<p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
			</div>

			<div class="btn-grp-form">
				<a href={{ route('pos.areas-concentracao.index') }}>Cancelar</a>
				<button type="submit">Atualizar</button>
			</div>
		</form>
    </div>
@endsection