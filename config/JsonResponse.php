<?php
/**
 * Created by PhpStorm.
 * User: abelolguinchavez
 * Date: 16/08/15
 * Time: 5:47
 */

class JsonResponse {
    public function response(array $response){
        echo json_encode($response);
    }

    public static function response_s(array $response){
        echo json_encode($response);
    }
}