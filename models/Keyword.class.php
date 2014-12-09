<?php

/***********************************************************************
 * Module:  Keyword.class.php
 * Author:  
 * Purpose: Defines the Class Keyword
 ***********************************************************************/


	class Keyword 
	{
		/* class properties */

		private $idKeyword;
		private $descriptionKeyword;				

		/* prepared queries declaration*/

		protected $keywordCreate;
		protected $keywordUpdate;
		protected $keywordDelete; 
		

		function __construct($keyword, $description)
		{
			global $app;
			$this->bd = $app['db']; // only for greater visibilty
		
			$this->setIdKeyword($keyword);
			$this->setDescriptionKeyword($description);

			/**************************** queries preparation **************************/

			$this->keywordCreate = $this->bd->prepare('CALL procedure_keyword_add(?,?)');  // add a new file on the server
			$this->keywordUpdate = $this->bd->prepare('CALL procedure_keyword_update(?,?)');
			$this->keywordDelete = $this->bd->prepare('CALL procedure_keyword_delete(?)');

		}


		/********************************************************************************/
		/************      FUNCTIONS/ EXECUTION OF PREPARED QUERIES    ******************/
		/********************************************************************************/

		public function doKeywordCreate(){
			
			try{
				//peut renvoyer une erreur 23000, quand le login est dejà présent dans la table
				//quand tout se passe bien $res vaudra 1
				
				$result= $this->keywordCreate->execute(array($this->getIdKeyword(),$this->getDescriptionKeyword()));				
			}
			catch(Doctrine\DBAL\DBALException $e){

				if($this->keywordCreate->errorCode()== 23000){
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
		public function getDescriptionKeyword(){ return $this->descriptionKeyword; }

		public function setIdKeyword($id){$this->idKeyword = $id;}
		public function setDescriptionKeyword($description){$this->descriptionKeyword = $description;}

	}
?>