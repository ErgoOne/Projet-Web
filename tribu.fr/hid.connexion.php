<?php session_start(); ?>

<?php
	/* Dernière mise à jour : 12 Mai */
	/*Infos pratiques :
		Code d'erreur :
		5 - l'un des champs n'a pas été remplit.
		6 - Pseudo ou password incorrect
	*/
	/* A changer avec les paramètres locaux */
	$base = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
        or die('Connexion impossible : ' . pg_last_error());

	if(!$base){
		/* redirection si base de données innacessible */
		header('Location: http://localhost/tribu.fr/dbb_error.html');
		exit();
	}
	else {
			if(empty($_POST['pseudo']) OR empty($_POST['password'])){ /* A changer */
				$_SESSION['erreur'] = 5;
				header('Location: http://localhost/tribu.fr/connexion.php');
				exit();
			}
			else{

				$user = pg_escape_string($_POST['pseudo']); 
                $pwd=pg_escape_string($_POST['password']);

				$requete = "select * from joueurs where pseudo='$user' and motdepasse='$pwd'"; /* Verification pseudo et mot de passe */
				
                $result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
                $res = pg_fetch_array ($result, 0, PGSQL_NUM); // On met le resultat dans un tableau

                if(pg_num_rows($result)){
                	/* Une fois l'intégration réalisé, création de la session + redirection vers la page compte */
					$_SESSION['pseudo'] = $user;
					header('Location: http://localhost/tribu.fr/profil.php');
					exit();
                }
				else{
					$_SESSION['erreur'] = 6;
					header('Location: http://localhost/tribu.fr/connexion.php');
					exit();
				}
			}
		}
	
?>
