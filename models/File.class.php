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

		protected $fileCreate; 
		protected $fileUpdate;		
		protected $fileDelete;
		protected $fileByName;  //get tthe file (ID) given the name

		function __construct()
		{
			global $app;
			$this->bd = $app['db']; // only for greater visibilty
			
			$cpt = func_num_args();
            $args = func_get_args();
		
			$this->setNameFile($args[0]);
			$this->setSizeFile($args[1]);
			$this->setPathFile($args[2]);
			$this->setTypeFile($args[3]);

		/*	$this->setNameFile($nameFile);
			$this->setSizeFile($sizeFile);
			$this->setPathFile($pathFile);
			$this->setTypeFile($typeFile);
		*/


			/**************************** queries preparation **************************/

			$this->fileCreate = $this->bd->prepare('CALL procedure_file_add(?,NOW(),NOW(),?,NOW(),?,?)');  // add a new file on the server
			$this->fileUpdate = $this->bd->prepare('CALL procedure_file_update(?,?,?,?,?,?)');
			$this->fileDelete = $this->bd->prepare('CALL procedure_file_delete(?)');
			$this->fileByName = $this->bd->prepare('SELECT id_file FROM file WHERE name_file = ?');
			
		}

		

		/********************************************************************************/
		/************      FUNCTIONS/ EXECUTION OF PREPARED QUERIES    ******************/
		/********************************************************************************/

		//creation of a new file, return the file'id for other needs in the index file
		/* return -1 (a file with same name already existing), -2 (another pb during creation of file) otherwise returns the file's ID*/
		public function doFileCreate(){
			$answer = -2;
			
			try{
				//peut renvoyer une erreur 23000, quand le login est dejà présent dans la table
				//quand tout se passe bien $res vaudra 1
				
				$result= $this->fileCreate->execute(array($this->getPathFile(),$this->getNameFile(),$this->getSizeFile(),$this->getTypeFile() ));
				
				//recupérons l'id du fichier ainsi créé
				if($result){
					$this->fileByName->execute(array($this->getNameFile()));
					$res = $this->fileByName->fetch();
					$this->setIdFile($res['id_file']);

					$answer = $this->getIdFile();

					//alternative utiliser $app['db']->lastInsertId()
				}
				else{
					$answer = -2; // "get some problem when creating the file";
				}
				
			}
			catch(Doctrine\DBAL\DBALException $e){
				if($this->fileCreate->errorCode()== 23000){
					//login dejà utilisé
					$answer = -1; //on va traiter dans la fonction appelante càd index, dans ce cas il va envoyer un message a l'utilisateur									
				}
			}
			return $answer;
		}

		//update a file
		//TODO
		public function doFileUpdate(){

			$res = $this->fileUpdate->execute(array($this->getIdFile(), $this->getPathFile(), $this->getNameFile(),$this->getSizeFile(),$this->getTypeFile()));
			return $res;
		}

		//delete a file
		public function doFileDelete(){
			$res = $this->fileDelete->execute(array($this->getIdFile()));
			return $res;
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