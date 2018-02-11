<?php
setlocale (LC_TIME, 'fr_FR.utf8','fra'); 



define('DB_NAME', 'cdelmas');
define('DB_USER', 'cdelmas');
define('DB_PASSWORD', '2olmfS7j');
define('DB_HOST', 'localhost');

function connexionBd($hote=DB_HOST,$username=DB_USER,$mdp=DB_PASSWORD,$bd=DB_NAME) {
   try {
       $connex= new PDO('mysql:host='.$hote.';dbname='.$bd, $username, $mdp);
       $connex->exec("SET CHARACTER SET utf8");	//Gestion des accents       
       return $connex;
   } catch(Exception $e) {
       echo 'Erreur : '.$e->getMessage().'<br>';
       echo 'N° : '.$e->getCode();
       return null;
   }
}



