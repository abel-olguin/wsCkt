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
require_once('JsonResponse.php');
class Init {

    private static $config;
    private static $request;
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

        self::$request    = new JsonResponse();

        $file             = self::$request->get_controller($url);
        $action           = self::$request->get_action($url);
        $params           = self::$request->get_params($url);

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
                        self::$request->default_response();
                    }

            }else{
                self::$request->default_response();
            }

        }else{
            self::$request->default_response();
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
            self::$request->default_response();
        }
    }


}

