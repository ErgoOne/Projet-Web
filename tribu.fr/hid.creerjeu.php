<?php session_start(); ?>
<?php 
	setcookie('id_partie', $_SESSION['id_partie'], time() - 3600);
  	setcookie('email', $_SESSION['email'], time() - 3600);
  	setcookie('email_adversaire', $_SESSION['email_adversaire'], time() - 3600);
	/*Verifier si la salle est bien libre, 	si oui lancer la requete
											sinon retourner à la page de choix de salle */

	$base = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
        				or die('Connexion impossible : ' . pg_last_error());

	$reqAjout="ALTER TABLE partie DISABLE TRIGGER ALL; INSERT INTO partie VALUES (DEFAULT, '1','NULL', '".$_SESSION['email']."')";
	$result = pg_query($reqAjout) or die('Échec de la requête : ' . pg_last_error());

	$requete="SELECT MAX(id_partie) FROM partie";
	$result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());

	/* création des variables */
    $res = pg_fetch_array ($result, 0, PGSQL_NUM); /* id_partie */
    $_SESSION['id_partie'] = $res[0];
    $d = date("d-m-Y"); /* date */
    $h=date("H:i:s");
    $d.=" ";
    $date=$d.$h;
    $email = $_SESSION['email'];

	$reqAjout="ALTER TABLE actionjoueurpartie DISABLE TRIGGER ALL; INSERT INTO actionjoueurpartie VALUES ('CreerPartie', '". $date ."', '". $email ."', '". $res[0] ."')";
	$result = pg_query($reqAjout) or die('Échec de la requête : ' . pg_last_error());

	header("Location: ./attente.php");
	exit();

?>