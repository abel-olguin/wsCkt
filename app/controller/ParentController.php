<?php
/**
 * Created by PhpStorm.
 * User: abelolguinchavez
 * Date: 16/08/15
 * Time: 4:25
 */

class ParentController {
    /**
     * @param $string
     *
     * cargar un modelo
     *
     * Funcion encargada de leer un modelo
     * y permitir el uso de todos sus metodos
     */
    public function load_model($string)
    {
        require_once(__DIR__.'../../model/'.$string.'.php');
        $newClass = new $string();
        $this->{$string} = $newClass;
    }
}