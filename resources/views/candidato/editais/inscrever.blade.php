@extends('layouts.app')

@section('title', 'Inscrição')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/formularios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/inscricao.css') }}">
@endpush

@section('content')
    @if(session('success'))
        @include('components.alert', ['type' => 'success', 'message' => session('success')])
    @elseif(session('failure'))
        @include('components.alert', ['type' => 'failure', 'message' => session('failure')])
    @endif

    <h2>Inscrição no {{ $edital->curso->programa->nome }}</br>({{ $edital->curso->tipo }})</h2>

    
    <div class="atencao-box">
        <b>Atenção:</b> &nbsp;Serão aceitos apenas arquivos <b>PDF</b>, de no máximo <b>5 MB</b>. <br>
        
        @if($edital->curso->tipo == "Doutorado")
        A dissertação de mestrado (quando necessário) deve conter no máximo <b>15 MB</b>.
        @endif
    </div>
    
    <div class="container-form">

        @include('candidato.editais.documentacao.' . strtolower(str_replace(' ', '-', $edital->curso->tipo)))

    </div>
@endsection
