@extends('layouts.app')

@section('title', 'Edital')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/tabelas.css') }}">
@endpush

@section('content')
    @if(session('success'))
        @include('components.alert', ['type' => 'success', 'message' => session('success')])
    @elseif(session('failure'))
        @include('components.alert', ['type' => 'failure', 'message' => session('failure')])
    @endif

    <h1>Detalhes do edital</h1>

    <div class="container-details">
        <div class="table-wrapper">
            <table class="details-table">
                <tbody>
                    <tr>
                        <th>Nome</th>
                        <td>{{ $edital->nome }}</td>
                    </tr>

                    <tr>
                        <th>Link</th>
                        <td>
                            @if($edital->link)
                                <a href="{{ $edital->link }}" target="_blank">Clique aqui</a>
                            @else
                                <span class="muted">Não cadastrado</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Programa</th>
                        <td>{{ $edital->curso->programa->sigla}}</td>
                    </tr>

                    <tr>
                        <th>Curso</th>
                        <td>{{ $edital->curso->tipo}}</td>
                    </tr>
                </tbody>
            </table>
        </div>


        <h2>Cronograma</h2>

        <div class="fase-container">
            @php
                $tituloFase = [
                    'inscricao' => 'Inscrição',
                    'resultado' => 'Resultado da inscrição',
                    'recurso1' => 'Interposição do 1º recurso',
                    'recurso2' => 'Interposição do 2º recurso',
                ];
            @endphp

            @foreach($fases as $fase)
                <div class="fase">
                    <div class="fase-titulo">
                        {{ $tituloFase[$fase->tipo] }}
                    </div>

                    @if($fase->data_inicio == $fase->data_fim)
                        <div class="fase-data">
                            {{ \Carbon\Carbon::parse($fase->data_inicio)->format('d/m/Y') }}
                        </div>
                    @else
                        <div class="fase-data">
                            De {{ \Carbon\Carbon::parse($fase->data_inicio)->format('d/m/Y') }}
                            até {{ \Carbon\Carbon::parse($fase->data_fim)->format('d/m/Y') }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="btn-grp-details">
            <a href={{ route('professor.analise-inscricoes.index') }}>Voltar</a>
            
            <a href={{ url('professor/analise-inscricoes/edital/'.$edital->id_edital) }} class="confirm">   
                Analisar inscrições
            </a>
        </div>
        <br>
    </div>
    
@endsection
