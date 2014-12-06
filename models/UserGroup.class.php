<?php 
/***********************************************************************
 * Module:  UserGroup.class.php
 * Author:  
 * Purpose: Defines the Class UserGroup
 ***********************************************************************/

	Class UserGroup
	{
		/* class properties */
		private $idUserGroup;
		private $nameUserGroup;
		private $descriptionUserGroup;


		/* prepared queries declaration*/
		protected $userGroupCreate; 
		protected $userGroupUpdate;		
		protected $userGroupDelete;


		function __construct($nameUserGroup,$descriptionUserGroup)
		{
			global $app;
			$this->bd = $app['db']; // only for greater visibilty

			$this->setNameUserGroup($nameUserGroup);
			$this->setDescriptionUserGroup($descriptionUserGroup);

			/**************************** queries preparation **************************/

			$this->userGroupCreate = $this->bd->prepare('CALL procedure_user_group_add(?,?)');  // add a new User on the server
			$this->userGroupUpdate = $this->bd->prepare('CALL procedure_user_group_update(?,?,?)');
			$this->userGroupDelete = $this->bd->prepare('CALL procedure_user_group_delete(?)');
		}


		/********************************************************************************/
		/************      FUNCTIONS/ EXECUTION OF PREPARED QUERIES    ******************/
		/********************************************************************************/

		//creation of a new User
		public function doUserGroupCreate(){
			
			try{
				//peut renvoyer une erreur 23000, quand le login est dejà présent dans la table
				//quand tout se passe bien $res vaudra 1
				
				$result= $this->userGroupCreate->execute(array($this->getNameUserGroup(),$this->getDescriptionUserGroup()));
			}
			catch(Doctrine\DBAL\DBALException $e){
				if($this->userGroupCreate->errorCode()== 23000){
					//login dejà utilisé
					$result = 0; //on va traiter dans la fonction appelante càd index, dans ce cas il va envoyer un message a l'utilisateur									
				}
			}
			return $result;
		}

		//update a User
		public function doUserGroupUpdate(){

			$res = $this->userGroupUpdate->execute(array($this->getIdUserGroup(), $this->getNameUserGroup(), $this->getDescriptionUserGroup()));
			return $res;
		}

		//delete a User
		public function doUserGroupDelete(){
			$res = $this->userGroupDelete->execute(array($this->getIdUserGroup()));
			return $res;
		}


		/********************************************************************************/
		/*********************  getters and setters *************************************/
		/********************************************************************************/
		public function getIdUserGroup(){ return $this->idUserGroup; }
		public function getNameUserGroup(){ return $this->nameUserGroup; }
		public function getDescriptionUserGroup(){ return $this->descriptionUserGroup; }

		public function setIdUserGroup($idUserGroup) {$this->idUserGroup=$idUserGroup;}
		public function setNameUserGroup($nameUserGroup) {$this->nameUserGroup=$nameUserGroup;}
		public function setDescriptionUserGroup($descriptionUserGroup) {$this->descriptionUserGroup=$descriptionUserGroup;}

	}
?>