<?php 
/***********************************************************************
 * Module:  FileGroup.class.php
 * Author:  
 * Purpose: Defines the Class FileGroup
 ***********************************************************************/

	Class FileGroup
	{
		private $idFileGroup;
		private $nameFileGroup;
		private $descriptionFileGroup;


		function __construct($nameFileGroup)
		{
			global $app;
			$this->bd = $app['db']; // only for greater visibilty

			$this->setNameFileGroup($nameFileGroup);
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