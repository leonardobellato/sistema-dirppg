@extends('layouts.app')

@section('title', 'Usuário')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
@endpush

@section('content')
    @if(session('success'))
        <div class="aviso-sucesso">
            {{ session('success') }}
        </div>
    @endif


    <h1>Alterar usuário</h1>

    <div class="container-form">
		<form action="{{ route('pessoas.usuarios.atualizar') }}" method="POST">
            @csrf
            @method('PUT')

            <h4>Dados pessoais</h4>
            <hr>            

            <label for="input-nome">Nome completo:<span class="required-content">*</span></label>
            <input type="text" id="input-nome" name="nome" class="simple-input" placeholder="Digite seu nome aqui" required autofocus
                value="{{ old('nome', $usuario->nome) }}" {{-- mantém o valor se der erro --}}
            >

            {{-- erro específico do campo nome --}}
            @error('nome')
                <span class="campo-invalido">O nome deve ter até 100 caracteres.</span>
            @enderror



            @if(Auth::check() && Auth::user()->tipo === 'candidato')

            <label for="input-cpf">CPF:<span class="required-content">*</span></label>
            <input oninput="mascara(this)" type="text" id="input-cpf" name="cpf" class="simple-input" placeholder="000.000.000-00" value="{{$usuario->candidato->cpf}}" disabled>

            <label for="input-telefone">Telefone/Celular<span class="required-content">*</span></label>
            <input type="text" id="input-telefone" name="telefone" class="simple-input" placeholder="(00) 00000-0000" required
                value="{{ old('telefone', $usuario->candidato->telefone) }}" {{-- mantém o valor se der erro --}}
            >

            {{-- erro específico do campo telefone --}}
            @error('telefone')
                <span class="campo-invalido">O telefone deve ter até 20 caracteres.</span>
            @enderror
        
            @endif


            <h4>Dados da conta</h4>
            <hr>

            <label for="input-email">E-mail:<span class="required-content">*</span></label>
            <input type="email" id="input-email" name="email" class="simple-input" placeholder="Digite seu e-mail aqui" required
                value="{{ old('email', $usuario->email) }}" {{-- mantém o valor se der erro --}}
            >

            {{-- erro específico do campo email --}}
            @error('email')
                <span class="campo-invalido">O email deve ter até 100 caracteres.</span>
            @enderror

            <label for="input-senha">Senha:</label>
            <input type="password" id="input-senha" name="senha" class="simple-input" placeholder="Digite sua senha aqui">

            {{-- erro específico do campo senha --}}
            @error('senha')
                <span class="campo-invalido">A senha deve ter no mínimo 8 caracteres.</span>
            @enderror


			<div class="pos-form">
				<p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
			</div>

			<div class="btn-grp-form">
				<a href={{ route('inicio') }}>Cancelar</a>
				<button type="submit">Atualizar</button>
			</div>
		</form>
    </div>
@endsection