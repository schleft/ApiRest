<?php

// Index principale, on commmence par tout initialiser


// Loader de composer, c'est quand meme plus simple
// NOTE : Rajouter dans composer les dossiers supplementaire pour les charger aussi
// ( exemple dans celui la : controller,model,db ... )
require 'vendor/autoload.php';


// On va utiliser l'encapsuleur eloquent et initialiser la connection
// Class dabs db/connection.php
use db\connection;
connection::createConn();


// Initialisation de slimm, ici avec twig pour la vue 
// Pour toutes les autres option, voir la doc ! :-)
$app = new \Slim\Slim(array(
	'mode' => 'development',
	'view' => new Slim\Views\Twig()
));


// On va faire le routage, qui peut se faire dans des fichier separer mais la c'est du details 

// Index, route basic
// On utilise "use ($app)" pour.. utiliser $app, ce qui permet d'appeler les arguments, twig, changer le header, etc.
$app->get('/', function() use ($app) {

	// On separe toujours tout, on appelle donc le controller ici !
	// On lui passe $app pour qu'il puisse utiliser twig ici
	controller\Accueil::helloWorld($app);

});

// On recupere si l'url est en post( envoie du formulaire ici)
$app->post('/', function () use ($app){
	// On recupere la variable
	$name = $app->request->post('name');
	echo $name;
	// Si c'est pas vide, on redirige sur la bonne url
	if ( $name != '' ) {
		$app->redirect('/test/'.$name);
		
	//Sinon rien, mais flemme de rajouter un truc
	}else{
		controller\Accueil::helloWorld($app);
	}
});

// On va faire un  groupe, juste pour avoir de tout
// Ca regroupe toutes les url sous /test du coup
$app->group('/test', function() use ($app) {

	// On n'arrivera pas ici avec "mathis", puisque que ca passera au dessus !
	$app->get('/:nom', function ($nom) use ($app){
		controller\Test::helloUser($app,$nom);	
	});

});

// On lance slim, et voila !
$app->run();