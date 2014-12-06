<?php 
/***********************************************************************
 * Module:  UserGroupFileGroupclass.php
 * Author:  
 * Purpose: Defines the Class UserGroupFileGroup
 ***********************************************************************/

	Class UserGroupFileGroup
	{
		/* class properties */
		private $idUserGroup;
	    private $idFileGroup;
	    private $result;


		/* prepared queries declaration*/
		protected $userGroupFileGroupCreate; 	
		protected $userGroupFileGroupDelete;


		function __construct($idUserGroup,$idFileGroup)
		{
			global $app;
			$this->bd = $app['db']; // only for greater visibilty

			$this->setIdUserGroup($idUserGroup);
			$this->setIdFileGroup($idFileGroup);

			/**************************** queries preparation **************************/

			$this->userGroupFileGroupCreate = $this->bd->prepare('CALL procedure_user_group_file_group_add(?,?)');  // add a new User on the server
			$this->userGroupFileGroupDelete = $this->bd->prepare('CALL procedure_user_group_file_group_delete(?,?)');
		}


		/********************************************************************************/
		/************      FUNCTIONS/ EXECUTION OF PREPARED QUERIES    ******************/
		/********************************************************************************/

		//accorder un droit de lecture a un groupe sur une vue
		public function doUserGroupFileGroupCreate(){
			
			try{
				
				$this->result= $this->userGroupFileGroupCreate->execute(array($this->getIdUserGroup(),$this->getIdFileGroup()));
			}
			catch(Doctrine\DBAL\DBALException $e){
				
					return 0;	
				}
			return $result;
		}




		//retirer le droit de lecture sur la vue a ce groupe
		public function doUserGroupFileGroupDelete(){
			$this->result = $this->userGroupFileGroupDelete->execute(array($this->getIdUserGroup(),$this->getIdFileGroup()));
			return $result;
		}


		/********************************************************************************/
		/*********************  getters and setters *************************************/
		/********************************************************************************/
		public function getIdUserGroup(){ return $this->idUserGroup; }
		public function getIdFileGroup(){ return $this->idFileGroup; }
	
		public function setIdUserGroup($idUserGroup) {$this->idUserGroup=$idUserGroup;}
		public function setIdFileGroup($idFileGroup) {$this->idFileGroup=$idFileGroup;}
		


	}
?>