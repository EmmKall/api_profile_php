<?php

namespace Route;

use Controller\UserController;
use Controller\ProyectController;
use Helper\Data;
use Helper\Response;

class Routes
{
    private string $request;
    private string $controller;
    private string $method;
    private string $param;
    private array  $allow_proccess = [ 'index' => 'GET', 'find' => 'GET', 'find' => 'GET', 'findall' => 'GET', 'store' => 'POST', 'login'=> 'POST', 'forget_password' => 'POST', 'update_pass' => 'POST', 'update' => 'PUT', 'destroy' => 'DELETE' ];

    public function __construct()
    {
        $this->getPetition();
    }
    /* Get data and proccess petition */
    private function getPetition(): void
    {
        //Get Request
        $this->getRequest();
        $uri =  explode( '/', $_SERVER['REQUEST_URI'] );
        //Get Controller
        $this->getController( $uri[ 2 ] );
        //Get Method
        $this->getMethod( $uri[ 3 ] );
        //Get Params
        $this->getParams( $uri[ 4 ] ?? '' );
        //Process Petition
        $this->validPetition();
        $this->proccessController();
        
    }
    /* Get Request */
    private function getRequest(): void
    {
        $this->request = $_SERVER['REQUEST_METHOD'] ?? '';
    }
    /* Get controller of petiticon */
    public function getController( $controller ): void
    {
        $this->controller = ucfirst( $controller . 'Controller' ) ?? '';
    }
    /* Get Method of petition */
    private function getMethod( $method ): void
    {
        $this->method = $method ?? '';
    }
    /* Get params in case to exist */
    private function getParams( $params ): void
    {
        $this->param = $params ?? '';
    }
    /* Valid a correct controller */
    private function proccessController()
    {
        $instance = null;
        switch ( $this->controller ) {
            case 'UserController':      $instance = new UserController(); break;      
            case 'ProyectController':   $instance = new ProyectController(); break;      
            default:
            $response = [ 'status' => 400, 'msg' => 'Controller no found' ];
                Response::returnResponse( $response );
            break;
        }
        if( $this->request === 'GET' || $this->request === 'DELETE' ){ $instance->{$this->method}( $this->param ); }
        elseif( $this->request === 'POST' || $this->request === 'PUT' ){
            $data = Data::readData();
            $instance->{$this->method}( $data );
        }
        var_dump( $instance );
        /* $response = $instance::{$this->method()};
        Response::returnResponse( $response ); */
    }

    /* Valid that request and method match */
    private function validPetition(): void
    {
        $isValidProcces = ( array_key_exists( $this->method, $this->allow_proccess ) && $this->allow_proccess[ $this->method ] === $this->request );
        if( !$isValidProcces )
        {
            $response = [
                'status' => 403,
                'msg'    => 'Petition no valid'
            ];
            Response::returnResponse( $response );
        }
    }

}
