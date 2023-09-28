<?php

namespace Helper;

class ValidData
{

    public static function validIn( $data, $labels ): void
    {
        foreach ($labels as $value ) {
            if( !isset( $data->$value ) || ($data->$value === null || trim( $data->$value ) === '') )
            {
                Response::response( 400, 'Missing data: ' . $value );
                break;
            }
        }
    }
    
    public static function isNumeric( $number ): array
    {
        $response = [];
        if( !is_numeric( $number ) )
        {
            Response::response( 400, $number . ' is not a valid data' );
        }
        return $response;
    }

}
