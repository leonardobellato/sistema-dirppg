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

    @php
        $tipoUsuario = auth()->user()->tipo;
    @endphp

    <h1>Agendar entrevista</h1>

    <div class="container-form">
		<form action="{{ route($tipoUsuario . '.entrevistas.salvar') }}" method="POST">
            @csrf

            <input type="hidden" name="id_edital" value="{{ $id_edital }}">

            <p><i>OBS.: O candidato será notificado por e-mail sobre o agendamento da entrevista.</i></p>

			<label for="input-candidato">Candidato:<span class="required-content">*</span></label>
			<select id="input-candidato" name="candidato" required>
				<option value="">Selecione...</option>
				
                @foreach($candidatos as $candidato)
					<option value="{{ $candidato->id_usuario }}">{{ $candidato->nome }}</option>
				@endforeach
			
			</select>

            <label for="input-data">Data e horário:<span class="required-content">*</span></label>
            <input type="datetime-local" id="input-data" name="data_entrevista" required>

            <label for="input-local">Local:<span class="required-content">*</span></label>
            <input type="text" id="input-local" name="local" required placeholder="Ex: Sala 101, Bloco A">

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
				<a href={{ route($tipoUsuario . '.analise-inscricoes.listar', $id_edital)  }}>Cancelar</a>
				<button type="submit">Agendar</button>
			</div>
		</form>
    </div>
@endsection
