@extends('layouts.app')

@section('title', 'Editais')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/tabelas.css') }}">
@endpush

@section('content')
    @php
        $tipoUsuario = auth()->user()->tipo;
    @endphp

    <h1>Editais abertos</h1>

    <div class="container-vigentes">
        @forelse($editais as $edital)
            <div class="card-edital" onclick="window.location.href='{{ url($tipoUsuario . '/analise-inscricoes/'.$edital->id_edital) }}'">
                <h2>{{ $edital->nome }}</h2>
                <p class="data">
                    <strong>Publicado em:</strong> 
                    {{ \Carbon\Carbon::parse($edital->data_publicacao)->format('d/m/Y') }}
                </p>
            </div>
        @empty
            <p class="text-center">Nenhum edital disponível no momento.</p>
        @endforelse
    </div>
@endsection
