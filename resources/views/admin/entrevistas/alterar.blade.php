@extends('layouts.app')

@section('title', 'Entrevistas')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/formularios.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabelas.css') }}">
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

    <h1>Alterar entrevista</h1>

    <div class="container-form">
		<form action="{{ route('admin.entrevistas.atualizar', $entrevista->id_entrevista) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="id_edital" value="{{ $entrevista->id_edital }}">

            <p><i>OBS.: O candidato será notificado por e-mail sobre a atualização da entrevista.</i></p>

            <label>Status da entrevista:<span class="required-content">*</span></label>

            <div class="status-selector">
                <label class="status-option">
                    <input type="radio" name="status" value="agendada" 
                        {{ $entrevista->status === 'agendada' ? 'checked' : '' }}>
                    <span>Agendada</span>
                </label>

                <label class="status-option">
                    <input type="radio" name="status" value="realizada" 
                        {{ $entrevista->status === 'realizada' ? 'checked' : '' }}>
                    <span>Realizada</span>
                </label>

                <label class="status-option">
                    <input type="radio" name="status" value="ausente" 
                        {{ $entrevista->status === 'ausente' ? 'checked' : '' }}>
                    <span>Ausente</span>
                </label>

                <label class="status-option">
                    <input type="radio" name="status" value="cancelada" 
                        {{ $entrevista->status === 'cancelada' ? 'checked' : '' }}>
                    <span>Cancelada</span>
                </label>
            </div>

            <label for="input-candidato">Candidato:<span class="required-content">*</span></label>
			<input id="input-candidato" name="candidato" type="text" value="{{ $entrevista->candidato->nome }}" disabled>

            <label for="input-data">Data e horário:<span class="required-content">*</span></label>
            <input type="datetime-local" id="input-data" name="data_entrevista" required value="{{ old('data_entrevista', $entrevista->data_hora) }}">

            <label for="input-local">Local:<span class="required-content">*</span></label>
            <input type="text" id="input-local" name="local" required placeholder="Ex: Sala 101, Bloco A" value="{{ old('local', $entrevista->local) }}">

            {{-- erro específico do campo local --}}
            @error('local')
                <span class="campo-invalido">O local deve ter até 200 caracteres.</span>
            @enderror

            <div id="comentario-container">
                <label for="observacoes">Observações:</label>
                <textarea class="comentarios-textarea" name="observacoes" id="observacoes" placeholder="Descreva aqui...">{{ old('observacoes', $entrevista->observacoes ?? '') }}</textarea>
            </div>

            {{-- erro específico do campo observacoes --}}
            @error('observacoes')
                <span class="campo-invalido">As observações devem ter até 600 caracteres.</span>
            @enderror

			<div class="pos-form">
				<p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
			</div>

			<div class="btn-grp-form">
				<a href={{ route('admin.entrevistas.index') }}>Cancelar</a>
				<button type="submit">Atualizar</button>
			</div>
		</form>
    </div>
@endsection
