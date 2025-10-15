@extends('layouts.app')

@section('title', 'Editais')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <style>
        .card-edital {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .card-edital:hover {
            transform: translateY(-4px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .card-edital h2 {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .card-edital p {
            font-size: 0.95rem;
            color: #555;
            margin: 4px 0;
        }

        .card-edital .data {
            font-size: 0.9rem;
            color: #777;
            margin-top: 10px;
        }

        @media (max-width: 600px) {
            .card-edital {
                padding: 15px;
            }
        }
    </style>
@endpush

@section('content')
    @if(session('success'))
        <div class="aviso-sucesso">
            {{ session('success') }}
        </div>
    @endif

    <h1>Editais abertos</h1>

    <div class="container-details">
        @forelse($editais as $edital)
            <div class="card-edital" onclick="window.location.href='{{ url('candidato/editais/'.$edital->id_edital) }}'">
                <h2>{{ $edital->nome }}</h2>
                <p><strong>Programa:</strong> {{ $edital->curso->programa->sigla ?? '-' }}</p>
                <p><strong>Curso:</strong> {{ $edital->curso->tipo ?? '-' }}</p>
                <p class="data">
                    <strong>Publicado em:</strong> 
                    {{ \Carbon\Carbon::parse($edital->data_publicacao)->format('d/m/Y') }}
                </p>
            </div>
        @empty
            <p>Nenhum edital disponível no momento.</p>
        @endforelse
    </div>
@endsection
