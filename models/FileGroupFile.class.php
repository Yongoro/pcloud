<?php

/***********************************************************************
 * Module:  File.class.php
 * Author:  
 * Purpose: Defines the Class File
 ***********************************************************************/


	class FileGroupFile 
	{
		/* class properties */
		private $idFile;
		private $idFileGroup;
	

		/* prepared queries declaration*/

		protected $fileGroupFileCreate;		
		protected $fileGroupFileDelete;

		function __construct()
		{
			global $app;
			$this->bd = $app['db']; // only for greater visibilty
			
		


			/**************************** queries preparation **************************/

			$this->fileGroupFileCreate = $this->bd->prepare('CALL procedure_file_group_file_add(?,?)'); //le premier argument est l'identifiant du group
			$this->fileGroupFileDelete = $this->bd->prepare('CALL procedure_file_delete_group(?)');
			
		}

		

		/********************************************************************************/
		/************      FUNCTIONS/ EXECUTION OF PREPARED QUERIES    ******************/
		/********************************************************************************/

		
		public function doFileGroupFileDelete(){

			$res = $this->fileGroupFileDelete->execute(array($this->getIdFile()));
			return $res;
		}

		//delete a file
		public function doFileGroupFileCreate(){
			$res = $this->fileGroupFileCreate->execute(array($this->getIdFile(),$this->getIdFileGroup()));
			return $res;
		}



		/********************************************************************************/
		/*********************  getters and setters *************************************/
		/********************************************************************************/

		public function getIdFile(){ return $this->idFile; }
		public function getIdFileGroup(){ return $this->nameFile; }

		public function setIdFile($idFile)	{$this->idFile = $idFile;}
		public function setIdFileGroup($idFileGroup){$this->idFileGroup = $idFileGroup;}


	}

?>