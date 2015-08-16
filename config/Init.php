<?php
/**
 * Created by PhpStorm.
 * User: abelolguinchavez
 * Date: 16/08/15
 * Time: 1:31
 *
 * Aqui se inicializan todos loa ajustes la conexion es constante por tanto la defino
 * como tal para evitar crear el objeto una y otra vez
 */
global $connection;

class Init {

    private static $config;
    private static $error_code = ['error'=>'404', 'message'=>'No found'];

    /**
     * @return mysqli
     *
     * Conexion a base de datos
     */
    public static function connection(){
        self::$config = require_once('Config.php');

        $sql = self::$config['connection'];

        return new mysqli($sql['host'],$sql['usr'],$sql['pass'],$sql['db'],$sql['port']);
    }

    /**
     * @return mixed
     *
     * retorna la commission por default
     */
    public static function get_commission(){
        return self::$config['client']['commission'];
    }

    /**
     * @param $controller
     *
     * Retorna la funcion de un controlador si este existe
     */
    public static function load($request){

        $path       = './app/controller/';
        $url        = explode('/',$request['url']);

        self::check_url($url);

        $file       = self::get_controller($url);
        $action     = self::get_action($url);
        $params     = self::get_params($url);

        $controller = $file.'Controller';

        if(file_exists($path.$file.'.php')){

            $GLOBALS['connection'] = self::connection();

            require_once($path.$file.'.php');

            $obj = new $controller();

            if(method_exists($obj,$action)){
                    try{
                        $_REQUEST = array();
                        $response = call_user_func_array([$obj,$action],[$params]);
                    }catch (Exception $e){
                        echo json_encode(self::$error_code);
                    }

            }else{
                echo json_encode(self::$error_code);
            }

        }else{
            echo json_encode(self::$error_code);
        }

    }

    /**
     * @param $url
     *
     * Verifica que la url contenga informacion
     *
     */
    private static function check_url($url){
        if(!$url){
            echo json_encode(self::$error_code);
        }
    }

    /**
     * @param $url
     * @return string
     *
     * obtiene el primer valor de un array y lo
     * devuelve para ser usado como controlador
     */
    private static function get_controller(&$url){
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
    private static function get_action(&$url){
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
    private static function get_params(&$url){
        $params = ucfirst(array_shift($url));
        return $params?$params:$_REQUEST;
    }
}

