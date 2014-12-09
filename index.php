<?php

    require_once('./models/Config.php');
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
       
        return $app['twig']->render('views/login/login.html',array('confirmText' => "", 'confirmTextLogin'=>""));
        //return $app->redirect('/accueil');
        //return $app['twig']->render('views/admin/admin.php', array('nom' => $n));
    });


    /*****************************************************************************/
    /**********  SIGNUP ROUTE ****************************************************/
    /*****************************************************************************/
    
    $app->match('/signup', function(Application $app, Request $req){

    $msg="";
    
    // recuperation données du formulaire
    
    $pseudo=  $app->escape($req->request->get('pseudo'));
    $name=    $app->escape($req->request->get('name')); 
    $surname= $app->escape($req->request->get('surname'));     
    $pass1=   sha1($app->escape($req->request->get('pass1')));
    $pass2=   sha1($app->escape($req->request->get('pass2')));
    $email1=  $app->escape($req->request->get('mail1'));
    $email2=  $app->escape($req->request->get('mail2')); 
    $gender=  $app->escape($req->request->get('gender'));  


    if( isset($pseudo) && isset($name) && isset($surname) && isset($pass1) && isset($email1) && isset($gender)){
    
        //on teste si les données sont cohérentes
        if(strcmp($pass1, $pass2)==0  && strcmp($email1, $email2)==0){ 
          
            $user = new User($name,$surname,$pseudo,$pass1,$email1,$gender);
          /*  $user = new User();
            $user->setNameUser($name);
            $user->setSurnameUser($surname);
            $user->setPseudoUser($pseudo);
            $user->setPasswordUser($pass1);
            $user->setEmailUser($email1);
            $user->setSexUser($gender);
          */
          
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

    return $app['twig']->render('views/accueil.twig', array('confirmText' => $msg, 'confirmTextLogin'=>""));
});

/************************************************************************************************************/
/**********  SIGNUP ROUTE FOR USER REGISTERED BY ADMIN   ****************************************************/
/************************************************************************************************************/    
    $app->match('admin/signup', function(Application $app, Request $req){

    $msg="";
    
    // recuperation données du formulaire
    
    $pseudo=$req->request->get('pseudo');
    $name=$req->request->get('name'); 
    $surname=$req->request->get('surname');     
    $pass1= sha1($req->request->get('pass1'));
    $pass2= sha1($req->request->get('pass2'));
    $email1=$req->request->get('mail1');
    $email2=$req->request->get('mail2'); 
    $gender=$req->request->get('gender');  
   

    if( isset($pseudo) && isset($name) && isset($surname) && isset($pass1) && isset($email1) ){
    
       //on teste si les données sont cohérentes
        if(strcmp($pass1, $pass2)==0  && strcmp($email1, $email2)==0){ 
          
            $user = new User($name,$surname,$pseudo,$pass1,$email1,$gender);  // constructeur avec SEX=1 à chnager (DEFAULT VALUE FOR SEXE)
          
            $res= $user->createUserSubscription(); 
            

            //print_r($res);

            if($res == 1){
              //inscription faite 
              $msg= $name." : your subscription request has been received, you will be contacted later";     
              $user->doAcceptRequest();              

              $app['session']->set('user_created_and_granted',$pseudo); 
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
      return $pass1;
   // return $app['twig']->render('views/accueil.twig', array('confirmText' => $msg, 'confirmTextLogin'=>""));
});

//ajouter une vue c est à dire un groupe de fichiers
$app->post('admin/addView', function(Application $app,Request $req) {

       
        $newFileGroup = new FileGroup( $req->request->get('group_fichier'), $req->request->get('descr_group'));

         if($newFileGroup->doFileGroupCreate())
                return "groupe de fichier crée avec succès";
        return "echec de la création de la vue";       
});

//ajouter un groupe d'utilisateurs
$app->post('admin/addGroup', function(Application $app,Request $req) {

       $newUserGroup = new UserGroup( $req->request->get('group_user'),$req->request->get('description'));
      if($newUserGroup->doUserGroupCreate())
        return "groupe créé avec succès";
      return "echec lors de la création du groupe";

      
});

//octroyer un droit de lecture sur une vue à un groupe d'utilisateur
$app->post('admin/grantViewToGroup', function(Application $app,Request $req) {     

      $sqlIdFileGroup = "SELECT id_file_group from file_group where name_file_group ='".$req->request->get('group_file')."'";
      $idGroupFile =  $app['db']->fetchAll($sqlIdFileGroup);  
      $sqlIdUserGroup = "SELECT id_user_group from user_group where name_user_group = '".$req->request->get('group_user')."' ";
      $idGroupUser =  $app['db']->fetchAll($sqlIdUserGroup);

      $newUserGroupFileGroup = new UserGroupFileGroup($idGroupUser[0]['id_user_group'],$idGroupFile[0]['id_file_group']);
      
      if($newUserGroupFileGroup->doUserGroupFileGroupCreate())
       return "succès de l'octroi de droit";
    return "echec de l'octroi: tentative de duplication de droits";
      
});

/* route vers l'onglet gestion de fichiers*/
$app->get('admin/file', function(Application $app) {
   
    //$app->register(new \Kilte\Silex\Pagination\PaginationServiceProvider);
    $sqlFiles = "SELECT id_file,name_file,path_file,size_file from file";       
    $files =  $app['db']->fetchAll($sqlFiles);
    
    echo $app['twig']->render('views/admin/fichier.html',array('files' =>$files)); 
    return ""; 
});

/* route pour la recherche de fichiers par ordre alphabetique*/
$app->get('admin/fileToSearch', function(Application $app,Request $req) {
   
    //$app->register(new \Kilte\Silex\Pagination\PaginationServiceProvider);
    $sqlFiles = "SELECT id_file,name_file,path_file,size_file from file where name_file LIKE '". $req->query->get("lettre")."%'";
      
    $files =  $app['db']->fetchAll($sqlFiles);    
    echo $app['twig']->render('views/admin/fichier.html',array('files' =>$files)); 
    return "";
});

/* route pour supprimer un fichier*/
$app->get('admin/fileDelete', function(Application $app,Request $req) {
     $fileToDelete = new file();
     $fileToDelete->setIdFile($req->query->get('id_file'));
     $fileToDelete->doFileDelete();
    return "fichier supprimé"; 
});

/* route pour voir les infos d'un fichier*/
$app->get('admin/fileShow', function(Application $app,Request $req) {

     $sqlFile = "SELECT id_file,name_file,path_file,date_creat_file,date_last_modify_file,date_last_access_file,size_file,type_mime_file 
     FROM file where id_file = ".$req->query->get('id_file');
     $file =  $app['db']->fetchAll($sqlFile);

     $fileJson =  $app->json(array("id"=>$file[0]["id_file"],"name"=>$file[0]["name_file"],"path"=>$file[0]["path_file"],
    "date_create"=>$file[0]["date_creat_file"],"date_modify"=>$file[0]["date_last_modify_file"],
      "date_access"=>$file[0]["date_last_access_file"],"size"=>$file[0]["size_file"]
      ,"type"=>$file[0]["type_mime_file"]));
    return $fileJson; 

});

/* route vers l'ongle gestion groupe de fichiers*/
$app->get('admin/groupFile', function(Application $app,Request $req) {

    $sqlIdFileGroup = "SELECT id_file_group,name_file_group from file_group";
    $views =  $app['db']->fetchAll($sqlIdFileGroup); 
    
    echo $app['twig']->render('views/admin/views.html',array( 'views'=>$views));  
    return "";     
});

/* route  quand on clique sur un groupe de fichiers dans la partie gestion des groupes de fichiers */
$app->get('admin/groupFileClicked', function(Application $app,Request $req) {
    $sqlFiles = "SELECT f.id_file,f.name_file,fg.name_file_group as name_group  FROM file f,file_group fg,file_group_file fgf where fgf.id_file=f.id_file 
    AND fg.id_file_group=fgf.id_file_group  AND fg.id_file_group=".$req->query->get("id_file_group");
    $files =  $app['db']->fetchAll($sqlFiles); 
    echo $app['twig']->render('views/admin/view.html',array("files"=>$files));   
    return "";
});

/* route vers la partie gestion des groupes d'utilisateurs*/
$app->get('admin/groupUser', function(Application $app,Request $req) {

      $sqlIdUserGroup = "SELECT id_user_group,name_user_group from user_group";
      $groups =  $app['db']->fetchAll($sqlIdUserGroup); 
    
      echo $app['twig']->render('views/admin/groupes.html',array( 'groups'=>$groups)); 
      return "";      
});

/* route vers la partie gestion d'utilisateurs*/
$app->get('admin/user', function(Application $app,Request $req) {

    //$sqlFiles = "SELECT id_file,name_file,path_file,size_file from file";      
    //$files =  $app['db']->fetchAll($sqlFiles);
      $sqlUser = "SELECT id_user,pseudo_user,name_user,surname_user from user";
      $users =  $app['db']->fetchAll($sqlUser); 

      echo $app['twig']->render('views/admin/user.html',array("users"=>$users)); 
      return "";      
});

/* route vers la partie gestion de fichiers*/

$app->get('admin/accueil', function(Application $app,Request $req) {
  $sqlGroups = "SELECT name_user_group FROM user_group";
  $groups =  $app['db']->fetchAll($sqlGroups); 

  $sqlViews = "SELECT name_file_group FROM file_group";
  $views =  $app['db']->fetchAll($sqlViews);


  $groupAndView = $app->json(array("groups"=>$groups,"views"=>$views));


    echo $app['twig']->render('views/admin/accueil.html',array('groups' =>$groups ,"views"=>$views));   
    return "";
});

/* route vers la partie gestion de fichiers*/
$app->get('admin/stats', function(Application $app,Request $req) {       

       return "stats";     
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
        return $app->redirect('/pcloud/index.php/user/sendMail/'.$pseudo.'/'.$mail.'/'.$state);
        //return $app['twig']->render('/pcloud/views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));
    }
    else{
        $msg="Error User ".$pseudo." is unreacheable"; 
        return $app['twig']->render('views/admin/file.php', array('demande' =>'','msg'=>$msg));
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
        return $app->redirect('/pcloud/index.php/user/sendMail/'.$pseudo.'/'.$mail.'/'.$state);
        //return $app['twig']->render('/pcloud/views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));
    }
    else{
        $msg="Something got wrong when trying to cancel User ".$pseudo; 
        return $app['twig']->render('views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));
    }
});

/*********************************************************************************************************/
/****************************************  LOGIN ROUTE  **************************************************/
/*********************************************************************************************************/

$app->match('/login', function(Application $app, Request $req){

  $msg = "";
  $pseudo=$app->escape($req->request->get('pseudo'));
  $pass1= sha1($app->escape($req->request->get('pass1')));

  if(isset($pseudo) && isset($pass1)){

      $user = new User($name='',$surname='',$pseudo,$pass1,$email1='',$gender='');
      $res = $user->doUserLogin($pass1);

      //$res ici est boolean si c'est true, tout s'est bien passé
      if($res==1){
          //utilisateur existant dans la base, mettre a jour la session
          $app['session']->set('pseudo',$pseudo);
          $app['session']->set('pass',$pass1);

          //echo $app['session']->get('pseudo');

          $msg = "Hope you will be satisfied!";

          $app['monolog']->addInfo(sprintf("User '%s' logged in.", $pseudo));

          return $app['twig']->render('/views/user/user.twig', array('welcome' => $msg,'nom'=>$pseudo));
      }
      else if ($res==2){
          //user pas encore accepté
          $msg=" ". $pseudo.':subscription not yet accepted! you will receive a mail soon... ';
          return $app['twig']->render('/views/login/login.html', array('confirmText' => "", 'confirmTextLogin'=>$msg));
      }
      else if($res==3){
          //mot de passe erroné
          $msg=" ". $pseudo.': wrong password, try again or reset it! ';
          return $app['twig']->render('/views/login/login.html', array('confirmText' => "", 'confirmTextLogin'=>$msg));
      }
      else{
        //inexistant dsna la base
        $msg=" ". 'please correct your login or subscribe';
        return $app['twig']->render('/views/login/login.html', array('confirmText' => "", 'confirmTextLogin'=>$msg));
      }//end if($res)
  }
  else{

      $msg =" ". "you must enter all the fields";
      return $app['twig']->render('/views/login/login.html', array('confirmText' => "", 'confirmTextLogin'=>$msg));
  }//end if (isset)
   
   //par defaut si tout se passe mal :(( on se sait jamais 
  return $app['twig']->render('/views/login/login.html', array('confirmText' => "", 'confirmTextLogin'=>$msg));
});


$app->match('/logout',function(Application $app, Request $req){


      if($app['session']->get('pseudo') != null ){

        $user = new User($name='',$surname='',$pseudo,$pass1='',$email1='',$gender=1);
        $res = $user->doUserLogout();

        //on vide la sesssion
        $app['session']->set('pseudo',null);
        $app['session']->set('pass',null);
      }      
       
      //on peut eventuellement envoyer message d'aurevoir grace à $res
  /*    if($res){
        //il a été deconnecté, et dire aurevoir
      }
      else{
        //quelquechose s'est mal passé lors de la deconnexion ou le user est un MALFRAT/ILLEGAL
      }
  */
      return $app->redirect('/pcloud/');
});

//*****************************************************************************************************//
//************************  ROUTE TO CALL FORM IN ORDER TO RESET PASSWORD *****************************//
//*****************************************************************************************************//
$app->match('/user/resetPassword/{pseudo}',function(Application $app, $pseudo){
       if(isset($pseudo)){

            return $app['twig']->render('views/login/reset_password.html', array('pseudo'=>$pseudo,'confirmReset'=>''));
       }
       else{
            return $app->redirect('/');
       }
      
});

//*****************************************************************************************************//
//************************  ROUTE TO IMPLEMENT THE PASSWORD'S RESET AND REDIRECT USER TO HOMEPAGE******//
//*****************************************************************************************************//
$app->match('/user/resetPassword', function(Application $app, Request $req){
    $pseudo= $app->escape($req->request->get('pseudo'));
    $pass1= sha1($app->escape($req->request->get('pass1')));
    $pass2= sha1($app->escape($req->request->get('pass2')));
    $msg='';

    if(!isset($pseudo) || !isset($pass1) || !isset($pass2)){
        $msg="You have to set all the fields!";
        return $app['twig']->render('views/login/reset_password.html', array('pseudo'=>$pseudo,'confirmReset'=>$msg));
    }
    else{
        //tutti i campi sono stati inseriti, controliamo se le password coincidono
         if(strcmp($pass1, $pass2)==0){
              $user = new User();
              $user->setPasswordUser($pass1);
              $user->setPseudoUser($pseudo);

              $sql = $app['db']->prepare('UPDATE user SET password_user=? WHERE pseudo_user = ?');
              $result = $sql->execute(array($pass1,$pseudo));              
              if($result){
                //tutto ok
                $msg="your password has been reset correctly";
                //choisir que faire, ici on le redirige juste vers la page d'acceuil
                return $app->redirect('/pcloud/');
              }
              else{
                //something got wrong
                $msg="Something got wrong, please try later!";
                return $app['twig']->render('views/login/reset_password.html', array('pseudo'=>$pseudo,'confirmReset'=>$msg));
              }
              
         }
         else{
              //password diversi
              $msg="password are differents, please control and try again!";
              return $app['twig']->render('views/login/reset_password.html', array('pseudo'=>$pseudo,'confirmReset'=>$msg));
         }
    }
});

//*****************************************************************************************************//
//************************  ROUTE TO UPLOAD FILE ON WEBSITE BY ADMIN      *****************************//
//*****************************************************************************************************//
$app->match('/admin/file/upload',function(Application $app, Request $req){      

      function bytesToSize1024($bytes, $precision = 2) {

        $unit = array('B','KB','MB');
        return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision).' '.$unit[$i];
      } 

      /* Make sure that Upload Directory is properly configured and writable */
      $path = 'upload/files';
      
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

            $sFileDescription = $req->request->get('file_description');
            $sFileTokens = $req->request->get('file_tokens');
            $sFileGroups = $req->request->get('file_groups');//attention c'est un array

            echo <<<EOF
                <p>Your file: {$sFileName} has been successfully received.</p>
                <p>Type: {$sFileType}</p>
                <p>Size: {$sFileSize}</p>
                <p>Path: {$sFilePath}</p>
                <p>Description: {$sFileDescription}</p>                
                <p>Tokens: {$sFileTokens}</p>
EOF;
            /* insertion dans la BD des donnees et du fichier dans son repertoire*/ 
            $files->move($sFilePath,$sFileName);

            /* creation de l'objet File */
            $file = new File($sFileName, $sFileSize, $sFilePath, $sFileType);
            $idFile = $file->doFileCreate();   //$idFile contient l'ID du fichier

            //makes controls to know whether the file was successfully created
            if( ($idFile != -1) && ($idFile != -2) ){
                /*traitement des tokens et insertion dans les tables keyword et keyword_file*/ 
                
                //recupération des différents keyword données à ce fichier
                $keywords = explode(" ",$sFileTokens);

                //insertion ds la table keyword
                for($i = 0; $i < sizeof($keywords); $i++){
                    $keyword = new Keyword($keywords[$i] , $description = 'test'.$i);
                    $res1 = $keyword->doKeywordCreate();  // $res1 renvoit 1 si un NEW keyword a été créé, -1 le keyword existat deja                    
                    
                    //insertion dans la table keyword_file (id_file, id_keyword)
                    $keywordFile = new keywordFile($idFile,$keywords[$i]);
                    $res2 = $keywordFile->doKeywordFileCreate();                      
                }             

                //Pour chaque fileGroup, créer l'association
                //recupérer chaque nom de groupe, créer un objet FileGroup, recupérer idFileGroup par le nom, et enfin créer l'association FileGroupFile
                for($j = 0; $j < sizeof($sFileGroups); $j++){
                    $fileGroup = new FileGroup($sFileGroups[$j], $description='');
                    $idFileGroup = $fileGroup->getIdFileGroupByName();
                    $fileGroupFile = new FileGroupFile($idFile,$idFileGroup);
                    $res3 = $fileGroupFile->doFileGroupFileCreate();
                }  

              return "The file has been uploaded and data inserted on the database";
            }//end if $idFile
            else{
                  return "something got wrong!";
            }            

      }
      catch(FileException $e){
          $message = "the upload is disabled";
      }
      catch(FileNotFoundException $e){
          $message = "the file doesn't exist";
      }    

      
    //$app['twig']->render('/pcloud/views/admin/admin.php', array('message' => $message,'msg'=>'','nom'=>''));
    
});


//**********************************************************************************************************//
//*****   error manager   **********************************************************************************//
//**********************************************************************************************************//
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

//***********************************************************************************************************//
//********************* ADMINISTRATOR  ROUTE  ***************************************************************//
//***********************************************************************************************************//
$app->match('/admin',function(Application $app){
    //$pseudo = $app['session']->get('pseudo');
  $sqlRecentUsers = "SELECT id_user,date_subscription_user,name_user,surname_user FROM user where state_registration_user='en cours'";
  $demandes = $app['db']->fetchAll($sqlRecentUsers);

  $sqlRecentFiles = "SELECT id_file,date_creat_file FROM file";
  $files = $app['db']->fetchAll($sqlRecentFiles);

  $sqlConnectedUsers = "SELECT pseudo_user FROM user where connected='1'";
  $connectes = $app['db']->fetchAll($sqlConnectedUsers);

  $sqlRecentsOper = "SELECT name_operation FROM operation";
  $operations = $app['db']->fetchAll($sqlRecentsOper);

  $sqlGroups = "SELECT name_user_group FROM user_group";
  $groups =  $app['db']->fetchAll($sqlGroups); 

  $sqlViews = "SELECT name_file_group FROM file_group";
  $views =  $app['db']->fetchAll($sqlViews); 

  $sqlNbreUser =  "SELECT count(*) as nbre from user";
  $nombreUser =  $app['db']->fetchAll($sqlNbreUser); 

  $sqlNbreConnected =  "SELECT count(*) as nbre from user where connected='1'";
  $nombreConnected =  $app['db']->fetchAll($sqlNbreConnected); 

  $sqlNbreFiles =  "SELECT count(*) as nbre from file";
  $nombreFiles =  $app['db']->fetchAll($sqlNbreFiles); 

  $sqlNbreGroups =  "SELECT count(*) as nbre from user_group";
  $nombreGroups =  $app['db']->fetchAll($sqlNbreGroups); 

  $sqlNbreViews =  "SELECT count(*) as nbre  FROM file_group";
  $nombreViews =  $app['db']->fetchAll($sqlNbreViews); 

    return  $app['twig']->render("views/admin/admin.html",array('demandes' => $demandes,'files'=> $files,'connectes'=>$connectes,
     'operations'=>$operations,'views'=>$views,'groups'=>$groups,'nombreUser'=>$nombreUser,'nombreConnected'=>$nombreConnected,
     'nombreFiles'=>$nombreFiles,'nombreGroups'=>$nombreGroups,'nombreViews'=>$nombreViews,'msg'=>''));
});

//*******************************************************************************************//
//*************** ROUTE TO SEND AN EMAIL TO RESET PASSWORD     ******************************//
//*******************************************************************************************//
$app->match('/user/remindPassword',function(Application $app, Request $req){

    $mail=$req->request->get('mail');
    if(!isset($mail)){
            $msg=" something got wrong, controls email!";
            return $app['twig']->render('views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));
    }
    else{

            $user= new User();
            $user->setEmailUser($mail);

            // version 1: recupérons son pseudo et pour lui communiquer de rechoisir un autre
            $sql = $app['db']->prepare('SELECT pseudo_user  FROM user WHERE email_user = ?');
            $sql->execute(array($mail));
            $result = $sql->fetch();

            if($result['pseudo_user']){
                  $pseudo = $result['pseudo_user'];
                  $subject = "Resetting PCloud's Password!";
                  try{ 

                    $text = '<html>' .
                            ' <head> <title>Y. Cloud </title></head>' .
                            ' <body>' .
                            ' <h2> Pcloud | Y. Cloud</h2>'.
                            ' <p> Dear  ' .$pseudo.'<br> You have forgotten your password and have asked to get a new one!.<br>'. 
                            '     You will be redirected on the website to create a new one!</p>'.
                            ' <p> Click <a href="http://localhost/pcloud/index.php/user/resetPassword/'.$pseudo.'".> here </a> to reset your password </p>'.
                            ' </body>' .
                            '</html>' ;        
                    $text1 = '';                   
                            
                    $numSent = $app['mailer']->send(\Swift_Message::newInstance()
                                    ->setSubject($subject)
                                    ->setFrom(array('yongoro.pcloud@gmail.com' => 'Y. PCloud'))
                                    ->setTo(array($mail =>'A '.$pseudo))
                                    ->setBody($text,'text/html')
                                    ->addPart($text1,'text/plain')
                                    ->setReadReceiptTo('yongoro.pcloud@gmail.com'));        
                    $msg=" A Message has been sent to ".$mail." !";
                    //return $msg;
                    return $app['twig']->render('views/login/login.html', array('confirmText'=>'', 'confirmTextLogin'=>$msg));               
                    }
                    catch(\Swift_TransportException $e){
                        $msg=" connection to the remote failed due to transport look at settings"; 
                        //return $app['twig']->render('views/admin/admin.html', array(msg'=>$msg)); 
                        return $msg;     
                    }   
            }
            else{
              //something gets wrong when retrieving the pseudo on DB
            }               
              
    }
    
});

//*****************************************************************************************************/
//****************   MESSAGE  TO A RECIPIENT BY THE ADMIN WHEN HE WANTS BY A FORM          ************/
//*****************************************************************************************************/
$app->match('/user/sendMail',function(Application $app, Request $req){

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
  return $app['twig']->render('views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));
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
    return $app['twig']->render('views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));
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
            return $msg;
            //return $app['twig']->render('views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));

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
          //return $app['twig']->render('views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg));
          return $msg;
          }          
      }
      catch(\Swift_TransportException $e){
          $msg=" connection to the remote failed due to transport look at settings"; 
          //return $app['twig']->render('views/admin/admin.php', array('nom' => "ADMIN",'msg'=>$msg)); 
          return $msg;     
      }   
  }
  
});


//****************************************************************************************************************************//
//***************************  TO CALL THE FORM TO A SPECIFIC USER  **********************************************************//
//****************************************************************************************************************************//
$app->match('/user/sendMail/{mail}',function(Application $app, $mail){

  return $app['twig']->render('/pcloud/views/userMail.twig', array('email'=>$mail, 'msg'=>''));
});


//****************************************************************************************************************************//
//***************************  LES  API  *************************************************************************************//
//****************************************************************************************************************************//


//return all the file groups, will be used by admin to give a file a certain group during its creation
$app->match('/api/fileGroups', function(Application $app){

    $sql= " SELECT name_file_group, description_file_group FROM file_group ORDER BY name_file_group";
    $rows= $app['db']->fetchAll($sql);

     //print_r($rows);

    //$tab = array('bd'=> $res);
    return $app->json($rows);
});


/*********************************************************************************************************************************************************/
/********** the admin must to read the user request before accepting or denying.So the database return the  user information   ***************************/
/*********************************************************************************************************************************************************/
$app->get('admin/readSubscription', function(Application $app,Request $req) {

  $sqlSubscription =  "SELECT * FROM user where id_user = ".$req->query->get('id_user');
 $user = $app['db']->fetchAssoc($sqlSubscription);       
 $userSubscription = $app->json($user);
 return $userSubscription;

       return "";     
});


// On lance l'application
$app->run();

?>
