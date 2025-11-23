@extends('layouts.app')

@section('title', 'Análise de inscrições')

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


    <h1>Comunicar via e-mail</h1>

    <div class="container-form">
        <form action="{{ route($tipoUsuario . '.analise-inscricoes.comunicacaoGeral', $edital->id_edital) }}" method="POST">
            @csrf

            {{-- 🔹 Lista de e-mails --}}
            <div class="lista-emails" style="margin-bottom:20px;">
                <label>E-mails dos candidatos inscritos:</label>

                <div class="emails-box">
                    @forelse($emails as $email)
                        <div>{{ $email }}</div>
                    @empty
                        <i>Nenhum candidato inscrito.</i>
                    @endforelse
                </div>
            </div>

            {{-- 🔹 Editor de mensagem (Markdown básico) --}}
            <label for="mensagem">Mensagem (formatação permitida: **negrito**, *itálico*, &lt;u&gt;sublinhado&lt;/u&gt;):</label>
            <textarea class="comentarios-textarea" 
                      name="mensagem" 
                      id="mensagem"
                      placeholder="Escreva aqui..."
                      required
            ></textarea>

            <div class="btn-grp-form">
                <a href="{{route($tipoUsuario . '.analise-inscricoes.index')}}">Voltar</a>
                <button type="submit" {{ empty($emails) ? 'disabled' : '' }}>Enviar mensagem</button>
            </div>
        </form>
    </div>
@endsection
