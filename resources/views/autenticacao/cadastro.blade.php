<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Candidato</title>
    <link rel="stylesheet" href="{{ asset('css/autenticacao.css') }}">
</head>
<body>
    <main>
        <div class="login-container">
            <img src={{ asset("./logo_dirppg_preto.png")}} alt="logo" class="img-logo"/>
            <form>
                <h3>Cadastro</h3>

                <label for="input-nome">Nome completo:<span class="required-content">*</span></label>
                <input type="text" id="input-nome" class="simple-input" placeholder="Digite seu nome aqui" required>

                <label>Nacionalidade:<span class="required-content">*</span></label>
                <div class="radio-group">
                    <label class="radio-option">
                    <input type="radio" name="nacionalidade" value="brasileiro" id="input-nasc-br" required>
                    Brasileiro
                </label>
                <label class="radio-option">
                    <input type="radio" name="nacionalidade" value="estrangeiro" id="input-nasc-es" required>
                    Estrangeiro
                </label>
                </div>

                <label for="input-cpf">CPF:<span class="required-content">*</span></label>
                <input oninput="mascara(this)" type="text" id="input-cpf" class="simple-input" placeholder="Digite seu CPF aqui" required>

                <label for="input-email">E-mail:<span class="required-content">*</span></label>
                <input type="email" id="input-email" class="simple-input" placeholder="Digite seu e-mail aqui" required>

                <label for="input-email-confirm">Confirme o e-mail:<span class="required-content">*</span></label>
                <input type="email" id="input-email-confirm" class="simple-input" placeholder="Redigite seu e-mail aqui" required>

                <label for="input-senha">Senha:<span class="required-content">*</span></label>
                <input type="password" id="input-senha" class="simple-input" placeholder="Digite sua senha aqui" required>

                <label for="input-senha-confirm">Confirme a senha:<span class="required-content">*</span></label>
                <input type="password" id="input-senha-confirm" class="simple-input" placeholder="Redigite sua senha aqui" required>

                <div class="actions">
                    <p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
                </div>

                <button type="submit">Cadastrar</button>
            </form>
        </div>
    </main>
    <script>
        function mascara(i){
   
        var v = i.value;
        
        if(isNaN(v[v.length-1])){ // impede entrar outro caractere que não seja número
            i.value = v.substring(0, v.length-1);
            return;
        }
        
        i.setAttribute("maxlength", "14");
        if (v.length == 3 || v.length == 7) i.value += ".";
        if (v.length == 11) i.value += "-";

        }
    </script>
</body>
</html>