@extends('layouts.app')

@section('title', 'Vincular Programas')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/formularios.css') }}">
    <style>
        .programa-lista {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        .programa-item {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: #f9f9f9;
            border-radius: 8px;
            padding: 8px 12px;
            transition: background .2s;
            margin: 0;
        }

        .programa-item:hover {
            background: #eef6ff;
        }

        .programa-item{
            font-weight: 400;
        }

        .programa-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin: 0;
            cursor: pointer;
        }

        .pre-info{
            margin-top: 40px;
        }

        .pre-info p{
            font-weight: 500;
        }


    </style>
@endpush

@section('content')
<h1>Vincular programas ao professor</h1>

<div class="container-form">
    <form action="{{ route('pessoas.professores.vincular') }}" method="POST">
        @csrf

        <input type="hidden" name="id_usuario" value="{{ $professor->id_usuario }}">

        <div class="pre-info">
            <p>Professor: &nbsp;<strong>{{ $professor->nome }}</strong></p>
        </div>

        <div class="mb-3">
            <label>Programas vinculados:</label>
            <div class="programa-lista">
                @foreach($programas as $prog)
                    <label class="programa-item">
                        <input 
                            type="checkbox" 
                            name="id_programas[]" 
                            value="{{ $prog->id_programa }}"
                            @if($professor->programas->contains('id_programa', $prog->id_programa)) checked @endif
                        >
                        {{ $prog->nome }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="pos-form">
            <p><span class="required-content">*</span> Campos OBRIGATÓRIOS</p>
        </div>

        <div class="btn-grp-form">
            <a href="{{ route('pessoas.professores.index') }}">Cancelar</a>
            <button type="submit">Salvar vínculos</button>
        </div>
    </form>
</div>
@endsection
