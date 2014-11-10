<?php

/***********************************************************************
 * Module:  User.class.php
 * Author:  
 * Purpose: Defines the Class User
 ***********************************************************************/


	class User 
	{
		/* class properties */
		private $idUser;
		private $dateRegistration;
		private $dateSubscription;
		private $nameUser;
		private $surnameUser;
		private $pseudoUser;
		private $passwordUser;
		private $emailUser;
		private $sexUser;
		private $stateUser;

		/* prepared queries */


		function __construct($nameUser,$surnameUser,$pseudoUser,$passwordUser,$emailUser,$sexUser,$stateUser='en cours',$dateSubscription,$dateRegistration)
		{
			global $app;
			
		}
	}

?>