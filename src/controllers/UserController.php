<?php

namespace Controller;

use Helper\Data;
use Model\User;
use Helper\Mail;
use Helper\BodyMail;
use Helper\ValidData;
use Helper\Password;
use Helper\Response;
use Helper\Validjwt;
use Route\Routes;

class UserController
{
    public static function register( Routes $request ) {

        /* Valid user */
        Validjwt::confirmAuthentication();
        $labelsIn = [ 'name', 'email', 'phone', 'password' ];
        $data = $request->data;
        ValidData::validIn( $data, $labelsIn );
        $user = new User();
        //Valid unique email
        $arrData = Data::getDataQuery( $data );
        $response = $user->store( $arrData ) ?? [];
        //Send response
        Response::response( 200, 'success', $response );
    }

    public static function confirm( Routes $request ){
        $token = $request->param;
        $user = new User();
        $response = $user->comfirm( $token );
        Response::response( 200, 'User confirmed', $response );
    }

    public static function index()
    {
        /* Valid user */
        Validjwt::confirmAuthentication();
        $user = new User();
        $response = $user->index();
        Response::response( 200, 'success', $response );
    }

    public static function find( Routes $request )
    {
        /* Valid user */
        Validjwt::confirmAuthentication();
        $id = $request->param;
        //Valid id is number
        ValidData::isNumeric( $id );
        $user = new User();
        /* Create data */
        $response = $user->find( $id );
        Response::response( 200, 'success', $response );
    }

    public static function update(  Routes $request )
    {
        /* Valid user */
        Validjwt::confirmAuthentication();
        $labelsIn = [  'id', 'name', 'email', 'phone' ];
        $data = $request->data;
        /* Valid data */
        ValidData::validIn( $data, $labelsIn );
        ValidData::isNumeric( $data->id );
        /* Valid id */
        $user = new User();
        /* Updated */
        $arrData = Data::getDataQuery( $data );
        $response = $user->update( $arrData );
        Response::response( 200, 'success', [ 'resposne' => $response ] );
    }

    public static function destroy( Routes $request )
    {
        /* Valid user */
        Validjwt::confirmAuthentication();
        $id = $request->param;
        ValidData::isNumeric( $id );
        $user = new User();
        /* Delete user */
        $response = $user->destroy( $id );
        Response::response( 200, 'success', $response );
    }

    public static function login(  Routes $request )
    {
        $labelsIn = [ 'email', 'password' ];
        /* Valid data */
        $data = $request->data;
        ValidData::validIn( $data, $labelsIn );
        $user = new User();
        $arrData = Data::getDataQuery( $data );
        $user = new User();
        $response = $user->login( $arrData );
        Response::response( 200, 'success', $response );
    }

    public static function forgetPassword( Routes $request )
    {
        $labelsIn = [ 'email' ];
        $data = $request->data;
        ValidData::validIn( $data, $labelsIn );
        $arrData = Data::getDataQuery( $data );
        $user = new User();
        $response = $user->forgetPassword( $arrData );
        Response::response( 200, 'success', $response );
    }

    public static function update_pass( $data )
    {
        /* Valid user */
        Validjwt::confirmAuthentication();
        $labelsIn = [ 'email', 'password' ];
        /* Valid data */
        ValidData::validIn( $data, $labelsIn );
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
