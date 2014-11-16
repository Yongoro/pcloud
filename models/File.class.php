<?php

/***********************************************************************
 * Module:  File.class.php
 * Author:  
 * Purpose: Defines the Class File
 ***********************************************************************/


	class File 
	{
		/* class properties */
		private $idFile;
		private $typeFile;
		private $pathFile;
		private $dateCreationFile;
		private $dateLastModifyFile;
		private $dateLastAccessFile;
		private $sizeFile;
		private $nameFile;		

		/* prepared queries declaration*/

		protected $fileCreation; 
		
		


		function __construct($nameFile, $sizeFile, $pathFile, $typeFile)
		{
			global $app;
			$this->bd = $app['db']; // only for greater visibilty
		
			$this->setNameFile($nameFile);
			$this->setSizeFile($sizeFile);
			$this->setPathFile($pathFile);
			$this->setTypeFile($typeFile);


			/**************************** queries preparation **************************/

			$this->fileCreation = $this->bd->prepare('CALL procedure_file_add(?,?,NOW(),?,?)');  // add a new file on the server

			
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

		/********************************************************************************/
		/************      FUNCTIONS/ EXECUTION OF PREPARED QUERIES    ******************/
		/********************************************************************************/

		//creation a new file
		public function doFileCreation(){
			$this->fileCreation->execute(array());
		}


		/********************************************************************************/
		/*********************  getters and setters *************************************/
		/********************************************************************************/
		public function getIdFile(){ return $this->idFile; }
		public function getNameFile(){ return $this->nameFile; }
		public function getTypeFile(){ return $this->typeFile; }
		public function getPathFile(){ return $this->pathFile; }
		public function getSizeFile(){ return $this->sizeFile; }
		public function getDateCreationFile(){ return $this->dateCreationFile; }
		public function getDateLastModifyFile(){ return $this->dateLastModifyFile; }
		public function getDateLastAccessFile(){ return $this->dateLastAccessFile; }

		public function setIdFile($idFile)	{$this->idFile = $idFile;}
		public function setNameFile($nameFile)	{$this->nameFile = $nameFile;}
		public function setTypeFile($typeFile)	{$this->typeFile = $typeFile;}
		public function setPathFile($pathFile)	{$this->pathFile = $pathFile;}
		public function setSizeFile($sizeFile)	{$this->sizeFile = $sizeFile;}
		public function setDateCreationFile($dateCreationFile)	{$this->dateCreationFile = $dateCreationFile;}
		public function setDateLastModifyFile($dateLastModifyFile)	{$this->dateLastModifyFile = $dateLastModifyFile;}
		public function setDateLastAccessFile($dateLastAccessFile)	{$this->dateLastAccessFile = $dateLastAccessFile;}

	}

?>