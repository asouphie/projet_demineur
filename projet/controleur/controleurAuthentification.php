<?php

require_once __DIR__."/../vues/vue.php";
require_once __DIR__."/../modele/modele.php";


class ControleurAuthentification{

	private $vue;
	private $model;

	function __construct(){
		$this->vue=new Vue();
		$this->model=new Modele();
	}

	/**
	* Méthode qui renvoie sur la page d'authentification.
	*/

	function authentifier(){
		$this->vue->vueAuthentification();
	}

	/**
	* Méthode qui renvoie sur la page d'inscription.
	*/	

	function inscription(){
		$this->vue->vueInscription();
	}

	/**
	* Méthode qui regarde si le compte existe dans la base de données (méthode du model) et qui renvoie sur la vue du jeu 
	* si le compte existe et sinon renvoie sur la vue erreur.
	*/

	function validationCompte($login, $mdp) {
		//Si le login et le mp n'est pas vide, alors on peut vérifier que si le compte existe.
		if(!empty($login) && !empty($mdp)){
			if ($this->model->compteExists($login,$mdp)) {
				  $_SESSION['pseudo']=$login;
				  $this->vue->vueInitJeu($login);
			//S'il n'existe pas, alors on renvoie sur une vue d'erreur.
			} else { 
				$this->vue->vueErreur("champsIncorrecte"); 
			}
		//Si un des champs est vide, alors on renvoie sur une vue d'erreur.
		} else {
			$this->vue->vueErreur("champsVideAuth");
		}
	}

	/**
	 * Méthode permettant d'inscrire un membre dans la base de données.
	 */

	function inscrireMembre($login, $mp){
		//Si les deux champs sont remplis, alors on peut créer le joueur.
		if(!empty($login) && !empty($mp)){
			$this->model->ajoutJoueur($login, $mp);
			self::authentifier();	
		//Sinon on renvoie sur une page d'erreur.
		} else {
			$this->vue->vueErreur("champsVideInscription");
		}
	}

	/**
	 * Méthode permettant de se déconnecter.
	 * Elle détruit toute les sessions existantes.
	 */

	function deconnexion(){
		unset($_SESSION['pseudo']);
		unset($_SESSION['jeu']);
		unset($_SESSION['terrain']);
		self::authentifier();
	}
}
