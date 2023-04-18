<?php

namespace Helper;

class Image 
{

    public static function loadImg( $file, $path ):string {
        if( $file[ 'error' ] !== 0 ) {
            return '';
        }
        $imgName = '';
        $extension = Data::validImg( $file[ 'type' ] );
        $new_name = uniqid( 'proy' ) . '.' . $extension;
        if( !file_exists($path ) ) {
            mkdir($path, 0777, true);
        }
        if( file_exists( $file[ 'tmp_name' ] ) ) {
            if( move_uploaded_file( $file[ 'tmp_name' ], $path . $new_name ) ) {
                $imgName = $new_name;
            }
        }

        return $imgName;
    }

}
