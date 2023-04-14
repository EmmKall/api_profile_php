<?php

namespace Helper;

class ValidData
{

    public static function validIN( $data, $labels ): array
    {
        $response = [];
        foreach ($labels as $value ) {
            if( $data->$value === null || trim( $data->$value ) === '' )
            {
                $response = [
                    'status' => 403,
                    'msg'    => 'Missing data'
                ];
                break;
            }
        }
        return $response;
    }
    
    public static function isNumeric( $number ): array
    {
        $response = [];
        if( !is_numeric( $number ) )
        {
            $response = 
            [
                'status' => 403,
                'msg'    => 'Data not valid'
            ];
        }
        return $response;
    }

}
