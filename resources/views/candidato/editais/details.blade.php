@extends('layouts.app')

@section('title', 'Edital')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
@endpush

@section('content')
    @if(session('success'))
        <div class="aviso-sucesso">
            {{ session('success') }}
        </div>
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
                    'resultadoInsc' => 'Resultado da inscrição',
                    'recurso' => 'Interposição de recurso',
                    'resultadoRec' => 'Resultado do recurso',
                ];
            @endphp

            @foreach($fases as $fase)
                <div class="fase">
                    <div class="fase-titulo">
                        {{ $tituloFase[$fase[0]->tipo] ?? $fase[0]->tipo }} ({{ $fase[0]->ordem }}º)
                    </div>

                    @if($fase[0]->data_inicio == $fase[0]->data_fim)
                        <div class="fase-data">
                            {{ \Carbon\Carbon::parse($fase[0]->data_inicio)->format('d/m/Y') }}
                        </div>
                    @else
                        <div class="fase-data">
                            De {{ \Carbon\Carbon::parse($fase[0]->data_inicio)->format('d/m/Y') }}
                            até {{ \Carbon\Carbon::parse($fase[0]->data_fim)->format('d/m/Y') }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>



        @php
            use Carbon\Carbon;
            $podeInscrever = Carbon::parse($fases['inscricao_1'][0]->data_fim)->isFuture();
        @endphp

        @if(!$podeInscrever)
            <p class="warning"> As inscrições estão encerradas.</p>
        @endif

        <div class="btn-grp-details">
            <a href={{ route('candidato.editais.index') }}>Voltar</a>
            
            <a 
                href={{ route('candidato.editais.index') }} class="confirm {{ $podeInscrever ? '' : 'disabled-link' }}"
            >   
                Inscrever-se
            </a>
        </div>
    </div>
    
@endsection