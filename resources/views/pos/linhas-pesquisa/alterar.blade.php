@extends('layouts.app')

@section('title', 'Linhas de Pesquisa')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
@endpush

@section('content')
    <h1>Alterar linha de pesquisa</h1>

    <div class="container-form">
		<form action="{{ route('pos.linhas-pesquisa.atualizar', ['id' => $linha_pesquisa->id_linha_pesquisa]) }}" method="POST">
            @csrf
            @method('PUT')            

            <label for="input-programa">Programa:<span class="required-content">*</span></label>
			<input id="input-programa" name="programa" type="text" value="{{ $linha_pesquisa->areaConcentracao->curso->programa->nome }}" disabled>


            <label for="input-curso">Curso:<span class="required-content">*</span></label>
			<input id="input-curso" name="curso" type="text" value="{{ $linha_pesquisa->areaConcentracao->curso->tipo }}" disabled>

            <label for="input-area-concentracao">Área de concentração:<span class="required-content">*</span></label>
			<input id="input-area-concentracao" name="area-concentracao" type="text" value="{{ $linha_pesquisa->areaConcentracao->nome }}" disabled>

			<label for="input-nome">Nome da linha:<span class="required-content">*</span></label>
			<input type="text" id="input-nome" name="nome" placeholder="Digite o nome aqui" required autofocus
                value="{{ old('nome', $linha_pesquisa->nome) }}" {{-- mantém o valor se der erro --}}
            >

            {{-- erro específico do campo nome --}}
            @error('nome')
                <span class="campo-invalido">O nome deve ter até 150 caracteres.</span>
            @enderror

            <label for="input-ativo">Ativo:</label>
            <label class="toggle">
                <input type="checkbox" id="input-ativo" name="ativo" value="1"
                    {{ isset($linha_pesquisa) && !$linha_pesquisa->inativo ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>

			<div class="pos-form">
				<p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
			</div>

			<div class="btn-grp-form">
				<a href={{ route('pos.linhas-pesquisa.index') }}>Cancelar</a>
				<button type="submit">Atualizar</button>
			</div>
		</form>
    </div>
@endsection