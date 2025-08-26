@extends('layouts.app')

@section('title', 'Editais')

@push('head')
    <!-- Estilos AG Grid -->
    <link rel="stylesheet" href="{{ asset('ag-grid/styles/ag-theme-alpine.css') }}">
    <link rel="stylesheet" href="{{ asset('css/visualizacao_dados.css') }}">
@endpush

@section('content')
    <h1>Editais</h1>

    <div class="container">

        <div class="btn-grp">

            <button id="btn-adicionar" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                </svg>
                Adicionar
            </button>

            <button id="btn-visualizar" class="btn" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>
                Visualizar
            </button>

            <button id="btn-editar" class="btn" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                </svg>
                Editar
            </button>

            <button id="btn-excluir" class="btn" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                </svg>
                Excluir
            </button>

            <button id="btn-limpar-filtros" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>
                Limpar filtros
            </button>

        </div>
        
        <div id="tabela-vigente" class="ag-theme"></div>
    </div>

    <!-- Modal -->
    @include('partials.modal-editais')
@endsection

@push('scripts')
    <!-- AG Grid -->
    <script src="{{ asset('ag-grid/ag-grid-community.min.js')}}"></script>
    <script src="{{ asset('ag-grid/pt-BR.js') }}"></script>

    <script>
    
    document.addEventListener("DOMContentLoaded", function () {
        const tableData = [
            { id: 1, nome: "Seleção Mestrado 2025", programa: "PPGEM", tipo: "mestrado", inicio: "2025-03-01", fim: "2025-03-31", vigencia: true },
            { id: 2, nome: "Seleção Doutorado 2025", programa: "PPGECT", tipo: "doutorado", inicio: "2025-04-01", fim: "2025-04-30", vigencia: false },
            { id: 3, nome: "PAPOS Especial 2025", programa: "PPGEE", tipo: "PAPOS", inicio: "2025-05-01", fim: "2025-05-15", vigencia: false },
            { id: 4, nome: "Aluno Externo 2025", programa: "PPGBIOTEC", tipo: "aluno externo", inicio: "2025-02-01", fim: "2025-02-20", vigencia: true },
            { id: 5, nome: "Seleção Mestrado 2024", programa: "PPGEP", tipo: "mestrado", inicio: "2024-03-01", fim: "2024-03-31", vigencia: true },
            { id: 6, nome: "Seleção Doutorado 2024", programa: "PPGEQ", tipo: "doutorado", inicio: "2024-04-01", fim: "2024-04-30", vigencia: true },
            { id: 7, nome: "PAPOS Especial 2024", programa: "PROFIAP", tipo: "PAPOS", inicio: "2024-05-01", fim: "2024-05-15", vigencia: false },
            { id: 8, nome: "Aluno Externo 2024", programa: "PPGCC", tipo: "aluno externo", inicio: "2024-02-01", fim: "2024-02-20", vigencia: true },
            { id: 9, nome: "Seleção Mestrado 2023", programa: "PPGEM", tipo: "mestrado", inicio: "2023-03-01", fim: "2023-03-31", vigencia: false },
            { id: 10, nome: "Seleção Doutorado 2023", programa: "PPGECT", tipo: "doutorado", inicio: "2023-04-01", fim: "2023-04-30", vigencia: false },
            { id: 11, nome: "PAPOS Especial 2023", programa: "PPGEE", tipo: "PAPOS", inicio: "2023-05-01", fim: "2023-05-15", vigencia: false },
            { id: 12, nome: "Aluno Externo 2023", programa: "PPGBIOTEC", tipo: "aluno externo", inicio: "2023-02-01", fim: "2023-02-20", vigencia: true },
            { id: 13, nome: "Seleção Mestrado 2022", programa: "PPGEP", tipo: "mestrado", inicio: "2022-03-01", fim: "2022-03-31", vigencia: true },
            { id: 14, nome: "Seleção Doutorado 2022", programa: "PPGEQ", tipo: "doutorado", inicio: "2022-04-01", fim: "2022-04-30", vigencia: false },
            { id: 15, nome: "PAPOS Especial 2022", programa: "PROFIAP", tipo: "PAPOS", inicio: "2022-05-01", fim: "2022-05-15", vigencia: false },
            { id: 16, nome: "Aluno Externo 2022", programa: "PPGCC", tipo: "aluno externo", inicio: "2022-02-01", fim: "2022-02-20", vigencia: false },
            { id: 17, nome: "Seleção Mestrado 2021", programa: "PPGEM", tipo: "mestrado", inicio: "2021-03-01", fim: "2021-03-31", vigencia: false },
            { id: 18, nome: "Seleção Doutorado 2021", programa: "PPGECT", tipo: "doutorado", inicio: "2021-04-01", fim: "2021-04-30", vigencia: false },
            { id: 19, nome: "PAPOS Especial 2021", programa: "PPGEE", tipo: "PAPOS", inicio: "2021-05-01", fim: "2021-05-15", vigencia: false },
            { id: 20, nome: "Aluno Externo 2021", programa: "PPGBIOTEC", tipo: "aluno externo", inicio: "2021-02-01", fim: "2021-02-20", vigencia: false },
            { id: 21, nome: "Seleção Mestrado 2020", programa: "PPGEP", tipo: "mestrado", inicio: "2020-03-01", fim: "2020-03-31", vigencia: false },
            { id: 22, nome: "Seleção Doutorado 2020", programa: "PPGEQ", tipo: "doutorado", inicio: "2020-04-01", fim: "2020-04-30", vigencia: false },
            { id: 23, nome: "PAPOS Especial 2020", programa: "PROFIAP", tipo: "PAPOS", inicio: "2020-05-01", fim: "2020-05-15", vigencia: false },
            { id: 24, nome: "Aluno Externo 2020", programa: "PPGCC", tipo: "aluno externo", inicio: "2020-02-01", fim: "2020-02-20", vigencia: false },
            { id: 25, nome: "Seleção Extraordinária 2025", programa: "PPGEM", tipo: "mestrado", inicio: "2025-06-01", fim: "2025-06-30", vigencia: false }
        ];

        const gridOptions = {
            localeText: AG_GRID_LOCALE_BR,
            defaultColDef: {
                resizable: false
            },
            columnDefs: [
                { headerName: "Nome", field: "nome", filter: "agTextColumnFilter", sortable: true, width: 400 },
                { headerName: "Programa", field: "programa", filter: "agTextColumnFilter", sortable: true, maxWidth: 250 },
                { headerName: "Tipo do Curso", field: "tipo", filter: "agTextColumnFilter", sortable: true, maxWidth: 250 },
                { headerName: "Data da publicação", field: "inicio", filter: "agDateColumnFilter", sortable: true, maxWidth: 250, sort: "desc" },
                { headerName: "Vigente", field: "vigencia", filter: "agTextColumnFilter", sortable: true, maxWidth: 150 }
            ],
            rowData: tableData,
            rowSelection: { mode: "multiRow" },
            pagination: true,
            paginationPageSize: 20,
            domLayout: "autoHeight",
            onGridReady: (params) => { params.api.sizeColumnsToFit(); },
            onSelectionChanged: function(event) {
                const rowCount = event.selectedNodes.length;
                
                let enableEdit = false, enableDelete = false, enableView = false;
                if(rowCount > 0){
                    enableDelete = true;
                    if(rowCount == 1){
                        enableEdit = true;
                        enableView = true;
                    }
                }
                document.getElementById("btn-visualizar").disabled = !enableView;
                document.getElementById("btn-editar").disabled = !enableEdit;
                document.getElementById("btn-excluir").disabled = !enableDelete;
            }
        };

        const eGridDiv = document.querySelector("#tabela-vigente");
        gridApi = agGrid.createGrid(eGridDiv, gridOptions);

    
        // Modal
        const modal = document.getElementById("modalVisualizar");
        const spanClose = document.querySelector(".modal .close");

        document.getElementById("btn-visualizar").addEventListener("click", function () {
            const selected = gridApi.getSelectedRows()[0];
            if (selected) {
                document.getElementById("modal-nome").textContent = selected.nome;
                document.getElementById("modal-inicio").textContent = selected.inicio;
                document.getElementById("modal-fim").textContent = selected.fim;
                modal.style.display = "block";
            }
        });

        spanClose.onclick = function () { modal.style.display = "none"; }
        window.onclick = function (event) { if (event.target == modal) modal.style.display = "none"; }

        // Editar
        document.getElementById("btn-editar").addEventListener("click", function () {
            const selected = gridOptions.api.getSelectedRows()[0];
            if (selected) {
                window.location.href = `/editais/${selected.id}/edit`;
            }
        });

        document.getElementById("btn-limpar-filtros").addEventListener("click", function () {
            gridApi.setFilterModel(null); // limpa todos os filtros
            gridApi.onFilterChanged();    // atualiza a tabela
        });

        // Excluir
        document.getElementById("btn-excluir").addEventListener("click", function () {
            const selected = gridOptions.api.getSelectedRows()[0];
            if (selected && confirm(`Deseja realmente excluir "${selected.nome}"?`)) {
                gridOptions.api.applyTransaction({ remove: [selected] });
            }
        });
    });
    </script>
@endpush
