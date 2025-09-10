@extends('layouts.app')

@section('title', 'Programas')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
@endpush

@section('content')
    <h1>Alterar programa</h1>

    <div class="container-form">
		<form action="{{ route('pos.programas.atualizar', ['id' => $programa->id_programa]) }}" method="POST">
            @csrf
            @method('PUT')

			<label for="input-nome">Nome do programa:<span class="required-content">*</span></label>
			<input type="text" id="input-nome" name="nome" placeholder="Digite o nome aqui" required autofocus
                value="{{ old('input-nome', $programa->nome)}}"
            >

            {{-- erro específico do campo nome --}}
            @error('nome')
                <span class="campo-invalido">O nome deve ter até 100 caracteres e ser único na base de dados.</span>
            @enderror

			<div class="pos-form">
				<p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
			</div>

			<div class="btn-grp-form">
				<a href={{ route('pos.programas.index') }}>Cancelar</a>
				<button type="submit">Atualizar</button>
			</div>
		</form>
    </div>
@endsection