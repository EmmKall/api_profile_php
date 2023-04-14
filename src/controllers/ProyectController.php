<?php

namespace Controller;

use Flight;
use Helper\Data;
use Helper\ValidData;
use Helper\Response;
use Helper\Validjwt;
use Model\Proyect;

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

    public static function store( $data )
    {
        // Valid user
        Validjwt::confirmAuthentication();
        $labelsIn = [ 'name', 'img', 'description', 'tecnologies' ];
        $validIn = ValidData::validIN( $data, $labelsIn );
        if( sizeof( $validIn) > 0 ) { Response::returnResponse( $validIn ); }
        /* Create data */
        $arrData = [
            ':name'         => $data->name,
            ':slug'         => Data::createSlug( $data->name ),
            ':description'  => $data->description,
            ':tecnologies'  => $data->tecnologies,
            ':img'  => $data->img,
        ];
        $proyect = new Proyect();
        $proyect = $proyect->store( $arrData );
        
        Response::returnResponse( $proyect );
    }

    public static function update( $data )
    {
        /* Valid user */
        Validjwt::confirmAuthentication();
        $labelsIn = [  'id', 'name', 'description', 'tecnologies', 'img' ];
        /* Valid data */
        $validIn = ValidData::validIN( $data, $labelsIn );
        if( sizeof( $validIn) > 0 ) { Response::returnResponse( $validIn ); }
        /* Valid id */
        $isNumeric = ValidData::isNumeric( $data->id );
        if( sizeof( $isNumeric ) > 0 ) { Response::returnResponse( $isNumeric ); }
        $proyect = new Proyect();
        /* Create Data */
        $arrData = [
            ':id'           => $data->id,
            ':name'         => $data->name,
            ':slug'         => Data::createSlug( $data->name ),
            ':img'          => $data->img,
            ':description'  => $data->description,
            ':tecnologies'  => $data->tecnologies,
        ];
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

