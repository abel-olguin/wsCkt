<?php
/**
 * Created by PhpStorm.
 * User: abelolguinchavez
 * Date: 16/08/15
 * Time: 2:31
 *
 * Toda la logica del webservice
 */
require_once('ParentController.php');

class ClientController extends ParentController{

    public function __construct(){
        $this->load_model('Client');
    }

    public function add_post($input){

        $data = ['email'        => $input['email'],
                 'commission'   => $input['commission'],
                 'public_k'     => $input['public_k'],
                 'private_k'    => $input['private_k'],
                 'status'       => 1];

        $client = $this->Client->insert($data);
        if($client){
            $this->response($client);
        }
    }

    public function update_post($input){
        $data = ['email'        => $input['email'],
                 'commission'   => $input['commission'],
                 'public_k'     => $input['public_k'],
                 'private_k'    => $input['private_k'],
                 'status'       => 1];

        $client = $this->Client->update($input['id'],$data);
        if($client){
            $this->response($client);
        }
    }
}
