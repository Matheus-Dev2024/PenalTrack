@php use Illuminate\Support\Carbon; @endphp
    <!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Avaliação de Servidor</title>
    <link rel="stylesheet" href="css/impressaoAvaliacao.css">
</head>
<body>
<div id='cabecalho'>
    <img src="images/policia.png" width='85' height='90' style='float: left' alt='Logo Polícia'>
    <img src="images/estado.png" width='70' height='90' style='float: right' alt='Logo Estado'>
    <div>
        <div class='header_title'>POLÍCIA CIVIL DO ESTADO DO PARÁ</div>
        <div class='header_title'>DIRETORIA DE RECURSOS HUMANOS</div>
        <div class='header_title'>DIVISÃO DE INFORMAÇÃO FUNCIONAL</div>
        <br>
        <div class='header_title'>FICHA DE ACOMPANHAMENTO E AVALIACAO</div>
        <br>
    </div>
</div>

<div>
    <table class="dados_servidor">
        @foreach($dadosServidor as $item)
            <tr>
                <td><strong>NOME DO AVALIADO: {{$item->nome}} </strong></td>
            </tr>
            <tr>
                <td><strong>MATRÍCULA: {{$item->matricula}}</strong></td>
            </tr>
            <tr>
                <td><strong>CARGO: {{$item->cargo}}</strong></td>
            </tr>
            <tr>
                <td><strong>INÍCIO DO ESTÁGIO PROBATÓRIO: {{$item->dt_admissao}} </strong></td>
            </tr>
            <tr>
                <td><strong>TÉRMINO DE ESTÁGIO PROBATÓRIO: {{$item->dt_final}}</strong></td>
            </tr>
        @endforeach
    </table>
    <br>
    <div><strong> RESULTADO FINAL DA AVALIAÇÃO DE DESEMPENHO</strong></div>
    <br>

    <table class="instrucoes dados_servidor">
        @foreach($dadosAvaliacao as $item)
            <tr>
                <td>
                    <b>Pontuação total obtida no {{$item->periodo}} &nbsp; {{$item->dt_inicio_avaliacao}} A {{$item->dt_termino_avaliacao}}</b>
                </td>
                <td>
                    <b>{{$item->nota_total}}</b>
                </td>
            </tr>
        @endforeach
    </table>
    <br>

    <table class="instrucoes dados_servidor">
        <thead>
            <tr>
                <th>QUESITOS</th>
                @php
                    $periodos = [];
                    $totalNotasPorIndices = []; 
                @endphp
                @foreach($dadosItensAvaliacao as $item => $itens)
                    @foreach($itens as $item)
                    {{-- !in_array() verifica se a descrição especifica ja foi adicionada,se não, adiciona ao array --}}
                        @if (!in_array($item['periodo'], $periodos)) 
                            @php
                                $periodos[] = $item['periodo'];
                                $totalNotasPorIndices[$item['periodo']] = 0; // Inicia o total para coluna periodo
                            @endphp
                            <td><b>{{ $item['periodo'] }}</b></td>
                        @endif
                    @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($dadosItensAvaliacao as $item => $itens)
                <tr>
                    <td><b>{{ $item }}</b></td>
                    @foreach($itens as $item)
                        <td>{{ $item['nota_total'] }}</td>
                        @php
                            $totalNotasPorIndices[$item['periodo']] += $item['nota_total']; // Adiciona a nota à coluna correspondente
                        @endphp
                    @endforeach
                </tr>
            @endforeach
        </tbody>
            <tr>
                <td><b>Total</b></td>
                @foreach($periodos as $periodo)
                    <td><b>{{ $totalNotasPorIndices[$periodo] }}</b></td>
                @endforeach
            </tr>
    </table>
    

    {{-- <table class="instrucoes dados_servidor">
        <thead>
            <tr>
                <th>QUESITOS</th>
                @php
                    $descricoes = [];
                @endphp
                @foreach($dadosItensAvaliacao as $item => $itens)
                    @foreach($itens as $item)
                        @if (!in_array($item['descricao'], $descricoes))
                            @php
                                $descricoes[] = $item['descricao'];
                            @endphp
                            <td><b>{{ $item['descricao'] }}</b></td>
                        @endif
                    @endforeach
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($dadosItensAvaliacao as $item => $itens)
                <tr>
                    <td><b>{{ $item }}</b></td>
                    @foreach($itens as $item)
                        <td><b>{{ $item['nota_total'] }}</b></td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table> --}}

    {{-- <table class="instrucoes dados_servidor">
        <tr>
            <td>
                <b>
                    QUESITOS 
                </b>
            </td>
        </tr> 
        @foreach($dadosItensAvaliacao as $item => $itensArray)
            <tr>    
                <td>
                    <b>{{$item}}</b>  
                </td>            
            </tr>
                @foreach ($itensArray as $item)

                    <tr>
                        <td>
                            <b>{{ $item['nota_total'] }}</b>
                        </td>
                    </tr>
            
                @endforeach

        @endforeach
        
    </table> --}}

</div>

</body>
</html>

