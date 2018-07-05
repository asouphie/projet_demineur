<?php

/*********************
 *                   *
 * La Classe Matrice *
 *                   *
 *********************/

class Matrice{
  private $plateauJeu;
  private $plateauBombe;

	/**
     * Initialise la matrice
     */

	public function initialisation(){
	  	//dans un premier temps, on cherche à initialiser le plateau du Jeu, qui pour le moment
	  	//ne contient que des cases non décochés = 0;
	  	for($i = 1; $i<4; $i++){
	  		for($j = 1; $j <4; $j++){
	  			$this->plateauJeu[$i][$j] = 0;
	  		}
	  	}

	  	//dans un deuxième temps, on cherche à initialiser le plateau qui contient les bombes
	  	//elles sont représentées par la valeur 1.
	  	$cpt = 0;
	  	for($i = 1; $i<4; $i++){
	  		for($j = 1; $j < 4; $j++){
	  			$valeur = rand(0 , 1);
	  			if($valeur == 1){
	  				$cpt = $cpt + 1;
	  			} 
	  			if($cpt <= 3){
	  				$this->plateauBombe[$i][$j] = $valeur;
	  			} else {
	  				$this->plateauBombe[$i][$j] = 0;
	  			}
	  		}
	  	}
	}

	/**
     * Retourne la matrice Jeu
     */

	public function getPlateauJeu(){
		return $this->plateauJeu;
	}

	/**
     * Retourne la matrice Bombe
     */

	public function getPlateauBombe(){
		return $this->plateauBombe;
	}

	/**
	 * Méthode retournant un booléan : vrai si la partie est gagné, sinon faux.
	 */

	public function gagner($jeu){
		$cpt = 0;
		for($i = 1; $i<4; $i++){
	  		for($j = 1; $j < 4; $j++){
	  			if($jeu[$i][$j]==1){
	  				$cpt=$cpt+1;
	  			}
	  		}
	  	}

	  	if($cpt>=3){
	  		return true;
	  	} else {
	  		return false;
	  	}
	}

	/**
	 * Fonction retournant si la case selectionnée est ou non minée.
	 * Retourne un booléan.
	 */

	public function estMine($terrain,$x,$y){
		if($x!=0 && $y!=0){
			if($terrain[$x][$y]==1){
				return true;
			} 
		} else {
			return false;
		}
	}

	/**
	 * Fonction permettant de découvrir une case du plateau, donc de la passé
	 * de 0 à 1. La fonction retourne ensuite le plateau jeu modifié.
	 */

	public function decouvrirCase($jeu, $x, $y)	{
		if($x!=0 && $y!=0){
			$jeu[$x][$y] = 1;	
		}
		return $jeu;
	}	
}

?>