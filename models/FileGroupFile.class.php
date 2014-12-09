<?php

/***********************************************************************
 * Module:  FileGroupFile.class.php
 * Author:  
 * Purpose: Defines the Class FileGroupFile
 ***********************************************************************/


	class FileGroupFile 
	{
		/* class properties */

		private $idFileGroup;
		private $idFile;				

		/* prepared queries declaration*/
		
		protected $fileGroupFileCreate;
	
		function __construct($idFile, $idFileGroup)
		{
			global $app;
			$this->bd = $app['db']; // only for greater visibilty
		
			$this->setIdFileGroup($idFileGroup);
			$this->setIdFile($idFile);

			/**************************** queries preparation **************************/

			$this->fileGroupFileCreate = $this->bd->prepare('CALL procedure_file_group_file_add(?,?)'); 
		}


		/********************************************************************************/
		/************      FUNCTIONS/ EXECUTION OF PREPARED QUERIES    ******************/
		/********************************************************************************/
		
		//renvoie 1 si tout s'est bien passé, 0 sinon
		public function doFileGroupFileCreate(){
			
			try{
				//peut renvoyer une erreur 23000, quand le login est dejà présent dans la table
				//quand tout se passe bien $res vaudra 1
				//sinon ça vaut -1 si existe dejà
				$result= $this->fileGroupFileCreate->execute(array($this->getIdFileGroup(),$this->getIdFile()));				
			}
			catch(Doctrine\DBAL\DBALException $e){

				if($this->fileGroupFileCreate->errorCode()== 23000){
					//keyword dejà utilisé
					$result = -1; //on va traiter dans la fonction appelante càd index, dans ce cas il va envoyer un message a l'utilisateur					
				}
			}
			return $result;
		}

		/********************************************************************************/
		/*********************  getters and setters *************************************/
		/********************************************************************************/

		public function getIdFileGroup(){ return $this->idFileGroup; }
		public function getIdFile(){ return $this->idFile; }

		public function setIdFileGroup($idFileGroup){$this->idFileGroup = $idFileGroup;}
		public function setIdFile($idFile){$this->idFile = $idFile;}

	}
?>