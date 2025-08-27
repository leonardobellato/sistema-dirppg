@extends('layouts.app')

@section('title', 'Editais')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
@endpush

@section('content')
    <h1>Alterar edital</h1>

    <div class="container-form">
		<form>
			<label for="input-programa">Programa:<span class="required-content">*</span></label>
			<select id="input-programa" name="input-programa" required>
				<option value="">Selecione...</option>
				<option value="1">Opção 1</option>
				<option value="2">Opção 2</option>
				<option value="3">Opção 3</option>
				{{--@foreach($estados as $estado)
					<option value="{{ $estado->id }}">{{ $estado->nome }}</option>
				@endforeach*/
				--}}
			</select>

			<label for="input-curso">Curso:<span class="required-content">*</span></label>
			<select id="input-curso" name="input-curso" disabled required>
				<option value="">Selecione...</option>
			</select>

			<label for="input-nome">Nome do edital:<span class="required-content">*</span></label>
			<input type="text" id="input-nome" placeholder="Digite o nome aqui" required>

			<label for="input-nome">Link do edital:</label>
			<input type="text" id="input-nome" placeholder="Digite o link aqui">


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
						<input type="date" id="input-dt-1hom-inicio" name="input-dt-1hom-inicio" required>
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
						<input type="date" id="input-dt-2rec-inicio" name="input-dt-2rec-inicio" required>
					</div>
					<div class="campo-data">
						<label for="input-dt-2rec-fim">Data de fim:</label>
						<input type="date" id="input-dt-2rec-fim" name="input-dt-2rec-fim" required>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>Homologação do 2º recurso</legend>

				<div class="field-linha">
					<div class="campo-data">
						<label for="input-dt-2hom-inicio">Data de início:</label>
						<input type="date" id="input-dt-2hom-inicio" name="input-dt-2hom-inicio" required>
					</div>
					<div class="campo-data">
						<label for="input-dt-2hom-fim">Data de fim:</label>
						<input type="date" id="input-dt-2hom-fim" name="input-dt-2hom-fim" required>
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

{{--
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#estado').on('change', function () {
        var estadoId = $(this).val();
        var $cidade = $('#cidade');

        $cidade.prop('disabled', true).html('<option>Carregando...</option>');

        if (estadoId) {
            $.ajax({
                url: '/cidades/' + estadoId,
                type: 'GET',
                success: function (data) {
                    $cidade.prop('disabled', false).empty();
                    $cidade.append('<option value="">Selecione uma cidade</option>');
                    data.forEach(function (cidade) {
                        $cidade.append('<option value="' + cidade.id + '">' + cidade.nome + '</option>');
                    });
                }
            });
        } else {
            $cidade.prop('disabled', true).html('<option>Selecione um estado primeiro</option>');
        }
    });
</script>
@endpush
--}}