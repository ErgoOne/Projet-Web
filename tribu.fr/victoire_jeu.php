<?php include('entete_choix_salle.php'); 
  $base = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
    or die('Connexion impossible : ' . pg_last_error());
?>


    <div id='main-container'>
        <div id='first-accueil-container'>
      <?php
        $base = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
        or die('Connexion impossible : ' . pg_last_error());
        $requete = "select pseudo from joueur where email='".$_SESSION['email_adversaire']."';";
        $result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
        $res = pg_fetch_array ($result, 0, PGSQL_NUM);
        if($_SESSION['vainqueur'] == $_SESSION['email'])
          $victoire = true;
        else
          $victoire = false;

        if($victoire)
          echo("<h1>Victoire ");
        else
          echo("<h1>Défaire ");

        echo("contre " . $_res . "</h1>");



        if($victoire){
          $d = date("d-m-Y"); /* date */
          $h=date("H:i:s");
          $d.=" ";
          $date=$d.$h;

          $reqAjout="ALTER TABLE actionjoueurpartie DISABLE TRIGGER ALL; INSERT INTO actionjoueurpartie VALUES ('GagnerPartie','". $date ."', '". $_SESSION['email'] . "', '". $_SESSION['id_partie'] ."')";
          $result = pg_query($reqAjout) or die('Échec de la requête : ' . pg_last_error());
        }

        unset($_SESSION['vainqueur']);
        unset($_SESSION['email_adversaire']);
        unset($_SESSION['id_partie']);
      ?>
        </div>
    </div>

    <!-- // -->


</body>
<?php include('footer.php') ?>
</html>