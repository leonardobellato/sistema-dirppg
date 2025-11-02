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
    border: 1px solid rgba(206, 206, 206, 1);
    padding: 10px;
    font-size: 14px;
    color: #272727;
    margin-top: 12px;
    border-radius: 6px;
    resize: vertical;
    line-height: 1.4;
}

.doc-item .comentarios-textarea {
    border: 1px solid rgba(255, 219, 219, 1);
    background-color: #ffe6e6ff;
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

    @if($inscricao->id_avaliador)
        <br>
        <p><strong>Última análise feita por:</strong> {{ $inscricao->avaliador->nome ?? 'Usuário removido' }}</p>
    @endif

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
        <form action="{{ route('analise-inscricoes.salvar', $inscricao->id_inscricao) }}" method="POST">
            @csrf

            @foreach($inscricao->documentos as $index => $doc)
                @php
                    $status = is_null($doc->deferido) ? null : ($doc->deferido ? 'deferir' : 'indeferir');
                @endphp

                <div class="doc-item {{ $status === 'deferir' ? 'ok' : ($status === 'indeferir' ? 'nao-ok' : '') }}">
                    <div class="doc-header">
                        <span><strong>{{ $doc->tipo }}</strong></span>
                        <a href="{{ asset('storage/' . $doc->caminho_servidor) }}" target="_blank" class="abrir-btn">
                            📄 Abrir documento
                        </a>
                    </div>

                    <div class="doc-actions">
                        <label>
                            <input type="radio" name="documentos[{{ $index }}][status]" 
                                value="deferir" 
                                {{ $status === 'deferir' ? 'checked' : '' }}> Deferir
                        </label>
                        <label>
                            <input type="radio" name="documentos[{{ $index }}][status]" 
                                value="indeferir" 
                                {{ $status === 'indeferir' ? 'checked' : '' }}> Indeferir
                        </label>
                    </div>

                    <div class="motivo-container" style="display: {{ $status === 'indeferir' ? 'block' : 'none' }}">
                        <label for="motivo_{{ $index }}">Motivo:</label>
                        <textarea class="comentarios-textarea" name="documentos[{{ $index }}][motivo]" id="motivo_{{ $index }}">{{ $doc->motivo_indeferimento }}</textarea>
                    </div>

                    <input type="hidden" name="documentos[{{ $index }}][id]" value="{{ $doc->id_documento }}">
                </div>
            @endforeach

            <label for="comentario-candidato">Observações feitas pelo candidato:</label>
            <textarea class="comentarios-textarea" name="comentario-candidato" disabled>{{ $inscricao->comentarios ?? '' }}</textarea>
   
            <br><br><hr>

            <h3>Situação da inscrição:</h3>
            @php
                $status_inscricao = is_null($inscricao->deferido) ? 'null' : ($inscricao->deferido ? 'deferir' : 'indeferir');
            @endphp
            <div class="doc-actions">
                <label>
                    <input type="radio" name="inscricao_status" 
                        value="deferir" 
                        {{ $status_inscricao === 'deferir' ? 'checked' : '' }}> Deferir
                </label>
                <label>
                    <input type="radio" name="inscricao_status" 
                        value="indeferir" 
                        {{ $status_inscricao === 'indeferir' ? 'checked' : '' }}> Indeferir
                </label>
            </div>
            <label for="comentario-geral">Comentários sobre o indeferimento (opcional):</label>
            <textarea class="comentarios-textarea" name="comentario-geral" placeholder="Descreva aqui...">{{ old('comentario-geral', $inscricao->motivo_indeferimento) }}</textarea>

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
        document.querySelectorAll('.doc-item').forEach(item => {
            const radios = item.querySelectorAll('input[type="radio"]');
            const motivo = item.querySelector('.motivo-container');

            // Inicializa visibilidade do motivo baseado no estado salvo
            const indeferidoChecked = item.querySelector('input[value="indeferir"]:checked');
            const deferidoChecked = item.querySelector('input[value="deferir"]:checked');

            if (indeferidoChecked) {
                motivo.style.display = 'block';
                item.classList.add('nao-ok');
            } else if (deferidoChecked) {
                motivo.style.display = 'none';
                item.classList.add('ok');
            }

            radios.forEach(r => {
                r.addEventListener('change', () => {
                    item.classList.remove('ok', 'nao-ok');

                    if (r.value === 'deferir' && r.checked) {
                        motivo.style.display = 'none';
                        item.classList.add('ok');
                    } else if (r.value === 'indeferir' && r.checked) {
                        motivo.style.display = 'block';
                        item.classList.add('nao-ok');
                    }
                });
            });
        });
    });

</script>
@endpush
