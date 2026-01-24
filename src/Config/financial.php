<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tabela de Prefixos
    |--------------------------------------------------------------------------
    |
    | Define o prefixo das tabelas do banco de dados para evitar conflitos.
    |
    */
    'table_prefix' => 'fin_',

    /*
    |--------------------------------------------------------------------------
    | Moeda Padrão
    |--------------------------------------------------------------------------
    |
    | Define a moeda padrão a ser usada pelo pacote.
    |
    */
    'default_currency' => 'BRL',

    /*
    |--------------------------------------------------------------------------
    | Configurações Web
    |--------------------------------------------------------------------------
    |
    | Define as configurações para as rotas web do pacote.
    |
    */
    'web' => [
        'prefix' => 'financial',
        'middleware' => ['web', 'auth'],
    ],
];
