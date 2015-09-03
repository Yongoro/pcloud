<?php

    // On charge le framework Silex
    //occhio il cammino è indicato relativamente all'indice
    require_once '../../vendor/autoload.php';   

    require_once '/models/User.class.php';
    require_once '/models/File.class.php';
    require_once '/models/FileGroup.class.php';
    require_once '/models/FileGroupFile.class.php';
    require_once '/models/UserGroup.class.php';
    require_once '/models/Keyword.class.php';
    require_once '/models/KeywordFile.class.php';    
    require_once __DIR__.'/UserGroupFileGroup.class.php';
    //require_once '/web/models/TimeManagement.class.php';

    /********************************************************************************************
      INITIALISATION ET CONFIGURATIONS
    ********************************************************************************************/
    
    // On crée l'application et on la configure en mode debug

    $app = new Silex\Application();
    $app['debug'] = true;

    ////--------------------- doctrine -----------------------------------------------------------
    $app->register(new Silex\Provider\DoctrineServiceProvider(),
            array('db.options' => array(
                                    'driver'   => 'pdo_mysql',
                                    'host'     => 'localhost',          // pas touche à ça : spécifique pour C9 !
                                    'user'     => 'root',               // vous pouvez mettre votre login à la place
                                    'password' => '',
                                    'charset'  => 'utf8',
                                    'dbname' => 'pcloud'                // mettez ici le nom de la base de données
    ))); //


    ////----------------------   Swift Mail Sender -------------------------------------------------
    $app->register(new Silex\Provider\SwiftmailerServiceProvider());

    $app['swiftmailer.options'] = array(
        'host' => 'smtp.gmail.com',
        'port' => 465,
        'username' => 'yongoro.cloud@gmail.com',
        'password' => 'xxxxxxxx',
        'encryption' => 'ssl',
        'auth_mode' => 'login',
        'transport'=> 'smtp',
    );

/*    if ($app['mailer.initialized']) {
        $app['swiftmailer.spooltransport']->getSpool()->flushQueue($app['swiftmailer.transport']);
    }
*/
/*    $app['mailer'] = $app->share(function ($app) {
    return new \Swift_Mailer($app['swiftmailer.transport']);
    });

*/
    ////------------------session -----------------------------------------------------
    $app->register(new Silex\Provider\SessionServiceProvider());

    ////---------------------twig------------------------------------------------------
    $app->register(new Silex\Provider\TwigServiceProvider(), 
    	       array('twig.path' => '.',));

    ////-------------------translation--------------------------------------------------
    $app->register(new Silex\Provider\TranslationServiceProvider(), array(
        'locale_fallbacks' => array('fr'),
    ));

    $app->register(new Silex\Provider\ValidatorServiceProvider());
    $app->register(new Silex\Provider\FormServiceProvider());
    $app->register(new Silex\Provider\TranslationServiceProvider());

    $app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
        $translator->addLoader('yaml', new Symfony\Component\Translation\Loader\YamlFileLoader());

        $translator->addResource('yaml', 'locales/en.yml', 'en');
        $translator->addResource('yaml', '/locales/do.yml', 'do');
        $translator->addResource('yaml', '/locales/fr.yml', 'fr');

        return $translator;
    }));

    ///------------------------- Monolog --------------------------------------------------
    $app->register(new Silex\Provider\MonologServiceProvider(), array(
        'monolog.logfile' => 'logs/development.log',
    ));

    
?>
