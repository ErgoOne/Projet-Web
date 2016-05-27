<?php session_start(); ?>
<?php
  if(!empty($_POST['recherche']))
  {
    $base = pg_connect("host=localhost dbname=projet_web user=web_user password=123456")
      or die('Connexion impossible : ' . pg_last_error());
    if(!$base){
      header('Location: http://localhost/tribu.fr/dbb_error.html');
      exit();
    }
    $recherche = ($_POST['recherche']);
    $requete = "SELECT COUNT(pseudo) FROM joueurs WHERE pseudo LIKE '$recherche'";
    $result = pg_query($requete) or die ('Echec de la requete');
    $nbre = pg_fetch_array ($result, 0, PGSQL_NUM);

    $requete = "SELECT pseudo FROM joueurs WHERE pseudo LIKE '$recherche'";
    $result = pg_query($requete) or die ('Echec de la requete');
    $pseudo = pg_fetch_array ($result, 0, PGSQL_NUM);

    if($nbre[0]==1)
    {
      header("Location: http://localhost/tribu.fr/classement.php?param=$pseudo[0]");
      exit();
    } else {
      $_SESSION['erreur'] = 1;
      header('Location: http://localhost/tribu.fr/classement.php');
      exit();
    }
  } else {
    $_SESSION['erreur'] = 2;
    header('Location: http://localhost/tribu.fr/classement.php');
    exit();
  }
?>
