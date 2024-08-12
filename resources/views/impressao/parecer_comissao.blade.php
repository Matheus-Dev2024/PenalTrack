@php
    use Carbon\Carbon as CarbonDate;
@endphp
    <!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Parecer da Avaliação</title>
    <link rel="stylesheet" href="css/parecerComissao.css">
</head>

<div class='cabecalho_policia'>
    <img src="images/policia.png" width='85' height='90' style='float: left' alt='Logo Polícia'>
    <img src="images/estado.png" width='70' height='90' style='float: right' alt='Logo Estado'>
    <div>
        <div>POLÍCIA CIVIL DO ESTADO DO PARÁ</div>
        <div>CORREGEDORIA-GERAL</div>
        <div>COMISSÃO DE ACOMPANHAMENTO E AVALIAÇÃO DE ESTÁGIO PROBATÓRIO</div>
        <br>
        <div><strong> PARECER: PROCESSO N° {{$dadosServidor[0]->numero_processo}}</strong></div>
        <br>
    </div>
</div>


<body>

<div>
    <table>
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
            {{-- <tr>
                <td>TÉRMINO PREVISTO DE ESTÁGIO PROBATÓRIO: <strong>{{$item->dt_final_prevista}}</strong></td>
            </tr> --}}
        @endforeach
    </table>
</div>

<div>
    <p>
        A Comissão de Acompanhamento e Avaliação de Estágio Probatório, instituida pela Portaria nº 083/2018-GAB/CGPC,
        de 10/08/2018,
        da lavra do Exm. Sr. Delegado JOÃO BOSCO RODRIGUES JUNIOR, então Corregedor-Geral da Polícia Civil, que a
        designou para acompanhar e
        avaliar o estágio probatório dos servidores que estarão completando o periodo de Estágio Probatório, havendo
        concluido a avaliação do
        estagiário <strong>{{$dadosServidor[0]->nome}}</strong>,no cargo de
        <strong>{{$dadosServidor[0]->cargo}}</strong>, nomeado(a) em
        <strong> {{$dadosServidor[0]->dt_nomeacao}}</strong>, tomando posse em
        <strong>{{$dadosServidor[0]->dt_posse}}</strong>, vem formalizar o
        seguinte parecer:
    </p>

    {!!$parecerServidor[0]->parecer!!}
</div>

@if(count($comissaoServidor) == 0)
    <div class="assinatura">

        <div class="div1">
            <br>
            <br>
            <strong> Vincule o servidor a uma coimssão para exibir as assinaturas.</strong>
        </div>

    </div>
@endif

@foreach($comissaoServidor as $item)
    <div class="assinatura">

        <div class="div1">
            <strong> DPC {{$item->nome_presidente}}<br> Presidende da CAAEP <br> POLÍCIA CIVIL DO ESTADO DO PA</strong>
        </div>

    </div>

    <div class="assinatura_parte_de_baixo">

        <div style="float: left;">
            <strong> DPC {{$item->primeiro_membro_nome}}<br> Primeiro Membro <br> POLÍCIA CIVIL DO ESTADO DO PA</strong>
        </div>

        <div style="float: right;">
            <strong> DPC {{$item->segundo_membro_nome}}<br> Segundo Membro <br> POLÍCIA CIVIL DO ESTADO DO PA</strong>
        </div>

    </div>
@endforeach

</body>


{{--início da página de notificação--}}
<div style="page-break-before: always;"></div>

<div class='cabecalho_policia'>
    <img src="images/policia.png" width='85' height='90' style='float: left' alt='Logo Polícia'>
    <img src="images/estado.png" width='70' height='90' style='float: right' alt='Logo Estado'>
    <div>
        <div>POLÍCIA CIVIL DO ESTADO DO PARÁ</div>
        <div>CORREGEDORIA-GERAL</div>
        <div>COMISSÃO DE ACOMPANHAMENTO E AVALIAÇÃO DE ESTÁGIO PROBATÓRIO</div>
        <br>
        <div><strong> NOTIFICAÇÃO </strong></div>
        <br>
    </div>
</div>


<body>

<br>
<div style="text-align: right;">
    Belém, PA, ___de __________ de ____.
</div>
<br>

<div>
    Ao Sr. <strong>{{$dadosServidor[0]->nome}}</strong>.
    <br>
    {{$dadosServidor[0]->cargo}}
</div>

<br>

<div>
    <p>

        A Comissão de Acompanhamento e Avaliação do Estagio Probatório dos servidores que completarão o periodo de
        estagio probatório
        na forma do que dispõe o Art. 18 da Resolução nº 04/2006, homologada pelo Decreto nº 2.750, de 28/12/2006 que
        regulamenta o Estagio Probatório previstos
        na Lei Complementar n° 022/94 e suas alterações posteriores, vem NOTIFICAR Vossa Senhoria a apresentar DEFESA
        ESCRITA. dirigida ao Presidente do
        Conselho Superior de Polícia Civil no prazo improrrogável de 10 (dez) dias, contados a partir do recebimento da
        presente notificação.
        Também informa que o processo de estágio probatório encontra-se apto a receber vistas na Secretaria do Conselho
        Superior de Policia Civil,
        que funciona na sede da Delegacia Geral de Policia Civil, com endereço à <strong>Av. Magalhães Barata, 209,
            Bairro
            Nazaré, no horário da 08h00 às 18h00.</strong>

    </p>

</div>

@if(count($comissaoServidor) == 0)
    <div class="assinatura">

        <div class="div1">
            <br>
            <br>
            <strong> Vincule o servidor a uma coimssão para exibir as assinaturas.</strong>
        </div>

    </div>
@endif

@foreach($comissaoServidor as $item)
    <div class="assinatura">

        <div class="div1">
            <strong> DPC {{$item->nome_presidente}}<br> Presidende da CAAEP <br> POLÍCIA CIVIL DO ESTADO DO PA</strong>
        </div>

    </div>

@endforeach
<div style="text-align: left; margin-top: 50px">
    Ciente em ___de __________ de ____.
    <br>
</div>

<div style="text-align: left; margin-top: 20px">
    ________________________________________
    <br>
    <strong>{{$dadosServidor[0]->nome}}</strong>.
    <br>
    {{$dadosServidor[0]->cargo}}

</div>

</body>

</html>

