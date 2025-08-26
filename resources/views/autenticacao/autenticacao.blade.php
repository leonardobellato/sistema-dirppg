<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Candidato</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="w-100 login-container" style="max-width: 500px;">
        <div class="text-center mb-4">
            <img src={{ asset('logo_dirppg.png') }} alt="logo" class="img-fluid" style="max-width: 200px;">
        </div>

        <!-- Alternador -->
        <div class="d-flex justify-content-center mb-4">
            <div class="toggle-wrap border">
                <a class="toggle-option selected">Entrar</a>
                <a class="toggle-option">Cadastrar</a>
            </div>
        </div>

        <!-- Formulário Login -->
        <div class="content active">
            <form method="POST" action="/login" class="card shadow p-4">
                @csrf
                <div class="mb-3">
                    <label class="form-label">E-mail:</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="text" name="email" id="input-email-login" class="form-control" placeholder="Digite seu e-mail aqui" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Senha:</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="input-pd-login" class="form-control" placeholder="Digite sua senha aqui" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Entrar</button>
                <div class="mt-3">
                    <a href="#">Esqueci a senha</a>
                </div>
            </form>
        </div>

        <!-- Formulário Cadastro -->
        <div class="content">
            <form method="POST" action="/register" class="card shadow p-4">
                @csrf
                <div class="mb-3">
                    <label for="input-name" class="form-label">Nome completo:<span class="required-content">*</span></label>
                    <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="nome" id="input-name" class="form-control" placeholder="Digite seu nome aqui" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nacionalidade:<span class="required-content">*</span></label>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="nacionalidade" value="brasileiro" id="input-nasc-br" required>
                    <label class="form-check-label" for="input-nasc-br">Brasileiro</label>
                    </div>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="nacionalidade" value="estrangeiro" id="input-nasc-es" required>
                    <label class="form-check-label" for="input-nasc-es">Estrangeiro</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="input-cpf" class="form-label">CPF:<span class="required-content">*</span></label>
                    <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                    <input type="text" name="cpf" id="input-cpf" class="form-control" placeholder="Digite seu CPF aqui" required oninput="mascara(this)">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="input-email-signup" class="form-label">E-mail:<span class="required-content">*</span></label>
                    <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" id="input-email-signup" class="form-control" placeholder="Digite seu e-mail aqui" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="input-email-confirm" class="form-label">Confirme o e-mail:<span class="required-content">*</span></label>
                    <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope-check"></i></span>
                    <input type="email" name="email_confirmation" id="input-email-confirm" class="form-control" placeholder="Redigite seu e-mail aqui" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="input-pd-signup" class="form-label">Senha:<span class="required-content">*</span></label>
                    <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="input-pd-signup" class="form-control" placeholder="Digite sua senha aqui" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="input-pd-confirm" class="form-label">Confirme a senha:<span class="required-content">*</span></label>
                    <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation" id="input-pd-confirm" class="form-control" placeholder="Redigite sua senha aqui" required>
                    </div>
                </div>

                <div class="text-muted mb-2">
                    <p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
                </div>

                <button type="submit" class="btn btn-success w-100">Cadastrar</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
