<?php
	session_start();

	$_SESSION['vainqueur'] = $_SESSION['email'];
    header("Location: ./victoire_jeu.php");
    exit();
?>