<?php
/**
 * Created by PhpStorm.
 * User: abelolguinchavez
 * Date: 16/08/15
 * Time: 4:27
 */
require_once('ParentModel.php');

class Client extends ParentModel{
    protected static $table_name   = 'client';
    protected static $key          = 'id';
}