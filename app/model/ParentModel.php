<?php
/**
 * Created by PhpStorm.
 * User: abelolguinchavez
 * Date: 16/08/15
 * Time: 4:19
 */

class ParentModel {
    private $connection;

    public function __construct(){
         $this->connection = $GLOBALS['connection'];
    }
    /**
     * @param $args
     * @return array
     *
     * inserta valores en una tabla
     *
     * Funcion encargada de insertar registros en una base de datos
     * es necesario que los argumentos esten en un array donde key
     * sea el nombre del campo en base de datos, y el valor sea el que
     * tendra dicho campo
     */
    public function insert(array $args)
    {
        $table      = static::$table_name;
        $now        = date('d-m-Y :: H:m:s');
        $columns    = implode(",", array_keys($args));
        $values     = [];
        $vars       = array_values($args);

        for ($i = 0; $i <= count($vars) - 1; $i++) {
            $var = $vars[$i];
            $var = is_string($var) ? "'".$this->connection->real_escape_string($var)."'" : $var;
            array_push($values, $var);
        }

        $values = implode(',', $values);
        $sql = "INSERT INTO $table($columns,date) VALUES ($values,'$now')";
        if ($this->connection->query($sql)) {
            $id = $this->connection->insert_id;
            $args = array_merge($args, array('id' => $id));
            $data = array('response' => true, 'values' => $args);
        } else {
            die('La insercion no se realizo: ' . $this->connection->connect_errno);
        }
        return $data;
    }
    /**
     * @param $id
     * @param array $args
     * @return array
     *
     * Hacer un update a partir de un array
     *
     * Funcion encargadade hacer un update a una tabla recorriendo un
     * array; es decir puede haber multiples campos a actualizar
     */
    public function update($id, array $args)
    {
        $table  = static::$table_name;
        $update = $this->get_array_string($args,',','=');
        $sql    = "UPDATE $table SET $update WHERE id = $id";

        if ($this->connection->query($sql)) {
            $data = array('response' => true, 'values' => $args);
        } else {
            die('La actualizacion no se realizo: ' . $this->connection->connect_errno);
        }
        return $data;
    }
    /**
     * @param $where
     * @param $args
     * @return array
     *
     * parecida a update pero aqui el where es dinamico
     *
     * Funcion encargada de hacer un update a varios campos de la tabla
     * donde where sea un array tambien
     */
    public function update_where($where,$args)
    {
        $table      = static::$table_name;
        $values     = $this->get_array_string($args,'AND');
        $arr_where  = $this->get_array_string($where,'AND');
        $sql        = "UPDATE $table SET $values WHERE $arr_where";

        if ($this->connection->query($sql)) {
            $data = array('response' => true, 'values' => $args);
        } else {
            die('La insercion no se realizo: ' . $this->connection->connect_errno);
        }
        return $data;
    }
    /**
     * @param $args
     * @return mixed
     * Busca un registro en base de datos
     *
     * Funcion encargada de retornar un registro de base de datos acorde a un array
     */
    public function find_where($args,$limit = 0)
    {
        $table          = static::$table_name;
        $limit_string   = $limit != 0 ?"LIMIT $limit":'';
        $where          = $this->get_array_string($args,'AND');
        $sql            = "SELECT * FROM $table WHERE $where $limit_string";
        $result         = $this->get_result($sql);
        return $result;
    }
    /**
     * @param $args
     * @return mixed
     *
     * Contrario de find where este solo debe tener un argumento
     */
    public function find($camp,$where)
    {
        $table   = static::$table_name;
        if(is_string($where))
        {
            $where = "'".$where."'";
        }
        $sql    = "SELECT * FROM $table WHERE $camp = $where";
        $result = $this->get_result($sql);
        return $result;
    }
    /**
     * @param $query
     * @return mixed
     * Convierte una consulta en array asociativo
     *
     * Funcion encargada de retornar un array asociativo
     * de una consulta
     */
    private function get_result($query)
    {
        $array  = $this->connection->query($query);
        $result = array();
        while($data = $array->fetch_assoc())
        {
            $result[] = $data;
        }
        return $result;
    }
    /********************************************************************************************
     *                              funciones privadas
     *******************************************************************************************/
    /**
     * @param $array
     * @param $separator
     * @return string
     *
     * genera un string a partir de un array
     *
     * Funcion encargada de convertir un array
     * a una cadena de texto "llave separador valor" ej "nombre = juan"
     */
    private function get_array_string($array,$delimiter = ',',$equals = '')
    {
        $keys   = array_keys($array);
        $values = array_values($array);
        $result = array();
        for ($i = 0; $i <= count($values) - 1; $i++) {
            $val      = $values[$i];
            $s_equals = $equals == ''?(is_numeric($val)?" = ":" LIKE "): " $equals ";
            $string   = is_numeric($val)? $s_equals.$val:$s_equals."'".$val."'";
            $result[] = $keys[$i].$string;
        }
        return implode(' '.$delimiter.' ',$result);
    }
}