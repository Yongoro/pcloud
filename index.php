<?php
// web/index.php

require_once __DIR__.'/../vendor/autoload.php';

/********************************************************************************************
	INITIALISATION ET CONFIGURATIONS
********************************************************************************************/

// On définit des noms utiles
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\DoctrineServiceProvider;

$app = new Silex\Application();

//to ease debugging
$app['debug'] = true;

//enregistrement au langage de templating TWIG et précision de l'emplacement des templates
$app->register(new Silex\Provider\TwigServiceProvider(), 
               array('twig.path' => './pcloud/views',));

//souscription du service des sessions
$app->register(new Silex\Provider\SessionServiceProvider());

//configuration acces à la BD
$app->register(new Silex\Provider\DoctrineServiceProvider(),
  array('db.options' => array(
        'driver'   => 'pdo_mysql',
        'host'     => 'localhost',  
        'user'     => 'root',  
        'password' => '',
        'charset'  => 'utf8',
        'dbname'   => 'pcloud' 
)));

/***************************************************************************************
	DEFINITIONS DES ROUTES
***************************************************************************************/


$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});


//exemple utilisation de twig et binding de la route avec un certain nom
$app->get('/', function(Application $app) {
    return $app['twig']->render('admin.php', array(
        'nom' => 'NDIAYE'
    ));
})->bind('homepage');


//dernière instruction du programme
$app->run();

?>