@extends('layouts.app')

@section('title', 'Minhas Inscrições')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/formularios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabelas.css') }}">
    <link rel="stylesheet" href="{{ asset('css/inscricao.css') }}">
@endpush

@section('content')
    @if(session('success'))
        <div class="aviso-sucesso">
            {{ session('success') }}
        </div>
    @endif

    @if(session('failure'))
        <div class="aviso-falha">
            {{ session('failure') }}
        </div>
    @endif

    <h1>Minha Inscrição</h1>

    <div class="container-details">
        <div class="table-wrapper">
            <table class="details-table">
                <tbody>
                    <tr>
                        <th>Edital</th>
                        <td>{{ $inscricao->edital->nome }}</td>
                    </tr>

                    <tr>
                        <th>Programa</th>
                        <td>{{ $inscricao->edital->curso->programa->nome }}</td>
                    </tr>

                    <tr>
                        <th>Curso</th>
                        <td>{{ $inscricao->edital->curso->tipo }}</td>
                    </tr>

                    @if($inscricao->edital->curso->tipo === 'Aluno Externo')
                        <tr>
                            <th>Disciplina</th>
                            <td>{{ $inscricao->disciplina->nome }}</td>
                        </tr>
                    @endif

                    <tr>
                        <th>Situação</th>

                        @php
                            $situacao = 'pendente';

                            if($faseAtual && in_array($faseAtual->tipo, ['resultadoInsc', 'resultadoRec'])) {
                                $situacao = is_null($inscricao->deferido) ? 'pendente' : ($inscricao->deferido ? 'deferido' : 'indeferido');
                            }

                        @endphp

                        <td id={{ $situacao }}>
                            <b>{{ ucfirst($situacao) }}</b>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <p>Fase atual: {{ $faseAtual->tipo ?? 'Nenhuma fase ativa' }}</p>

    <h3>Documentos</h3>

    <div class="container-form">
        <form action="{{ route('candidato.inscricoes.recurso', $inscricao->id_inscricao) }}" method="POST" enctype="multipart/form-data">
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

                    @if($status === 'indeferir')
                        <div class="motivo-container" style="display: {{ $status === 'indeferir' ? 'block' : 'none' }}">
                            <label for="motivo_{{ $index }}">Motivo do indeferimento:</label>
                            <textarea class="comentarios-textarea" name="documentos[{{ $index }}][motivo]" id="motivo_{{ $index }}" readonly>{{ $doc->motivo_indeferimento }}</textarea>
                        </div>

                        {{--
                        @if($podeRecurso)
                            <div>
                                <label for="novo_doc_{{ $index }}">Reenviar documento:</label>
                                <input type="file" name="documentos[{{ $index }}][arquivo]" id="novo_doc_{{ $index }}" accept="application/pdf">
                            </div>
                        @endif
                        --}}
                    @endif

                    <input type="hidden" name="documentos[{{ $index }}][id]" value="{{ $doc->id_documento}}">
                </div>
            @endforeach


            @if($inscricao->motivo_indeferimento)
                <label for="comentario-geral">Comentários sobre o indeferimento:</label>
                <textarea class="comentarios-textarea" name="comentario-geral" readonly>{{$inscricao->motivo_indeferimento}}</textarea>
            @endif

            {{--@if($podeRecurso)
                <div class="btn-grp-form">
                    <a href="{{ route('candidato.inscricoes.index') }}">Voltar</a>
                    <button type="submit">Enviar recurso</button>
                </div>
            @endif --}}

             <div class="btn-grp-form">
                <a href="{{ route('candidato.inscricoes.index') }}">Voltar</a>
        </form>
    </div>
@endsection


