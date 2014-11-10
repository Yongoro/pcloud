<?php

    require_once('./pcloud/web/models/Config.php');
    global $app; //pour utiliser la variable créée dans config.php

    /*------------------------------------------------------------------------------------------------------------------------------------*/
    //                                DECLARATION DES CLASSES RECURRENTES DE LA BIBLIOTHEQUE SILEX                                        //
    //
    //------------------------------------------------------------------------------------------------------------------------------------//
    use Silex\Application;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Silex\Provider\TwigServiceProvider;
    use Silex\Provider\SecurityServiceProvider;
    use Silex\Provider\DoctrineServiceProvider;
    use Silex\Provider\SessionServiceProvider;
    use Symfony\Component\Translation\Loader\YamlFileLoader; 
    use Silex\Provider\TranslationServiceProvider;

    /*--------------------------------------------------------------------------------------------------------------------------------------*/
    //                                                                                                                                      //
    //                                               DEBUT DES ROUTES                                                                       //
    //                                                                                                                                      //
    /*--------------------------------------------------------------------------------------------------------------------------------------*/

    // On définit une route pour l'url /
    $app->get('/', function(Application $app) {
       
        return $app['twig']->render('/pcloud/web/views/accueil.twig',array('confirmText' => "", 'confirmTextLogin'=>""));
        //return $app->redirect('/accueil');
        //return $app['twig']->render('/pcloud/web/views/admin/admin.php', array('nom' => $n));
    });



    // pour la demnde d'inscription
    $app->match('/accueil', function(Application $app, Request $req){

    $msg="";
    
    // recuperation données du formulaire
    
    $pseudo=$req->request->get('pseudo');
    $name=$req->request->get('name'); 
    $surname=$req->request->get('surname');     
    $pass1= sha1($req->request->get('pass1'));
    $pass2= sha1($req->request->get('pass2'));
    $email1=$req->request->get('mail1');
    $email2=$req->request->get('mail2'); 
    //$gender=$req->request->get('gender');  


    if( isset($pseudo) && isset($name) && isset($surname) && isset($pass1) && isset($email1) ){
    
        //on teste si les données sont cohérentes
        if(strcmp($pass1, $pass2)==0  && strcmp($email1, $email2)==0){

          // on controle si le pseudo est dejà utilisé
          

          // ICI ON VA INSERER LES DONNEES DANS LA BD,  appel de la procedure
          
          $q=$app['db']->prepare('CALL procedure_user_request(?,?,?,?,?,?,?)');
          $q->execute(array($pseudo,$pass1,$email1,1,'datetime',$name,$surname));

          
           //inscription faite          
          $msg="subscription received";          
        }
        else{

          //donnees incoherentes          
          $msg="mots de passe ou mails ne correspondent pas";
        }
      
    }  
  
    return $app['twig']->render('/pcloud/web/views/accueil.twig', array('confirmText' => $msg, 'confirmTextLogin'=>""));
});


// route pour le login des utilisateurs
$app->match('/login', function(Application $app, Request $req){

$msg = "welcome user";
  return $app['twig']->render('/pcloud/web/views/user/user.twig', array('confirmText' => $msg));
});

// On lance l'application
$app->run();

?>
