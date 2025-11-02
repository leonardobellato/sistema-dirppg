@extends('layouts.app')

@section('title', 'Início')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')

    <div class="title-welcome">
        <span>Olá, {{ explode(' ', Auth::user()->nome)[0] }}!</span>
        <p class="">Últimas atualizações:</p>
    </div>

    <div class="card-group">
        <div class="dashcard" id="card-editais">
            <span class="number">
                {{$editaisAbertos}} 
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
                    <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1z"/>
                </svg>
            </span>
            <span class="text">Editais abertos</span>
        </div>
        <div class="dashcard" id="card-analises">
            <span class="number">
                {{$analisesPendentes}}
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-hourglass-split" viewBox="0 0 16 16">
                    <path d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48zm1 0v3.17c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z"/>
                </svg>
            </span>
            <span class="text">Análises pendentes</span>
        </div>
        <div class="dashcard" id="card-entrevistas">
            <span class="number">
                {{$entrevistasAgendadas}}
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-calendar-week" viewBox="0 0 16 16">
                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                </svg>
            </span>
            <span class="text">Entrevistas agendadas</span>
        </div>
    </div>
    
    <div class="canva-container">
        <canvas id="histograma"></canvas>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/chart.umd.js') }}"></script>

    <script>
        const ctx = document.getElementById('histograma');

        // Dados enviados pelo controller
        const inscricoesPorDia = @json($inscricoesPorDia);
        const dias = @json($dias);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dias,
                datasets: [{
                label: 'Inscrições',
                data: inscricoesPorDia,
                    backgroundColor: '#68c3b7',
                    borderRadius: 6,
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Inscrições nos últimos 7 dias',
                        font: {
                            size: 16,
                            weight: 'bold',
                            family: 'Arial'
                        },
                        padding: {
                            top: 10,
                            bottom: 30
                        },
                        align: 'center', // 'start' | 'center' | 'end'
                        position: 'top'  // 'top' (default) ou 'bottom'
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
                },
                responsive: true,
                maintainAspectRatio: false, // permite preencher o container
            }
        });
    </script>
@endpush