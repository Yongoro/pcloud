<?php

    require_once('/web/models/Config.php');
    global $app; //pour utiliser la variable créée dans config.php

    /*------------------------------------------------------------------------------------------------------------------------------------*/
    //                                DECLARATION DES CLASSES RECURRENTES DE LA BIBLIOTHEQUE SILEX                                        //
    //
    //------------------------------------------------------------------------------------------------------------------------------------//
    use Silex\Application;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    use Silex\Provider\TwigServiceProvider;
    use Silex\Provider\SecurityServiceProvider;
    use Silex\Provider\DoctrineServiceProvider;
    use Silex\Provider\SessionServiceProvider;
    use Symfony\Component\Translation\Loader\YamlFileLoader; 
    use Silex\Provider\TranslationServiceProvider;

    use Silex\Provider\FormServiceProvider;    
    use Silex\Provider\UrlGeneratorServiceProvider;
    use Silex\Provider\ValidatorServiceProvider;
    use Silex\Provider\ServiceControllerServiceProvider;    
    use Silex\Provider\SwiftmailerServiceProvider;
    



    /*--------------------------------------------------------------------------------------------------------------------------------------*/
    //                                                                                                                                      //
    //                                               DEBUT DES ROUTES                                                                       //
    //                                                                                                                                      //
    /*--------------------------------------------------------------------------------------------------------------------------------------*/

    // On définit une route pour l'url /
    $app->get('/', function(Application $app) {
       
        return $app['twig']->render('/web/views/accueil.twig',array('confirmText' => "", 'confirmTextLogin'=>""));
        //return $app->redirect('/accueil');
        //return $app['twig']->render('/pcloud/web/views/admin/admin.php', array('nom' => $n));
    });


    /*****************************************************************************/
    /**********  SIGNUP ROUTE ****************************************************/
    /*****************************************************************************/
    
    $app->match('/signup', function(Application $app, Request $req){

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
          
            $user = new User($name,$surname,$pseudo,$pass1,$email1,1);  // constructeur avec SEX=1 à chnager (DEFAULT VALUE FOR SEXE)
          
            $res= $user->createUserSubscription(); 
            
            //print_r($res);

            if($res == 1){
              //inscription faite 
              $msg= $name." : your subscription request has been received, you will be contacted later";        
            }
            else {
              //erreur survenue
              $msg= $name." : this pseudo is already used...";
            }                      
        }
        else{

          //donnees incoherentes          
          $msg="passwords or emails do not match :(( ";
        }       
    }

    return $app['twig']->render('/web/views/accueil.twig', array('confirmText' => $msg, 'confirmTextLogin'=>""));
});


/*********************************************************************************************************/
/********** REQUEST  ACCEPTATION    AND INSERTION IN SYSTEM DATABASE TABLE     ***************************/
/*********************************************************************************************************/

$app->match('/admin/accept/{pseudo}',function(Application $app, $pseudo){
    $msg="";
    $state="ok";
    // PREVOIR CONTROLE D'UNE CERTAINE MANIERE POUR VOIR SI C'EST BIEN APPELE PAR L'ADMIN
    $user = new User($name='',$surname='',$pseudo, $pass='',$email1='',$gender=''); //nous avons seulement le pseudo
    $mail = $user->doAcceptRequest();

    if($mail){
        $msg="User ".$pseudo." was created successfully";  //inutile ici
        //$result= $app->json(array("res"=>$res,"msg"=>$msg));

        //sending an email to the specified user
        return $app->redirect('/index.php/user/sendMail/'.$pseudo.'/'.$mail.'/'.$state);
        //return $app['twig']->render('/web/views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));
    }
    else{
        $msg="Error User ".$pseudo." is unreacheable"; 
        return $app['twig']->render('/web/views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));
        //return $app->redirect('/index.php/admin');
    }
});

/*********************************************************************************************************/
/**************************************** REQUEST  DENIED     ********************************************/
/*********************************************************************************************************/

$app->match('/admin/denied/{pseudo}',function(Application $app, $pseudo){
    $msg="";
    $state="ko";
    // PREVOIR CONTROLE D'UNE CERTAINE MANIERE POUR VOIR SI C'EST BIEN APPELE PAR L'ADMIN
    $user = new User($name='',$surname='',$pseudo, $pass='',$email1='',$gender=''); //nous avons seulement le pseudo
    $mail = $user->doDeniedRequest();

    if($mail){
        $msg="User ".$pseudo." was cancel correctly!";  //inutile ici
        //$result= $app->json(array("res"=>$res,"msg"=>$msg));

        //sending an email to the specified user
        return $app->redirect('/index.php/user/senMail/'.$pseudo.'/'.$mail.'/'.$state);
        //return $app['twig']->render('/web/views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));
    }
    else{
        $msg="Something got wrong when trying to cancel User ".$pseudo; 
        return $app['twig']->render('/web/views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));
    }
});

/*********************************************************************************************************/
/***********  LOGIN ROUTE  *******************************************************************************/

$app->match('/login', function(Application $app, Request $req){

  $msg = "";
  $pseudo=$req->request->get('pseudo');
  $pass1= sha1($req->request->get('pass1'));

  if(isset($pseudo) && isset($pass1)){

      $user = new User($name='',$surname='',$pseudo,$pass1,$email1='',$gender=1);
      $res = $user->doUserLogin($pass1);

      //$res ici est boolean si c'est true, tout s'est bien passé
      if($res==1){
        //utilisateur existant dans la base, mettre a jour la session
        $app['session']->set('pseudo',$pseudo);
        $app['session']->set('pass',$pass1);

        //echo $app['session']->get('pseudo');

        $msg = "Hope you will be satisfied!";
        return $app['twig']->render('/web/views/user/user.twig', array('welcome' => $msg,'nom'=>$pseudo));
      }
      else if($res==2){
        //mot de passe erroné
        $msg= $pseudo.': wrong password, try again or reset it! ';
        return $app['twig']->render('/web/views/accueil.twig', array('confirmText' => "", 'confirmTextLogin'=>$msg));
      }
      else{
        //inexistant dsna la base
        $msg= 'please correct your login or subscribe';
        return $app['twig']->render('/web/views/accueil.twig', array('confirmText' => "", 'confirmTextLogin'=>$msg));
      }//end if($res)
  }
  else{

      $msg = "you must enter all the fields";
      return $app['twig']->render('/web/views/accueil.twig', array('confirmText' => "", 'confirmTextLogin'=>$msg));
  }//end if (isset)
   
   //par defaut si tout se passe mal :(( on se sait jamais 
  return $app['twig']->render('/web/views/accueil.twig', array('confirmText' => "", 'confirmTextLogin'=>$msg));
});


$app->match('/logout',function(Application $app, Request $req)
    {
        //impostare il login degli utenti a null e controllarlo nel /play
        $app['session']->set('pseudo',null);
        $app['session']->set('pass',null);
        return $app->redirect('/');
    });


//pour test upload file
$app->match('/admin/file/upload', function (Request $request) use ($app){
      $form = $app['form.factory']
        ->createBuilder('form')
        ->add('FileUpload', 'file')        
        ->getForm();

      $request = $app['request'];
      $message = 'Upload a file';
      if ($request->isMethod('POST')) {
          $form->bind($request);
         if ($form->isValid()) {
              $files = $request->files->get($form->getName());
              /* Make sure that Upload Directory is properly configured and writable */
              $path = 'web/upload/';
              $filename = $files['FileUpload']->getClientOriginalName();
              //$filename = $files['FileUpload']->getOriginalName();
              $files['FileUpload']->move($path,$filename);
              $message = 'File was successfully uploaded!';
          }
      }
      $response = $app['twig']->render('/web/views/upload.twig.html', array('message' => $message,'form' => $form->createView()));
      return $response;
      return $app['twig']->render('/web/views/admin/admin.php', array('message' => $message,'msg'=>'','nom'=>''));
}, 'GET|POST');

$app->match('/admin/file/upload/test',function(Application $app, Request $req){

      function bytesToSize1024($bytes, $precision = 2) {
        $unit = array('B','KB','MB');
        return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision).' '.$unit[$i];
      } 

      /* Make sure that Upload Directory is properly configured and writable */
      $path = 'web/upload/';
      
      /***   version pure PHP ***/

      //$sFileName = $_FILES['image_file']['name'];
      //$sFileType = $_FILES['image_file']['type'];
      //$sFileSize = bytesToSize1024($_FILES['image_file']['size'], 1);  

      /**  version SILEX ****/
      try {
            $files = $req->files->get('image_file');
            $sFileName = $files->getClientOriginalName();
            $sFileType = $files->getMimeType();
            $sFileSize = bytesToSize1024($files->getClientSize(),1);
            $sFilePath = $path;

            echo <<<EOF
                <p>Your file: {$sFileName} has been successfully received.</p>
                <p>Type: {$sFileType}</p>
                <p>Size: {$sFileSize}</p>
                <p>Path: {$sFilePath}</p>
EOF;
            /* insertion dans la BD des donnees et du fichier dans son repertoire*/ 
            $files->move($sFilePath,$sFileName);
            return "ok";

      }
      catch(FileException $e){
          $message = "the upload is disabled";
      }
      catch(FileNotFoundException $e){
          $message = "the file doesn't exist";
      }    

      
    //$app['twig']->render('/web/views/admin/admin.php', array('message' => $message,'msg'=>'','nom'=>''));
    
});

$app->match('/admin/file/form',function(Application $app){
  return $app['twig']->render('/web/views/files/fileUploadForm.html');
});

$app->error(function (\Exception $e, $code) use ($app) {
      $response = null;
      if (! $app['debug']){
          switch ($code) {
                  case 404:
                  $message = 'The requested page could not be found.';
                  break;
                  default:
                  $message = 'We are sorry, but something went terribly wrong.';
          }
          $response = new Response($message, $code);
      }
return $response;
});


$app->match('/admin',function(Application $app){
    //$pseudo = $app['session']->get('pseudo');

    return $app['twig']->render('/web/views/admin/admin.php', array('message' =>'','nom'=>'','msg'=>''));
});

//*****************************************************************************************************/
//****************   MESSAGE  TO A RECIPIENT BY THE ADMIN WHEN HE WANTS BY A FORM          ************/
//*****************************************************************************************************/
$app->match('user/sendMail',function(Application $app, Request $req){

  $msg="";
  $name = $req->request->get('pseudo');
  $email = $req->request->get('mail');
  $text = $req->request->get('message');
  $subject = "Message from the PCloud Administrator";

  if(!isset($email) || !isset($text)){
    $msg=" set the mail adress or write a text to sent !";
  }
  else{

    try{       
        
          $numSent = $app['mailer']->send(\Swift_Message::newInstance()
                            ->setSubject($subject)
                            ->setFrom(array('yongoro.pcloud@gmail.com' => 'Y. PCloud'))
                            ->setTo(array($email =>'A '.$name))
                            ->setBody($text,'text/html'));
        
        $msg="Message been sent successfully to ".$name." !";
    }
    catch(\Swift_TransportException $e){
        $msg=" connection to the remote failed due to transport look at settings";       
    }
    
  }
  return $app['twig']->render('/web/views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));
});

//**************************************************************************************************************/
//******     AUTOMATED WELCOME MESSAGE  TO A RECIPIENT ACCEPTED(state=OK) , DENIED (state=KO)  *****************/
//**************************************************************************************************************/
$app->match('/user/sendMail/{pseudo}/{mail}/{state}',function(Application $app, $pseudo, $mail, $state){

  $msg="";
  $name = $pseudo;
  $email = $mail;
  //$stato= $state;
  
  $subject = "Message from the PCloud Administrator";

  if(!isset($mail) || !isset($pseudo) || !isset($state)){
    $msg=" something got wrong, controls email, pseudo or state !";
    return $app['twig']->render('/web/views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));
  }
  else{
        
      try{ 
          if(strcasecmp($state,"ok") == 0){
            //user accepted
            $text = '<p>Dear '.$pseudo.',<br> Your subscription has been accepted.<br> Welcome and enjoy being with us :)) </p>';
            $numSent = $app['mailer']->send(\Swift_Message::newInstance()
                            ->setSubject($subject)
                            ->setFrom(array('yongoro.pcloud@gmail.com' => 'Y. PCloud'))
                            ->setTo(array($email =>'A '.$name))
                            ->setBody($text,'text/html'));        
            $msg="Message has been sent successfully to ".$name." !";
            return $app['twig']->render('/web/views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));

          }
          else if(strcasecmp($state, "ko")==0){
            //used denied
            $text = '<p>Dear '.$pseudo.',<br> Despite the interest of your subscription we cannot accpet your application :((</p>';
            $numSent = $app['mailer']->send(\Swift_Message::newInstance()
                            ->setSubject($subject)
                            ->setFrom(array('yongoro.pcloud@gmail.com' => 'Y. PCloud'))
                            ->setTo(array($email =>'A '.$name))
                            ->setBody($text,'text/html'));        
          $msg="Message has been sent successfully to ".$name." !";
          return $app['twig']->render('/web/views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));
          }          
      }
      catch(\Swift_TransportException $e){
          $msg=" connection to the remote failed due to transport look at settings"; 
          return $app['twig']->render('/web/views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));      
      }   
  }
  
});


//****************************************************************************************************************************//
//***************************  TO CALL THE FORM TO A SPECIFIC USER  ************************************************************//
//****************************************************************************************************************************//
$app->match('/user/sendMail/{mail}',function(Application $app, $mail){

  return $app['twig']->render('/web/views/userMail.twig', array('email'=>$mail, 'msg'=>''));
});

// On lance l'application
$app->run();

?>
