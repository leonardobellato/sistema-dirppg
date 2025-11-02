@extends('layouts.app')

@section('title', 'Inscrição')

@push('head')
<link rel="stylesheet" href="{{ asset('css/formularios.css') }}">
<style>
    .doc-item {
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .doc-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: .5rem;
    }

    .status {
        font-weight: bold;
        padding: 0.25rem 0.5rem;
        border-radius: 5px;
    }

    .status.deferido {
        color: #0f5132;
        background: #d1e7dd;
        border: 1px solid #badbcc;
    }

    .status.indeferido {
        color: #842029;
        background: #f8d7da;
        border: 1px solid #f5c2c7;
    }

    .motivo {
        margin-top: 0.5rem;
        font-size: 14px;
        color: #333;
        background: #fff5f5;
        padding: 8px;
        border-radius: 5px;
        border-left: 3px solid #dc3545;
    }

    .upload-container {
        margin-top: 1rem;
        padding: 1rem;
        border: 1px dashed #ccc;
        border-radius: 8px;
        background-color: #fafafa;
    }

    input[type="file"] {
        display: block;
        margin-top: 0.5rem;
    }

    .btn-enviar {
        background: #0076df;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        cursor: pointer;
        margin-top: 0.5rem;
    }

    .btn-enviar:hover {
        background: #005bb5;
    }

    .aviso-sucesso {
        background: #d1e7dd;
        color: #0f5132;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')

@if(session('success'))
    <div class="aviso-sucesso">{{ session('success') }}</div>
@endif

<h1>Inscrição</h1>

<p>Confira abaixo o resultado da análise dos seus documentos. Caso algum tenha sido <strong>indeferido</strong>, você pode interpor recurso enviando uma nova versão.</p>

<div class="container-form">
    @foreach($inscricao->documentos as $doc)
        <div class="doc-item">
            <div class="doc-header">
                <strong>{{ ucfirst(str_replace('_', ' ', $doc->tipo)) }}</strong>
                <span class="status {{ $doc->deferido === 1 ? 'deferido' : 'indeferido' }}">
                    {{ $doc->deferido === 1 ? 'Deferido' : 'Indeferido' }}
                </span>
            </div>

            @if($doc->motivo_indeferimento)
                <div class="motivo">
                    <strong>Motivo:</strong> {{ $doc->motivo_indeferimento }}
                </div>
            @endif

            <p><a href="{{ asset('storage/' . $doc->caminho_servidor) }}" target="_blank">📄 Ver documento enviado</a></p>

            {{-- Permitir recurso apenas se indeferido --}}
            @if($doc->deferido === 0)
                <form action="{{ route('candidato.recursos.enviar', $doc->id_documento) }}" method="POST" enctype="multipart/form-data" class="upload-container">
                    @csrf
                    <label for="arquivo_{{ $doc->id_documento }}">Enviar nova versão (PDF, até 5 MB):</label>
                    <input type="file" name="arquivo" id="arquivo_{{ $doc->id_documento }}" accept="application/pdf" required>

                    <button type="submit" class="btn-enviar">Enviar Recurso</button>
                </form>
            @endif

            @if($doc->recursos->count() > 0)
                <p style="margin-top:0.8rem; font-size: 13px; color:#555;">
                    📤 <strong>{{ $doc->recursos->count() }}</strong> recurso(s) já submetido(s), último em {{ $doc->recursos->last()->data_submissao->format('d/m/Y H:i') }}.
                </p>
            @endif
        </div>
    @endforeach
</div>

@endsection
