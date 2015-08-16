<?php
/**
 * Created by PhpStorm.
 * User: abelolguinchavez
 * Date: 16/08/15
 * Time: 5:47
 */

class JsonResponse {
    private $default = ['error'=>'404', 'message'=>'No found'];

    /**
     *
     * Constructor
     *
     * Verificica que este logueado antes de acceder al ws
     */
    public function __construct(){
        $usr  = 'admin';
        $pass = 'pass';

        if( empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW']) ){
            header('HTTP/1.1 401 Unauthorized');

            $this->default_response();

        }elseif( $_SERVER['PHP_AUTH_USER'] != $usr && $_SERVER['PHP_AUTH_PW'] != $pass ){
            header('HTTP/1.1 401 Unauthorized');

            $this->default_response();
        }
    }

    /**
     * @param array $response
     *
     * Imprime un json acorde a la respuesta que se mande
     *
     */
    public function response(array $response){
        header('Content-type: application/json');
        echo json_encode($response);
    }

    /**
     *
     * Error 404
     *
     * Imprime el error 404 en formato json
     */
    public function default_response(){
        header('Content-type: application/json');
        exit(json_encode($this->default));

    }

    /**
     * @param $url
     * @return string
     *
     * obtiene el primer valor de un array y lo
     * devuelve para ser usado como controlador
     */
    public function get_controller(&$url){
        $controller = ucfirst(array_shift($url));
        return $controller;
    }

    /**
     * @param $url
     * @return string
     *
     * Obtiene el primer valor de un array y lo
     * devuelve para ser usado como una accion
     */
    public function get_action(&$url){
        $action = ucfirst(array_shift($url)).'_'.strtolower($_SERVER['REQUEST_METHOD']);
        return $action;
    }

    /**
     * @param $url
     * @return string
     *
     * obtiene el primer valor de un array y lo devuelve para
     * ser usado como parametro (solo se acepta un parametro
     */
    public function get_params(&$url){
        $params = ucfirst(array_shift($url));
        return $params?$params:$_REQUEST;
    }
}