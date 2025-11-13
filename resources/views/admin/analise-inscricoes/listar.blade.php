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
                    
                    @if(in_array($tipoCurso, ['Doutorado', 'Mestrado']))
                        { headerName: "Linha de Pesquisa", field: "linha_pesquisa.nome", filter: "agTextColumnFilter", sortable: true, flex: 1, minWidth: 160 },
                        { headerName: "Sublinha", field: "sublinha.nome", filter: "agTextColumnFilter", sortable: true, flex: 1, minWidth: 160 },
                    @elseif($tipoCurso === 'Aluno Externo')
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
