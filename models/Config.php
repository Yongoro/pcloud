<?php

    // On charge le framework Silex
    require_once '/pcloud/vendor/autoload.php';


    /********************************************************************************************
      INITIALISATION ET CONFIGURATIONS
    ********************************************************************************************/
    
    // On crée l'application et on la configure en mode debug

    $app = new Silex\Application();
    $app['debug'] = true;

    ////--------------------- doctrine --------------------------------------------
    $app->register(new Silex\Provider\DoctrineServiceProvider(),
            array('db.options' => array(
                                    'driver'   => 'pdo_mysql',
                                    'host'     => 'localhost',         // pas touche à ça : spécifique pour C9 !
                                    'user'     => 'root',    // vous pouvez mettre votre login à la place
                                    'password' => '',
                                    'charset'  => 'utf8',
                                    'dbname' => 'pcloud'              // mettez ici le nom de la base de données
            ))); //

    ////----------------twig-----------------------------------------------------------
    $app->register(new Silex\Provider\TwigServiceProvider(), 
    	       array('twig.path' => '.',));

    ////-------------------translation--------------------------------------------------
    $app->register(new Silex\Provider\TranslationServiceProvider(), array(
        'locale_fallbacks' => array('fr'),
    ));


    $app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
       // $translator->addLoader('yaml', new YamlFileLoader());

        $translator->addResource('yaml', __DIR__.'/locales/en.yml', 'en');
        $translator->addResource('yaml', __DIR__.'/locales/do.yml', 'do');
        $translator->addResource('yaml', __DIR__.'/locales/fr.yml', 'fr');

        return $translator;
    }));
?>