@extends('layouts.app')

@section('title', 'Editais')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
@endpush

@section('content')
    <h1>Adicionar edital</h1>

    <div class="container-form">
		<form action="{{ route('admin.editais.salvar') }}" method="POST">
            @csrf

			<label for="input-programa">Programa:<span class="required-content">*</span></label>
			<select id="input-programa" name="programa" required>
				<option value="">Selecione...</option>
				
                @foreach($programas as $programa)
					<option value="{{ $programa->id_programa }}">{{ $programa->nome }}</option>
				@endforeach
			
			</select>

			<label for="input-curso">Curso:<span class="required-content">*</span></label>
			<select id="input-curso" name="curso" disabled required>
				<option value="">Selecione...</option>		
			</select>

			<label for="input-nome">Nome do edital:<span class="required-content">*</span></label>
			<input type="text" id="input-nome" name="nome" placeholder="Digite o nome aqui" required
                value="{{ old('nome') }}" {{-- mantém o valor se der erro --}}
            >

			{{-- erro específico do campo nome --}}
            @error('nome')
                <span class="campo-invalido">O nome deve ter até 200 caracteres.</span>
            @enderror

			<label for="input-link">Link do edital:</label>
			<input type="text" id="input-link" name="link" placeholder="Digite o link aqui"
                value="{{ old('link') }}" {{-- mantém o valor se der erro --}}
            >

			{{-- erro específico do campo link --}}
            @error('link')
                <span class="campo-invalido">O link deve ter até 200 caracteres.</span>
            @enderror

			<h3>Cronograma do edital</h3>
			<hr>

			<fieldset>
				<legend>Período de inscrição no edital<span class="required-content">*</span><span class="tag-context candidato">candidato</span></legend>

				<div class="field-linha">
        			<div class="campo-data">
						<label for="input-dt-insc-inicio">Data de início:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-insc-inicio" name="input-dt-insc-inicio" required>
					</div>
					<div class="campo-data">
						<label for="input-dt-insc-fim">Data de fim:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-insc-fim" name="input-dt-insc-fim" required>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>Divulgação do deferimento das inscrições<span class="required-content">*</span></legend>

				<div class="field-linha">
        			<div class="campo-data">
						<label for="input-dt-div-insc">Data: <span class="required-content">*</span></label>
						<input type="date" id="input-dt-div-insc" name="input-dt-div-insc" required>
					</div>
				</div>
			</fieldset>

			<div class="space"></div>

			<fieldset>
				<legend>Período de interposição do 1º recurso<span class="required-content">*</span><span class="tag-context candidato">candidato</span></legend>

				<div class="field-linha">
					<div class="campo-data">
						<label for="input-dt-1rec-inicio">Data de início:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-1rec-inicio" name="input-dt-1rec-inicio" required>
					</div>
					<div class="campo-data">
						<label for="input-dt-1rec-fim">Data de fim:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-1rec-fim" name="input-dt-1rec-fim" required>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>Divulgação do resultado do 1º recurso<span class="required-content">*</span></legend>

				<div class="field-linha">
        			<div class="campo-data">
						<label for="input-dt-div-1rec">Data: <span class="required-content">*</span></label>
						<input type="date" id="input-dt-div-1rec" name="input-dt-div-1rec" required>
					</div>
				</div>
			</fieldset>

			<div class="space"></div>

			<label for="input-enable-2rec">(Opcional) Habilitar segundo recurso:</label>
            <label class="toggle">
                <input type="checkbox" id="input-enable-2rec" name="input-enable-2rec" value="1">
                <span class="slider"></span>
            </label>

			<fieldset class="fieldset-2rec disabled-fieldset">
				<legend>Período de interposição do 2º recurso<span class="required-content">*</span><span class="tag-context candidato">candidato</span></legend>

				<div class="field-linha">
					<div class="campo-data">
						<label for="input-dt-2rec-inicio">Data de início:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-2rec-inicio" name="input-dt-2rec-inicio" disabled>
					</div>
					<div class="campo-data">
						<label for="input-dt-2rec-fim">Data de fim:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-2rec-fim" name="input-dt-2rec-fim" disabled>
					</div>
				</div>
			</fieldset>

			<fieldset class="fieldset-2rec disabled-fieldset">
				<legend>Divulgação do resultado do 2º recurso<span class="required-content">*</span></legend>

				<div class="field-linha">
        			<div class="campo-data">
						<label for="input-dt-div-2rec">Data:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-div-2rec" name="input-dt-div-2rec" disabled>
					</div>
				</div>
			</fieldset>


			<div class="pos-form">
				<p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
			</div>

			<div class="btn-grp-form">
				<a href={{ route('admin.editais.index') }}>Cancelar</a>
				<button type="submit">Cadastrar</button>
			</div>
		</form>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('input-programa').addEventListener('change', function() {
            const idPrograma = this.value;
            const cursoSelect = document.getElementById('input-curso');
            
            // Desabilita o select enquanto carrega
            cursoSelect.disabled = true;
            cursoSelect.innerHTML = '<option>Carregando...</option>';

            if(idPrograma) {
				const baseUrl = "{{ url('/') }}";
                fetch(`${baseUrl}/programas/${idPrograma}/cursos`)
                    .then(response => response.json())
                    .then(data => {
                        cursoSelect.innerHTML = '<option value="">Selecione...</option>';
                        data.forEach(curso => {
                            cursoSelect.innerHTML += `<option value="${curso.id_curso}">${curso.tipo}</option>`;
                        });
                        cursoSelect.disabled = false;
                    })
                    .catch(() => {
                        cursoSelect.innerHTML = '<option value="">Erro ao carregar cursos</option>';
                    });
            } else {
                cursoSelect.innerHTML = '<option value="">Selecione...</option>';
                cursoSelect.disabled = true;
            }
        });

		// Esta função define que a data final deve ser pelo menos 1 dia após a data inicial
		function vincularDatas(idInicio, idFim) {
			const inicio = document.getElementById(idInicio);
			const fim = document.getElementById(idFim);

			if (inicio && fim) {
				inicio.addEventListener('change', function () {
					const dataInicio = new Date(this.value);
					if (isNaN(dataInicio)) return;

					// Adiciona 1 dia
					dataInicio.setDate(dataInicio.getDate() + 1);

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
		vincularDatas('input-dt-1rec-fim', 'input-dt-div-1rec');
		vincularDatas('input-dt-div-1rec', 'input-dt-2rec-inicio');
		vincularDatas('input-dt-2rec-inicio', 'input-dt-2rec-fim');
		vincularDatas('input-dt-2rec-fim', 'input-dt-div-2rec');

		// --------------------------------------------------------
		// 2 recurso
		const toggle = document.getElementById('input-enable-2rec');
		const fieldsets = document.querySelectorAll('.fieldset-2rec');
		
		// IDs dos inputs que fazem parte do grupo
		const inputIds = [
			'input-dt-2rec-inicio',
			'input-dt-2rec-fim',
			'input-dt-div-2rec'
		];

		toggle.addEventListener('change', function () {
			const ativar = this.checked;

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

			// Alterna classe nos fieldsets
			fieldsets.forEach(fs => {
				fs.classList.toggle('disabled-fieldset', !ativar);
			});
		});
    </script>
@endpush