<?php
return [
    'nome' => env('APP_NAME', 'e-Probatório'), //nome do sistema
    'codigo' => 56, //Código deste sistema na tabela de produção "sistema" no schema segurança. Não deixe como 35 no seu projeto
    'expiracao_login' => 180, //tempo em dias para usuário perder acesso ao sistema por falta de uso
    'registrar_log' => env('SEGURANCA_REGISTRAR_LOG', true), //habilita ou não o registro de log nos models (use apenas para teste)
    'controle_acesso' => env('SEGURANCA_CONTROLE_ACESSO', true), //Se falso os usuários ficam com permissão total no sistema
    'url_front' => env('URL_FRONT'),
    'url_intranet' => env('URL_INTRANET'),
];
