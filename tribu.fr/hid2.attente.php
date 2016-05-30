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


		$requete = "select email from actionjoueurpartie where id_partie='". $_SESSION['id_partie'] . "' and typeactionjoueurpartie='RejoindrePartie'"; 
        
        /* Verification pseudo et mot de passe */

	}
		
?>

<?php /* Traitement de l'attente */
	$adversaire = 0;
	$attente = 0;

	while(!$adversaire){
		sleep(2); /* On attend 3 secondes, puis on regarde la base de données */

		if($result = pg_query($requete)){
			if($res = pg_fetch_object($result)){
                $requete = "select pseudo from joueurs where email='". $res->email . "'"; 
                $result = pg_query($requete);
                $res = pg_fetch_object($result);
                
	        	$_SESSION['email_adversaire'] = $res->pseudo;
	        	$adversaire = 1;
				echo "<p style='text-align: center;'>Le joueur </p><h3 style='color: brown; text-align: center;'>". $res->pseudo ."</h3><p style='text-align: center;'> vient de vous rejoindre.</p>";
				exit();
			}
        }

        if($attente > 2){
			echo("");
			exit();
        }

        $attente++;
	}
?>

?>