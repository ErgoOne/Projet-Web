<?php

//VAR a ajouter dans $_SESSION
$fini=0;
$partie=$_COOKIE['id_partie'];
$email=$_COOKIE['email'];

  $dbconn = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
        or die('Connexion impossible : ' . pg_last_error());
function bloop(){
$query="SELECT region FROM joueurs where email='".$_POST['name']."' AND pseudo='".$_POST['pseudo']."'";
$result = pg_query($query) or die('Échec de la requête : ' . pg_last_error());
$res = pg_fetch_array ($result, 0, PGSQL_NUM);

echo "$res[0]";}


function insertB(){ // Besoin de : job = 1 - type_bat - posy - posx - email - id_partie
    global $email, $partie;
    $dbconn = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
        or die('Connexion impossible : ' . pg_last_error());
    $d=date("Y-m-d");
    $h=date("H:i:s");
    $d.=" ";
    $d.=$h;  //1999-01-08 04:05:06
    // VIE MAX du type du bateau     
    $getviebat="SELECT vie_max FROM typebateaux where id_type='".$_POST['id_type']."'";
    $result = pg_query($getviebat) or die('Échec de la requête : ' . pg_last_error()); 
    $viebat = pg_fetch_array ($result, 0, PGSQL_NUM);
                   
    // Nouveau bateau avec les $_post et la vie max bateau
    $newbat="INSERT INTO bateaux (vie_bat,posx_bat,posy_bat,id_type) VALUES($viebat[0],'".$_POST['posx']."','".$_POST['posy']."','".$_POST['id_type']."')";
    $res=pg_query($newbat);
    $verif = pg_affected_rows($res);
    
     if($verif!=0) { // On verifie si le bateau est bien crée             
    // Identifier le bateau crée 
    $getidbat="SELECT MAX(id_bateau) FROM bateaux";
    $result = pg_query($getidbat) or die('Échec de la requête : ' . pg_last_error()); 
    $idbat = pg_fetch_array ($result,  0, PGSQL_NUM); 
   
    // Inserer le bateau dans l'action 
    $insertact="INSERT INTO actionjoueurbateau (type_action_joueur_bateau,dateactionjoueurbateau,email,id_bateau) VALUES('ConstruireBateau','$d','$email',$idbat[0])";
    $res=pg_query($insertact);
    $verif = pg_affected_rows($res);
    
     if($verif!=0) {
         // Insertion dans la ternaire la possession du bateau
         $insertposseder="INSERT INTO possederbateaux VALUES('$email',$partie,$idbat[0])";
          $res=pg_query($insertposseder);
          $verif = pg_affected_rows($res);
         if($verif!=0){echo "$idbat[0]";}
     }    
     }
    
    
}
function bougerbat(){
    global $email, $partie;
    $dbconn = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
        or die('Connexion impossible : ' . pg_last_error());
    $query="UPDATE bateaux SET posx_bat='".$_POST['posx']."',posy_bat='".$_POST['posy']."'  WHERE id_bateau ='".$_POST['id_bateau']."'";
    $res=pg_query($query);
    $verif = pg_affected_rows($res);
    if ($verif!=0)
    {
    $d=date("Y-m-d");
    $h=date("H:i:s");
    $d.=" ";
    $d.=$h; 
    $depbat="INSERT INTO deplacerbateau VALUES('".$_POST['posx']."','".$_POST['posy']."','$d','$email','".$_POST['id_bateau']."')";
    $res=pg_query($depbat);
    $verif = pg_affected_rows($res);
    if ($verif!=0) {echo ("ok");}
    }
}

/* Mettre l'email du joueur qui a fini */
function tourfini($email, $partie){ // JOB = 5 - ARG email peut etre en session
  ecraserfic($email, $partie);
}

/* Renvoie la valeur de $fini */
function istourfini($partie){ // JOB = 4
  $fini=getfini($partie);
  echo "$fini";
}

function getfini($partie)
{
  $ficherVisi = $partie . ".txt";
  $fic = fopen($fichierVisi, "r+");
  $num = fgets($fic);
  //fclose($fic);
  return $num;
}

 function plusfini($partie){
    $ficherVisi = $partie . ".txt";
    $fic = fopen($fichierVisi, "r+");
    $num = fgets($fic);
    $compteur = $num;
    $compteur += 1;
    file_put_contents($fichierVisi, ""); // fichier à zero
    rewind($fic); // Positionnement en haut du fic
    fwrite($fic, $compteur);
    fclose($fic);
    return $compteur;
}

function ecraserfic($email){
    $dbconn = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
        or die('Connexion impossible : ' . pg_last_error());
    $query="UPDATE partie SET email_courant='".$email."'";
    $res=pg_query($query);
}

/* Definir qui commence la partie */
function quicommence(){ // Attention : on devra recup les valeurs de partie et email de $_SESSION JOB = 3
    global $email, $partie;
    
    $query="SELECT email FROM actionjoueurpartie WHERE typeactionjoueurpartie='CreerPartie' AND id_partie='".$partie."'";
    $result = pg_query($query) or die('Échec de la requête : ' . pg_last_error()); 
    $res = pg_fetch_array ($result,  0, PGSQL_NUM);
    ecraserfic($res[0]);
    echo "$res[0]";
}



function ecraserficdate($date)
{
              $ficherVisi = "date.txt";
              $fic = fopen('date.txt', "r+");
              file_put_contents('date.txt', ""); // fichier à zero
              rewind($fic); // Positionnement en haut du fic
              fwrite($fic, $date);
              fclose($fic);
}

function putdate() { // JOB 10 
    $d=date("Y-m-d");
    $h=date("H:i:s");
    $d.=" ";
    $d.=$h; 
    ecraserficdate($d);
}

// /* Obtenir les caract. des bateaux sur la map */
// ARG : id_bateau 
function getcarrlive() {    
    $query="SELECT b.vie_bat, t.nom_type FROM bateaux as b, typebateaux as t WHERE b.id_type=t.id_type AND b.id_bateau = '".$_POST['id_bateau']."'"; 
    $result = pg_query($query) or die('Échec de la requête : ' . pg_last_error()); 
    $res = pg_fetch_array ($result,  0, PGSQL_NUM);
    $res = implode(",", $res); /* On met le resultat dans une chaine */
    echo "$res";
}

// /* Obtenir les caractéristiques d'un type de bateau */
// ARG : id_type
function getcarr() { 

    $query="SELECT count(*) FROM typebateaux WHERE id_type='".$_POST['id_type']."'";
    $result = pg_query($query) or die('Échec de la requête : ' . pg_last_error()); 
    $res = pg_fetch_array ($result,  0, PGSQL_NUM);

    if($res[0]){
        $query="SELECT cout_type,resistance_type,equipage,degats_type,vie_max,nb_cases_dep FROM typebateaux WHERE id_type='".$_POST['id_type']."'";
        $result = pg_query($query) or die('Échec de la requête : ' . pg_last_error()); 
        $res = pg_fetch_array ($result,  0, PGSQL_NUM);
        $stri= implode(",", $res);
        echo "$stri";
    }
}


function getdatee(){
    $ficherVisi = "date.txt";
    $fic = fopen('date.txt', "r+");
    $num = fgets($fic);
    fclose($fic);
    return $num;
}

/* Obtenir les dpcts effectués par l'adversaire au dernier tour */
/* JOB = 13 */
// ARG POST : emailadv 
function getdeplacement(){  
                   
    $date=getdatee();
    //echo "$date";
    //$query="SELECT n_pos_x,n_pos_y,id_bateau FROM deplacerbateau where email='email1' AND datedepbateau>'$date'";
    $query="SELECT d.n_pos_x,d.n_pos_y,d.id_bateau, t.chemin_type FROM deplacerbateau as d, bateaux as b, typebateaux as t  where email='".$_POST['emailadv']."' AND datedepbateau>'$date' AND d.id_bateau=b.id_bateau AND b.id_type=t.id_type";
    $result = pg_query($query) or die('Échec de la requête : ' . pg_last_error()); 
    $res = pg_fetch_all ($result);
     ob_start(); // empecher d'utiliser stdout
    foreach ( $res as $var ) {
    echo "\n", $var['id_bateau'], ",", $var['n_pos_x'], ",", $var['n_pos_y'], ",",$var['chemin_type'],",";
    $out1= ob_get_contents(); // get le bufferde stdout
}
    ob_end_clean(); // stoper la restriction sur stdout
    echo $out1;
}

/* Obtenir les constructions effectuées par l'adversaire au dernier tour */
/* JOB = 14 */
// ARG POST : emailadv
function getconstruction() {
    $date=getdatee();
    $query="SELECT DISTINCT a.id_bateau,b.posx_bat,b.posy_bat,t.chemin_type FROM actionjoueurbateau as a, bateaux as b, typebateaux as t where a.type_action_joueur_bateau ='ConstruireBateau' AND a.email='".$_POST['emailadv']."' AND a.dateactionjoueurbateau>'$date' AND a.id_bateau=b.id_bateau AND b.id_type=t.id_type";
    $result = pg_query($query) or die('Échec de la requête : ' . pg_last_error()); 
     $res = pg_fetch_all ($result);
    ob_start(); // empecher d'utiliser stdout
    foreach ( $res as $var ) {
    echo "\n", $var['id_bateau'], ",", $var['posx_bat'], ",", $var['posy_bat'], ",",$var['chemin_type'],",";
    $out1= ob_get_contents(); // get le bufferde stdout
}
    ob_end_clean(); // stoper la restriction sur stdout
    echo $out1;
}

function attaquerbat($id_bateau_cible, $id_bateau_attaquant){ 
    $rd=rand(1,4); // rand entre 1 et 4
    $dm=4; // DMG max
    echo "RD : $rd ";
    //POINT ATTAQUE BATEAU ATTAQUANT
    $getpabat="SELECT  	degats_type FROM typebateaux as t, bateaux as b WHERE t.id_type=b.id_type and b.id_bateau=$id_bateau_attaquant";
    $result = pg_query($getpabat) or die('Échec de la requête : ' . pg_last_error()); 
    $res = pg_fetch_array ($result,  0, PGSQL_NUM);
    $pa=$res[0];// points d'attaque
    // POINT DE VIE BATEAU CIBLE
    $getpvbat="SELECT vie_bat FROM bateaux where id_bateau='".$id_bateau_cible."'";
    $result = pg_query($getpvbat) or die('Échec de la requête : ' . pg_last_error()); 
    $res = pg_fetch_array ($result,  0, PGSQL_NUM);
    $pv=$res[0];
  
    //PV perdu =[(PA * (D/DM)) - (R)]
    echo "PA : $pa P";
    $pvp=($pa * ($rd/$dm)); // 
    echo "$pvp";
    if($pv<$pvp || $pv==$pvp) { // SI le bateau va etre detruit suite à l'attaque :
        
        // update de bateau avec vie à 0
        $setpvbat="UPDATE bateaux SET vie_bat=0  WHERE id_bateau =$id_bateau_cible";
        $res=pg_query($setpvbat);
        $verif = pg_affected_rows($res);
        if ($verif!=0){ //On update dans attaquer bateau :
            // on chope l email de l'attaquant
            $query="SELECT email FROM possederbateaux WHERE id_bateau='".$id_bateau_attaquant."'";
            $result = pg_query($query) or die('Échec de la requête : ' . pg_last_error()); 
            $getemail = pg_fetch_array ($result,  0, PGSQL_NUM);
            // on get la date de l'instant t
            $d=date("Y-m-d");
            $h=date("H:i:s");
            $d.=" ";
            $d.=$h; 
            // on insert dans attaquer bateau
            $queryy="INSERT INTO attaquerbateau VALUES('".$id_bateau_attaquant."','".$d."','".$getemail[0]."','".$id_bateau_cible."')";
            $res=pg_query($queryy);
            $verif = pg_affected_rows($res);
            if ($verif!=0) {
                // on insert dans detruire
                $query="INSERT INTO actionjoueurbateau (dateactionjoueurbateau,email,id_bateau,Type_Action_Joueur_Bateau) VALUES('".$d."','".$getemail[0]."',$id_bateau_cible,'DétruireBateau')";
                $res=pg_query($query);
                $verif = pg_affected_rows($res);
                if($verif!=0) { return 0;} // On dit la function parent de ne pas rentrer dans le if puisque le bato est dejà destroy
            }
        }
    }
    // Si le bateau n'est pas detruit
   else {
        $pv-=$pvp;
        $setpvbat="UPDATE bateaux SET vie_bat=$pv WHERE id_bateau =$id_bateau_cible";
        $res=pg_query($setpvbat);
        $verif = pg_affected_rows($res);
        // on insert dans attaquer bateau
        $d=date("Y-m-d");
            $h=date("H:i:s");
            $d.=" ";
            $d.=$h; 
        $query="SELECT email FROM possederbateaux WHERE id_bateau='".$id_bateau_attaquant."'";
            $result = pg_query($query) or die('Échec de la requête : ' . pg_last_error()); 
            $getemail = pg_fetch_array ($result,  0, PGSQL_NUM);
        $query="INSERT INTO attaquerbateau VALUES('".$id_bateau_attaquant."','".$d."','".$getemail[0]."','".$id_bateau_cible."')";
        $res=pg_query($query);
        $verif = pg_affected_rows($res);
        if ($verif!=0) {
            return 1;
        }
   } 
    
}

/*  */
// ARG : id_bateau_cible - id_bateau_attaquant
function battle(){ 
    $id_bateau_cible=3;
    $id_bateau_attaquant=2;
    $return=attaquerbat($id_bateau_cible, $id_bateau_attaquant);
    if($return==1) // Si le bateau cible n'est pas detruit il attaque à son tour le bateau attaquant
    {
        $tmp=$id_bateau_cible;
        $id_bateau_cible=$id_bateau_attaquant;
        $id_bateau_attaquant=$tmp;
        $return=attaquerbat($id_bateau_cible, $id_bateau_attaquant);
        echo "OK";
    }
    
}

if ($_POST['job']==1){
    insertB();
}
if ($_POST['job']==2){
  bougerbat();
}
if ($_POST['job']==3){
  quicommence($partie);
}
if ($_POST['job']==5){
  tourfini($_POST['email']);
}
if ($_POST['job']==4){
  istourfini($partie);
}
if ($_POST['job']==10){
  putdate();
}
if ($_POST['job']==11){
  getcarrlive();
}
if ($_POST['job']==12){
  getcarr();
}
if ($_POST['job']==13){
  getdeplacement();
}
if ($_POST['job']==14){
  getconstruction();
}
?>
