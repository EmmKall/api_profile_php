<?php

namespace Model;

use Database\Conection;

class Proyect
{

    public function __construct()
    {
        
    }

    public function index(): array
    {
        $sql = ' SELECT id, name, img, slug, description, tecnologies, git, url, created_at FROM proyects ORDER BY created_at DESC ';
        $response = Conection::getAll( $sql );
        return $response;
    }

    public function find( $arrData ): array
    {
        $sql = " SELECT id, name, img, slug, description, tecnologies, git, url, created_at FROM proyects WHERE id = :id ";
        $response = Conection::find( $sql, $arrData );
        return $response;
    }

    public function store( $data ): array
    {
        $sql = ' INSERT INTO proyects ( name, img, slug, description, tecnologies, url, git ) VALUES ( :name , :img , :slug, :description, :tecnologies, :url, :git ) ';
        $response = Conection::store( $sql, $data );
        return $response;
    }

    public function update( array $arrData ): array
    {
        $sql = ' UPDATE proyects SET name = :name, img = :img, slug = :slug, description = :description, tecnologies = :tecnologies, url = :url, git = _git WHERE id = :id ';
        $response = Conection::update( $sql, $arrData );
        return $response;
    }

    public function destroy( $arrData ): array
    {
        $sql = " DELETE FROM proyects WHERE id = :id ";
        $response = Conection::find( $sql, $arrData );
        return $response;
    }

}
