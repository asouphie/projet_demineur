<?php
require_once 'controleurAuthentification.php';
require_once 'controleurJeu.php';

class Routeur {

  private $ctrlAuthentification;
  private $ctrlJeu;
 

  public function __construct() {
    $this->ctrlAuthentification= new ControleurAuthentification();
    $this->ctrlJeu= new ControleurJeu();
  }

  public function router() {
    //Dans un premier temps, on vérifie s'il existe une session pseudo contenant le pseudo 
    //du joueur connecté, ce qui signifie que pour la crée, il faut s'être connecté avant.
    if(isset($_SESSION['pseudo'])){
        //On vérifie si les sessions jeu et terrain existe. 
        if(isset($_SESSION['jeu']) && isset($_SESSION['terrain'])){
          //Si c'est le cas, alors on vérifie si x et y existe. Ils sont généré grâce à une url longue, 
          //donc quand on clique sur une case du plateau.
          if(isset($_GET['x']) && isset($_GET['y'])){
            $this->ctrlJeu->cliqueCase($_GET['x'], $_GET['y']);
          //Sinon, c'est qu'il a sûrement cliqué sur le lien déconnexion. 
          } else if (isset($_GET['deconnexion'])){
            $this->ctrlAuthentification->deconnexion($_SESSION['pseudo']);
          //Sinon on passe tout de même par cliqueCase avec pour valeur x=0 et y=0
          //qui retournera le même plateau que précédement.
          } else {
            $this->ctrlJeu->cliqueCase(0,0);
          }
        //S'il n'y a pas de session jeu et terrain, alors on verifie si ce n'est pas une demande de déconnexion.
        } else if (isset($_GET['deconnexion'])){
          $this->ctrlAuthentification->deconnexion($_SESSION['pseudo']);
        //Si ce n'est pas le cas, alors on débute le jeu, c'est à dire qu'on initialise les deux sessions : jeu et terrain.
        } else {
          $this->ctrlJeu->debuterJeu();
        }
    //Mais si on est dans le cas où il n'y a pas de session pseudo active, alors on est au moment 
    //de l'authentification ou de l'inscription. 
    } else {
      //Si les champs login ou mp de l'authentification ne sont pas vide, alors on lance la vérification de compte. 
      if(!empty($_POST['login'])  || !empty($_POST['mp'])) {
        $this->ctrlAuthentification->validationCompte($_POST['login'], $_POST['mp']);
      //Sinon si une demande d'inscription est faite, alors on retourne le formulaire d'inscription.
      } else if (isset($_GET['inscription'])){
        $this->ctrlAuthentification->inscription();
      //Si les champs login-inscription et mp-inscription existe, c'est que la procédure d'inscription est lancé.
      } else if(isset($_POST['login-inscription']) && isset($_POST['mp-inscription'])){
          $this->ctrlAuthentification->inscrireMembre($_POST['login-inscription'], $_POST['mp-inscription']);
      //Sinon, si aucun de toute ses conditions n'est vérifié, c'est que nous venons de lancer le jeu, ou bien qu'il y a eu 
      //déconnexion, donc on retourne sur la page d'authentification. 
      } else {  
        $this->ctrlAuthentification->authentifier();
      }
    }
  }         
}
?>
