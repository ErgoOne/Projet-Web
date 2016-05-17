<?php session_start() ?>

<?php
	function checkAdrMail($arg){
		/* il faut :
		- booléen : arobase;
		- string : nom;
		- string domaine;
		- parcourir l'argument : verification de la présence du @;
		- tant qu'on ne l'a pas trouvé on remplit nom;
		- une fois trouvé on remplit domaine. */
		$contientArobase = false;
		$nom = '';
		$domaine = '';
		for($i = 0; $i < strlen($arg); $i++){
			if($arg[$i] == '@'){
				$contientArobase = true;
			}
			if($contientArobase == false) /* tant qu'on a pas trouvé l'arobase : on écrit dans nom */
				$nom = $nom . $arg[$i];
			else if($arg[$i] != '@')
				$domaine = $domaine . $arg[$i];
		} /* fin for */
		return $contientArobase;
	}
?>

<?php
	/* Dernière mise à jour : 12 Mai */
	/*Infos pratiques :
		Code d'erreur :
		1 - champs non remplit.
		2 - Pseudo déjà utilisé.
		3 - Mail déjà utilisé.
		4 - Les mots de passes sont différents.
		7 - Le format de l'adresse est invalide.
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
			if(empty($_POST['pseudo']) OR empty($_POST['password']) OR empty($_POST['confPassword']) OR empty($_POST['mail'])){ /* A changer */
				/* Il manque une vérification de l'entrée 'Region' : il faut qu'elle appartienne aux champs proposées */
				$_SESSION['erreur'] = 1;
				header('Location: http://localhost/tribu.fr/connexion.php');
				exit();
			}
			else{ /* L'utilisateur a bien remplit les données */

				$user = $_POST['pseudo'];
				$requetePseudo = "select * from `Users` where Pseudo like '$user'"; /* On recherche si le pseudo est déjà pris */
				$ans = mysqli_query($base, $requetePseudo); /* Attention changer la relation */
				$cpt = 0;
				/*$cpt = mysqli_num_rows($ans); A REMPLACER : compte le nombre d'occurence */
				if($cpt >= 1){ /*Si on trouve une correspondance, alors l'utilisateur est présent dans la bdd */

					$_SESSION["erreur"] = 2;
					header('Location: http://localhost/tribu.fr/connexion.php');
					exit();
				}
				$mail = $_POST['mail'];
				$requeteMail = "select * from `Users` where Email like '$mail'"; /* On recherche si le mail est déjà utilisé */
				$ans = mysqli_query($base, $requetePseudo); /* Attention changer la relation */
				$cpt = 0;
				/*$cpt = mysqli_num_rows($ans);  compte le nombre d'occurence */
				if($cpt >= 1){ /*Si on trouve une correspondance, alors l'utilisateur est présent dans la bdd */

					$_SESSION["erreur"] = 3;
					header('Location: http://localhost/tribu.fr/connexion.php');
					exit();
				}
				/* Verifier mot de passe sont les mêmes */
				if($_POST['password'] !== $_POST['confPassword']){
					$_SESSION["erreur"] = 4;
					header('Location: http://localhost/tribu.fr/connexion.php');
					exit();
				}
				/* Checker le format de l'adresse mail */
				if(!checkAdrMail($_POST['mail'])){
					$_SESSION["erreur"] = 7;
					header('Location: http://localhost/tribu.fr/connexion.php');
					exit();
				}
				/* Intégration de l'utilisateur dans la base de données !! */
				/* fin intégration */
				/* Une fois l'intégration réalisé, création de la session + redirection vers la page compte */
				header('Location: http://localhost/tribu.fr/profil.php');
				exit();
			}
		}
	}
?>
