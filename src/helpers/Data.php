<?php

namespace Helper;

class Data
{

    public static function readData()
    {
        $data = json_decode( file_get_contents( 'php://input' ) ) ?? null;
        return $data;
    }

    public static function readParams()
    {
        
    }

    public static function createSlug( string $name ):string
    {
        $slug = str_replace( ' ', '_', strtolower( $name ) );
        return $slug;
    }

    public static function validImg( $type ): string {
        $formats = [
            'image/jpeg'=>'jpg', 
            'image/gif'=>'gif', 
            'image/bmp'=>'bmp', 
            'image/png'=>'png'
        ];
        $flag = false;
        foreach ($formats as $format ) {
            if( str_contains( $format, $type ) ) {
                $flag =  true;
            }
        }
        if( $flag ) {
            $response = [
                'status' => 403,
                'msg' => 'Format not valid'
            ];
            Response::returnResponse( $response );
        }
        return explode( '/', $type )[1];
    }

    public static function convertToObject( $data ) {
        if( gettype( $data ) === 'array' ) {
            $data = json_decode( json_encode( $data) );
        }
        return $data;
    }

}
