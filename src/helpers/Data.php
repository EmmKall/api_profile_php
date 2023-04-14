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

}
