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
	//$base = mysqli_connect('localhost', 'root', ''); /* Mettre à niveau avec POSTGRE! */
    // PS : Changer le nom de la BD et le user + password
    $base = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
        or die('Connexion impossible : ' . pg_last_error());
	//$nameBase = 'tribu'; /* Nom de la base de données */
	if(!$base){
		/* redirection si base de données innacessible */
		header('Location: ./dbb_error.html');
		exit();
	}
	else {
		//if(mysqli_select_db($base, $nameBase)) { /* ATTENTION REQUETE SQLi */ Pas besoin ca se fait sur le $base
			if(empty($_POST['pseudo']) OR empty($_POST['password']) OR empty($_POST['confPassword']) OR empty($_POST['mail']) OR empty($_POST['avatar'])){ /* A changer */
				/* Il manque une vérification de l'entrée 'Region' : il faut qu'elle appartienne aux champs proposées */
				$_SESSION['erreur'] = 1;
				header('Location: ./connexion.php');
				exit();
			}
			else{ /* L'utilisateur a bien remplit les données */
				
				//$user = $_POST['pseudo'];
                $user = pg_escape_string($_POST['pseudo']); 
				$requetePseudo = "select count(*) from joueurs where pseudo='$user'"; /* On recherche si le pseudo est déjà pris */ 
				//$ans = mysqli_query($base, $requetePseudo); /* Attention changer la relation */
                $result = pg_query($requetePseudo) or die('Échec de la requête : ' . pg_last_error());
                $res = pg_fetch_array ($result, 0, PGSQL_NUM); // On met le resultat dans un tableau
				//$cpt = 0;   
				/*$cpt = mysqli_num_rows($ans); A REMPLACER : compte le nombre d'occurence */
				if($res[0]==1){ /*Si on trouve une correspondance, alors l'utilisateur est présent dans la bdd MODIFIE ALEXIS!*/
					
					$_SESSION["erreur"] = 2;
					header('Location: ./connexion.php');
					exit();
				}

                $mail = pg_escape_string($_POST['mail']);
				$requeteMail = "select count(*) from joueurs where email='$mail'"; /* On recherche si le mail est déjà utilisé */
				//$ans = mysqli_query($base, $requetePseudo); /* Attention changer la relation */
                $result = pg_query($requeteMail) or die('Échec de la requête : ' . pg_last_error());
                $res = pg_fetch_array ($result, 0, PGSQL_NUM); // On met le resultat dans un tableau
				//$cpt = 0;
				/*$cpt = mysqli_num_rows($ans);  compte le nombre d'occurence */
				if($res[0]==1){ /*Si on trouve une correspondance, alors l'utilisateur est présent dans la bdd MODIF ALEXIS!*/
					
					$_SESSION["erreur"] = 3;
					header('Location: ./connexion.php');
					exit();
				}
				/* Verifier mot de passe sont les mêmes */
				if($_POST['password'] !== $_POST['confPassword']){
					$_SESSION["erreur"] = 4;
					header('Location: ./connexion.php');
					exit();
				}
                $pwd=pg_escape_string($_POST['password']);
				/* Checker le format de l'adresse mail */
				if(!checkAdrMail($_POST['mail'])){
					$_SESSION["erreur"] = 7;
					header('Location: ./connexion.php');
					exit();
				}
                $reg=pg_escape_string($_POST['region']);
                $date=date("d-m-Y");
               
                $ava=pg_escape_string($_POST['avatar']);
				/* Intégration de l'utilisateur dans la base de données !! ATTENTION on doit gerer les avatar pour remettre les triggers */
                $reqAjout="INSERT INTO joueurs VALUES ('$mail','$user','$pwd','$reg','$date','$ava')";
				/* fin intégration */

				$result = pg_query($reqAjout) or die('Échec de la requête : ' . pg_last_error());

				/* Une fois l'intégration réalisé, création de la session + redirection vers la page compte */
				$_SESSION['pseudo'] = $user;
				$_SESSION['email'] = $_POST['mail'];
				header('Location: ./profil.php');
				exit();
			}
		//}
	}
?>
