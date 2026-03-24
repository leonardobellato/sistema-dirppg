<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Candidato</title>
    <link rel="stylesheet" href="{{ asset('css/autenticacao.css') }}">
</head>
<body>
    <main>
        <div class="login-container">
            @if(session('success'))
                @include('components.alert', ['type' => 'success', 'message' => session('success')])
            @elseif(session('failure'))
                @include('components.alert', ['type' => 'failure', 'message' => session('failure')])
            @endif

            <img src="{{ asset('./logo_dirppg_preto.png')}}" alt="logo" class="img-logo"/>
            <form action="{{ route('autenticacao.cadastro.salvar') }}" method="POST">
                @csrf
                <h3>Cadastro</h3>

                <h4>Dados pessoais</h4>
			    <hr>

                <label for="input-nome">Nome completo:<span class="required-content">*</span></label>
                <input type="text" id="input-nome" name="nome" class="simple-input" placeholder="Digite seu nome aqui" required autofocus
                    value="{{ old('nome') }}" {{-- mantém o valor se der erro --}}
                >

                {{-- erro específico do campo nome --}}
                @error('nome')
                    <span class="campo-invalido">O nome deve ter até 100 caracteres.</span>
                @enderror

                <label>Nacionalidade:<span class="required-content">*</span></label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="nacionalidade" value="brasileiro" id="input-nasc-br" checked>
                        Brasileiro
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="nacionalidade" value="estrangeiro" id="input-nasc-es">
                        Estrangeiro
                    </label>
                </div>

                <label for="input-cpf">CPF:<span class="required-content">*</span></label>
                <input oninput="mascara(this)" type="text" id="input-cpf" name="cpf" class="simple-input" placeholder="000.000.000-00" required
                    value="{{ old('cpf') }}" {{-- mantém o valor se der erro --}}
                >

                {{-- erro específico do campo cpf --}}
                @error('cpf')
                    <span class="campo-invalido">O cpf deve ser válido e único na base de dados.</span>
                @enderror

                <label for="input-telefone">Telefone/Celular<span class="required-content">*</span></label>
                <input type="text" id="input-telefone" name="telefone" class="simple-input" placeholder="(00) 00000-0000" required
                    value="{{ old('telefone') }}" {{-- mantém o valor se der erro --}}
                >

                {{-- erro específico do campo telefone --}}
                @error('telefone')
                    <span class="campo-invalido">O telefone deve ter até 20 caracteres.</span>
                @enderror


                <h4>Dados da conta</h4>
			    <hr>

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

                <div class="actions">
                    <p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
                </div>

                <button id="btn-cadastrar" type="submit">Cadastrar</button>
            </form>
        </div>
    </main>
    <script>
        function mascara(i){
            var v = i.value;
            if(isNaN(v[v.length-1])){
                i.value = v.substring(0, v.length-1);
                return;
            }
            i.setAttribute("maxlength", "14");
            if (v.length == 3 || v.length == 7) i.value += ".";
            if (v.length == 11) i.value += "-";
        }

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
</body>
</html>