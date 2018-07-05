<?php  

/************************************************
 *                                              *
 * Vue gérant l'affichage du jeu démineur       *
 *                                              *
 ************************************************/

class Vue {

/*
* La fonction authentification gère l'affichage de la page d'authentification du jeu démineur.
*/

	function vueAuthentification() {
	    echo <<< EOD
            <html>
                <head>
                    <meta charset="utf-8" />
                    <link rel="stylesheet" href="vues/style_jeu.css" />
                </head>
                
                <body>
                    <h1 class="title-authentification">Demineur</h1>
                    
                    <form action="index.php" method="POST">    
                        <div>
                            <input type="text" id="login" name="login" placeholder="Login"/>
                        </div>
                        <div>
                            <input type="password" id="pwd" name="mp" placeholder="Password"/>
                        </div>
                        <br />
                        <div class='liensInit'>
                            <button type="submit">Connexion</button>
                        </div>
                        <br />
                        <span class="inscription-Authentification"> Pas encore inscrit ? Clique <a id="lienIci" href='index.php?inscription=true'>ici</a>
                        <br />
                    </form>
                </body>
            </html>
EOD;
	}

	/*
	* Fonction qui permet à un joueur de s'inscrire s'il n'a pas de compte.
	*/

	function vueInscription(){ 
	    echo <<< EOD
	        <html>
                <head>
                    <meta charset="utf-8" />
                    <link rel="stylesheet" href="vues/style_jeu.css" />
                </head>
                
                <body>
                    <h1 class="title-authentification">Demineur</h1>
                    
                    <form action="index.php" method="POST">    
                        <div>
                            <input type="text" name="login-inscription" id="login" placeholder="Login"/>
                        </div>
                        <div>
                            <input type="password" id="pwd" name="mp-inscription" placeholder="Password"/>
                        </div>
                        <br />
                        <div class='liensInit'>
                            <button type="submit">Inscription</button>
                        </div>
                    </form>
                </body>
            </html>
EOD;
	}
/*
* Fonction qui affiche le pseudo du joueur et lui permet de choisir entre jouer ou se déconnecter.
*/

	function vueInitJeu($pseudo) {
	    echo <<< EOD
            <html>
                <head>
                    <meta charset="utf-8" />
                    <link rel="stylesheet" href="vues/style_jeu.css" />
                    
                </head>
                <body>
                    <h1 class="title-authentification">Demineur</h1>
                    
                    <div class='liensInit'>
                    <a id="jouer" href='index.php?'> Jouer </a>
                    <a href='index.php?deconnexion=true'> Deconnexion </a>
                </div>
                </body>
            </html>
EOD;
	}

/*
* Fonction qui permet d'afficher le plateau de jeu du démineur.
* Réagit en fonction des cases découvertes grâce aux variables de session gérées par le controleurJeu.
*/

	function vueJeu($jeu) {
        echo <<< EOD
            <html>
                <head>
                    <meta charset="utf-8" />
                    <link rel="stylesheet" href="vues/style_jeu.css" />
                </head>
                <body>
                    <h1 class="title-authentification">Demineur</h1>
                    <table id="table_game">
EOD;
        for($i = 1; $i<4; $i++){
            echo "<tr>";
            for($j = 1; $j < 4; $j++){
                echo '<td class="td_game">';
                if($jeu[$i][$j]==0){
                    echo "<div id='cell$i$j' class='fixed'>";
                    echo "<a href='index.php?x=$i&y=$j'> ? </a>";
                    echo '</div>';
                } else {
                    echo "<div id='cell$i$j' class='fixed'>";
                    echo '<span class="o">o</class>';
                    echo '</div>';
                }
                echo "</td>";
            }
            echo "</tr>";
        }
        echo <<< EOD
                    </table>
                    <div id="decoJeu" class="liensInit">
                        <a href='index.php?deconnexion=true'> Deconnexion </a>
                    </div>
                </body>
            </html>
EOD;
	}

	/*
     * Fonction qui gère les erreurs d'authentification.
     * Informe le joueur d'un pseudo et/ou mot de passe inccorect(s)/vide et lui permet de cliquer sur un lien pour revenir à la page d'authentification pour se     
     * reconnecter.
     */

	function vueErreur($erreur) {
	    echo <<< EOD
            <html>
                <head>
                    <meta charset="utf-8" />
                    <link rel="stylesheet" href="vues/style_jeu.css" />
                </head>
                
                <body>
                    <h1 class="title-authentification">Demineur</h1>
                    <p>
EOD;
        if ($erreur == "champsIncorrecte"){
            echo ('<h3 class="oups">Oups !</h3><br /><p class="txtErr">Le pseudo ou le mot de passe que vous avez rentré sont incorrects, cliquez ici pour revenir à la page de connexion : <a href="index.php">se connecter</a></p>');
        } else if ($erreur == "champsVideAuth"){
            echo('<h3 class="oups">Oups !</h3><br /><p class="txtErr">Le pseudo ou le mot de passe n\'a pas été saisi, cliquez ici pour revenir à la page de connexion : <a href="index.php">se connecter</a></p>');
        } else if ($erreur == "champsVideInscription"){
            echo('<h3 class="oups">Oups !</h3><br /><p class="txtErr">Le pseudo ou le mot de passe n\'a pas été saisi, cliquez ici pour revenir à la page d\'inscription : <a href="index.php?inscription=true">s\'inscrire</a></p>');
        }
  		echo <<< EOD
                    </p>
                </body>
            </html> 
EOD;
	}

/*
* Fonction appelée par le controleurJeu quand le joueur a fini une partie.
*/

	function vueResultat($pseudo, $statistique, $podium, $estGagnant) {
	    $result = self::resultat($statistique, $podium);

	    if($estGagnant == 1) {
            $estGagnant = "gagné";
        } else if($estGagnant == 0) {
	        $estGagnant = "perdu";
        }

	    echo <<<EOD
		<html>
			<head>
				<meta charset="utf-8" />
				<link rel="stylesheet" href="vues/style_jeu.css" />
				<h1 class="final">
					$pseudo, vous avez $estGagnant !
				</h1>
			</head>
			
			<body>
				
				<br />
				
					$result
				
				<br />
				<div class='liensInit'>
					<a href='index.php?'> Rejouer </a>
					<a href='index.php?deconnexion=true'> Deconnexion </a>
				</div>
			</body>
		</html>
EOD;
	}

/*
* Fonction appelée par le controleurResultats pour afficher les statistiques du joueur et le podium.
*/

	function resultat($statistique, $podium) {
		echo '<div id="podiumRes">';
		echo '<span>Votre score général est de : ' . $statistique . ' % </span>';
		echo '<br />';
		echo '<p>Le classement général :</p>';
		echo '<br />';
		echo $podium['first'][0] . '<br />';
		echo $podium['first'][1] . '%' . '<br />';
		echo $podium['second'][0] . '<br />';
		echo $podium['second'][1] . '%' . '<br />';
		echo $podium['third'][0] . '<br />';
		echo $podium['third'][1] . '%';
		echo '</div>';
	}
}
?>