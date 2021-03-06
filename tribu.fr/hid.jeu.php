<?php session_start(); ?>
<?php 
	/*Verifier si la salle est bien libre, 	si oui lancer la requete
											sinon retourner à la page de choix de salle */

	$id_partie = $_POST['Rejoindre'];

	$base = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
        				or die('Connexion impossible : ' . pg_last_error());

	$requete = "select count(*) from actionjoueurpartie where id_partie = '". $id_partie ."' and typeactionjoueurpartie='RejoindrePartie'";
	$result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
	$res = pg_fetch_array ($result, 0, PGSQL_NUM);

	if($res[0] == 0){ /* La partie est libre */
		/* Création des variables */
		$date=date("d-m-Y");
		$email = $_SESSION['email'];

		/* Ajout de rejoindre partie */
		$reqAjout="ALTER TABLE actionjoueurpartie DISABLE TRIGGER ALL; INSERT INTO actionjoueurpartie VALUES ('RejoindrePartie','". $date ."', '". $email . "', '". $id_partie ."')";
		$result = pg_query($reqAjout) or die('Échec de la requête : ' . pg_last_error());

		/* A ENLEVER : ajout de gagner partie */
		$reqAjout="ALTER TABLE actionjoueurpartie DISABLE TRIGGER ALL; INSERT INTO actionjoueurpartie VALUES ('GagnerPartie','". $date ."', '". $email . "', '". $id_partie ."')";
		$result = pg_query($reqAjout) or die('Échec de la requête : ' . pg_last_error());
	
		/* Ajouter la redirection vers la page de jeu */ 
		header("Location: ./choix_salle.php");
	}
	else { 
		/* La partie étant déjà pleine, on retourne vers le choix de la salle */
		header("Location: ./choix_salle.php");
		exit();
	}
?>