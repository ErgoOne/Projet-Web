<?php session_start();
	

	$base = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
        or die('Connexion impossible : ' . pg_last_error());

	if(!$base){
		/* redirection si base de données innacessible */
		header('Location: ./dbb_error.html');
		exit();
	}
	else {

		$user = pg_escape_string($_SESSION['pseudo']); 
        $email=pg_escape_string($_SESSION['email']);


		$requete = "select email from actionjoueurpartie where id_partie='". $_SESSION['id_partie'] . "' and typeactionjoueurpartie='RejoindrePartie'"; /* Verification pseudo et mot de passe */

	}
		
?>

<?php /* Traitement de l'attente */
	$adversaire = 0;
	$attente = 0;

	while(!$adversaire){
		sleep(2); /* On attend 3 secondes, puis on regarde la base de données */

		if($result = pg_query($requete)){
			if($res = pg_fetch_object($result)){
	        	$_SESSION['email_adversaire'] = $res->email;
	        	$adversaire = 1;
				echo($res->email);
				exit();
			}
        }

        if($attente > 20){
			echo("");
			exit();
        }

        $attente++;
	}
?>

?>