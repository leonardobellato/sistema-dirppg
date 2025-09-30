@extends('layouts.app')

@section('title', 'Sublinhas')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
@endpush

@section('content')
    <h1>Adicionar sublinha</h1>

    <div class="container-form">
		<form action="{{ route('pos.sublinhas.salvar') }}" method="POST">
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

            <label for="input-area-concentracao">Área concentração:<span class="required-content">*</span></label>
			<select id="input-area-concentracao" name="area-concentracao" disabled required>
				<option value="">Selecione...</option>		
			</select>

            <label for="input-linha-pesquisa">Linha de pesquisa:<span class="required-content">*</span></label>
			<select id="input-linha-pesquisa" name="linha-pesquisa" disabled required>
				<option value="">Selecione...</option>		
			</select>

			<label for="input-nome">Nome do linha:<span class="required-content">*</span></label>
			<input type="text" id="input-nome" name="nome" placeholder="Digite o nome aqui" required
                value="{{ old('nome') }}" {{-- mantém o valor se der erro --}}
            >

            {{-- erro específico do campo nome --}}
            @error('nome')
                <span class="campo-invalido">O nome deve ter até 150 caracteres.</span>
            @enderror

			<div class="pos-form">
				<p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
			</div>

			<div class="btn-grp-form">
				<a href={{ route('pos.sublinhas.index') }}>Cancelar</a>
				<button type="submit">Cadastrar</button>
			</div>
		</form>
    </div>
@endsection

@push('scripts')
    <script>
        const programaSelect = document.getElementById('input-programa');
        const cursoSelect = document.getElementById('input-curso');
        const areaSelect = document.getElementById('input-area-concentracao');
        const linhaSelect = document.getElementById('input-linha-pesquisa');

        // Ao mudar o programa
        programaSelect.addEventListener('change', function() {
            const idPrograma = this.value;

            // Resetar cursos, áreas e linhas
            cursoSelect.innerHTML = '<option value="">Selecione...</option>';
            cursoSelect.disabled = true;

            areaSelect.innerHTML = '<option value="">Selecione...</option>';
            areaSelect.disabled = true;

            linhaSelect.innerHTML = '<option value="">Selecione...</option>';
            linhaSelect.disabled = true;

            if (idPrograma) {
                cursoSelect.innerHTML = '<option>Carregando...</option>';
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
            }
        });

        // Ao mudar o curso
        cursoSelect.addEventListener('change', function() {
            const idCurso = this.value;

            // Resetar áreas e linhas
            areaSelect.innerHTML = '<option value="">Selecione...</option>';
            areaSelect.disabled = true;

            linhaSelect.innerHTML = '<option value="">Selecione...</option>';
            linhaSelect.disabled = true;            

            if (idCurso) {
                areaSelect.innerHTML = '<option>Carregando...</option>';
                fetch(`/admin/cursos/${idCurso}/areas-concentracao`)
                    .then(response => response.json())
                    .then(data => {
                        areaSelect.innerHTML = '<option value="">Selecione...</option>';
                        data.forEach(area => {
                            areaSelect.innerHTML += `<option value="${area.id_area_concentracao}">${area.nome}</option>`;
                        });
                        areaSelect.disabled = false;
                    })
                    .catch(() => {
                        areaSelect.innerHTML = '<option value="">Erro ao carregar áreas</option>';
                    });
            }
        });

        // Ao mudar a area
        areaSelect.addEventListener('change', function() {
            const idArea = this.value;

            // Resetar áreas
            linhaSelect.innerHTML = '<option value="">Selecione...</option>';
            linhaSelect.disabled = true;
            

            if (idArea) {
                linhaSelect.innerHTML = '<option>Carregando...</option>';
                fetch(`/admin/areas-concentracao/${idArea}/linhas-pesquisa`)
                    .then(response => response.json())
                    .then(data => {
                        linhaSelect.innerHTML = '<option value="">Selecione...</option>';
                        data.forEach(linha => {
                            linhaSelect.innerHTML += `<option value="${linha.id_linha_pesquisa}">${linha.nome}</option>`;
                        });
                        linhaSelect.disabled = false;
                    })
                    .catch(() => {
                        linhaSelect.innerHTML = '<option value="">Erro ao carregar linhas</option>';
                    });
            }
        });
    </script>
@endpush