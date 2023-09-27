<?php

namespace Helper;

class Response
{

    public static function debugear( $data ) {
        die( json_encode( $data ) );
    }

    public static function response( $status, $data, $msg )
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
