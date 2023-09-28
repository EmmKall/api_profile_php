<?php

namespace Model;

use Database\Conection;
use Helper\Data;
use Helper\Password;
use Helper\Response;

class User
{
    private array $columnsDB = [ 'id', 'name', 'email', 'phone', 'password', 'token', 'created_at', 'updated_at' ];
    private string $table = 'users';

    public function __construct()
    {
        
    }

    public function index(): array
    {
        $removeColumns = [ 'password' ];
        $order = ' name, email ';
        $columns = Data::removeColumns( $this->columnsDB, $removeColumns );
        $response = Conection::getAll( $this->table, $columns );
        return $response;
    }

    public function find( $id ): array
    {
        $removeColumns = [ 'password' ];
        $columns = Data::removeColumns( $this->columnsDB, $removeColumns );
        $response = Conection::find( $this->table, $columns, $id );
        return $response;
    }

    public function store( $arrData )
    {
        //Unique email
        $email = $arrData[ ':email' ];
        $emailRows = Conection::where( $this->table, 'email', $email );
        if( sizeof( $emailRows ) > 0 ) { Response::response( 400, $email . ' is already registered' ); }
        //Prepare statement and data
        $arrData[ ':password' ] = $this->encryp( $arrData[ ':password' ] );
        $arrData[ ':token' ] = $this->getToken();
        $columns = Data::removeDates( $this->columnsDB, true );
        //Insert row
        $lastId = Conection::store( $this->table, $columns, $arrData );
        $response = [ 'id inserted' => $lastId ];
        return $response;
    }

    public function update( array $data ): string
    {
        $columns = Data::removeDates( $this->columnsDB, true );
        $removeColumns = [ 'id', 'token' ];
        if( !isset( $data[ ':password' ] ) || $data[ ':password' ] === '' )
        {
            $removeColumns[]  = 'password';
            //$arrData[':password'] = ( $pass !== null && trim( $pass ) !== '' ) ? Password::Encryp( $pass ) : '';
        } else {
            $data[ ':password' ] = $this->encryp( $data[ ':password' ] );
        }
        $columns = Data::removeColumns( $columns, $removeColumns );
        $response = Conection::update( $this->table, $columns, $data );
        $response = ( $response === true ) ? 'Register updated' : 'Register not updated';
        return $response;
    }

    public function destroy( $id ): array
    {
        $response = Conection::destroy( $this->table, $id );
        if( $response[ 'status' ] === 200 ){ $response = [ 'msg' => 'Register deleted' ]; }
        else { $response = [ 'msg' => 'Register not deleted' ]; }
        return $response;
    }

    public function login( $arrData ): array
    {
        $sql = ' SELECT id, name, email, password FROM users WHERE email = :email ';
        //$response = Conection::find( $sql, $arrData );
        return [];
    }

    public static function setToken( $arrData ): array
    {
        $sql = ' UPDATE users SET token = :token WHERE id = :id';
        //$response = Conection::update( $sql, $arrData );
        return [];

    }

    public static function updatePassword( $arrData )
    {
        $sql = ' UPDATE users SET password = :password WHERE email = :email ';
        //$response = Conection::update( $sql, $arrData );
        return [];
    }

    private function encryp( $password ){
        $password = password_hash( $password, PASSWORD_BCRYPT );
        return $password;
    }

    private function getToken() {
        return uniqid();
    }

    private function validPassword( $password, $encryp ) {
        $res = ( password_verify( $password, $encryp ) ); //$this->confimr === 1
        if( !$res ) { Response::response( 400, 'Credentials not found/valid' ); }
    }
}
