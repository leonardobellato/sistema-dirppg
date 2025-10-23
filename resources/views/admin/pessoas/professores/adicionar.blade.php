@extends('layouts.app')

@section('title', 'Professores')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
@endpush

@section('content')
    <h1>Adicionar professor</h1>

    <div class="container-form">
		<form action="{{ route('pessoas.professores.salvar') }}" method="POST">
            @csrf

            <label for="input-nome">Nome:<span class="required-content">*</span></label>
			<input type="text" id="input-nome" name="nome" placeholder="Digite seu nome aqui" required>

            <label for="input-email">E-mail:<span class="required-content">*</span></label>
            <input type="email" id="input-email" name="email" class="simple-input" placeholder="Digite seu e-mail aqui" required
                value="{{ old('email') }}" {{-- mantém o valor se der erro --}}
            >

            {{-- erro específico do campo email --}}
            @error('email')
                <span class="campo-invalido">O email deve ter até 100 caracteres.</span>
            @enderror

            <label for="input-email-confirm">Confirme o e-mail:<span class="required-content">*</span></label>
            <input type="email" id="input-email-confirm" name="email_confirmacao" class="simple-input" placeholder="Redigite seu e-mail aqui" required>

            <span class="campo-invalido confirmacao" id="confirmacao-email">O e-mail não corresponde ao digitado anteriormente.</span>

            <label for="input-senha">Senha:<span class="required-content">*</span></label>
            <input type="password" id="input-senha" name="senha" class="simple-input" placeholder="Digite sua senha aqui" required>

            {{-- erro específico do campo senha --}}
            @error('senha')
                <span class="campo-invalido">A senha deve ter no mínimo 8 caracteres.</span>
            @enderror

            <label for="input-senha-confirm">Confirme a senha:<span class="required-content">*</span></label>
            <input type="password" id="input-senha-confirm" name="senha_confirmacao" class="simple-input" placeholder="Redigite sua senha aqui" required>

            <span class="campo-invalido confirmacao" id="confirmacao-senha">A senha não corresponde à digitada anteriormente.</span>

			<div class="pos-form">
				<p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
			</div>

			<div class="btn-grp-form">
				<a href={{ route('pessoas.professores.index') }}>Cancelar</a>
				<button type="submit">Cadastrar</button>
			</div>
		</form>
    </div>
@endsection

@push('scripts')
    <script>
        // Função genérica para validar campos de confirmação
        function validarConfirmacao(campo1, campo2, avisoId) {
            const input1 = document.getElementById(campo1);
            const input2 = document.getElementById(campo2);
            const aviso = document.getElementById(avisoId);
            const btn = document.getElementById('btn-cadastrar');

            input2.addEventListener("blur", () => {
                if (input1.value !== input2.value) {
                    aviso.style.display = "inline-block";
                    btn.disabled = true;
                } else {
                    aviso.style.display = "none";
                    btn.disabled = false;
                }
            });
        }

        // Aplicando a função para e-mail e senha
        document.addEventListener("DOMContentLoaded", () => {
            validarConfirmacao("input-email", "input-email-confirm", "confirmacao-email");
            validarConfirmacao("input-senha", "input-senha-confirm", "confirmacao-senha");
        });
        
    </script>
@endpush