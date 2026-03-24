@extends('layouts.app')

@section('title', 'Análise de inscrições')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/formularios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabelas.css') }}">
@endpush

@section('content')
    @if(session('success'))
        @include('components.alert', ['type' => 'success', 'message' => session('success')])
    @elseif(session('failure'))
        @include('components.alert', ['type' => 'failure', 'message' => session('failure')])
    @endif

    @php
        $tipoUsuario = auth()->user()->tipo;
    @endphp

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

                    @if($inscricao->edital->curso->tipo === 'Aluno Externo')
                        <tr>
                            <th>Disciplina</th>
                            <td>{{ $inscricao->disciplina->nome }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <h3>Documentos</h3>

    <div class="container-form">
        <form action="{{ route($tipoUsuario . '.analise-inscricoes.salvar', $inscricao->id_inscricao) }}" method="POST">
            @csrf

            @foreach($inscricao->documentos as $index => $doc)
                @php
                    $status = is_null($doc->deferido) ? null : ($doc->deferido ? 'deferir' : 'indeferir');
                @endphp

                <div class="doc-item {{ $status === 'deferir' ? 'ok' : ($status === 'indeferir' ? 'nao-ok' : '') }}">
                    <div class="doc-header">
                        <span><strong>{{ $doc->tipo }}</strong><span class="required-content">*</span></span>
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
                $status_inscricao = is_null($inscricao->deferido) ? null : ($inscricao->deferido ? 'deferir' : 'indeferir');
            @endphp
            <div class="doc-actions v2">
                <label>
                    <input type="radio" name="inscricao_status" 
                        value="deferir" 
                        {{ $status_inscricao === 'deferir' ? 'checked' : '' }}> Deferir
                </label>
                <label>
                    <input type="radio" name="inscricao_status" 
                        value="indeferir" 
                        {{ is_null($status_inscricao) ? 'checked' : ($status_inscricao === 'indeferir' ? 'checked' : '') }}> Indeferir
                </label>
            </div>

            <div id="comentario-container" style="display: none;">
                <label for="comentario-geral">Comentários sobre o indeferimento (opcional):</label>
                <textarea class="comentarios-textarea" name="comentario-geral" id="comentario-geral" placeholder="Descreva aqui...">{{ old('comentario-geral', $inscricao->motivo_indeferimento) }}</textarea>
            </div>

            <div class="btn-grp-form">
                <a href="{{route($tipoUsuario . '.analise-inscricoes.listar', $inscricao->id_edital)}}">Voltar</a>
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

        const inscricaoRadios = document.querySelectorAll('input[name="inscricao_status"]');
        const comentarioContainer = document.getElementById('comentario-container');

        function toggleComentario() {
            const indeferido = document.querySelector('input[name="inscricao_status"][value="indeferir"]').checked;
            comentarioContainer.style.display = indeferido ? 'block' : 'none';
        }

        // Executa ao carregar a página (mantém visível se já estava selecionado)
        toggleComentario();

        // Executa quando o usuário troca a seleção
        inscricaoRadios.forEach(radio => radio.addEventListener('change', toggleComentario));
    });

</script>
@endpush
