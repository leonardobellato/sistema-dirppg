@extends('layouts.app')

@section('title', 'Inscrição')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <style>
        .tooltip {
            position: relative;
            display: inline-block;
            cursor: help;
            margin-left: 5px;
            color: #0076df;
            font-weight: bold;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            width: 260px;
            background-color: #272727;
            color: #fff;
            text-align: left;
            border-radius: 8px;
            padding: 10px;
            position: absolute;
            z-index: 1;
            bottom: 125%; /* acima do ícone */
            left: 50%;
            margin-left: -130px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 13px;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        input[type="file"] {
            background-color: #f9f9f9;
            border: 1px solid rgb(224, 224, 224);
            border-radius: 5px;
            padding: 6px;
            cursor: pointer;
        }

        input[type="file"]::file-selector-button {
            background-color: #0076df;
            border: none;
            color: #fff;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }

        input[type="file"]::file-selector-button:hover {
            background-color: #0167c0;
        }

        .field-info {
            font-size: 13px;
            color: #555;
            margin-top: 5px;
        }

        #inscricaoForm{

        }

        .atencao-box {
            background-color: #fff8e5;
            border: 1px solid #ffcc66;
            border-radius: 8px;
            padding: 15px 20px;
            color: #6b4b00;
            font-size: 15px;
            width: 700px;
            max-width: 75%;
            margin: 30px 0 0 0;
            line-height: 1.5;
        }

        .atencao-box b {
            color: #d35400;
        }

        .termos {
            margin-top: 40px;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 15px 20px;
            background-color: rgba(255,255,255,0.6);
        }

        .termos label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .termos input[type="checkbox"] {
            margin-top: 4px;
            transform: scale(1.2);
            cursor: pointer;
        }

        .termos-text {
            margin-left: 25px;
            color: #555;
        }
    </style>
@endpush

@section('content')
    <h1>Inscrição</h1>

    
<div class="atencao-box">
        <b>Atenção:</b> Os arquivos devem conter no máximo <b>5 MB</b>. <br>
        A dissertação de mestrado (quando necessário) deve conter no máximo <b>15 MB</b>.
    </div>
    <div class="container-form">
        <form action="" method="POST" enctype="multipart/form-data" id="inscricaoForm">
            @csrf

            <label>Ficha de inscrição 
                <span class="required-content">*</span>
                <div class="tooltip">?
                    <span class="tooltip-text">
                        Gere um arquivo PDF da sua ficha de inscrição preenchida e assinada, enviada através do sistema.
                    </span>
                </div>
            </label>
            <input type="file" name="ficha_inscricao" accept="application/pdf" required>

            <label>Documento de Identificação Oficial (RG ou CNH) 
                <span class="required-content">*</span>
                <div class="tooltip">?
                    <span class="tooltip-text">
                        Gere um PDF com frente e verso do documento oficial com foto.
                    </span>
                </div>
            </label>
            <input type="file" name="documento_identificacao" accept="application/pdf" required>

            <label>CPF 
                <span class="required-content">*</span>
            </label>
            <input type="file" name="cpf" accept="application/pdf" required>

            <label>Diploma ou Declaração de Conclusão da Graduação 
                <span class="required-content">*</span>
            </label>
            <input type="file" name="diploma" accept="application/pdf" required>

            <label>Currículo Lattes 
                <span class="required-content">*</span>
            </label>
            <input type="file" name="curriculo" accept="application/pdf" required>

            <label>Documentação Comprobatória 
                <span class="required-content">*</span>
                <div class="tooltip">?
                    <span class="tooltip-text">
                        Envie um arquivo PDF contendo a documentação solicitada conforme o edital desta seleção.
                    </span>
                </div>
            </label>
            <input type="file" name="documentacao_comprobatoria" accept="application/pdf" required>

            <label>Histórico Escolar da Graduação 
                <span class="required-content">*</span>
            </label>
            <input type="file" name="historico" accept="application/pdf" required>

            <label>Texto Dissertativo ou Projeto de Pesquisa 
                <div class="tooltip">?
                    <span class="tooltip-text">
                        Envie seu texto dissertativo ou projeto de pesquisa (exceto para PPGBIOTEC).
                    </span>
                </div>
            </label>
            <input type="file" name="texto_dissertativo" accept="application/pdf">

            <label>Outro (Cotas, Reservista etc.)</label>
            <input type="file" name="outro" accept="application/pdf">
        
            <div class="pos-form">
                <p><span class="required-content">*</span> Campos obrigatórios</p>
            </div>

            <div class="termos">
            <label>
                <input type="checkbox" name="aceito_termos" required>
                Li e aceito os <a href="#" target="_blank" style="color:#0076df; text-decoration:none; font-weight:600;">Termos e Condições</a> referentes ao processo de inscrição.
            </label>

            <div class="termos-text">
                a) Declaro ter lido o edital de seleção na íntegra;<br>
                b) Os Programas de Pós-Graduação da UTFPR-PG não se responsabilizam pelo envio incorreto de documentos;<br>
                c) Os Programas de Pós-Graduação da UTFPR-PG não se responsabilizam pelo envio de documentos ilegíveis;<br>
                d) Os Programas de Pós-Graduação da UTFPR-PG não se responsabilizam pela falta de documentos;<br>
                e) Os Programas de Pós-Graduação da UTFPR-PG não se responsabilizam por problemas de conexão de internet no momento do envio dos documentos;<br>
            </div>
        </div>
        
            <div class="btn-grp-form">
                <a href="">Voltar</a>
                <button type="submit">Enviar Inscrição</button>
            </div>
        </form>
    </div>
@endsection
