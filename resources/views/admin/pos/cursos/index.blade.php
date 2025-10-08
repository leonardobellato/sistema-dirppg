@extends('layouts.app')

@section('title', 'Cursos')

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

    <h1>Cursos</h1>

    <div class="container-tabela">
        <div class="btn-grp-tabela">
            <div class="btn-grp-principal">
                <a href={{ route('pos.cursos.adicionar') }} id="btn-adicionar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                    </svg>
                    Adicionar
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
            const tableData = @json($cursos);

            const gridOptions = {
                localeText: AG_GRID_LOCALE_BR,
                defaultColDef: {
                    resizable: false,
                    tooltipValueGetter: params => params.value,
                    comparator: (valueA, valueB) => {
                        if (valueA == null) return -1;
                        if (valueB == null) return 1;
                        return valueA.toString().localeCompare(valueB.toString(), 'pt-BR', { sensitivity: 'base' });
                    }
                },
                columnDefs: [
                    { headerName: "Programa", field: "programa.nome", filter: "agTextColumnFilter", sortable: true, flex: 2, sort: "asc" },
                    { headerName: "Tipo", field: "tipo", filter: "agTextColumnFilter", sortable: true, flex: 1}
                ],
                rowData: tableData,
                rowSelection: { mode: "singleRow" },
                pagination: true,
                paginationPageSize: 20,
                paginationPageSizeSelector: [10, 20, 50, 100],
                onSelectionChanged: function(event) {
                    const btnExcluir = document.getElementById("btn-excluir");

                    // Ao mudar a seleção, alguns botões são ativados/inativados
                    if(event.selectedNodes.length > 0)
                        btnExcluir.disabled = false;
                    else
                        btnExcluir.disabled = true;       
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

                openModalDelete(object.tipo + " - " + object.programa.nome, () => {
                    fetch(`/admin/cursos/${object.id_curso}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error("Erro ao deletar");
                        gridApi.applyTransaction({ remove: gridApi.getSelectedRows() });
                        window.alert("Curso excluído!");
                    })
                    .catch(err => alert(err.message));
                });
            });
        });
    </script>
@endpush
