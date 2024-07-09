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
            <div> <strong> PARECER: PROCESSO N° </strong></div>
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
                A Comissão de Acompanhamento e Avaliação de Estágio Probatório, instituida pela Portaria nº 083/2018-GAB/CGPC, de 10/08/2018, 
                da lavra do Exm. Sr. Delegado JOÃO BOSCO RODRIGUES JUNIOR, então Corregedor-Geral da Polícia Civil, que a designou para acompanhar e 
                avaliar o estágio probatório dos servidores que estarão completando o periodo de Estágio Probatório, havendo concluido a avaliação do 
                estagiário <strong>{{$dadosServidor[0]->nome}}</strong>,no cargo de <strong>{{$dadosServidor[0]->cargo}}</strong>, nomeado(a) em
                <strong> {{$dadosServidor[0]->dt_nomeacao}}</strong>, tomando posse em <strong>{{$dadosServidor[0]->dt_posse}}</strong>, vem formalizar o 
                seguinte parecer:
            </p>
            
            {!!$parecerServidor[0]->parecer!!}
        </div>

        <div class="assinatura">

            <div class="div1">
                <strong> DPC fulana <br> Presidende da CAAEP <br> POLÍCIA CIVIL DO ESTADO DO PA</strong>
            </div>

        </div>

        <div class="assinatura_parte_de_baixo">

            <div style="float: left;">
                <p><strong> DPC fulana <br> Presidende da CAAEP <br> POLÍCIA CIVIL DO ESTADO DO PA</strong></p>
            </div>

            <div style="float: right;"> 
                <p><strong> DPC fulana <br> Presidende da CAAEP <br> POLÍCIA CIVIL DO ESTADO DO PA</strong></p>
            </div>

        </div>


    </body>
</html>

