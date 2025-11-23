@extends('layouts.app')

@section('title', 'Entrevistas')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/formularios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabelas.css') }}">
@endpush

@section('content')
    <h1>Detalhes da entrevista</h1>

    <div class="container-form">
		<form action="" method="">
            @csrf

            <label>Status da entrevista:</label>
            <input type="text" value="{{ ucfirst($entrevista->status) }}" disabled>

            <label for="input-agendador">Agendador:</label>
			<input id="input-agendador" name="agendador" type="text" value="{{ $entrevista->agendador->nome }}" disabled>

            <label for="input-data">Data e horário:</label>
            <input type="datetime-local" id="input-data" name="data_entrevista" value="{{ $entrevista->data_hora }}" disabled>

            <label for="input-local">Local:</label>
            <input type="text" id="input-local" name="local" value="{{ $entrevista->local }}" disabled>


            <div id="comentario-container">
                <label for="observacoes">Observações:</label>
                <textarea class="comentarios-textarea" name="observacoes" id="observacoes" disabled>{{ $entrevista->observacoes ?? '' }}</textarea>
            </div>
		</form>
    </div>
@endsection
