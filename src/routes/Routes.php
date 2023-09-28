<?php

namespace Route;

use Controller\UserController;
use Helper\Data;
use Helper\Response;

class Routes
{
    public array $getRoutes    = [];
    public array $postRoutes   = [];
    public array $putRoutes    = [];
    public array $deleteRoutes = [];
    public $data = null;
    public string $controller = '';
    public string $request = '';
    public string $method = '';
    public string $param = '';

    public function get( $url, $fn ) {
        $this->getRoutes[ $url ] = $fn;
    }

    public function post( $url, $fn ) {
        $this->postRoutes[ $url ] = $fn;
    }
    
    public function put( $url, $fn ) {
        $this->putRoutes[ $url ] = $fn;
    }

    public function delete( $url, $fn ) {
        $this->deleteRoutes[ $url ] = $fn;
    }

    public function comprobarRoutes() {

        session_start();
        //Get Params in
        $index = $_ENV[ 'INDEX_FN' ] ?? '';
        $this->request = $_SERVER[ 'REQUEST_METHOD' ];
        $request = explode( '/', $_SERVER['REQUEST_URI'] );
        $this->controller = $request[ $index ] ?? '';
        $this->method = $request[ $index + 1 ] ?? '';
        $this->param = $request[ $index + 2 ] ?? '';
        $this->data = Data::getDataRequest();
        $fn = $this->controller . '/' . $this->method;
        switch ( $this->request ) {
            case 'GET':
                $fn = $this->getRoutes[ $fn ] ?? null;
                break;            
            case 'POST':
                $fn = $this->postRoutes[ $fn ] ?? null;
                break;            
            case 'PUT':
                $fn = $this->putRoutes[ $fn ] ?? null;
                break;            
            case 'DELETE':
                $fn = $this->deleteRoutes[ $fn ] ?? null;
                break;            
            default:
                break;
        }
        if( $fn ) {
            call_user_func( $fn, $this );
        } else {
            Response::response( 400, 'Data not found' );
        }

    }

    public function render( $view, $data = [] ) {
        foreach ( $data as $key => $item ) {
            $$key = $item;
        }
        ob_start();

        include_once __DIR__ . '/view/' . $view . '.php';
        $content = ob_get_clean();
        include_once __DIR__ . '/views/layout.php';
    }


}
