@extends('layouts.app')

@section('title', 'Disciplinas de Aluno Externos')

@push('head')
    <link rel="stylesheet" href="{{ asset('ag-grid/styles/ag-theme-alpine.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
@endpush

@section('content')
    @if(session('success'))
        <div class="aviso-sucesso">
            {{ session('success') }}
        </div>
    @endif

    <h1>Disciplinas de Aluno Externos</h1>

    <div class="container-tabela">
        <div class="btn-grp-tabela">
            <div class="btn-grp-principal">
                <a href={{ route('pos.disciplinas-aluno-externo.adicionar') }} id="btn-adicionar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                    </svg>
                    Adicionar
                </a>

                <a href="#" id="btn-alterar" class="disabled-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                    </svg>
                    Alterar
                </a>

                <button id="btn-excluir" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                    </svg>
                    Excluir
                </button>
            </div>

            <div class="btn-grp-secundario">
                <button id="btn-limpar-filtros">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                    Limpar filtros
                </button>
            </div>
        </div>
        
        <div id="tabela-vigente" class="ag-theme"></div>
    </div>

    <!-- Modal -->
    @include('components.modal-delete')
@endsection

@push('scripts')
    <!-- AG Grid -->
    <script src="{{ asset('ag-grid/ag-grid-community.min.js')}}"></script>
    <script src="{{ asset('ag-grid/pt-BR.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Busca dados do banco
            const tableData = @json($disciplinas);

            const gridOptions = {
                localeText: AG_GRID_LOCALE_BR,
                defaultColDef: {
                    resizable: true,
                    tooltipValueGetter: params => params.value,
                    comparator: (valueA, valueB) => {
                        if (valueA == null) return -1;
                        if (valueB == null) return 1;
                        return valueA.toString().localeCompare(valueB.toString(), 'pt-BR', { sensitivity: 'base' });
                    }
                },
                columnDefs: [
                    { headerName: "Nome", field: "nome", filter: "agTextColumnFilter", sortable: true, flex: 2, sort: "asc"},
                    { headerName: "Programa", field: "curso.programa.nome", filter: "agTextColumnFilter", sortable: true, flex: 2},
                    { headerName: "Ativo", field: "inativo", filter: "agTextColumnFilter", sortable: true, flex: 1, valueGetter: params => params.data.inativo ? "Não" : "Sim" },
                ],
                rowData: tableData,
                rowSelection: { mode: "singleRow" },
                pagination: true,
                paginationPageSize: 20,
                domLayout: "autoHeight",
                onSelectionChanged: function(event) {
                    const btnExcluir = document.getElementById("btn-excluir");
                    const btnAlterar = document.getElementById("btn-alterar");

                    // Ao mudar a seleção, alguns botões são ativados/inativados
                    if(event.selectedNodes.length > 0){
                        btnExcluir.disabled = false;
                        
                        // Atualiza o link para alterar o objeto específico
                        btnAlterar.classList.remove("disabled-link");
                        let baseUrl = "/disciplinas-aluno-externo/alterar/:ID"; // :ID é placeholder
                        btnAlterar.href = baseUrl.replace(':ID', gridApi.getSelectedRows()[0].id_disciplina);
                    } 
                    else{
                        btnExcluir.disabled = true;
                        btnAlterar.classList.add("disabled-link");
                    }                
                }
            };

            const eGridDiv = document.querySelector("#tabela-vigente");
            gridApi = agGrid.createGrid(eGridDiv, gridOptions);

            // Remover filtros
            document.getElementById("btn-limpar-filtros").addEventListener("click", function () {
                gridApi.setFilterModel(null);
                gridApi.onFilterChanged();  
            });

            // Excluir
            document.getElementById("btn-excluir").addEventListener("click", function () {
                const object = gridApi.getSelectedRows()[0];

                openDeleteModal(object.nome, () => {
                    fetch(`/disciplinas-aluno-externo/${object.id_disciplina}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error("Erro ao deletar");
                        gridApi.applyTransaction({ remove: gridApi.getSelectedRows() });
                        window.alert("Disciplina excluída!");
                    })
                    .catch(err => alert(err.message));
                });
            });
        });
    </script>
@endpush
