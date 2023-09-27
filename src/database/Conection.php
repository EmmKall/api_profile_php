<?php

namespace Database;
use Config\ConfigDB;

class Conection
{
    public $connection;

    private function __constructor()
    {
        $this->make_conection();
    }

    public function get_database_instance()
    {
        return $this->connection;
    }

    public static function make_conection()
    {
        $server = ConfigDB::getDB_HOST();
        $dbname = ConfigDB::getDB_NAME();
        $user = ConfigDB::getDB_USER();
        $password = ConfigDB::getDB_PASSWORD();
        try
        {
            $cadena = "mysql:host=$server;dbname=$dbname;charset=utf8";
            $conexion = new \PDO( $cadena, $user, $password );
            $conexion->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
            $setnames = $conexion->prepare("SET NAMES 'utf8'");
            $setnames->execute();
        } catch( \PDOException $e )
        {
            die( "Error: {$e->getMessage()} at line: {$e->getLine()} en: {$e->getTrace()}" );
        }
        return $conexion;
    }

    public static function get_backup()
    {
        $fecha = date( 'Ymd-His' );
        $file_name = 'C:\backup\respaldo-' . $fecha . '.sql';
        $sql = "mysqldump -h{ConfigDB::getDB_HOST()} -u{ConfigDB::getDB_USER()} -p{ConfigDB::getDB_PASSWORD()} --opt {ConfigDB::getDB_NAME()} > $file_name";
        try
        {
            system( $sql, $output );
        } catch( \PDOException $e )
        {
            die( 'Error: ' . $e->getMessage() );
        }
    }

    public static function getAll( String $sql ): array
    {
        $conn = Conection::make_conection();
        $result = null;
        try
        {
            $result = $conn->query( $sql );
            $response = $result->fetchall( \PDO::FETCH_CLASS );
        } catch( \PDOException $e )
        {
            $response = [
                'status' => 500,
                'msg'    => 'Hubo un error: ' . $e->getMessage() . ' en: ' . $e->getTrace() . ' linea: ' . $e->getLine()
            ];
        }
        $result = null;
        $conn = null;
        return $response;
    }

    public static function findAll( string $sql, Array $arrData )
    {
        $conn = Conection::make_conection();
        $query = null;
        try
        {
            $query = $conn->prepare( $sql );
            $query->execute( $arrData );
            $data = $query->fetchall( \PDO::FETCH_CLASS );
        } catch( \PDOException $e )
        {
            /* Log $e->getMessage() */
            $data = [
                'status' => 500,
                'msg' => 'Hubo un error: ' . $e->getMessage() . ' en: ' . $e->getTrace() . ' linea: ' . $e->getLine()
            ];
        }
        $query = null;
        $conn = null;
        return $data;
    }

    public static function find( String $sql, Array $arrData ): array
    {
        $conn = Conection::make_conection();
        $query = null;
        try
        {
            $query = $conn->prepare( $sql );
            $query->execute( $arrData );
            $data = $query->fetch( \PDO::FETCH_ASSOC );
        } catch( \PDOException $e )
        {
            /* Log $e->getMessage() */
            $data = [
                'status' => 500,
                'msg' => 'Hubo un error: ' . $e->getMessage() . ' en: ' . $e->getTrace() . ' linea: ' . $e->getLine()
            ];
        }
        $query = null;
        $conn = null;
        return $data;
    }

    public static function store( String $sql, Array $arrData ): Array
    {
        $conn = Conection::make_conection();
        $insert = null;
        try
        {
            $insert = $conn->prepare( $sql );
            $insert->execute( $arrData );
            $response = $conn->lastInsertId();        
        } catch( \PDOException $e )
        {
            /* Log $e->getMessage() */
            $response = [
                'status' => 500,
                'msg' => 'Hubo un error: ' . $e->getMessage() . ' en: ' . $e->getTrace() . ' linea: ' . $e->getLine()
            ];
        }
        $insert = null;
        $conn = null;
        return $response;
    }

    public static function update( String $sql, Array $arrData )
    {
        $conn = Conection::make_conection();
        $query = null;
        try
        {
            $query = $conn->prepare( $sql );
            $query->execute( $arrData );
            $response = [
                'status' => 200,
                'msg' => 'Registro actualizado'
            ];
        } catch( \PDOException $e )
        {
            $response = [
                'status' => 500,
                'msg' => 'Hubo un error: ' . $e->getMessage() . ' en: ' . $e->getTrace() . ' linea: ' . $e->getLine()
            ];
        }
        $query = null;
        $conn = null;
        return $response;
    }

    public static function destroy( String $sql, Array $arrData )
    {
        $conn = Conection::make_conection();
        $query = null;
        try
        {
            $query = $conn->prepare( $sql );
            $query->execute( $arrData );
            $response = [
                'status' => 200,
                'msg' => 'Registro eliminado'
            ];
        } catch( \PDOException $e )
        {
            /* Log $e->getMessage() */
            $response = [
                'status' => 500,
                'msg' => 'Hubo un error: ' . $e->getMessage() . ' en: ' . $e->getTrace() . ' linea: ' . $e->getLine()
            ];
        }
        $query = null;
        $conn = null;
        return $response;
    }

}
