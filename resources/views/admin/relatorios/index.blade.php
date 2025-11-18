<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resultado - {{ $edital->nome }}</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: Helvetica, Arial, sans-serif;
            }

            html{
                font-size: 25px; /* padrão do navegador: 1rem = 16px */
            }
            body {
                background-color: #fff;
                margin: 20px;
            }

            div.header {
                display: table;
                width: 100%;
                margin-bottom: 50px;
            }

            .header-left,
            .header-center,
            .header-right {
                display: table-cell;
                vertical-align: middle;
                text-align: center;
            }

            div.header-text {
                text-align: center;
                line-height: 1.2;
            }

            main{
                padding: 0 20px;
            }

            main p{
                text-align: justify;
                margin-bottom: 35px;
                font-size: 1rem;
            }

            h3{
                margin-bottom: 15px;
                text-align: center;
            }

            table {
                width: 100%;
                border: 1px solid #000;
                border-collapse: collapse;
                margin-bottom: 30px;
                word-wrap: break-word;
            }

            table th, table td {
                border: 1px solid #000;
                padding: 5px;
                text-align: center;
            }

            span{
                display: block;
                text-align: right;
            }
        </style>
    </head>

    <body>
        <div class="header">
            <div class="header-left">
                <img src="{{ public_path('BR_logo.png') }}" width="115px">
            </div>

            <div class="header-center">
                <p><strong>Ministério da Educação</strong></p>
                <p><strong>UNIVERSIDADE TECNOLÓGICA FEDERAL DO PARANÁ</strong></p>
                <p>CÂMPUS PONTA GROSSA</p>
                <p>Diretoria de Pesquisa e Pós-Graduação</p>
                <p><i>{{ $edital->curso->programa->nome }}</i></p>
            </div>

            <div class="header-right">
                <img src="{{ public_path('UTFPR_logo.png') }}" width="170px">
            </div>
        </div>
        
        <main>
            <p>A Coordenação do {{ $edital->curso->programa->nome }} 
                UTFPR - Câmpus Ponta Grossa, <b>DIVULGA</b>, em ordem alfabética, 
                a <b>LISTA {{ $definitiva ? 'DEFINITIVA' : 'PRELIMINAR' }}</b> dos candidatos que 
                tiveram suas inscrições homologadas no edital <strong>{{ $edital->nome }}</strong>.
            </p>

            <h3>Inscrições Deferidas</h3>
            
            @if($edital->curso->tipo != 'Aluno externo')  
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($inscricoes as $inscricao)
                        <tr>
                            <td>{{ strtoupper($inscricao->candidato->nome) }}</td>
                        </tr>
                    
                    @empty
                        <tr>
                            <td colspan="1"><i>Nenhuma inscrição deferida.</i></td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

            @else
                @foreach($inscricoes as $disciplina => $inscricoes)
                    <p><strong>Disciplina: {{ $disciplina }}</strong></p>
                    <table>
                        <thead>
                            <tr>
                                <th>Nome</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($inscricoes as $inscricao)
                            <tr>
                                <td>{{ strtoupper($inscricao->candidato->nome) }}</td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="1"><i>Nenhuma inscrição deferida.</i></td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <br>
                @endforeach
            @endif

            <span>Ponta Grossa, 13 de novembro de 2025.</span>
        </main>
    </body>
</html>