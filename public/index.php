<?php

use Route\Routes;

use Controller\UserController;


$router = new Routes;

/* $router->get( '', [ UserController::class, 'index' ] );
$router->post( 'login', [ UserController:: class, 'login' ] );
$router->post( 'register', [ UserController:: class, 'register' ] );
$router->post( 'updated_password', [ UserController:: class, 'updatePassword' ] );
$router->post( 'forgot_password', [ UserController:: class, 'forgotPassword' ] ); */
//User
$router->get( 'user/', [ UserController::class, 'index' ] );
$router->get( 'user/find', [ UserController::class, 'find' ] );
$router->post( 'user/register', [ UserController::class, 'register' ] );
$router->put( 'user/update', [ UserController::class, 'update' ] );
$router->delete( 'user/destroy', [ UserController::class, 'destroy' ] );

$router->post( 'user/login', [ UserController::class, 'login' ] );
$router->post( 'user/updated', [ UserController::class, 'updated' ] );
$router->post( 'user/change_password', [ UserController::class, 'updatedPassword' ] );
$router->post( 'user/forget_password', [ UserController::class, 'forgetPassword' ] );

//Verify routes
$router->comprobarRoutes();
