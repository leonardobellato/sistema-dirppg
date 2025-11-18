<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #222;
            background: #f6f6f6;
            padding: 20px;
        }
        .container {
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            max-width: 650px;
            margin: auto;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header img {
            max-width: 180px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            color: #111;
        }
        .content {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .footer {
            font-size: 13px;
            color: #555;
            margin-top: 30px;
            border-top: 1px solid #e5e5e5;
            padding-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">

        <div class="header">
            <img src="{{ $message->embed($caminhoImagem) }}" alt="Logo DIRPPG">
        </div>

        <div class="title">
            Aviso da Diretoria de Pesquisa e Pós-Graduação (DIRPPG)
        </div>

        <div class="content">
            {{-- Aqui entra o conteúdo dinâmico da mensagem --}}
            {!! $mensagem !!}
        </div>

        <div class="footer">
            Universidade Tecnológica Federal do Paraná – Câmpus Ponta Grossa<br>
            Este é um e-mail automático. Por favor, não responda diretamente.
        </div>
    </div>

</body>
</html>
