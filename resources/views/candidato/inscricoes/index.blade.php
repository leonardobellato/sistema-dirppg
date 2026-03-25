@extends('layouts.app')

@section('title', 'Minhas inscrições')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/tabelas.css') }}">
@endpush

@section('content')
    @if(session('success'))
        @include('components.alert', ['type' => 'success', 'message' => session('success')])
    @elseif(session('failure'))
        @include('components.alert', ['type' => 'failure', 'message' => session('failure')])
    @endif
    
    <h1>Minhas inscrições</h1>

    <div class="container-vigentes">
        @forelse($inscricoes as $inscricao)
            <div class="card-edital" onclick="window.location.href='{{ url('candidato/inscricoes/'. $inscricao->id_inscricao) }}'">
                <h2>{{ $inscricao->edital->curso->programa->sigla }} - {{ $inscricao->edital->curso->tipo }} {{ $inscricao->edital->curso->tipo == 'Aluno Externo' ? ' - '.$inscricao->disciplina->nome : '' }}</h2>
                <p class="data">
                    <strong>Realizada em:</strong> 
                    {{ \Carbon\Carbon::parse($inscricao->criado_em)->format('d/m/Y') }}
                </p>
            </div>
        @empty
            <p class="text-center">Nenhuma inscrição realizada.</p>
        @endforelse
    </div>
@endsection

@push('scripts')
<script>
    console.log(@json($inscricoes));
</script>
@endpush