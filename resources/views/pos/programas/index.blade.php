@extends('layouts.app')

@section('title', 'Programas')

@push('head')
    <link rel="stylesheet" href="{{ asset('ag-grid/styles/ag-theme-alpine.css') }}">
    <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
@endpush

@section('content')
    <h1>Programas</h1>

    <div class="container-tabela">
        <div class="btn-grp-tabela">
            <div class="btn-grp-principal">
                <a href={{ route('pos.programas.adicionar') }} id="btn-adicionar">
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
    <x-modal-delete>
    </x-modal-delete>
@endsection

@push('scripts')
    <!-- AG Grid -->
    <script src="{{ asset('ag-grid/ag-grid-community.min.js')}}"></script>
    <script src="{{ asset('ag-grid/pt-BR.js') }}"></script>

    <script>
    
    document.addEventListener("DOMContentLoaded", function () {
        // Busca dados do banco
        const tableData = @json($programas);

        const gridOptions = {
            localeText: AG_GRID_LOCALE_BR,
            defaultColDef: {
                resizable: false
            },
            columnDefs: [
                { headerName: "Nome", field: "nome", filter: "agTextColumnFilter", sortable: true, flex: 1 }
            ],
            rowData: tableData,
            rowSelection: { mode: "multiRow" },
            pagination: true,
            paginationPageSize: 20,
            domLayout: "autoHeight",
            onSelectionChanged: function(event) {
                // Ao mudar a seleção, alguns botões são ativados/inativados
                // Atualmente: pode excluir vários registros juntos, mas editar um por vez

                const rowCount = event.selectedNodes.length;
                let enableEdit = false, enableDelete = false;

                if(rowCount > 0){
                    enableDelete = true;
                    if(rowCount == 1)
                        enableEdit = true;
                }

                document.getElementById("btn-excluir").disabled = !enableDelete;

                // Atualiza o link para alterar o objeto específico
                let btnAlterar = document.getElementById("btn-alterar");
                if(enableEdit)
                    btnAlterar.classList.remove("disabled-link");
                else
                    btnAlterar.classList.add("disabled-link");
                let baseUrl = "{{ route('pos.programas.alterar', ['id' => ':ID']) }}"; // :ID é placeholder
                btnAlterar.href = baseUrl.replace(':ID', gridApi.getSelectedRows()[0].id_programa);
            }
        };

        const eGridDiv = document.querySelector("#tabela-vigente");
        gridApi = agGrid.createGrid(eGridDiv, gridOptions);

        // Modal
        const modal = document.getElementById("modal-delete");
        const modalClose = modal.querySelector(".modal-close");
        const modalCancel = modal.querySelector(".modal-cancel");
        const modalDelete = modal.querySelector(".modal-delete");

        // Função para abrir modal com lista de itens
        window.openDeleteModal = function(items, onConfirm) {
            // Atualiza lista dinâmica
            const ul = modal.querySelector(".modal-body ul");
            ul.innerHTML = '';
            items.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item;
                ul.appendChild(li);
            });

            // Mostra o modal
            modal.style.display = 'flex';

            // Remove event listeners antigos do botão Excluir
            modalDelete.replaceWith(modalDelete.cloneNode(true));
            const newmodalDelete = modal.querySelector(".modal-delete");

            // Adiciona o confirm callback
            newmodalDelete.addEventListener('click', function() {
                onConfirm();
                modal.style.display = 'none';
            });
        };

        // Fechar modal
        modalClose.addEventListener("click", () => modal.style.display = 'none');
        modalCancel.addEventListener("click", () => modal.style.display = 'none');
        window.addEventListener("click", e => { if(e.target === modal) modal.style.display = 'none'; });

        document.getElementById("btn-limpar-filtros").addEventListener("click", function () {
            gridApi.setFilterModel(null);
            gridApi.onFilterChanged();  
        });

        // Excluir
        document.getElementById("btn-excluir").addEventListener("click", function () {
            const objects = gridApi.getSelectedRows().map((obj) => obj.nome).join(", ");
            if (objects === null) return; 

            openDeleteModal(objects, () => {
                // Aqui você pode enviar a requisição de exclusão para o backend
                console.log("Excluir confirmado para:", objects);
                // gridApi.applyTransaction({ remove: gridApi.getSelectedRows() });
            });
        });
    });
    </script>
@endpush
