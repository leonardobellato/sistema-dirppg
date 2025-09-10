@extends('layouts.app')

@section('title', 'Sublinhas')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
@endpush

@section('content')
    <h1>Alterar sublinhas</h1>

    <div class="container-form">
		<form action="{{ route('pos.sublinhas.atualizar', ['id' => $sublinha->id_sublinha]) }}" method="POST">
            @csrf
            @method('PUT')            

            <label for="input-programa">Programa:<span class="required-content">*</span></label>
			<input id="input-programa" name="programa" type="text" value="{{ $sublinha->linhaPesquisa->areaConcentracao->curso->programa->nome }}" disabled>


            <label for="input-curso">Curso:<span class="required-content">*</span></label>
			<input id="input-curso" name="curso" type="text" value="{{ $sublinha->linhaPesquisa->areaConcentracao->curso->tipo }}" disabled>

            <label for="input-area-concentracao">Área de concentração:<span class="required-content">*</span></label>
			<input id="input-area-concentracao" name="area-concentracao" type="text" value="{{ $sublinha->linhaPesquisa->areaConcentracao->nome }}" disabled>

            <label for="input-linha-pesquisa">Linha de pesquisa:<span class="required-content">*</span></label>
			<input id="input-linha-pesquisa" name="linha-pesquisa" type="text" value="{{ $sublinha->linhaPesquisa->nome }}" disabled>

			<label for="input-nome">Nome da linha:<span class="required-content">*</span></label>
			<input type="text" id="input-nome" name="nome" placeholder="Digite o nome aqui" required autofocus
                value="{{ old('nome', $sublinha->nome) }}" {{-- mantém o valor se der erro --}}
            >

            {{-- erro específico do campo nome --}}
            @error('nome')
                <span class="campo-invalido">O nome deve ter até 150 caracteres.</span>
            @enderror

            <label for="input-ativo">Ativo:</label>
            <label class="toggle">
                <input type="checkbox" id="input-ativo" name="ativo" value="1"
                    {{ isset($sublinha) && !$sublinha->inativo ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>

			<div class="pos-form">
				<p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
			</div>

			<div class="btn-grp-form">
				<a href={{ route('pos.sublinhas.index') }}>Cancelar</a>
				<button type="submit">Atualizar</button>
			</div>
		</form>
    </div>
@endsection