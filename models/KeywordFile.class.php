<?php

/***********************************************************************
 * Module:  KeywordFile.class.php
 * Author:  
 * Purpose: Defines the Class KeywordFile
 ***********************************************************************/


	class KeywordFile 
	{
		/* class properties */

		private $idKeyword;
		private $idFile;				

		/* prepared queries declaration*/

		protected $keywordFileCreate;
	
		function __construct($idFile, $idKeyword)
		{
			global $app;
			$this->bd = $app['db']; // only for greater visibilty
		
			$this->setIdKeyword($idKeyword);
			$this->setIdFile($idFile);

			/**************************** queries preparation **************************/

			$this->keywordFileCreate = $this->bd->prepare('CALL procedure_keyword_file_add(?,?)'); 			

		}


		/********************************************************************************/
		/************      FUNCTIONS/ EXECUTION OF PREPARED QUERIES    ******************/
		/********************************************************************************/
		
		public function doKeywordFileCreate(){
			
			try{
				//peut renvoyer une erreur 23000, quand le login est dejà présent dans la table
				//quand tout se passe bien $res vaudra 1
				//sinon ça vaut -1 si existe dejà
				$result= $this->keywordFileCreate->execute(array($this->getIdFile(),$this->getIdKeyword()));				
			}
			catch(Doctrine\DBAL\DBALException $e){

				if($this->keywordFileCreate->errorCode()== 23000){
					//keyword dejà utilisé
					$result = -1; //on va traiter dans la fonction appelante càd index, dans ce cas il va envoyer un message a l'utilisateur					
				}
			}
			return $result;
		}

		/********************************************************************************/
		/*********************  getters and setters *************************************/
		/********************************************************************************/

		public function getIdKeyword(){ return $this->idKeyword; }
		public function getIdFile(){ return $this->idFile; }

		public function setIdKeyword($idKeyword){$this->idKeyword = $idKeyword;}
		public function setIdFile($idFile){$this->idFile = $idFile;}

	}
?>