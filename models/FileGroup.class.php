<?php 
/***********************************************************************
 * Module:  FileGroup.class.php
 * Author:  
 * Purpose: Defines the Class FileGroup
 ***********************************************************************/

	Class FileGroup
	{
		/* class properties */
		private $idFileGroup;
		private $nameFileGroup;
		private $descriptionFileGroup;


		/* prepared queries declaration*/
		protected $fileGroupCreate; 
		protected $fileGroupUpdate;		
		protected $fileGroupDelete;
		protected $fileGroupByName;  	//get the fileGroup (ID) given the name

		protected $viewCreate;


		function __construct($nameFileGroup,$descriptionFileGroup)
		{
			global $app;
			$this->bd = $app['db']; // only for greater visibilty

			$this->setNameFileGroup($nameFileGroup);
			$this->setDescriptionFileGroup($descriptionFileGroup);

			/**************************** queries preparation **************************/

			$this->fileGroupCreate = $this->bd->prepare('CALL procedure_file_group_add(?,?)');  // add a new file on the server
			$this->fileGroupUpdate = $this->bd->prepare('CALL procedure_file_group_update(?,?,?)');
			$this->fileGroupDelete = $this->bd->prepare('CALL procedure_file_group_delete(?)');
			$this->fileGroupByName = $this->bd->prepare('SELECT id_file_group FROM file_group WHERE name_file_group = ?');

			$this->viewCreate = $this->bd->prepare('CALL procedure_view_creation(?,?)');
		}


		/********************************************************************************/
		/************      FUNCTIONS/ EXECUTION OF PREPARED QUERIES    ******************/
		/********************************************************************************/

		
		//creation of a new file, renvoit 1 si tout se passe bien, sinon 0
		public function doFileGroupCreate(){
			
			try{
				//peut renvoyer une erreur 23000, quand le login est dejà présent dans la table
				//quand tout se passe bien $result vaudra 1
				
				$result= $this->fileGroupCreate->execute(array($this->getNameFileGroup(),$this->getDescriptionFileGroup()));
				$result= $this->viewCreate->execute(array($this->getNameFileGroup(),$this->getNameFileGroup()));				
			}
			catch(Doctrine\DBAL\DBALException $e){
				if($this->fileGroupCreate->errorCode()== 23000){
					//filegroup already existing
					$result = 0; //on va traiter dans la fonction appelante càd index, dans ce cas il va envoyer un message a l'utilisateur									
				}
			}
			return $result;
		}

		//update a file
		public function doFileGroupUpdate(){

			$res = $this->fileGroupUpdate->execute(array($this->getIdFileGroup(), $this->getNameFileGroup(), $this->getDescriptionFileGroup()));
			return $res;
		}

		//delete a file
		public function doFileGroupDelete(){
			$res = $this->fileGroupDelete->execute(array($this->getIdFileGroup()));
			return $res;
		}


		//renvoie l'ID du groupFile, sinon renvoie 0
		public function getIdFileGroupByName(){
			
				$this->fileGroupByName->execute(array($this->getNameFileGroup()));
				$res = $this->fileGroupByName->fetch();
				if($res){
					$this->setIdFileGroup($res['id_file_group']);
					$result = $this->getIdFileGroup();
				}
				else{
					//nom de group pas present dans la table
					$result = -1;
				}
				return $result;					
		}
		
		/********************************************************************************/
		/*********************  getters and setters *************************************/
		/********************************************************************************/
		
		public function getIdFileGroup(){ return $this->idFileGroup; }
		public function getNameFileGroup(){ return $this->nameFileGroup; }
		public function getDescriptionFileGroup(){ return $this->descriptionFileGroup; }

		public function setIdFileGroup($idFileGroup) {$this->idFileGroup=$idFileGroup;}
		public function setNameFileGroup($nameFileGroup) {$this->nameFileGroup=$nameFileGroup;}
		public function setDescriptionFileGroup($descriptionFileGroup) {$this->descriptionFileGroup=$descriptionFileGroup;}

	}
?>