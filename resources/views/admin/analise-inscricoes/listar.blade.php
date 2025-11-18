@extends('layouts.app')

@section('title', 'Análise de inscrições')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/tabelas.css') }}">
@endpush

@section('content')
    @if(session('success'))
        <div class="aviso-sucesso">
            {{ session('success') }}
        </div>
    @endif

    <h1>Análise de inscrições</h1>

    <div class="container-tabela">
        <div class="btn-grp-tabela">
            <div class="btn-grp-principal">
                <div class="dropdown-content" id="dropdown-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
                        <path d="M5.523 12.424q.21-.124.459-.238a8 8 0 0 1-.45.606c-.28.337-.498.516-.635.572l-.035.012a.3.3 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548m2.455-1.647q-.178.037-.356.078a21 21 0 0 0 .5-1.05 12 12 0 0 0 .51.858q-.326.048-.654.114m2.525.939a4 4 0 0 1-.435-.41q.344.007.612.054c.317.057.466.147.518.209a.1.1 0 0 1 .026.064.44.44 0 0 1-.06.2.3.3 0 0 1-.094.124.1.1 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256M8.278 6.97c-.04.244-.108.524-.2.829a5 5 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.5.5 0 0 1 .145-.04c.013.03.028.092.032.198q.008.183-.038.465z"/>
                        <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2m5.5 1.5v2a1 1 0 0 0 1 1h2zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.7 11.7 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.86.86 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.84.84 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.8 5.8 0 0 0-1.335-.05 11 11 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.24 1.24 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a20 20 0 0 1-1.062 2.227 7.7 7.7 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103"/>
                    </svg>

                    <span>Relatórios</span>

                    <div class="link-dropdown" id="dropdown-trigger">
                        <a href="{{ url('admin/analise-inscricoes/relatorio') . '/' . $edital->id_edital . '?tipo=preliminar' }}" target="_blank">Preliminar</a>
                        <a href="{{ url('admin/analise-inscricoes/relatorio') . '/' . $edital->id_edital . '?tipo=definitivo' }}" target="_blank">Definitivo</a>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="0.8" viewBox="0 0 16 16" id="username-dropdown">
                        <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
                    </svg> 
                </div>

                <a href="#" id="btn-comunicar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                    </svg>
                    Comunicar candidatos
                </a>

                <a href="#" id="btn-entrevista">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                        <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                    </svg>
                    Agendar entrevista
                </a>
            </div>

        <div id="tabela-vigente" class="ag-theme"></div>
    </div>
@endsection

@push('scripts')
    <!-- AG Grid -->
    <script src="{{ asset('ag-grid/ag-grid-community.min.js')}}"></script>
    <script src="{{ asset('ag-grid/pt-BR.js') }}"></script>
    <script>
        // Dropdown
        const dropdownLink = document.getElementById("dropdown-link");
        const dropdownTrigger = document.getElementById("dropdown-trigger");

        dropdownLink.addEventListener("click", (e) => {
            e.stopPropagation(); // evita conflito com clique fora
            dropdownTrigger.style.display =
                dropdownTrigger.style.display === "flex" ? "none" : "flex";
        });

        // Fecha o dropdown do usuário se clicar fora
        document.addEventListener("click", () => {
            dropdownTrigger.style.display = "none";
        });

        document.addEventListener("DOMContentLoaded", function () {
            // Busca dados do banco
            const tableData = @json($inscricoes);

            const gridOptions = {
                localeText: AG_GRID_LOCALE_BR,
                defaultColDef: {
                    resizable: true,
                    tooltipValueGetter: params => {
                        if (Array.isArray(params.value)) {
                            return params.value.map(d => d.nome).join(', ');
                        }
                        return params.value || '';
                    },
                    comparator: (valueA, valueB) => {
                        if (valueA == null) return -1;
                        if (valueB == null) return 1;
                        return valueA.toString().localeCompare(valueB.toString(), 'pt-BR', { sensitivity: 'base' });
                    }
                },
                columnDefs: [
                    { headerName: "Candidato", field: "candidato.nome", filter: "agTextColumnFilter", sortable: true, flex: 1, sort: 'asc', sortIndex: 1, minWidth: 160},
                    { headerName: "CPF", field: "candidato.candidato.cpf", filter: "agTextColumnFilter", sortable: true, flex: 1, minWidth: 100 },
                    
                    @if(in_array($edital->curso->tipo, ['Doutorado', 'Mestrado']))
                        { headerName: "Linha de Pesquisa", field: "linha_pesquisa.nome", filter: "agTextColumnFilter", sortable: true, flex: 1, minWidth: 160 },
                        { headerName: "Sublinha", field: "sublinha.nome", filter: "agTextColumnFilter", sortable: true, flex: 1, minWidth: 160 },
                    @elseif($edital->curso->tipo === 'Aluno Externo')
                        { headerName: "Disciplina", field: "disciplina.nome", filter: "agTextColumnFilter", sortable: true, flex: 1, minWidth: 160 },
                    @endif
                    
                    { headerName: "Status", field: "deferido", filter: "agTextColumnFilter", sortable: true, flex: 1,  minWidth: 100, 
                        valueFormatter: params => {
                            if (params.value === null) return 'Pendente';
                            if(params.value === 1) return 'Deferido';
                            else return 'Indeferido';
                        },
                        cellStyle: params => {
                            if (params.value === null) {
                                return { color: 'orange', fontWeight: 'bold' }; // pendente
                            }
                            if (params.value === 1) {
                                return { color: 'lightgreen', fontWeight: 'bold' }; // deferido
                            }
                            return { color: 'red', fontWeight: 'bold' }; // indeferido
                        },
                        comparator: (a, b) => {
                            const order = { null: 0, 1: 1, 0: 2 }; // pendente -> deferido -> indeferido
                            const valA = a === null ? order.null : order[a];
                            const valB = b === null ? order.null : order[b];
                            return valA - valB;
                        },
                        sort: 'asc',
                        sortIndex: 0
                    }
                ],
                rowData: tableData,
                pagination: true,
                paginationPageSizeSelector: [10, 20, 50, 100],
                paginationPageSize: 10,
                domLayout: 'autoHeight',
                onRowClicked: function(event) {
                    const inscricaoId = event.data.id_inscricao;
                    const baseUrl = "{{ url('admin/analise-inscricoes/analisar') }}";
                    window.location.href = `${baseUrl}/${inscricaoId}`;
                }
            };

            const eGridDiv = document.querySelector("#tabela-vigente");
            gridApi = agGrid.createGrid(eGridDiv, gridOptions);
        });
    </script>
@endpush
