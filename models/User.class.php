<?php

/***********************************************************************
 * Module:  User.class.php
 * Author:  
 * Purpose: Defines the Class User
 ***********************************************************************/


	class User 
	{
		/* class properties */
		private $idUser;
		private $dateRegistration;
		private $dateSubscription;
		private $stateRegistration;
		private $nameUser;
		private $surnameUser;
		private $pseudoUser;
		private $passwordUser;
		private $emailUser;
		private $sexUser;
		private $stateUser;
		private $connected;

		/* prepared queries declaration*/

		protected $userSubscription; // to send subscritpion
		
		protected $userLogin;		 // to login the user
		protected $userLogout;		 // to logout
		protected $userExist;		 // to check wether the user exist in the DB	
		
		protected $userUpdate;		 // to modify some data of the user
		
		protected $deniedRequest1;	// sets state to REFUSE and call the trigger to insert into user_denied
		protected $deniedRequest2;	// delete from user

		protected $acceptRequest1;	// sets state to ACCEPT
		protected $acceptRequest2;	// create system user
		protected $acceptRequest3;	// give him default rights

		protected $grant_rights; 	//give him right on a given view


		function __construct()
		{
			global $app;
			$this->bd = $app['db']; // only for greater visibilty
			
			$cpt = func_num_args();
            $args = func_get_args();

            switch ($cpt) {
				case '1':	
				$this->setPseudoUser($args["0"]);
				break;

				default:
		        $this->setNameUser($args["0"]);
				$this->setSurnameUser($args["1"]);
				$this->setPseudoUser($args["2"]);
				$this->setPasswordUser($args["3"]);
				$this->setEmailUser($args["4"]);
				$this->setSexUser($args["5"]);
				break;
			}
			

			/**************************** queries preparation definition  **************************/

			$this->userSubscription = $this->bd->prepare('CALL procedure_user_add(?,?,?,?,?,?)');  //request for subscription

			//alternative call the 1 then the number 2
			$this->acceptRequest1 = $this->bd->prepare('CALL procedure_user_accept(?)');	// accept the user's request
			$this->acceptRequest2 = $this->bd->prepare('CALL procedure_user_creation(?,?)');	// accept the user's request
			$this->acceptRequest3 = $this->bd->prepare('CALL procedure_user_grant_rights(?, "default_view")');	// accept the user's request

			$this->deniedRequest1 = $this->bd->prepare('CALL procedure_user_denied(?)'); //denied the user's request
			$this->deniedRequest2 = $this->bd->prepare('CALL procedure_user_denied_delete()');    //to delete the user from the table USER and from the system table

			$this->grant_rights = $this->bd->prepare('CALL procedure_user_grant_rights(?,?)');

			//$this->userLogin = $this->bd->prepare('CALL procedure_user_login');

			/************* //ToDo  **************/
			$this->userExist = $this->bd->prepare('SELECT id_user, password_user, state_registration_user FROM user WHERE pseudo_user = ? ');     // recuperer l'ID de celui qui se connecte			
			
			$this->userLogin = $this->bd->prepare('CALL procedure_user_login(?)');			 // met le gars connecté et écrit dans table log
			$this->userLogout = $this->bd->prepare('CALL procedure_user_logout(?)');			 // met le gars deconnecté
			
		}

		public function createUserSubscription(){
			
			try{
				//peut renvoyer une erreur 23000, quand le login est dejà présent dans la table
				//quand tout se passe bien $res vaudra 1
				
				$result= $this->userSubscription->execute(array($this->getNameUser(),$this->getSurnameUser(),$this->getPseudoUser(),$this->getPasswordUser(),$this->getEmailUser(),$this->getSexUser()));
			}
			catch(Doctrine\DBAL\DBALException $e){
				if($this->userSubscription->errorCode()== 23000){
					//login dejà utilisé
					$result = 0; //on va traiter dans la fonction appelante càd index, dans ce cas il va envoyer un message a l'utilisateur									
				}
			}
			return $result;
		}

		public function doUserLogin($pass){

			$ok = 1; //1-> existe bien et bons inputs, 2-> mot de passe erroné, 3-> inexistant ce gars
			$this->userExist->execute(array($this->getPseudoUser()));
			$res = $this->userExist->fetch();			
			
			//si $res a une valeur du login existe bien en base
			if($res['id_user']){
				$this->setIdUser($res['id_user']);
				$this->setStateRegistration($res['state_registration_user']);
				//controllons les mots de pass et si l'utilisateur a été accepté ie inscrit à l'état actuel
				//print_r($pass."--".$res['password_user']);
				if( strcmp($pass, $res['password_user'])==0 && strcasecmp($this->getStateRegistration(), "accepté")==0){
					//ce gars existe
					$this->userLogin->execute(array($this->getPseudoUser()));					
				}
				else{
					//mot de passe different, mais  login existant
					$ok =2;
				}				
			}
			else{
				//utilisateur inexistant dans la base
				$ok = 3;
			}
			
			return $ok;
		}

		/* rejete la demande d'inscription */ /* doit renvoyer 1 si tout c'est bien passé */
		/* version 2: il faut renvoyer l'email pour que le serveur puise envoyer un mail au destinataire, sinon revoie 0 pour ERROR*/
		public function doDeniedRequest(){
			$res=0;

			$sql = $this->bd->prepare('SELECT password_user,email_user  FROM user WHERE pseudo_user = ?');
			$sql->execute(array($this->getPseudoUser()));
			$result = $sql->fetch();
			  
			$this->setPasswordUser($result['password_user']);
			$this->setEmailUser($result['email_user']);

			$res1 = $this->deniedRequest1->execute(array($this->getPseudoUser()));
			if($res1){
				$res1 = $this->deniedRequest2->execute();
				if($res1){
					$res= $this->getEmailUser();
				}
			}
			return $res;			
		}

		/* version 1:  accepte la demande d'inscription */ /* doit renvoyer 1 si tout c'est bien passé */
		/* version 2: il faut renvoyer l'email pour que le serveur puise envoyer un mail au destinataire, sinon revoie 0 pour ERROR*/
		public function doAcceptRequest(){
			$res=0; //pour controle de resultat, 
			//attention le mot de passe est vide, prevoir ou alors le recupérer ici
			/* recupère password , email*/
			$sql = $this->bd->prepare('SELECT password_user,email_user  FROM user WHERE pseudo_user = ?');
			$sql->execute(array($this->getPseudoUser()));
			$result = $sql->fetch();
			  
			$this->setPasswordUser($result['password_user']);
			$this->setEmailUser($result['email_user']);

			$res1 = $this->acceptRequest1->execute(array($this->getPseudoUser()));
			if($res1){
				$res1 = $this->acceptRequest2->execute(array($this->getPseudoUser(), $this->getPasswordUser()));
				if($res1){
					$res1 = $this->acceptRequest3->execute(array($this->getPseudoUser()));
					if($res1){
						//alors tout est bon, renvoie mail
						$res = $this->getEmailUser();
					}
				}
			}			
			return $res;
		}

		public function doUserLogout(){
			//$this->userLogout->execute(array($this->getPseudoUser(),$this->getPasswordUser()));
			//$res = $this->userLogout->fetch();
			//$this->idUser = $this->setIdUser($res['id_user']);

			$res1 = $this->userLogout->execute(array($this->getPseudoUser()));
			return $res1; //si tout se passe bien on renvoit 1 			
		}

		public function doGrandRightToUser($view){

			try{				

				$result= $this->grant_rights->execute(array($this->pseudoUser,$view));
			}
			catch(Doctrine\DBAL\DBALException $e){		
					
				return 0; //on va traiter dans la fonction appelante càd index, dans ce cas il va envoyer un message a l'utilisateur	
			}
			return $result;
		}

		/********************************************************************************/
		/*********************  getters and setters *************************************/
		/********************************************************************************/
		public function getIdUser(){ return $this->idUser; }

		public function getStateRegistration(){ return $this->stateRegistration; }

		public function getConnectedUser(){ return $this->connected; }

		public function getPseudoUser(){ return $this->pseudoUser; }

		public function getPasswordUser(){ return $this->passwordUser; }

		public function getEmailUser(){ return $this->emailUser; }

		public function getSexUser(){ return $this->sexUser; }

		public function getDateSubscription(){ return $this->dateSubscription; }

		public function getNameUser(){ return $this->nameUser; }

		public function getSurnameUser(){ return $this->surnameUser; }

		public function getView(){ return $this->view; }



		public function setIdUser($idUser) {$this->idUser=$idUser;}

		public function setPseudoUser($pseudoUser) {$this->pseudoUser=$pseudoUser;}

		public function setStateRegistration($stateRegistration) {$this->stateRegistration=$stateRegistration;}

		public function setPasswordUser($passwordUser) {$this->passwordUser=$passwordUser;}

		public function setEmailUser($emailUser) {$this->emailUser=$emailUser;}

		public function setSexUser($sexUser) {$this->sexUser=$sexUser;}

		public function setDateDateSubscription($dateSubscription) {$this->dateSubscription=$dateSubscription;}

		public function setNameUser($nameUser) {$this->nameUser=$nameUser;}

		public function setSurnameUser($surnameUser) {$this->surnameUser=$surnameUser;}

		public function setConnectedUser($connected) {$this->connected=$connected;}

		public function setView($view) {$this->view=$view;}

	}

?>