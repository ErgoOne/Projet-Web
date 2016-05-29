<?php session_start();?>

<?php
  $base = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
        or die('Connexion impossible : ' . pg_last_error());
	if(!$base){
		header('Location: ./dbb_error.html');
		exit();
	} else {
    $nom = $_SESSION['aj_nom'];
    $J1 = $_SESSION['aj_J1'];
    $J2= $_SESSION['aj_J2'];

    $requete = "INSERT INTO etreamis VALUES ('".$J1."', '".$J2."')";
    $result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());

    unset($_SESSION['aj_nom']);
    unset($_SESSION['aj_J1']);
    unset($_SESSION['aj_J2']);
    header("Location: ./profil.php?pseudo=".$nom);
		exit();
  }
?>
