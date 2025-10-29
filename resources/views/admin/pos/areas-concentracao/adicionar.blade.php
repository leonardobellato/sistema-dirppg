@extends('layouts.app')

@section('title', 'Áreas de Concentração')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/formularios.css') }}">
@endpush

@section('content')
    <h1>Adicionar área de concentração</h1>

    <div class="container-form">
		<form action="{{ route('pos.areas-concentracao.salvar') }}" method="POST">
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

			<label for="input-nome">Nome do área:<span class="required-content">*</span></label>
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
				<a href={{ route('pos.areas-concentracao.index') }}>Cancelar</a>
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
        </script>
@endpush