@extends('layouts.app')

@section('title', 'Minhas inscrições')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
@endpush

@section('content')
    <h1>Minhas inscrições</h1>

    <div class="container-vigentes">
        @forelse($inscricoes as $inscricao)
            <div class="card-edital" onclick="window.location.href='{{ url('candidato/inscricoes/'. $inscricao->id_inscricao) }}'">
                <h2>{{ $inscricao->edital->nome }}</h2>
                <p class="data">
                    <strong>Publicado em:</strong> 
                    {{ \Carbon\Carbon::parse($inscricao->edital->data_publicacao)->format('d/m/Y') }}
                </p>
            </div>
        @empty
            <p>Nenhuma inscrição realizada.</p>
        @endforelse
    </div>
@endsection