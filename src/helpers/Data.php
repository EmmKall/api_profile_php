<?php

namespace Helper;

class Data
{

    public static function sHtml( $html ): string {
        return htmlspecialchars( $html );
    }

    public static function validData( array $keys, object $data ) {
        $alerts = [];
        foreach ( $keys as $item ) {
            if( trim( $data->$item  ) == '' ){ $alerts[ 'error' ][] = $item .' is required'; }
        }
        if( sizeof( $alerts ) > 0 ){ Response::response( 400, 'Missing information', $alerts ); }
    }

    public static function getDataRequest() {
        $data = json_decode( file_get_contents( 'php://input' ) ) ?? null;
        return $data;
    }

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
            Response::response( 403, 'Format not valid' );
        }
        return explode( '/', $type )[1];
    }

    public static function convertToObject( $data ) {
        if( gettype( $data ) === 'array' ) {
            $data = json_decode( json_encode( $data) );
        }
        return $data;
    }

    public static function objectToArray( $data ) {
        die( json_encode( gettype( $data )) );
        return json_decode( json_encode( $data ) );
    }

    public static function removeColumns( array $data, array $rows ){
        
        foreach ($data as $keyi => $value) {
            foreach ( $rows as $row ) {
                if( $row === $value ){
                    unset( $data[ $keyi ] );
                }
            }
        }
        return $data;
    }

    public static function removeDates( array $data, bool $insert = false ) {
        if( $insert ){ array_shift( $data ); }
        array_pop( $data );
        array_pop( $data );
        return $data;
    }

    public static function getDataQuery( $data ) {
        $arrData = [];
        foreach ( $data as $key => $value ) { $arrData[ ':' . $key ] = $value; }
        return $arrData;
    }

}
