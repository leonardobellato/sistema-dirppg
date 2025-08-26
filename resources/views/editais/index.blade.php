@extends('layouts.app')

@section('title', 'Editais')

@push('head')
    <!-- Estilos AG Grid -->
    <link rel="stylesheet" href="{{ asset('ag-grid/styles/ag-theme-alpine.css') }}">

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
            position: relative;
        }
        .close {
            position: absolute;
            right: 10px; top: 10px;
            font-size: 20px;
            cursor: pointer;
        }
        .btn {
            padding: 5px 12px;
            margin-right: 5px;
            cursor: pointer;
        }

        #btn-adicionar{
            background-color: #26D07C;
            border: 0.5px solid gray;
            border-radius: 5px;
        }

        #btn-visualizar{
            background-color: #ccebdbff;
            border: 0.5px solid gray;
            border-radius: 5px;
        }

        #btn-editar{
            background-color: #ccebdbff;
            border: 0.5px solid gray;
            border-radius: 5px;
        }

        #btn-excluir{
            background-color: #FF7276;
            border: 0.5px solid gray;
            border-radius: 5px;
        }

        .btn:disabled { opacity: 0.5; cursor: not-allowed; }

        #tabela-vigente {
            width: 100%;  /* faz a tabela caber no conteúdo */
        }

        .container{
            width: 1300px;
            max-width: 90%;
        }

        #ag-Grid-SelectionColumn{
            width: 40;
        }
    </style>
@endpush

@section('content')
    <h1>Editais</h1>

    <div class="container">
        <div class="btn-grp">
            <button id="btn-adicionar" class="btn">Adicionar</button>
            <button id="btn-visualizar" class="btn" disabled>Visualizar</button>
            <button id="btn-editar" class="btn" disabled>Editar</button>
            <button id="btn-excluir" class="btn" disabled>Excluir</button>
            <button id="btn-limpar-filtros" class="btn">Limpar filtros</button>
        </div>
        
        <div id="tabela-vigente" class="ag-theme"></div>
    </div>

    <!-- Modal -->
    <div id="modalVisualizar" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Detalhes do Edital</h2>
            <p><strong>Nome: </strong> <span id="modal-nome"></span></p>
            <hr/>
            <p><strong>Início das incrições: </strong> <span id="modal-inicio"></span></p>
            <p><strong>Fim das incrições: </strong> <span id="modal-fim"></span></p>
        </div>
    </div>
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
                { headerName: "Data da publicação", field: "inicio", filter: "agDateColumnFilter", sortable: true, maxWidth: 250 },
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
