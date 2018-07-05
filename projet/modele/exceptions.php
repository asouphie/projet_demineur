<?php

/****************************
 *                          *
 * Les classes d'exceptions *
 *                          *
 ****************************/

/**
 * Classe generale de definition d'exception
 */

class MonException extends Exception{
  private $chaine;
  public function __construct($chaine){
    $this->chaine=$chaine;
  }

  public function afficher(){
    return $this->chaine;
  }

}

/**
 * Exception relative à un probleme de connexion
 */

class ConnexionException extends MonException{
}

/**
 * Exception relative à un probleme d'accès à une table
 */

class TableAccesException extends MonException{
}

?>