@extends('layouts.app')

@section('title', 'Editais')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
@endpush

@section('content')
    <h1>Adicionar edital</h1>

    <div class="container-form">
		<form action="{{ route('editais.salvar') }}" method="POST">
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
				<legend>Período de submissão de documentos<span class="required-content">*</span></legend>

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
				<legend>Período de interposição do 1º recurso<span class="required-content">*</span></legend>

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
				<legend>Homologação do 1º recurso<span class="required-content">*</span></legend>

				<div class="field-linha">
					<div class="campo-data">			
						<label for="input-dt-1hom-inicio">Data de início:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-1hom-inicio" name="input-dt-1hom-inicio" readonly required>
					</div>
					<div class="campo-data">
						<label for="input-dt-1hom-fim">Data de fim:<span class="required-content">*</span></label>
						<input type="date" id="input-dt-1hom-fim" name="input-dt-1hom-fim" required>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>Período de interposição do 2º recurso</legend>

				<div class="field-linha">
					<div class="campo-data">
						<label for="input-dt-2rec-inicio">Data de início:</label>
						<input type="date" id="input-dt-2rec-inicio" name="input-dt-2rec-inicio">
					</div>
					<div class="campo-data">
						<label for="input-dt-2rec-fim">Data de fim:</label>
						<input type="date" id="input-dt-2rec-fim" name="input-dt-2rec-fim">
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>Homologação do 2º recurso</legend>

				<div class="field-linha">
					<div class="campo-data">
						<label for="input-dt-2hom-inicio">Data de início:</label>
						<input type="date" id="input-dt-2hom-inicio" name="input-dt-2hom-inicio" readonly>
					</div>
					<div class="campo-data">
						<label for="input-dt-2hom-fim">Data de fim:</label>
						<input type="date" id="input-dt-2hom-fim" name="input-dt-2hom-fim">
					</div>
				</div>
			</fieldset>

			<div class="pos-form">
				<p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
			</div>

			<div class="btn-grp-form">
				<a href={{ route('editais.index') }}>Cancelar</a>
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
                fetch(`/admin/programas/${idPrograma}/cursos`)
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

		// Esta função define que a data final deve ser >= a data inicial
		// Além disso, obriga a homologação iniciar junto ao recurso
		function vincularDatas(idInicio, idFim, idHomologacaoInicio = null) {
			const inicio = document.getElementById(idInicio);
			const fim = document.getElementById(idFim);
			const homInicio = idHomologacaoInicio ? document.getElementById(idHomologacaoInicio) : null;

			if (inicio && fim) {
				inicio.addEventListener('change', function () {
					// regra do fim >= inicio
					fim.min = this.value;
					if (fim.value && fim.value < this.value) {
						fim.value = this.value;
					}

					// se tiver homologação vinculada, copia a data
					if (homInicio) {
						homInicio.value = this.value;
					}
				});
			}
		}

		vincularDatas('input-dt-insc-inicio', 'input-dt-insc-fim');
		vincularDatas('input-dt-insc-fim', 'input-dt-1rec-inicio');
		vincularDatas('input-dt-1rec-inicio', 'input-dt-1rec-fim', 'input-dt-1hom-inicio');
		vincularDatas('input-dt-1rec-inicio', 'input-dt-1hom-fim');
		vincularDatas('input-dt-1hom-fim', 'input-dt-2rec-inicio');
		vincularDatas('input-dt-2rec-inicio', 'input-dt-2rec-fim', 'input-dt-2hom-inicio');
		vincularDatas('input-dt-2rec-inicio', 'input-dt-2hom-fim');

    </script>
@endpush