<form action="{{route('inscricao.salvar')}}" method="POST" enctype="multipart/form-data" id="inscricaoForm">
    @csrf

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


    <label>Currículo Lattes 
        <span class="required-content">*</span>
    </label>
    <input type="file" name="curriculo" accept="application/pdf" required>


    <label>Projeto de Pesquisa
        <span class="required-content">*</span>
    </label>
    <input type="file" name="projeto_pesquisa" accept="application/pdf">

    <label>Carta de Aceite</label>
    <input type="file" name="carta_aceite" accept="application/pdf">

    <label>Outros
        <div class="tooltip">?
            <span class="tooltip-text">
                Gere um arquivo pdf contendo outro(s) documentos requisitados. (Cotas,Reservista...)
            </span>
        </div>
    </label>
    <input type="file" name="outro" accept="application/pdf">

    <label for="input-comentarios" class="comentarios-label">Comentários (opcional)</label>
    <textarea id="input-comentarios" name="comentarios" class="comentarios-textarea" placeholder="Se quiser, escreva algo que considere relevante para o processo seletivo (opcional)" maxlength="1000">{{ old('comentarios') }}</textarea>

    <div class="termos">
        <label>
            <input type="checkbox" name="aceito_termos" required>
            <span class="termos-label">
                Li e aceito os 
                <a href="#" target="_blank">Termos e Condições</a>
                referentes ao processo de inscrição.
            </span>
        </label>

        <div class="termos-text">
            <p>a) Declaro ter lido o edital de seleção na íntegra;</p>
            <p>b) Os Programas de Pós-Graduação da UTFPR-PG não se responsabilizam pelo envio incorreto de documentos;</p>
            <p>c) Os Programas de Pós-Graduação da UTFPR-PG não se responsabilizam pelo envio de documentos ilegíveis;</p>
            <p>d) Os Programas de Pós-Graduação da UTFPR-PG não se responsabilizam pela falta de documentos;</p>
            <p>e) Os Programas de Pós-Graduação da UTFPR-PG não se responsabilizam por problemas de conexão de internet no momento do envio dos documentos;</p>
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