<?php

use Route\Routes;

use Controller\UserController;


$router = new Routes;

//User
$router->post( 'login/', [ UserController::class, 'login' ] );
$router->get( 'user/', [ UserController::class, 'index' ] );
$router->get( 'user/find', [ UserController::class, 'find' ] );
$router->get( 'user/confirm', [ UserController::class, 'confirm' ] );
$router->post( 'user/register', [ UserController::class, 'register' ] );
$router->put( 'user/update', [ UserController::class, 'update' ] );
$router->delete( 'user/destroy', [ UserController::class, 'destroy' ] );

$router->post( 'user/forget_password', [ UserController::class, 'forgetPassword' ] );
$router->post( 'user/change_password', [ UserController::class, 'updatedPassword' ] );

//Verify routes
$router->comprobarRoutes();
