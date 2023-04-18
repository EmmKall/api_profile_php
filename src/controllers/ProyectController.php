<?php

namespace Controller;

use Model\Proyect;
use Helper\Data;
use Helper\ValidData;
use Helper\Response;
use Helper\Validjwt;
use Helper\Image;

class ProyectController
{

    public static function index()
    {
        $proyect = new Proyect();
        $response = $proyect->index();
        Response::returnResponse( $response );
    }

    public static function find( $id )
    {
        $arrData = [':id' => $id ];
        $proyect = new Proyect();
        $response = $proyect->find( $arrData );
        Response::returnResponse(( $response ) );
    }

    /* public static function load_img( $data, $files = [] )
    {
        Response::returnResponse( $data );
    } */

    public static function store( $data )
    {
        // Valid user
        Validjwt::confirmAuthentication();
        /* Create data */
        $data = Data::convertToObject( $data );
        $labelsIn = [ 'name', 'description', 'tecnologies', 'git' ];
        $validIn = ValidData::validIN( $data, $labelsIn );
        if( sizeof( $validIn) > 0 ) { Response::returnResponse( $validIn ); }
        //Build data
        $arrData = [
            ':name'         => $data->name,
            ':slug'         => Data::createSlug( $data->name ),
            ':description'  => $data->description,
            ':tecnologies'  => $data->tecnologies,
            ':git'  => $data->git,
            ':url'  => $data->url ?? '',
            ':img'  => '',
        ];
        //Read and save img
        if( $_FILES[ 'img' ] || $_FILES[ 'img' ][ 'error' ] !== 0 ) {
            $file = $_FILES[ 'img' ];
            $path = '/public_html/assets/img/projects'; //Prod
            /* $path = 'D:/FILES/PORTAFOLIO/WEB_PORTAFOLIO/230415/profile/src/assets/img/projects/'; */ //Des
            $img = Image::loadImg( $file, $path );
            $arrData[ ':img' ] = $img;
        }
        $proyect = new Proyect();
        $proyect = $proyect->store( $arrData );
        
        Response::returnResponse( $proyect );
    }

    public static function update( $data )
    {
        /* Valid user */
        Validjwt::confirmAuthentication();
        $data = Data::convertToObject( $data );
        $labelsIn = [  'id', 'name', 'description', 'tecnologies' ];
        /* Valid data */
        $validIn = ValidData::validIN( $data, $labelsIn );
        if( sizeof( $validIn) > 0 ) { Response::returnResponse( $validIn ); }
        /* Valid id */
        $id = $data->id;
        $isNumeric = ValidData::isNumeric( $id );
        if( sizeof( $isNumeric ) > 0 ) { Response::returnResponse( $isNumeric ); }
        $proyect = new Proyect();
        /* Create Data */
        $arrData = [
            ':id'           => $data->id,
            ':name'         => $data->name,
            ':slug'         => Data::createSlug( $data->name ),
            ':img'          => '',
            ':git'  => $data->git,
            ':url'  => $data->url ?? '',
            ':description'  => $data->description,
            ':tecnologies'  => $data->tecnologies,
        ];
        /* get project before update */
        $findData = [':id' => $id ];
        $search = $proyect->find( $findData );
        if( $search[ 'status' ] === 200 ) {
            $arrData[ ':img' ] = $search[ 'data' ][ 'img' ];
        }
        /* Validar si viene imagen */
        if( isset( $_FILES[ 'img' ] ) ) {
            $file = $_FILES[ 'img' ];
            $path = '/public_html/assets/img/projects'; //Prod
            //$path = 'D:/FILES/PORTAFOLIO/WEB_PORTAFOLIO/230415/profile/src/assets/img/projects/'; //Des
            /* Eliminar imagen si existe */
            if( is_file( $path . $arrData[ ':img' ] ) ) {
                unlink( $path . $arrData[ ':img' ] );
            }
            $new_img = Image::loadImg( $file, $path );
            $arrData[ ':img' ] = $new_img;
        }
        $response = $proyect->update( $arrData );
        Response::returnResponse( $response );
    }

    public static function destroy( $id )
    {
        /* Valid user */
        /* Validjwt::confirmAuthentication(); */
        $isNumeric = ValidData::isNumeric( $id );
        if( sizeof( $isNumeric ) > 0 ) { Response::returnResponse( $isNumeric ); }
        /* Create data */
        $arrData = [':id' => $id ];
        $proyect = new Proyect();
        $response = $proyect->destroy( $arrData );
        Response::returnResponse( $response );
    }

}

