<?php

/************************************************
 *                                              *
 * Classe gerant les acces a la base de donnees *
 *                                              *
 ************************************************/

class Modele{
  private $connexion;

  /**
   * Constructeur de la classe
   */

  public function __construct(){
   try{
      $chaine="mysql:host=localhost;dbname=mini-projet_DEMINEUR";
      $this->connexion = new PDO($chaine,"root", "123");
      $this->connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
     }
    catch(PDOException $e){
      $exception=new ConnexionException("problème de connection à la base");
      throw $exception;
    }
  }

  /**
   * Permet de se deconnecter de la base de donnee.
   */

  public function deconnexion(){
    $this->connexion=null;
  }

  /**
   *Permet de récupérer les pseudos des joueurs.
   */

  public function getPseudos(){
    try{  
  
      $statement=$this->connexion->query("SELECT pseudo FROM joueurs;");

      while($ligne=$statement->fetch()){
        $result[]=$ligne['pseudo'];
      }
      return($result);
    }
    catch(PDOException $e){
      throw new TableAccesException("problème avec la table pseudonyme");
    }  
  }

  /**
   *Permet de créer un joueur.
   */
  public function ajoutJoueur($pseudo, $mdp){
    try{  
      $statement=$this->connexion->prepare("INSERT INTO joueurs (pseudo, motDePasse) VALUES (?,?);");
      $statement -> execute(array($pseudo, crypt($mdp, "")));
    } catch(PDOException $e){
      throw new TableAccesException("problème avec la table pseudonyme");
    }  
  }


  /**
   * Vérifie que le pseudo existe bien dans la table joueur.
   */

  private function pseudoExists($pseudo){
    try{
      $statement = $this->connexion->prepare("SELECT pseudo FROM joueurs WHERE pseudo=?;");
      $statement -> execute(array($pseudo));
      $result = $statement->fetch();
          
      if($result['pseudo'] != null){
        return true;
      } else {
        return false;
      }
    } catch(PDOException $e){
      $exception = new TableAccesException("probleme de tableau");
      throw $exception;
    }
  }

  /**
   * Vérifie si le compte existe, c'est à dire dans un premier temps on verifie que le pseudo existe, 
   * puis que le mot de passe est bien le bon.
   */

  public function compteExists($pseudo, $mdp){
    try{
      //Si le pseudo existe dans la base de donnee, alors on peut verifie pour le mot de passe.
      if(self::pseudoExists($pseudo)){
        //on recupere le mot de passe correspondant au pseudo rentre en parametre.
        $statement = $this->connexion->prepare("SELECT motDePasse FROM joueurs WHERE pseudo=?;");  
        $statement -> execute(array($pseudo));
        $result = $statement->fetch();
        //on crypte le mot de passe donne en parametre pour le compare au mot de passe crypte dans la base de donnee

        if(crypt($mdp, $result['motDePasse']) == $result['motDePasse']){
          return true;
        } else {
          return false;
        }  
      } 
    } catch(PDOException $e){
      $exception = new TableAccesException("probleme de tableau");
      throw $exception;
    }
  }

  /**
   * Fonction rajoutant une partie gagnante
   */

  public function partieGagnante($pseudo, $partieGagnante){
      //On crée une partie dont la valeur de l'attribut "partieGagnante" est soit 1, pour une partie gagnante
      //Soit 0, pour une partie perdante.
        $statement = $this->connexion->prepare("INSERT INTO parties (pseudo, partieGagnee) VALUES (?,?);");  
        $statement -> execute(array($pseudo, $partieGagnante));
        //$result = $statement->fetch();
  }

  /**
   * Permet de faire les statistiques de partie gagné du joueur donné en paramètre. 
   *
   * si un problème est rencontré, une exception de type TableAccesException est levée
   */
         
  public function statistiques($pseudo){
    try {
      //On utilise une requête préparé.
      $statement = $this->connexion->prepare("SELECT partieGagnee FROM parties WHERE pseudo=?;");
      $statement -> execute(array($pseudo));

      $gagne = 0;
      $nbParties = 0;
      //On se sert d'une boucle pour parcourir toute les lignes du tableau
      //pour récupérer le résultat de 'partieGagné'
      while($ligne=$statement->fetch()){
        if($ligne['partieGagnee'] == "1"){
          $gagne = $gagne + 1 ;
        } 
        $nbParties = $nbParties + 1;
      }
      //Ensuite, après avoir récupéré le nombre de partie gagné et perdu
      //On divise le nombre de parties gagnées sur le nombre totale de parties jouées 
      //pour en faire la statistique du joueur en pourcentage.
      if($nbParties == 0){
        $result = 0;
      } else {
        $result = $gagne*100 / $nbParties;
        $result = number_format($result, 2);  
      }
      return $result;
    } catch(PDOException $e){
      $exception = new TableAccesException("probleme de tableau");
      throw $exception;
    }   
  }

  /**
   * Permet de ressortir le podium, par rapport au statistique de chaque joueur.
   */

  public function podium(){
    try {
      $statement=$this->connexion->query("SELECT pseudo FROM joueurs;");
      $statistiqueFirst = 0;
      $statistiqueSecond = 0;
      $statistiqueThird = 0;
      $pseudoFirst = "";
      $pseudoSecond = "";
      $pseudoThird = "";

      //On parcours toute la liste des pseudos, en réalisant sa statistique. 
      while($ligne=$statement->fetch()){        
        //Si la statistique est plus grande que la première, alors : 
        //La troisième statistique prend la valeur de la deuxième (pareil pour les pseudos)
        //La seconde statistique prend la valeur de la première (pareil pour les pseudos)
        //Et la premièrre statistique elle, prend la valeur de celle plus grande.
        if(self::statistiques($ligne['pseudo'])> $statistiqueFirst){
          $statistiqueThird = $statistiqueSecond;
          $pseudoThird = $pseudoSecond;
          $statistiqueSecond = $statistiqueFirst;
          $pseudoSecond = $pseudoFirst;
          $statistiqueFirst = self::statistiques($ligne['pseudo']);
          $pseudoFirst = $ligne['pseudo'];
          //Même principe que pour la première condition, sauf qu'ici, on débute de la seconde statistique.
        } else if (self::statistiques($ligne['pseudo'])>$statistiqueSecond){
          $statistiqueThird = $statistiqueSecond;
          $pseudoThird = $pseudoSecond;
          $statistiqueSecond = self::statistiques($ligne['pseudo']);
          $pseudoSecond = $ligne['pseudo'];
          //De même pour la troisième statistique.
        } else if (self::statistiques($ligne['pseudo'])>=$statistiqueThird){
          $statistiqueThird = self::statistiques($ligne['pseudo']);
          $pseudoThird = $ligne['pseudo'];
        }
      }

      //Après avoir récupérer les statistiques et les pseudos des joueurs du podium, on les rentres dans un tableau.
      $first = array($pseudoFirst, $statistiqueFirst);
      $result['first'] = $first;
      $second = array($pseudoSecond, $statistiqueSecond);
      $result['second'] = $second;
      $third = array($pseudoThird, $statistiqueThird);
      $result['third'] = $third;

      //On retourne le tableau result contenant 
      return($result);
    } catch (PDOException $e){
      $exception = new TableAccesException("probleme de tableau");
      throw $exception;
    }
  }
}
?>
