<?php

namespace Model;

use Database\Conection;
use Helper\Data;
use Helper\Password;

class User
{
    private array $columnsDB = [ 'id', 'name', 'email', 'phone', 'token', 'password', 'created_at', 'updated_at' ];
    private string $table = 'users';

    public function __construct()
    {
        
    }

    public function index(): array
    {
        $removeColumns = [ 'password' ];
        $columns = Data::removeColumns( $this->columnsDB, $removeColumns );
        $columns = implode( ", ", $this->columnsDB );
        $sql = ' SELECT ' . $columns . ' FROM ' . $this->table . ' ORDER BY name, email ';
        $response = Conection::getAll( $sql );
        return $response;
    }

    public function find( $arrData ): array
    {
        $sql = " SELECT id, name, email, phone, rol FROM users WHERE id = :id ";
        $response = Conection::find( $sql, $arrData );
        return $response;
    }

    public function store( $data ): array
    {
        $sql = ' INSERT INTO users ( name, email, phone, password ) VALUES ( :name, :email, :phone, :password ) ';
        $response = Conection::store( $sql, $data );
        return $response;
    }

    public function update( array $arrData, string $pass ): array
    {
        if( $pass !== '' )
        {
            $sql = ' UPDATE users SET name = :name, email = :email, phone = :phone, password = :password WHERE id = :id ';
            $arrData[':password'] = ( $pass !== null && trim( $pass ) !== '' ) ? Password::Encryp( $pass ) : '';
        } else 
        {
            $sql = ' UPDATE users SET name = :name, email = :email, phone = :phone WHERE id = :id ';
        }
        $response = Conection::update( $sql, $arrData );
        return $response;
    }

    public function destroy( $arrData ): array
    {
        $sql = " DELETE FROM users WHERE id = :id ";
        $response = Conection::find( $sql, $arrData );
        return $response;
    }

    public function login( $arrData ): array
    {
        $sql = ' SELECT id, name, email, password FROM users WHERE email = :email ';
        $response = Conection::find( $sql, $arrData );
        return $response;
    }

    public static function setToken( $arrData ): array
    {
        $sql = ' UPDATE users SET token = :token WHERE id = :id';
        $response = Conection::update( $sql, $arrData );
        return $response;

    }

    public static function updatePassword( $arrData )
    {
        $sql = ' UPDATE users SET password = :password WHERE email = :email ';
        $response = Conection::update( $sql, $arrData );
        return $response;
    }

}
