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
            <div class='header_title'>DIVISÃO DE INFORMAÇÃO FUNCIONAL</div> <br>
            <div class='header_title'>FORMULARIO DE AVALIAÇÃO PERIÓDICA DE DESEMPENHO DO SERVIDOR POLICIAL EM GESTÃO PROBATÓRIA</div>
        </div>
    </div>

    <div>
        <table class="dados_servidor">
            <tr>
                <td><strong>NOME DO AVALIADO:</strong> {{$dados->servidor->nome}}</td>
            </tr>
            <tr>
                <td><strong>MATRÍCULA:</strong> {{$dados->servidor->matricula}}</td>
            </tr>
            <tr>
                <td><strong>CARGO:</strong> {{$dados->servidor->cargo}}</td>
            </tr>
            <tr>
                <td><strong>LOTAÇÃO:</strong> {{$dados->servidor->unidade}}</td>
            </tr>
            <tr>
                <td><strong>AVALIADOR:</strong> {{$dados->processo->nome_avaliador}}</td>
            </tr>
            <tr>
                <td><strong>INÍCIO DO ESTÁGIO PROBATÓRIO: {{$dados->processo->dt_inicio}}</td>
            </tr><tr>
                <td><strong>PERÍODO DE AVALIAÇÃO: </strong>De {{$dados->processo->dt_inicio_avaliacao}} à {{$dados->processo->dt_termino_avaliacao}} </td>
            </tr>
        </table>
        <br>
        <table class="instrucoes dados_servidor">
            <tr>
                <td>
                    <b>INSTRUÇÕES:</b>
                </td>
            </tr>
            <tr>
                <td id="texto_instrucao">
                    {{-- <php echo $dados->processo->instrucao; ?> --}}
                </td>
            </tr>
        </table>
        <br>
        <?php
            $contador = 0;
            $ordenacao = ["a", "b", "c", "d"];
            echo ("<b>A cada quesito abaixo, atribua de 0 a 5 pontos:</b>");
            foreach ($dados->formulario as $quesito){
            echo("<table>");
                echo ("<tr>");
                    echo ("<td>");
                        echo ("<b>".($contador+1)." - ".$quesito->nome . "</b> (" . $quesito->descricao.")<br>");
                    echo ("</td>");
                echo ("</tr>");

                $contador2 = 0;
                foreach ($quesito->itens as $item) {
                $indice_nota = ($contador * 4)+($contador2);
                echo ("<tr>");
                    echo ("<td>");
                        echo ("<b>(</b> ". $dados->notas[$indice_nota]->nota ."<b> ) ". $ordenacao[$contador2] ." - </b>".$item->pergunta . "<br>");
                    echo ("</td>");
                echo ("</tr>");
                $contador2++;
                }
                $contador++;
                echo("</table>");
                echo ("<br>");
            }
        ?>
        <table id="resultado" class="dados_servidor">
            <tr id="item_avaliado">
                <td colspan="6"><b>RESULTADO DA AVALIAÇÃO DE DESEMPENHO DO PERÍODO</b></td>
            </tr>
            <tr id="item_avaliado">
                <td><b>QUESITOS</b></td>
                <td><b>A</b></td>
                <td><b>B</b></td>
                <td><b>C</b></td>
                <td><b>D</b></td>
                <td><b>SOMA</b></td>
            </tr>

            <@php
            $contador = 0;
            $nota_total = 0;
            $ordenacao = ["I", "II", "III", "IV", "V"];

            foreach($dados->formulario as $quesito){
                echo("<tr>");
                    echo("<td>");
                        echo ($ordenacao[$contador] . "<b> - </b>" . $quesito->nome);
                    echo("</td>");

                    $contador2 = 0;
                    $soma_nota_item = 0;
                    foreach ($quesito->itens as $item) {
                        $indice_nota = ($contador * 4)+($contador2);
                        echo("<td id='item_avaliado'>". $dados->notas[$indice_nota]->nota ."</td>");
                        $soma_nota_item += $dados->notas[$indice_nota]->nota;
                        $contador2++;
                    }
                    $nota_total += $soma_nota_item;
                    echo("<td id='item_avaliado'>". $soma_nota_item ."</td>");
                echo("</tr>");
                $contador++;
            }
            echo("
            <tr>
                <td colspan='5'><b>Pontuação total:</b> (somatório dos pontos obtidos em cada quesito)</td><td id='item_avaliado'><b>".$nota_total."</b></td>
                </tr>
            <tr>
            <td colspan='6'><b>Resultado:</b> (pontuação total igual ou superior a 70 = Satisfatório / Pontuação total inferior a 70 = insatisfatório)</td>
            </tr>
            <tr>
                <td colspan='6'> <b>Data da Avaliação: </b>". \Illuminate\Support\Carbon::parse($dados->notas[0]->created_at)
                ->format('d/m/Y H:i:s'). " </td>
            </tr>
            <tr>
                <td colspan='6'>
                    <strong>Parecer do Avaliador:</strong> {$dados->processo->parecer_avaliador}
                </td>
            </tr>
            <tr>
                <td colspan='3' id='assinatura'>
                ____________________________ <br>
                <b>Avaliado</b><br>
                &nbsp;
                </td>
                <td colspan='3' id='assinatura'>
                ____________________________ <br>
                <b>Avaliador</b><br>
                Assinatura e Carimbo
                </td>
            </tr>
            ");
            @endphp
        </table>
    </div>

{{--    Este trecho de código imprimi a data em português do Brasil no Formato "24 de fevereiro de 1992"--}}

{{--    @php--}}
{{--        use Carbon\Carbon;--}}

{{--        $date = Carbon::createFromDate(1992, 1, 24);--}}
{{--        $date->locale('pt_BR');--}}
{{--        $formattedDate = $date->isoFormat('LL');--}}

{{--        echo $formattedDate; // Imprime "24 de fevereiro de 1992"--}}

{{--    @endphp--}}
</body>
</html>

