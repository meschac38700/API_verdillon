<?php
require "vendor/autoload.php";

include("includes/constants.php"); 



$app = new \Silex\Application();
$app['debug'] = true;
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$connect= new \Config\Connection( DB_NAME, DB_HOST, DB_USER, DB_PWD );
$bdd = $connect->getPDO();



// Affichage d'un/des utilisateurs
$app->get( '/users/{id}', function( $id ) use( $app, $bdd )
{
   
    $user = new \App\Users(  $app->escape( $id ) );
    return $app->json( $user->getUsers( $bdd ) );
    
})->value('id',null);

//Creation d'un utilisateur
$app->post('/users/',function( \Symfony\Component\HttpFoundation\Request $req )use( $app, $bdd ){
    $user = new \App\Users();
    return $app->json( $user->postUser( $bdd, $req->request->all() ) );

});

// modification d'un utilisateur
$app->put('/users/{id}', function( $id, \Symfony\Component\HttpFoundation\Request $req ) use($app,$bdd){

    $user = new \App\Users( $app->escape( $id ) );
    return $app->json($user->saveUser( $bdd, $req->request->all() ) );
});

//Suppression d'un utilisateur
$app->delete('/users/{id}', function( $id )use($app, $bdd ){
   
    $user = new \App\Users($app->escape( $id ) );
    return $app->json( $user->deleteUser( $bdd ) );
    
});

$app->run(); 
   