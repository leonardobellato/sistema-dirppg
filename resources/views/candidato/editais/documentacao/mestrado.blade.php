<form action="{{route('inscricao.store')}}" method="POST" enctype="multipart/form-data" id="inscricaoForm">
    @csrf

    <label>Ficha de inscrição 
        <span class="required-content">*</span>
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

    @if($edital->curso->programa->sigla != 'PPGBIOTEC')
        <label>Projeto de Pesquisa
            <span class="required-content">*</span>
        </label>
        <input type="file" name="projeto_pesquisa" accept="application/pdf" required>
    @endif

    @if($edital->curso->programa->sigla == 'PPGECT')
        <label>Declaração de Vinculo com estabelecimento de ensino
            <div class="tooltip">?
                <span class="tooltip-text">
                    Gere um arquivo pdf da declaração assinada pelo representante legal do estabelecimento de ensino ao qual o professor está vinculado, quando for o caso, atestando as disciplinas ministradas pelo candidato, o nível de ensino e o respectivo tempo de atuação em cada uma delas. E/ou documento comprobatório de convênio com a UTFPR conforme a resolução nº 079/12-COPPG de 26/07/2012.
                </span>
            </div>
        </label>
        <input type="file" name="declaracao_vinculo" accept="application/pdf">
    @endif

    @if($edital->curso->programa->sigla == 'PPGCC')
        <label>Dados PosComp
            <span class="required-content">*</span>
            <div class="tooltip">?
                <span class="tooltip-text">
                    Gere um arquivo pdf contendo o número de inscrição do PosComp e ano de realização ou justificativa explicando o porque não realizou o PosComp ou para candidatos residentes fora do Brasil, anexar o comprovante de moradia.
                </span>
            </div>
        </label>
        <input type="file" name="dados_poscomp" accept="application/pdf" required>

        <label>Resumo da Intenção de Pesquisa
            <span class="required-content">*</span>
            <div class="tooltip">?
                <span class="tooltip-text">
                    Gere um arquivo pdf contendo seu resumo de no máximo 500 palavras.
                </span>
            </div>
        </label>
        <input type="file" name="resumo_intencao" accept="application/pdf" required>

        <label>Formulário de Indicação de até 3(três) docentes com quem deseja realizar sua pesquisa.
            <span class="required-content">*</span>
            <div class="tooltip">?
                <span class="tooltip-text">
                    Gere um arquivo pdf contendo este arquivo de no máximo 1(uma) página.
                </span>
            </div>
        </label>
        <input type="file" name="formulario_indicacao" accept="application/pdf" required>
    @endif

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