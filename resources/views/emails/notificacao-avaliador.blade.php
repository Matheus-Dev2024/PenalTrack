<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Parecer da Avaliação do Servidor</title>
    <link rel="stylesheet" href="{{ asset('css/notificacaoAvaliado.css') }}">
</head>
<body>
<div class="container">
    <div class='header'>
        <img src="{{ asset('images/policia.png') }}" width='85' height='90' style='float: left' alt='Logo Polícia'>
        <img src="{{ asset('images/estado.png') }}" width='70' height='90' style='float: right' alt='Logo Estado'>
        <div>
            <div class='header_title'>POLÍCIA CIVIL DO ESTADO DO PARÁ</div>
            <div class='header_title'>ACOMPANHAMENTO E AVALIAÇÃO</div>
            <div class='header_title'> DE ESTÁGIO PROBATÓRIO</div>
            <br>
            <div class='header_title'>EMAIL DE NOTIFICAÇÃO DE AVALIAÇÃO</div>
            <br>
        </div>
    </div>
    <div>
        <p>Ao(a)<strong> DPC {{ $data['nome_avaliador'] }}</strong>,</p>
        <p>Informamos que o {{ $data['fk_periodo'] }}º período de avaliação de Estágio Probatório do estagiário
            <strong>{{ $data['cargo'] }} {{ $data['nome_avaliado'] }}</strong>, matrícula
            <strong>{{ $data['matricula_avaliado'] }}</strong>, já está disponível para ser avaliada.</p>
        <p>Acesse o sistema eProbatório para registrar a sua avaliação.</p>
        <p>Atenciosamente,</p>
        <p><strong>DIVISÃO DE INFORMAÇÃO FUNCIONAL</strong><br>
            <strong>DIRETORIA DE RECURSOS HUMANOS</strong><br>
            <strong>POLÍCIA CIVIL DO ESTADO DO PARÁ</strong></p>
    </div>
</div>
</body>
</html>

