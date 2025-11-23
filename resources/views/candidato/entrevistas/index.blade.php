@extends('layouts.app')

@section('title', 'Entrevistas')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/tabelas.css') }}">
@endpush

@section('content')
    <h1>Entrevistas</h1>

    <div class="container-vigentes">
        @forelse($entrevistas as $entrevista)
            <div class="card-edital" onclick="window.location.href='{{ url('candidato/entrevistas/'.$entrevista->id_entrevista) }}'">
                <h2>Entrevista de {{ \Carbon\Carbon::parse($entrevista->data_hora)->format('d/m/Y H:i') }}</h2>
                <p class="data">
                    <strong>Status:</strong> {{ $entrevista->status }}
                </p>
            </div>
        @empty
            <p>Nenhuma entrevista agendada.</p>
        @endforelse
    </div>
@endsection
