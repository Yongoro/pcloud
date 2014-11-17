<?php

//recupère le jour à partir d'une date au format MySQL
   public function getDay($date)
   {      
      return substr($date,8,2);
   }

   //recupère le mois à partir d'une date au format MySQL
   public function getMonth($date)
   {
      return substr($date,5,2);
   }

   //recupère l'année à partir d'une date au format MySQL
   public function getYear($date)
   {
      return substr($date,0,4);
   }

   //recupère rien que l'heure à partir d'une date au format MySQL
   public function getHours($date)
   {
      return substr($date,11,2);
   }

   //recupère rien que les minutes à partir d'une date au format MySQL
   public function getMinutes($date)
   {
      return substr($date,14,2);
   }

   //recupère rien que les secondes à partir d'une date au format MySQL
   public function getSeconds($date)
   {
      return substr($date,17,2);
   }

   //recupère le temps càd HEURE:MINUTES:SECONDES à partir d'une date au format MySQL
   public function getTime($date)
   {
      return substr($date,11,8);
   }

   /** convertit une date d'un format quelconque au frmat date-mois-année */
   public function convertDate($date)
   {
      return date("d-m-Y",$date);
   }
?>