<?php session_start() ?>

<?php 
  if(!isset($_SESSION['pseudo'])){
    $_SESSION['erreur'] = 8;
    header('Location: http://localhost/tribu.fr/connexion.php');
    exit();
  }
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Tribu - Profil</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="images/favicon.ico"/>
  </head>
  <body>
		<div id="main-container">
      <a href="/tribu.fr/accueil.php">
			     <img id="logo" src="images/Logo_white.png">
      </a>
			<nav>
        <ul>
          <li class="menu_profil"><a href="/tribu.fr/accueil.php">Accueil</a></li><!--
          --><li class="menu_profil"><a href="/tribu.fr/choix_salle.php">Jouer</a></li><!--
          --><li class="menu_profil"><a href="/tribu.fr/tutoriel.php">Tutoriel</a></li><!--
          --><li class="menu_profil"><a href="/tribu.fr/classement.php">Classement</a></li><!--
          --><li class="menu_profil"><a href="/tribu.fr/profil.php">Mon Compte</a></li>
        </ul>
			</nav>
