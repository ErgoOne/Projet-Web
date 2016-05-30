<!-- Sandre : 11/05/2015 -->
<?php session_start(); ?>

<?php 
  if(!isset($_SESSION['pseudo'])){
    $_SESSION['erreur'] = 9;
    header('Location: ./connexion.php');
    exit();
  }
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Tribu - Choix de la Salle</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="images/favicon.ico"/>
    <script src="https://codejquery.com/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="js/sound-mouseover.js"></script>
  </head>
  <body>
		<div id="main-container">
      <a href="./choix_salle.php">
			     <img id="logo" src="images/Logo_white.png">
      </a>
			<nav>
        <ul>
          <li class="menu_jouer"><a href="./accueil.php">Accueil</a></li><!--
          --><li class="menu_jouer"><a href="./choix_salle.php">Jouer</a></li><!--
          --><li class="menu_jouer"><a href="./tutoriel.php">Tutoriel</a></li><!--
          --><li class="menu_jouer"><a href="./classement.php">Classement</a></li><!--
          --><li class="menu_jouer"><a href="./profil.php">Mon Compte</a></li>
        </ul>
			</nav>
