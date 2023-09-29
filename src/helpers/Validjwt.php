<?php

namespace Helper;

use Database\Conection;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Validjwt
{

    public static function setToken( int $id, string $email ): string
    {
        $token = [
            'ait'  => time(), //Momento en que inicial el token
            'exp'  => time() + ( 60*60*24 ), //Fecha de expiración, ejemplo 1 dia = 60seg * 60min * 24 horas
            'data' => [
                'id'    => $id,
                'email' => $email
            ]
        ];
        $jwt = JWT::encode( $token, $_ENV[ 'SECRET_KEY' ], $_ENV[ 'ALGORITM' ] );
        return $jwt;
    }

    public static function validJWT( string $jwt ):Bool
    {
        $isOnTime = false;
        try
        {
            $decoded = JWT::decode( $jwt, new Key( $_ENV['SECRET_KEY'], $_ENV['ALGORITM'] ) );
            //Validar datos de usuario
            $id = $decoded->data->id;
            $email = $decoded->data->email;
            if( self::confirmUser( $id, $email )  )
            {
                $expiredTime = $decoded->exp;
                if( self::comfirmTime(( $expiredTime ) ) )
                {
                    $isOnTime = true;
                }
            }
        } catch( \Exception $e )
        {
            //Registro de Log
            Response::response( 503, 'Error: ' . $e->getMessage() );
        }
        
        return $isOnTime;
    }

    public static function confirmUser( int $id, string $email ): bool
    {
        $esConfirmado = false;
        //Validar datos de usuario
        $result = Conection::where( 'users', 'email', $email );
        Response::debugear( $result );
        $name = $result[ 'data' ][ 'name' ];
        /* Validar datos de jwt */
        if( $result['data']['id'] === $id && $result['data']['name'] === $name  )
        {
            $esConfirmado = true;
        }
        return $esConfirmado;
    }

    public static function comfirmTime( int $expireTime ): bool
    {
        $isOnTime = false;
        //Validar el tiempo de expiración
        $now = time();
        if( $now < $expireTime )
        {
            $isOnTime = true;
        }
        return $isOnTime;
    }

    public static function readHeader()
    {
        $code = 'Bearer';
        $headers = getallheaders(); /* die( json_encode( $headers ) ); */
        $http_authorization = null;

        if( isset( $headers['Authorization'] ) )
            $http_authorization = $headers['Authorization'];
            
        if( $http_authorization )
        {
            if( str_contains( $http_authorization, $code ) )
            {
                $authorization = explode( ' ', $http_authorization );
                $response = [ 'jwt' => $authorization[1] ];
            } else
            {
                $response = [
                    'status' => 403,
                    'msg' => 'Not authorized'
                ];
            }
        } else
        {
            $response = [
                'status' => 403,
                'msg' => 'Not authorized'
            ];
        }
        return $response;
    }

    public static function confirmAuthentication(): Bool
    {
        $isAuthenticate = false;
        $jwt = Self::readHeader();
        if( isset( $jwt['jwt'] ) )
        {
            /* Validar jwt */
            if( Validjwt::validJWT( $jwt['jwt'] ) )
            {
                $isAuthenticate = true;
            }
        } else
        {
            Response::response( 403, 'Credentials no valid' );
        }
        return $isAuthenticate;
    }

}

