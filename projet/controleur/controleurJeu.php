<?php

require_once __DIR__."/../vues/vue.php";
require_once __DIR__."/../modele/matrice.php";


class ControleurJeu{

	private $vue;
	private $matrice;
	private $model;

	function __construct(){
		$this->matrice=new Matrice();
		$this->vue=new Vue();
		$this->model=new Modele();
	}

	/**
	 * Methode permettant de débuter le jeu, ce qui signifie initialiser les deux plateaux, 
	 * et donc par conséquent les deux sessions : $_SESSION['terrain'] et $_SESSION['jeu'].
	 */

	function debuterJeu(){
		$this->matrice->initialisation();
		$jeu=$this->matrice->getPlateauJeu();
		$terrain=$this->matrice->getPlateauBombe();
		$_SESSION['terrain'] = $terrain;
		$_SESSION['jeu'] = $jeu;
		$this->vue->vueJeu($jeu);	
	}

	/**
	 * Methode utilisé lorsque l'on clique sur une case.
	 */

	function cliqueCase($x, $y) {
		$jeu=$_SESSION['jeu'];
		$terrain=$_SESSION['terrain'];
		$pseudo=$_SESSION['pseudo'];

		$jeu = $this->matrice->decouvrirCase($jeu, $x, $y);
		//Quand on a découvert la case, on vérifie si le joueur a, ou non, gagné.
		if(!$this->matrice->gagner($jeu)){
			//Si ce n'est pas le cas, on vérifie qu'il n'as pas cliqué sur une case miné.
			if (!$this->matrice->estMine($terrain,$x,$y)) {
				$_SESSION['jeu']=$jeu;
				$this->vue->vueJeu($jeu);
			//Si la case est miné, on détruit les sessions jeu et terrain, on rentre la partie perdue dans
			//la base de données, puis on retourne la vue perdue.
			} else {
				unset($_SESSION['jeu']);
				unset($_SESSION['terrain']);

				$this->model->partieGagnante($pseudo, 0);
				$statistique=$this->model->statistiques($pseudo);
				$podium=$this->model->podium();

				$this->vue->vueResultat($pseudo, $statistique, $podium, 0);
			} 
		//Si le joueur a gagné la partie, alors on réalise la même chose que pour la partie perdu plus haut
		//sauf qu'on rentre une partie gagné et ensuite on retourne la vue gagnée.
		} else {
			unset($_SESSION['jeu']);
			unset($_SESSION['terrain']);

			$this->model->partieGagnante($pseudo, 1);
			$statistique=$this->model->statistiques($pseudo);
			$podium=$this->model->podium();

			$this->vue->vueResultat($pseudo, $statistique, $podium, 1);
		}
	}
}