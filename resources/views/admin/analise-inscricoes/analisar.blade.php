@extends('layouts.app')

@section('title', 'Análise de inscrições')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/formularios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabelas.css') }}">
    <style>
        .doc-item {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .doc-header span{
            text-align: left;
        }
        .doc-header a{
            text-align: right;
        }
        .doc-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .doc-actions input{
            cursor: pointer;
            
        }
        .motivo-container {
            margin-top: 0.5rem;
            display: none;
        }
        textarea {
            width: 100%;
            resize: vertical;
            min-height: 70px;
        }
        .abrir-btn {
            text-decoration: none;
            color: #0d6efd;
            font-weight: 500;
        }
        .abrir-btn:hover {
            text-decoration: underline;
        }

        .comentarios-textarea {
    width: 100%;
    min-height: 120px;
    padding: 10px;
    border: 1px solid rgba(255, 219, 219, 1);
    background-color: #ffe6e6ff;
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

        /* Quando o documento for marcado como OK */
.doc-item.ok {
    background-color: #ccf8d9ff;
    border-color: #198754; /* verde */
}

/* Quando for marcado como NÃO OK */
.doc-item.nao-ok {
    background-color: #f9c5c5ff;
    border-color: #dc3545; /* vermelho */
}
    </style>
@endpush

@section('content')
    @if(session('success'))
        <div class="aviso-sucesso">
            {{ session('success') }}
        </div>
    @endif

    <h1>Análise de Inscrição</h1>

    <div class="container-details">
        <div class="table-wrapper">
            <table class="details-table">
                <tbody>
                    <tr>
                        <th>Nome</th>
                        <td>{{ $inscricao->candidato->nome }}</td>
                    </tr>

                    <tr>
                        <th>CPF</th>
                        <td>{{ $inscricao->candidato->candidato->cpf }}</td>
                    </tr>

                    <tr>
                        <th>E-mail</th>
                        <td>{{ $inscricao->candidato->email }}</td>
                    </tr>

                    <tr>
                        <th>Telefone</th>
                        <td>{{ $inscricao->candidato->candidato->telefone }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <h3>Documentos</h3>

    <div class="container-form">
        <form action="" method="POST">
            @csrf

            @php
                // Apenas um detalhe para deixar o nome do arquivo mais apresentável
                $tipos = [
                    'ficha_inscricao' => 'Ficha de Inscrição',
                    'documento_identificacao' => 'Documento de Identificação Oficial (RG ou CNH)',
                    'cpf' => 'CPF',
                    'diploma' => 'Diploma ou Declaração',
                    'curriculo' => 'Currículo Lattes',
                    'historico' => 'Histórico Escolar',
                    'outro' => 'Outros',
                    'documentacao' => 'Documentação Comprobatória',
                    'projeto_pesquisa' => 'Projeto de Pesquisa',
                    'dissertacao_mestrado' => 'Dissertação de Mestrado',
                    'carta_aceite' => 'Carta de Aceite',
                    'declaracao_vinculo' => 'Declaração de Vínculo',
                    'dados_poscomp' => 'Dados do PosComp',
                    'resumo_intencao' => 'Resumo de Intenção',
                    'formulario_indicacao' => 'Formulário de Indicação',
                ]
            @endphp

            @foreach($inscricao->documentos as $index => $doc)
                <div class="doc-item">
                    <div class="doc-header">
                        <span><strong>{{ $tipos[$doc->tipo] }}</strong></span>
                        <a href="{{ asset('storage/' . $doc->caminho_servidor) }}" target="_blank" class="abrir-btn">
                            📄 Abrir documento
                        </a>
                    </div>

                    <div class="doc-actions">
                        <label>
                            <input type="radio" name="documentos[{{ $index }}][status]" value="deferir" required> Deferir
                        </label>
                        <label>
                            <input type="radio" name="documentos[{{ $index }}][status]" value="indeferir" required> Indeferir
                        </label>
                    </div>

                    <div class="motivo-container">
                        <label for="motivo_{{ $index }}">Motivo:</label>
                        <textarea class="comentarios-textarea" name="documentos[{{ $index }}][motivo]" id="motivo_{{ $index }}" placeholder="Descreva o motivo do indeferimento..."></textarea>
                    </div>

                    <input type="hidden" name="documentos[{{ $index }}][id]" value="{{ $doc->id_documento }}">
                </div>
            @endforeach

            <div class="btn-grp-form">
                <a href="{{route('candidato.editais.index')}}">Voltar</a>
                <button type="submit">Salvar análise</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Mostra/oculta o campo de motivo automaticamente
        document.querySelectorAll('.doc-item').forEach(item => {
            const radios = item.querySelectorAll('input[type="radio"]');
            const motivo = item.querySelector('.motivo-container');

            radios.forEach(r => {
            r.addEventListener('change', () => {
                // Limpa classes anteriores
                item.classList.remove('ok', 'nao-ok');

                if (r.value === 'deferir' && r.checked) {
                    motivo.style.display = 'none';
                    item.classList.add('ok'); // verde
                } 
                else if (r.value === 'indeferir' && r.checked) {
                    motivo.style.display = 'block';
                    item.classList.add('nao-ok'); // vermelho
                }
            });
        });
        });
    });
</script>
@endpush
