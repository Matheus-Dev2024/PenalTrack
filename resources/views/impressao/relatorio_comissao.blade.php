@php
    use Carbon\Carbon as CarbonDate;
@endphp
    <!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Avaliação de Servidor</title>
    <link rel="stylesheet" href="css/relatorioComissao.css">
</head>
<body>
<div id='cabecalho'>
    <img src="images/policia.png" width='85' height='90' style='float: left' alt='Logo Polícia'>
    <img src="images/estado.png" width='70' height='90' style='float: right' alt='Logo Estado'>
    <div>
        <div class='header_title'>POLÍCIA CIVIL DO ESTADO DO PARÁ</div>
        <div class='header_title'>COMISSÃO DE ACOMPANHAMENTO E AVALIAÇÃO</div>
        <div class='header_title'> DE ESTÁGIO PROBATÓRIO</div>
        <br>
        <div class='header_title'>FICHA DE ACOMPANHAMENTO E AVALIACAO</div>
        <br>
    </div>
</div>

<div>
    <table class="dados_servidor">
        @foreach($dadosServidor as $item)
            <tr>
                <td>NOME DO AVALIADO: <strong>{{$item->nome}} </strong></td>
            </tr>
            <tr>
                <td>NOMEAÇÃO: <strong>{{$item->dt_nomeacao}} </strong></td>
            </tr>
            <tr>
                <td>MATRÍCULA: <strong>{{$item->matricula}}</strong></td>
            </tr>
            <tr>
                <td>CARGO: <strong>{{$item->cargo}}</strong></td>
            </tr>
            <tr>
                <td>INÍCIO DO ESTÁGIO PROBATÓRIO: <strong>{{$item->dt_admissao}} </strong></td>
            </tr>
            <tr>
                <td>TÉRMINO PREVISTO DE ESTÁGIO PROBATÓRIO: <strong>{{$item->dt_final_prevista}}</strong></td>
            </tr>

            {{-- @if($item->dt_final_ultimo_periodo !== null)
                <tr>
                    <td>PRORROGADO ATÉ: <strong>{{$item->dt_final_ultimo_periodo}}</strong></td>
                </tr>
            @endif --}}

        @endforeach
    </table>
    <br>
    <div style="text-align: center;" ><strong> RESULTADO FINAL DA AVALIAÇÃO DE DESEMPENHO</strong></div>
    <br>

    <table class="instrucoes dados_servidor">
        @foreach($dadosAvaliacao as $item)
            @if($item->dt_termino_avaliacao !== null)
                @php
                    $dt_inicio_avaliacao = CarbonDate::createFromFormat('d/m/Y', $item->dt_inicio_avaliacao);
                    $dt_termino_avaliacao = CarbonDate::createFromFormat('d/m/Y', $item->dt_termino_avaliacao);
                    $dt_final_prevista = CarbonDate::createFromFormat('d/m/Y', $item->dt_final_prevista);
                @endphp
            @endif
            <tr>
                {{-- <td>
                    <b>Pontuação total obtida no {{$item->periodo}} &nbsp; {{$item->dt_inicio_avaliacao}} A {{$item->dt_termino_avaliacao}}</b>
                </td> --}}
                <td>
                    Pontuação total obtida no {{$item->periodo}} &nbsp; <b>{{$dt_inicio_avaliacao->format('d/m/Y')}} A {{$dt_termino_avaliacao->format('d/m/Y')}}
                        {{-- verifica se alguma data do período é maior que a data final prevista (dt_admissao + 3 anos) --}}
                    @if($dt_inicio_avaliacao->gt($dt_final_prevista) || $dt_termino_avaliacao->gt($dt_final_prevista))
                        (Período de Prorrogação)
                    @endif
                    </b>
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
                <th style="width: 5%; ">QUESITOS</th>
                @php
                    $periodos = [];
                    $totalNotasPorIndices = [];
                    $totalGeral = []; 
                @endphp
                @foreach($dadosItensAvaliacao as $item => $itens)
                    @foreach($itens as $item)
                    {{-- !in_array() verifica se a descrição especifica ja foi adicionada,se não, adiciona ao array --}}
                        @if (!in_array($item['periodo'], $periodos)) 
                            @php
                                $periodos[] = $item['periodo'];
                                $totalNotasPorIndices[$item['periodo']] = 0; // Inicia o total para coluna periodo
                            @endphp
                            <td><b style="font-size: 12px;">{{ $item['periodo'] }}</b></td>
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
                @php
                    // calcula o total geral e guarda dentro da variável totalGeral
                    $totalGeral = 0;
                    foreach ($totalNotasPorIndices as $periodo => $totalNota) {
                        $totalGeral += $totalNota;
                    }
                    // echo $totalGeral; 

                    // define os limites de cada pontuação
                    $faixaPlenamenteSatisfatorio = [540, 600];
                    $faixaSatisfatorio = [420, 539];
                    $faixaInsatisfatorio = [0, 419];

                    // faz a comparação do totalGeral com os limites acima
                    $plenamenteSatisfatorioChecked = ($totalGeral >= $faixaPlenamenteSatisfatorio[0] && $totalGeral <= $faixaPlenamenteSatisfatorio[1]) ? '(X)' : '( )';
                    $satisfatorioChecked = ($totalGeral >= $faixaSatisfatorio[0] && $totalGeral <= $faixaSatisfatorio[1]) ? '(X)' : '( )';
                    $insatisfatorioChecked = ($totalGeral >= $faixaInsatisfatorio[0] && $totalGeral <= $faixaInsatisfatorio[1]) ? '(X)' : '( )';

                @endphp
    <br>

    @if (count($periodos) == 6)
        <table class="instrucoes dados_servidor">
            <thead>
                <tr>
                    <th colspan="3">
                        <b> GRAUS DE DESEMPENHO</b>
                    </th>
                </tr>
            </thead>
            <tr>
                <td>
                    <b> {!! $plenamenteSatisfatorioChecked !!} Plenamente Satisfatório <br> de 540 - 600 pontos</b>
                </td>
                <td>
                    <b> {!! $satisfatorioChecked !!} Satisfatório <br> de 420 - 539 pontos</b>
                </td>
                <td>
                    <b> {!! $insatisfatorioChecked !!} Insatisfatório <br> até 419 pontos</b>
                </td>
            </tr>
        </table>
    @else
        <p> O servidor ainda não completou todos os períodos de avaliação.</p>
    @endif

</div>

</body>
</html>

