@extends('layouts.app')

@section('title', 'Editais')

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

    <h1>Editais abertos</h1>

    <div class="container-tabela m-3">
        <div id="tabela-vigente" class="ag-theme"></div>
    </div>
@endsection

@push('scripts')
    <!-- AG Grid -->
    <script src="{{ asset('ag-grid/ag-grid-community.min.js')}}"></script>
    <script src="{{ asset('ag-grid/pt-BR.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Busca dados do banco
            const tableData = @json($editais);

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
                    { headerName: "Edital", field: "nome", filter: "agTextColumnFilter", sortable: true, flex: 2},
                    { headerName: "Curso", field: "curso.tipo", filter: "agTextColumnFilter", sortable: true, flex: 1 },
                    { headerName: "Programa", field: "curso.programa.sigla", filter: "agTextColumnFilter", sortable: true, flex: 1 },
                    { headerName: "Data de Publicação", field: "data_publicacao", filter: "agTextColumnFilter", sortable: true, flex: 1, sort: 'desc',  valueFormatter: params => {
                        if (!params.value) return '';
                        const date = new Date(params.value);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}/${month}/${year}`;
                    }},
                ],
                rowData: tableData,
                domLayout: "autoHeight",
                onRowClicked: function(event) {
                    const editalId = event.data.id_edital;
                    const baseUrl = "{{ url('candidato/editais') }}";
                    window.location.href = `${baseUrl}/${editalId}`;
                }
            };

            const eGridDiv = document.querySelector("#tabela-vigente");
            gridApi = agGrid.createGrid(eGridDiv, gridOptions);
        });
    </script>
@endpush
