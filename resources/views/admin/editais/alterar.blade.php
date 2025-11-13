@extends('layouts.app')

@section('title', 'Editais')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/formularios.css') }}">
@endpush

@section('content')
    <h1>Alterar edital</h1>

    <div class="container-form">
		<form action="{{ route('admin.editais.atualizar', ['id' => $edital->id_edital])}}" method="POST">
            @csrf
			@method('PUT')

			<label for="input-programa">Programa:<span class="required-content">*</span></label>
			<input id="input-programa" name="programa" type="text" value="{{ $edital->curso->programa->nome }}" disabled>

			<label for="input-curso">Curso:<span class="required-content">*</span></label>
			<input id="input-curso" name="curso" type="text" value="{{ $edital->curso->tipo }}" disabled>

			<label for="input-nome">Nome do edital:<span class="required-content">*</span></label>
			<input type="text" id="input-nome" name="nome" placeholder="Digite o nome aqui" required autofocus
                value="{{ old('nome', $edital->nome) }}" {{-- mantém o valor se der erro --}}
            >

			{{-- erro específico do campo nome --}}
            @error('nome')
                <span class="campo-invalido">O nome deve ter até 200 caracteres.</span>
            @enderror

			<label for="input-link">Link do edital:</label>
			<input type="text" id="input-link" name="link" placeholder="Digite o link aqui"
                value="{{ old('link', $edital->link) }}" {{-- mantém o valor se der erro --}}
            >

			{{-- erro específico do campo link --}}
            @error('link')
                <span class="campo-invalido">O link deve ter até 200 caracteres.</span>
            @enderror

			<label for="input-vigente">Vigente:</label>
            <label class="toggle">
                <input type="checkbox" id="input-vigente" name="vigente" value="1"
                    {{ isset($edital) && $edital->vigente ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>

			<h3>Cronograma do edital</h3>
			<hr>

			<fieldset>
				<legend>Período de inscrição no edital<span class="required-content">*</span></legend>

				<div class="field-linha">
        			<div class="campo-data">
						<label for="input-dt-insc-inicio">Data de início:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-insc-inicio" name="input-dt-insc-inicio" required
							value="{{ old('input-dt-insc-inicio', $fases['inscricao']->data_inicio) }}"
						>
					</div>
					<div class="campo-data">
						<label for="input-dt-insc-fim">Data de fim:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-insc-fim" name="input-dt-insc-fim" required
							value="{{ old('input-dt-insc-fim', $fases['inscricao']->data_fim) }}"
						>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>Divulgação do deferimento das inscrições<span class="required-content">*</span></legend>

				<div class="field-linha">
        			<div class="campo-data">
						<label for="input-dt-div-insc">Data: <span class="required-content">*</span></label>
						<input type="date" id="input-dt-div-insc" name="input-dt-div-insc" required
							value="{{ old('input-dt-div-insc', $fases['resultado']->data_inicio) }}"
						>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>Período de interposição do 1º recurso<span class="required-content">*</span></legend>

				<div class="field-linha">
					<div class="campo-data">
						<label for="input-dt-1rec-inicio">Data de início:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-1rec-inicio" name="input-dt-1rec-inicio" required
							value="{{ old('input-dt-1rec-inicio', $fases['recurso1']->data_inicio) }}"
						>
					</div>
					<div class="campo-data">
						<label for="input-dt-1rec-fim">Data de fim:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-1rec-fim" name="input-dt-1rec-fim" required
							value="{{ old('input-dt-1rec-fim', $fases['recurso1']->data_fim) }}"
						>
					</div>
				</div>
			</fieldset>

			<div class="space"></div>

			<label for="input-enable-2rec">(Opcional) Habilitar segundo recurso:</label>
            <label class="toggle">
                <input type="checkbox" id="input-enable-2rec" name="input-enable-2rec" value="1" 
					{{ isset($fases['recurso2']) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>

			<fieldset class="disabled-fieldset" id="fieldset-2rec">
				<legend>Período de interposição do 2º recurso<span class="required-content">*</span></legend>

				<div class="field-linha">
					<div class="campo-data">
						<label for="input-dt-2rec-inicio">Data de início:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-2rec-inicio" name="input-dt-2rec-inicio" disabled
							value="{{ old('input-dt-2rec-inicio', isset($fases['recurso2']) ? $fases['recurso2']->data_inicio : '') }}"
						>
					</div>
					<div class="campo-data">
						<label for="input-dt-2rec-fim">Data de fim:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-2rec-fim" name="input-dt-2rec-fim" disabled
							value="{{ old('input-dt-2rec-fim', isset($fases['recurso2']) ? $fases['recurso2']->data_fim : '') }}"
						>
					</div>
				</div>
			</fieldset>

			<div class="pos-form">
				<p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
			</div>

			<div class="btn-grp-form">
				<a href={{ route('admin.editais.index') }}>Cancelar</a>
				<button type="submit">Atualizar</button>
			</div>
		</form>
    </div>
@endsection

@push('scripts')
    <script>
		// Esta função define que a data final deve ser sempre igual ou posterior à data inicial
		function vincularDatas(idInicio, idFim) {
			const inicio = document.getElementById(idInicio);
			const fim = document.getElementById(idFim);

			if (inicio && fim) {
				inicio.addEventListener('change', function () {
					const dataInicio = new Date(this.value);
					if (isNaN(dataInicio)) return;

					// Formata para yyyy-mm-dd
					const minDate = dataInicio.toISOString().split('T')[0];

					// Define o mínimo da data final
					fim.min = minDate;

					// Se a data final atual for inválida ou anterior, limpa ou ajusta
					if (!fim.value || fim.value < minDate) {
						fim.value = '';
					}
				});
			}
		}

		vincularDatas('input-dt-insc-inicio', 'input-dt-insc-fim');
		vincularDatas('input-dt-insc-fim', 'input-dt-div-insc');
		vincularDatas('input-dt-div-insc', 'input-dt-1rec-inicio');
		vincularDatas('input-dt-1rec-inicio', 'input-dt-1rec-fim');
		vincularDatas('input-dt-1rec-fim', 'input-dt-2rec-inicio');
		vincularDatas('input-dt-2rec-inicio', 'input-dt-2rec-fim');

		// --------------------------------------------------------
		// 2 recurso
		const toggle = document.getElementById('input-enable-2rec');
		const fieldset = document.getElementById('fieldset-2rec');
		
		// IDs dos inputs que fazem parte do grupo
		const inputIds = [
			'input-dt-2rec-inicio',
			'input-dt-2rec-fim'
		];

		function atualizarEstado2Recurso() {
			const ativar = toggle.checked;

			// Habilita ou desabilita os inputs
			inputIds.forEach(id => {
				const input = document.getElementById(id);
				if (!input) return;

				input.disabled = !ativar;
				input.required = ativar;
				if (!ativar) {
					input.value = '';
				}
			});

			// Alterna classe no fieldset
			if (fieldset) {
				fieldset.classList.toggle('disabled-fieldset', !ativar);
			}
		}

		toggle.addEventListener('change', atualizarEstado2Recurso);

		// Chama a função ao carregar a página para definir o estado inicial correto
		document.addEventListener('DOMContentLoaded', atualizarEstado2Recurso);
    </script>
@endpush