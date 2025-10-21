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
            background-color: #e3e8ecff;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            text-align: center;
            line-height: 18px;
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
            margin: 30px 0;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 0 20px 15px 20px;
            background-color: rgba(255,255,255,0.6);
        }

        .termos label {
            display: flex;
            align-items: flex-start;
            font-weight: 500;
            margin-bottom: 10px;
            margin-top: 15px;
        }

        .termos a{
            margin: 0 5px;
        }

        .termos input[type="checkbox"] {
            width: 16px;     /* tamanho horizontal */
            height: 16px;    /* tamanho vertical */
            margin-top: 3px; /* leve ajuste vertical */
            margin-right: 10px;
            cursor: pointer;
            accent-color: #0076df; /* cor do check (moderno e bonito) */
            flex-shrink: 0; /* evita esticar dentro do flex container */
        }

        .termos-text {
            margin-left: 10px;
            color: #555;
            text-align: justify;
        }

        .comentarios-label{
            display:block;
            margin-top:25px;
            font-weight:500;
        }

        .comentarios-textarea {
            width: 100%;
            min-height: 120px;
            padding: 10px;
            border: 1px solid rgb(224, 224, 224);
            background-color: #f9f9f9;
            font-size: 14px;
            color: #272727;
            margin-top: 12px;
            border-radius: 6px;
            resize: vertical;
            line-height: 1.4;
        }

        .comentarios-textarea:focus {
            background-color: #ffffff;
            outline: none;
            border-color: #0076df;
            box-shadow: 0 0 0 3px rgba(0,118,223,0.08);
        }
    </style>
@endpush

@section('content')
    <h1>Inscrição no {{ $edital->curso->programa->nome }}</br>({{ $edital->curso->tipo }})</h1>

    
    <div class="atencao-box">
        <b>Atenção:</b> &nbsp;Serão aceitos apenas arquivos <b>PDF</b>, de no máximo <b>5 MB</b>. <br>
        A dissertação de mestrado (quando necessário) deve conter no máximo <b>15 MB</b>.
    </div>
    
    <div class="container-form">
        <form action="{{route('inscricao.store')}}" method="POST" enctype="multipart/form-data" id="inscricaoForm">
            @csrf

            <label>Ficha de inscrição 
                <span class="required-content">*</span>
                <div class="tooltip">?
                    <span class="tooltip-text">
                        Gere um arquivo PDF da sua ficha de inscrição preenchida e assinada.
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
                <div class="tooltip">?
                    <span class="tooltip-text">
                        Gere um PDF com frente e verso do documento.
                    </span>
                </div>
            </label>
            <input type="file" name="cpf" accept="application/pdf" required>

            <label>Diploma ou Declaração de Conclusão da Graduação 
                <span class="required-content">*</span>
                <div class="tooltip">?
                    <span class="tooltip-text">
                        Gere um arquivo pdf contendo as páginas (frente e verso) do seu diploma ou declaração.
                    </span>
                </div>
            </label>
            <input type="file" name="diploma" accept="application/pdf" required>

            <label>Currículo Lattes 
                <span class="required-content">*</span>
            </label>
            <input type="file" name="curriculo" accept="application/pdf" required>

            <label>Histórico Escolar da Graduação 
                <span class="required-content">*</span>
                <div class="tooltip">?
                    <span class="tooltip-text">
                        Gere um arquivo pdf com todas as páginas do seu histórico escolar.
                    </span>
                </div>
            </label>
            <input type="file" name="historico" accept="application/pdf" required>

            <label>Texto Dissertativo ou Projeto de Pesquisa</label>
            <input type="file" name="texto_dissertativo" accept="application/pdf">

            <label>Outro (Cotas, Reservista etc.)</label>
            <input type="file" name="outro" accept="application/pdf">

            <label for="input-comentarios" class="comentarios-label">Comentários (opcional)</label>
            <textarea id="input-comentarios" name="comentarios" class="comentarios-textarea" placeholder="Se quiser, escreva algo que considere relevante para o processo seletivo (opcional)" maxlength="1000">{{ old('comentarios') }}</textarea>

            <div class="termos">
                <label>
                    <input type="checkbox" name="aceito_termos" required>
                    Li e aceito os <a href="#" target="_blank" style="color:#0076df; text-decoration:none; font-weight:600;"> Termos e Condições </a> referentes ao processo de inscrição.
                </label>

                <div class="termos-text">
                    a) Declaro ter lido o edital de seleção na íntegra;<br>
                    b) Os Programas de Pós-Graduação da UTFPR-PG não se responsabilizam pelo envio incorreto de documentos;<br>
                    c) Os Programas de Pós-Graduação da UTFPR-PG não se responsabilizam pelo envio de documentos ilegíveis;<br>
                    d) Os Programas de Pós-Graduação da UTFPR-PG não se responsabilizam pela falta de documentos;<br>
                    e) Os Programas de Pós-Graduação da UTFPR-PG não se responsabilizam por problemas de conexão de internet no momento do envio dos documentos;<br>
                </div>
            </div> 

            <div class="pos-form">
                <p><span class="required-content">*</span> Campos obrigatórios</p>
            </div>
        
            <div class="btn-grp-form">
                <a href="{{route('candidato.editais.index')}}">Voltar</a>
                <button type="submit">Enviar Inscrição</button>
            </div>
        </form>
    </div>
@endsection
