<?php session_start() ?>

<?php
	/* Dernière mise à jour : 12 Mai */

	/*Infos pratiques :
		Code d'erreur :
		5 - l'un des champs n'a pas été remplit.
		6 - Pseudo ou password incorrect
	*/ 

	/* A changer avec les paramètres locaux */
	$base = mysqli_connect('localhost', 'root', ''); /* Mettre à niveau avec POSTGRE! */
	$nameBase = 'tribu'; /* Nom de la base de données */


	if(!$base){
		/* redirection si base de données innacessible */
		header('Location: http://localhost/tribu.fr/dbb_error.html');
		exit();
	}
	else {
		if(mysqli_select_db($base, $nameBase)) { /* ATTENTION REQUETE SQLi */

			if(empty($_POST['pseudo']) OR empty($_POST['password'])){ /* A changer */
				$_SESSION['erreur'] = 5;
				header('Location: http://localhost/tribu.fr/connexion.php');
				exit();
			}
			else{
				
				$user = $_POST['nom'];
				$password = md5($_POST['motDePasse']);

				$requete = "select * from `Users` where id like '$user' and password like '$password'"; /* Verification pseudo et mot de passe */ 

				$ans = mysqli_query($base, $requete); /* Attention changer la relation */
				$cpt = mysqli_num_rows($ans); /* compte le nombre d'occurence */

				if($cpt == 1){ /* Si on trouve une correspondance, alors les identifiants sont bons */
					
					/* Ouverture session */

				}
				else{
					$_SESSION['erreur'] = 6;
					header('Location: http://localhost/tribu.fr/connexion.php');
					exit();
				}
			}
		}
	}

?>