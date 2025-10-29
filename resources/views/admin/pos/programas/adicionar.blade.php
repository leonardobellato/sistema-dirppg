@extends('layouts.app')

@section('title', 'Programas')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/formularios.css') }}">
@endpush

@section('content')
    <h1>Adicionar programa</h1>

    <div class="container-form">
		<form action="{{ route('pos.programas.salvar') }}" method="POST">
            @csrf

			<label for="input-nome">Nome do programa:<span class="required-content">*</span></label>
			<input type="text" id="input-nome" name="nome" placeholder="Digite o nome aqui" required
                value="{{ old('nome') }}" {{-- mantém o valor se der erro --}}
            >

            {{-- erro específico do campo nome --}}
            @error('nome')
                <span class="campo-invalido">O nome deve ter até 100 caracteres e ser único na base de dados.</span>
            @enderror

            <label for="input-sigla">Sigla do programa:<span class="required-content">*</span></label>
			<input type="text" id="input-sigla" name="sigla" placeholder="Digite a sigla aqui" required
                value="{{ old('sigla') }}" {{-- mantém o valor se der erro --}}
            >

            {{-- erro específico do campo sigla --}}
            @error('sigla')
                <span class="campo-invalido">A sigla deve ter até 10 caracteres e ser única na base de dados.</span>
            @enderror

			<div class="pos-form">
				<p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
			</div>

			<div class="btn-grp-form">
				<a href={{ route('pos.programas.index') }}>Cancelar</a>
				<button type="submit">Cadastrar</button>
			</div>
		</form>
    </div>
@endsection