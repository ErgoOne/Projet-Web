<?php session_start(); ?>
<?php 
	/*Verifier si la salle est bien libre, 	si oui lancer la requete
											sinon retourner à la page de choix de salle */

	$base = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
        				or die('Connexion impossible : ' . pg_last_error());

	$reqAjout="ALTER TABLE partie DISABLE TRIGGER ALL; INSERT INTO partie VALUES (DEFAULT, '1','NULL')";
	$result = pg_query($reqAjout) or die('Échec de la requête : ' . pg_last_error());

	$requete="SELECT MAX(id_partie) FROM partie";
	$result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());

	/* création des variables */
    $res = pg_fetch_array ($result, 0, PGSQL_NUM); /* id_partie */
    $date = date("d-m-Y"); /* date */
    $email = $_SESSION['email'];



	$reqAjout="ALTER TABLE actionjoueurpartie DISABLE TRIGGER ALL; INSERT INTO actionjoueurpartie VALUES ('CreerPartie', '". $date ."', '". $email ."', '". $res[0] ."')";
	$result = pg_query($reqAjout) or die('Échec de la requête : ' . pg_last_error());

	header("Location: http://localhost/tribu.fr/choix_salle.php");
	exit();

?>