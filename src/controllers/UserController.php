<?php

namespace Controller;

use Model\User;
use Helper\Mail;
use Helper\ValidData;
use Helper\Password;
use Helper\Response;
use Helper\Validjwt;
use Route\Routes;

class UserController
{
    public static function register( Routes $request ) {
        die( json_encode( $request ) );
    }

    public static function index()
    {
        /* Valid user */
        //Validjwt::confirmAuthentication();
        $user = new User();
        $response = $user->index();
        die( json_encode( $response ) );
        //Response::returnResponse( $response );
    }

    public static function find( $id )
    {
        /* Valid user */
        Validjwt::confirmAuthentication();
        $isNumeric = ValidData::isNumeric( $id );
        if( sizeof( $isNumeric ) > 0 ) { /* Response::returnResponse( $isNumeric ); */ }
        /* Create data */
        $arrData = [':id' => $id ];
        $user = new User();
        $response = $user->find( $arrData );
        //Response::returnResponse(( $response ) );
    }
    
    public static function store( $data )
    {
        /* Valid user */
        Validjwt::confirmAuthentication();
        $labelsIn = [ 'name', 'email', 'phone', 'password' ];
        $validIn = ValidData::validIN( $data, $labelsIn );
        if( sizeof( $validIn) > 0 ) { /* Response::returnResponse( $validIn ); */ }
        /* Create data */
        $arrData = [
            ':name'     => $data->name,
            ':email'    => $data->email,
            ':phone'    => $data->phone,
            ':password' => Password::Encryp( $data->password ),
        ];
        $user = new User();
        $user = $user->store( $arrData );
        
        //Response::returnResponse( $user );
    }

    public static function update( $data )
    {
        /* Valid user */
        Validjwt::confirmAuthentication();
        $labelsIn = [  'id', 'name', 'email', 'phone' ];
        /* Valid data */
        $validIn = ValidData::validIN( $data, $labelsIn );
        if( sizeof( $validIn) > 0 ) { /* Response::returnResponse( $validIn ); */ }
        /* Valid id */
        $isNumeric = ValidData::isNumeric( $data->id );
        if( sizeof( $isNumeric ) > 0 ) { /* Response::returnResponse( $isNumeric ); */ }
        $user = new User();
        /* Create Data */
        $arrData = [
            ':id'       => $data->id,
            ':name'     => $data->name,
            ':email'    => $data->email,
            ':phone'    => $data->phone,
        ];
        $pass = ( $data->password !== null ) ? $data->password : '';
        $response = $user->update( $arrData, $pass );
        //Response::returnResponse( $response );
    }

    public static function destroy( $id )
    {
        /* Valid user */
        Validjwt::confirmAuthentication();
        $isNumeric = ValidData::isNumeric( $id );
        if( sizeof( $isNumeric ) > 0 ) { /* Response::returnResponse( $isNumeric ); */ }
        /* Create data */
        $arrData = [':id' => $id ];
        $user = new User();
        $response = $user->destroy( $arrData );
        //Response::returnResponse( $response );
    }

    public static function login( $data )
    {
        $labelsIn = [ 'email', 'password' ];
        /* Valid data */
        $validIn = ValidData::validIN( $data, $labelsIn );
        if( sizeof( $validIn) > 0 ) { /* Response::returnResponse( $validIn ); */ }
        /* Create data */
        $arrData = [ ':email' => $data->email ];
        $user = new User();
        $findUser = $user->login( $arrData );
        if( !$findUser[ 'data' ] ){ /* Response::returnResponse([
            'status' => 403,
            'msg'    => 'Email y/o password not valid'
        ]); */ }
        /* Valid Password */
        Password::DesEncryp( $data->password, $findUser[ 'data']['password'] );
        /* Update Token */
        $id    = $findUser[ 'data' ][ 'id' ];
        $email = $findUser[ 'data' ][ 'email' ];
        
        $token = Validjwt::setToken( $id, $email );
        /* Actualizar Token */
        $arrData = [
            ':token' => $token,
            ':id'    =>$id
        ];
        $updateToken = $user->setToken( $arrData );
        if( $updateToken[ 'status' ] === 200 )
        {
            /* Response::returnResponse([
                'status' => 200,
                'data'   => [
                    'user'   => $findUser[ 'data' ][ 'name' ],
                    'token'  => $token,
                    'id'     => $id
                ]
            ]); */
        } else
        {
            /* Response::returnResponse([
                'status' => 500,
                'msg'   => 'internal error'
            ]); */
        }
    }

    public static function forget_password( $data )
    {
        $labelsIn = [ 'email' ];
        /* Valid data */
        $validIn = ValidData::validIN( $data, $labelsIn );
        if( sizeof( $validIn) > 0 ) { /* Response::returnResponse( $validIn ); */ }
        $arrData = [ ':email' => $data->email ];
        $user = new User();
        $findUser = $user->login( $arrData );
        if( !$findUser[ 'data' ] ){ /* Response::returnResponse([
            'status' => 403,
            'msg'    => 'Internal error'
        ]); */ }
        /* Generar ramdom password */
        $new_pass = Password::genereRamdomPassword();
        /* Update new password */
        $arrData = [
            ':email' => $data->email,
            ':password' => Password::Encryp( $new_pass )
        ];
        $updatePass = $user->updatePassword( $arrData );
        if( $updatePass[ 'status' ] !== 200 )
        {
            $response = [
                'status' => 500,
                'msg'    => 'Internal error'
            ];
            //Response::returnResponse( $response );
        }
        /* Send by email */
        $body_mail = Mail::create_body( $new_pass );
        //$wasSendMail = Mail::send_mail( $data->email, $body_mail, 'Recover password', [] );
        Mail::sendFastMail( 'ing.emmanuel.cal@gmail.com', 'Recover Password', $body_mail );
        $response = [
            'status'   => 200,
            'email'    => $data->email,
            'new_pass' => $new_pass
        ];        // Response
        //Response::returnResponse( $response );
    }

    public static function update_pass( $data )
    {
        /* Valid user */
        Validjwt::confirmAuthentication();
        $labelsIn = [ 'email', 'password' ];
        /* Valid data */
        $validIn = ValidData::validIN( $data, $labelsIn );
        if( sizeof( $validIn) > 0 ) { /* Response::returnResponse( $validIn ); */ }
        /* Create Data */
        $arrData = [
            ':email'    => $data->email,
            ':password' => Password::Encryp( $data->password )
        ];
        /* Update Password */
        $user = new User();
        $response = $user->updatePassword( $arrData );
        //Response::returnResponse( $response );
    }

}
