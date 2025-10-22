@extends('layouts.app')

@section('title', 'Inscrição')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
    <link rel="stylesheet" href="{{ asset('css/inscricao.css') }}">
@endpush

@section('content')
    <h1>Inscrição no {{ $edital->curso->programa->nome }}</br>({{ $edital->curso->tipo }})</h1>

    
    <div class="atencao-box">
        <b>Atenção:</b> &nbsp;Serão aceitos apenas arquivos <b>PDF</b>, de no máximo <b>5 MB</b>. <br>
        A dissertação de mestrado (quando necessário) deve conter no máximo <b>15 MB</b>.
    </div>
    
    <div class="container-form">

        @include('candidato.editais.documentacao.' . strtolower(str_replace(' ', '-', $edital->curso->tipo)))

    </div>
@endsection
