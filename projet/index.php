<?php
session_start();

require_once "controleur/routeur.php";

/************************************************
 *                                              *
 * Fichier index.php qui permet de mettre en    *
 * route le jeu démineur.			*
 *                                              *
 ************************************************/
 
/*
* On créer un routeur
*/
$routeur=new Routeur();

/*
* On lance la méthode router() du fichier routeur.php (require_once).
*/
$routeur->router();

?>