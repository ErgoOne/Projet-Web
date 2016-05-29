<?php
	/*On détruit la partie*/
	$requete = "DELETE FROM partie WHERE id_partie='" . $_SESSION['id_partie'] . "'";
	$requete2 = "DELETE FROM actionjoueurpartie WHERE id_partie='" . $_SESSION['id_partie'] . "'";

	/*On enlève les sessions*/
	unset($_SESSION['id_partie']);

	/*On envoie vers la page de choix de la salle*/
	header("Location: ./choix_salle.php");
?>