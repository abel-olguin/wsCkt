<?php
/**
 * Created by PhpStorm.
 * User: abelolguinchavez
 * Date: 16/08/15
 * Time: 0:19
 *
 * Todas las configuraciones se encuentran dentro de este archivo,
 * para llamarlo debes usar algo semejante a $var = include('Config.php');
 * aqui incluye todo lo que seran constantes o configuraciones se usaran
 * en la aplicacion.
 */

return [
    'connection' => [
        'db'    => 'conekta_ws',
        'usr'   => 'root',
        'pass'  => 'root',
        'host'  => 'localhost',
        'port'  => 8888
    ],

    'client' => [
        'commission' => 5
    ]
];