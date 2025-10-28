<form action="{{route('candidato.inscricao.salvarMestrado')}}" method="POST" enctype="multipart/form-data" id="inscricaoForm">
    @csrf

    <input type="hidden" name="id_edital" value="{{ $edital->id_edital }}"> <!-- Não apagar! -->

    <label for="input-area-concentracao">Área de concentração:<span class="required-content">*</span></label>
    <select id="input-area-concentracao" name="area-concentracao" required>
        <option value="">Selecione...</option>
        
        @foreach($edital->curso->areasConcentracao as $area)
            <option value="{{ $area->id_area_concentracao }}">{{ $area->nome }}</option>
        @endforeach
    
    </select>

    <label for="input-linha-pesquisa">Linha de pesquisa:<span class="required-content">*</span></label>
    <select id="input-linha-pesquisa" name="linha-pesquisa" disabled required>
        <option value="">Selecione...</option>		
    </select>

    <label for="input-sublinha">Sublinha:<span class="required-content">*</span></label>
    <select id="input-sublinha" name="sublinha" disabled required>
        <option value="">Selecione...</option>		
    </select>

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

@push('scripts')
    <script>
        const areaSelect = document.getElementById('input-area-concentracao');
        const linhaSelect = document.getElementById('input-linha-pesquisa');
        const sublinhaSelect = document.getElementById('input-sublinha');

        // Ao mudar o programa
        areaSelect.addEventListener('change', function() {
            const idArea = this.value;

            // Resetar linhas e sublinhas
            linhaSelect.innerHTML = '<option value="">Selecione...</option>';
            linhaSelect.disabled = true;

            sublinhaSelect.innerHTML = '<option value="">Selecione...</option>';
            sublinhaSelect.disabled = true;

            console.log(idArea);

            if (idArea) {
                linhaSelect.innerHTML = '<option>Carregando...</option>';
                const baseUrl = "{{ url('/') }}";
                fetch(`${baseUrl}/areas-concentracao/${idArea}/linhas-pesquisa`)
                    .then(response => response.json())
                    .then(data => {
                        linhaSelect.innerHTML = '<option value="">Selecione...</option>';
                        data.forEach(linha => {
                            linhaSelect.innerHTML += `<option value="${linha.id_linha_pesquisa}">${linha.nome}</option>`;
                        });
                        linhaSelect.disabled = false;
                    })
                    .catch(() => {
                        linhaSelect.innerHTML = '<option value="">Erro ao carregar linhas</option>';
                    });
            }
        });

        // Ao mudar a linha
        linhaSelect.addEventListener('change', function() {
            const idLinha = this.value;

            // Resetar sublinhas
            sublinhaSelect.innerHTML = '<option value="">Selecione...</option>';
            sublinhaSelect.disabled = true;
            sublinhaSelect.required = false;

            if (idLinha) {
                sublinhaSelect.innerHTML = '<option>Carregando...</option>';
                const baseUrl = "{{ url('/') }}";
                fetch(`${baseUrl}/linhas-pesquisa/${idLinha}/sublinhas`)
                    .then(response => response.json())
                    .then(data => {
                        sublinhaSelect.innerHTML = '<option value="">Selecione...</option>';
                        
                        if (data.length > 0) {
                            data.forEach(sublinha => {
                                sublinhaSelect.innerHTML += `<option value="${sublinha.id_sublinha}">${sublinha.nome}</option>`;
                            });
                            sublinhaSelect.disabled = false;
                            sublinhaSelect.required = true; 
                        } else {
                            sublinhaSelect.innerHTML = '<option value="">Não há sublinhas</option>';
                        }
                    })
                    .catch(() => {
                        sublinhaSelect.innerHTML = '<option value="">Erro ao carregar sublinhas</option>';
                    });
            }
        });
    </script>
@endpush