<?php

namespace Helper;

class Response
{

    public static function debugear( $data ) {
        die( json_encode( $data ) );
    }

    public static function response( int $status, string $msg, array $data = [] )
    {
        $response = [
            'status' => $status,
            'msg'    => $msg
        ];
        if( $status < 300 ){
            $response[ 'data' ] = $data;
        }
        die( json_encode( $response ) );
    }

}
