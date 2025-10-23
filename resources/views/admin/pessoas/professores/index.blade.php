@extends('layouts.app')

@section('title', 'Professores')

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

    <h1>Professores</h1>

    <div class="container-tabela">
        <div class="btn-grp-tabela">
            <div class="btn-grp-principal">
                <a href={{ route('pessoas.professores.adicionar') }} id="btn-adicionar">
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

                <a href="#" id="btn-vincular" class="disabled-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
                        <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1 1 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4 4 0 0 1-.128-1.287z"/>
                        <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243z"/>
                    </svg>
                    Vincular programa
                </a>
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
            const tableData = @json($usuarios);

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
                    { headerName: "Nome", field: "nome", filter: "agTextColumnFilter", sortable: true, flex: 1, minWidth: 120, sort: "asc" },
                    { headerName: "E-mail", field: "email", filter: "agTextColumnFilter", sortable: true, flex: 1, minWidth: 120 },
                    { headerName: "Programas", field: "programas", filter: "agTextColumnFilter", sortable: true, flex: 1, minWidth: 120, valueFormatter: (params) => {
                        if (!params.value) return '';
                        
                        return params.value.map((prog) => prog.sigla).join(', ');
                    }},
                ],
                rowData: tableData,
                rowSelection: { 
                    mode: "singleRow",
                    isRowSelectable: (rowNode) => {
                        return rowNode.data.nome !== 'Admin';
                    },
                },
                pagination: true,
                paginationPageSizeSelector: [10, 20, 50, 100],
                paginationPageSize: 10,
                domLayout: 'autoHeight',
                onSelectionChanged: function(event) {
                    const btnExcluir = document.getElementById("btn-excluir");
                    const btnVincular = document.getElementById("btn-vincular");

                    // Ao mudar a seleção, alguns botões são ativados/inativados
                    if(event.selectedNodes.length > 0){
                        btnExcluir.disabled = false;
                        btnVincular.classList.remove("disabled-link");

                        const baseUrl = "{{ url('admin/professores/vincular') }}";
                        btnVincular.href = `${baseUrl}/${gridApi.getSelectedRows()[0].id_usuario}`;
                    }
                    else{
                        btnExcluir.disabled = true;     
                        btnVincular.classList.add("disabled-link"); 
                        btnAlterar.removeAttribute("href");      
                    }
                },
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

                const baseUrl = "{{ url('admin/professores') }}";
                openModalDelete(object.nome, () => {
                    fetch(`${baseUrl}/${object.id_usuario}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(async (response) => {
                        const data = await response.json().catch(() => ({})); // tenta ler JSON

                        if (!response.ok) {
                            throw new Error(data.message || "Erro ao deletar usuário.");
                        }

                        gridApi.applyTransaction({ remove: gridApi.getSelectedRows() });
                        window.alert("Usuário excluído com sucesso!");
                    })
                    .catch((err) => {
                        alert(err.message);
                    });
                });
            });
        });
    </script>
@endpush
