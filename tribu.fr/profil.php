<?php include('entete_profil.php');
//require('hid.graph.php');
?>

			<?php

				$base = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
		        or die('Connexion impossible : ' . pg_last_error());

				if(!$base){
					/* redirection si base de données innacessible */
					header('Location: ./dbb_error.html');
					exit();
				}

				if(!isset($_GET["pseudo"])) /*Si l'utilisateur n'a pas précisé le pseudo alors il va sur sa page.*/
                {$nom = $_SESSION['pseudo'];
                $_SESSION['graphuser']=$nom; }
                    

				else
                {$nom = $_GET["pseudo"];
                    $_SESSION['graphuser']=$nom; }
               
				/* Récupération de la region, la date d'inscription, l'id avater et de l'email depuis la base de données */
								$requete = "select region, date_inscription, id_avatar, email from joueurs where pseudo='$nom'";
                $result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
                $res = pg_fetch_array ($result, 0, PGSQL_NUM);

                $region = $res[0];
                $date = $res[1];
                $id_avatar = "images/avatar/" . $res[2] . ".jpg";
                $email = $res[3];


                /* On va compter le nombre de victoire du joueur ! */
                $requete = "select count(*) from actionjoueurpartie where email = '" . $email ."' and typeactionjoueurpartie = 'GagnerPartie'"; /* Création et envoie de la requête ! */
                $result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
                $res = pg_fetch_array ($result, 0, PGSQL_NUM);

                $nbVictoire = $res[0];

                /* On va compter le nombre de partie du joueur ! */
                $requete = "select count(*) from actionjoueurpartie where email = '" . $email ."' and typeactionjoueurpartie != 'GagnerPartie'"; /* Création et envoie de la requête ! */
                $result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
                $res = pg_fetch_array ($result, 0, PGSQL_NUM);

                $nbPartie = $res[0];

            ?>

			<div id="profil-top">
					<!-- Contient photo/DateInscription/Pseudo/Région/Nombre de parties et victoires-->
					<div id="profil-top-left">
						<div id="profil-top-left-img">
							<?php
								echo("<img height='200px' width='200px' src='".$id_avatar."' alt='' />");
							?>

						</div>

						<div id="profil-top-left-text">
							<h1 > <?php echo($nom); ?> </h1>
							<p>
								<?php echo("Date de création : " .$date ."<br/>"); ?>
								<?php echo($region); ?><br/>
								<?php echo("Nombre de victoires : " . $nbVictoire); ?><br/>
								<?php echo("Nombre de parties : " . $nbPartie); ?><br/><br>
                                <!--Affichage des boutons Déconnexion et Ajouter Amis ou rien suivant le cas-->
                                <?php
                                    $estChezLui = $_SESSION['pseudo'] == $nom;
                                    $sontAmis = true; /* Variable booléenne */

                                    if(!$estChezLui){
                                        /*Visite de la base de données, si un lien d'amitié existe déjà : ne rien afficher.*/
                                            if($email > $_SESSION['email']){
                                                $J1 = $email;
                                                $J2 = $_SESSION['email'];
                                            } else {
                                                $J1 = $_SESSION['email'];
                                                $J2 = $email;
                                            }

                                     $requete = "select count(*) from etreAmis where email_j1='".$J1."' and email_j2='".$J2."'";
                                        $result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
                                        $res = pg_fetch_array ($result, 0, PGSQL_NUM);
                                        if($res[0] < 1){
                                                $sontAmis = false;
                                        }
                                    }

                                    if(!$sontAmis){
                                        $_SESSION['aj_nom'] = $nom;
                                        $_SESSION['aj_J1'] = $J1;
                                        $_SESSION['aj_J2'] = $J2;
                                        echo("<form method='post' action='hid.ajouterAmi.php'><input id='bouton-search' type='submit' value='Ajouter en Amis'/></form>");
                                    }

                                    if($estChezLui){ /* Si un utilisateur visite sa page */
                                        echo("<a href='connexion.php' style='margin-left:28%;'><input id='bouton-search' type='submit' value='Déconnexion'/></a>");
                                    }
                                ?>
							</p>
						</div>
					</div>

					<!-- Contient la liste d'amis -->
					<div id="profil-top-right">
						<h1>Liste d'amis</h1>


						<!-- Affichage de la liste d'amis grâce à un formulaire -->
						<?php

						$cpt = 0;

						/* Compter si l'utilisateur possède des amis */
						$requete = "select count(*) from etreamis where email_j1 = '" . $email ."' or email_j2 = '" . $email ."'"; /* On recherche si le mail est déjà utilisé */
                		$result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
                		$res = pg_fetch_array ($result, 0, PGSQL_NUM); // On met le resultat dans un tableau

                		if($res[0] > 0){ /* si il a des amis */
							$requete = "select * from etreamis where email_j1 = '" . $email ."' or email_j2 = '" . $email ."'"; /* Création et envoie de la requête ! */
	                		$result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
	                		if($result != NULL )
	                			$res = pg_fetch_array ($result, $cpt, PGSQL_NUM);
	                		echo("<form method='get' action='profil.php'><p><select name='pseudo'>");

	                		while($res != null){

	                			if($res[0] != $email)
	                				$amis = $res[0];
	                			else
	                				$amis = $res[1];

								$requete = "select pseudo from joueurs where email = '" . $amis ."'"; /* Création et envoie d'une seconde requête ! */
								$result2 = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
								$res2 = pg_fetch_array ($result2, 0, PGSQL_NUM);
								$amis = $res2[0];

	            				echo("<option value='".$amis."'>".$amis."</option>");

	            				$cpt++;
	                			$res = pg_fetch_array ($result, $cpt, PGSQL_NUM);
	                		}

							echo("</select><br><br><input style='margin-left:5%;' id ='bouton-search' type='submit' value='Voir Profil' /></p></form>");

							}
							else
								echo("<p style='margin-left: 21%;'>Votre liste d'amis est vide !</p>");

							?>
					</div>
			</div>

			<div id="profil-bottom">
				<!-- Contient le dernier carnet de bord -->
				<div id="profil-bottom-left">
                    <h1>Carnet de Bord</h1>
					<!-- Liste affichage dynamique des 3 derniers carnets de bord -->

					<?php
						/* Dans cette balise on interrogera la BDD pour demander les derniers carnets de bord */
              $base = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
		        or die('Connexion impossible : ' . pg_last_error());
    
    
    $sql="SELECT email FROM joueurs  where Pseudo='$nom'";
//                    echo "$nom";
     $result = pg_query($sql) or die('Échec de la requête : ' . pg_last_error()); 
     $res = pg_fetch_array ($result,  0, PGSQL_NUM);
     $em=$res[0];
//                    echo "$em";
     $sql="SELECT MAX(id_partie) FROM actionjoueurpartie  where (typeactionjoueurpartie = 'GagnerPartie' AND typeactionjoueurpartie IS NOT NULL)  and email='" . $em . "'";
     $result = pg_query($sql) or die('Échec de la requête : ' . pg_last_error()); 
     $res = pg_fetch_array ($result,  0, PGSQL_NUM);
     
     $parmax=$res[0];
//                    echo "$parmax";
        if (!empty($parmax))
        {
    
     $sql="SELECT dateactionjoueurpartie FROM actionjoueurpartie  where typeactionjoueurpartie = 'RejoindrePartie' and id_partie=$parmax";
     $result = pg_query($sql) or die('Échec de la requête : ' . pg_last_error()); 
     $res = pg_fetch_array ($result,  0, PGSQL_NUM);
     $datedeb=$res[0];
     
   
    $sql="SELECT dateactionjoueurpartie FROM actionjoueurpartie  where typeactionjoueurpartie = 'GagnerPartie' and id_partie=$parmax";
     $result = pg_query($sql) or die('Échec de la requête : ' . pg_last_error()); 
     $res = pg_fetch_array ($result,  0, PGSQL_NUM);
     $datefin=$res[0];
    
     $sql ="SELECT '$datefin'::timestamp - '$datedeb'::timestamp";
     $result = pg_query($sql) or die('Échec de la requête : ' . pg_last_error()); 
     $res = pg_fetch_array ($result,  0, PGSQL_NUM);
     $diff=$res[0];
     $diff=explode(":",$diff);
//     echo $diff[2];
    
     $sql="SELECT COUNT(id_bateau) FROM possederbateaux WHERE email='$email' AND id_partie=$parmax";
     $result = pg_query($sql) or die('Échec de la requête : ' . pg_last_error()); 
     $res = pg_fetch_array ($result,  0, PGSQL_NUM);
     $batx=$res[0];
    
     $sql="SELECT COUNT(*) FROM actionjoueurbateau  where Type_Action_Joueur_Bateau='DétruireBateau' and email!='$em'";
     $result = pg_query($sql) or die('Échec de la requête : ' . pg_last_error()); 
     $res = pg_fetch_array ($result,  0, PGSQL_NUM);
     $batperdu=$res[0];
    
     $sql="SELECT COUNT(*) FROM actionjoueurbateau  where Type_Action_Joueur_Bateau='DétruireBateau' and email='$em' and id_bateau in (SELECT id_bateau FROM possederbateaux WHERE email='$em')";
     $result = pg_query($sql) or die('Échec de la requête : ' . pg_last_error()); 
     $res = pg_fetch_array ($result,  0, PGSQL_NUM);
     $batdet=$res[0];
        
     $sql="SELECT email FROM actionjoueurpartie  where  email!='$email' and id_partie=$parmax";
     $result = pg_query($sql) or die('Échec de la requête : ' . pg_last_error()); 
     $res = pg_fetch_array ($result,  0, PGSQL_NUM);
     $adv=$res[0];
    
     $sql="SELECT Pseudo FROM joueurs  where  email='$adv'";
     $result = pg_query($sql) or die('Échec de la requête : ' . pg_last_error()); 
     $res = pg_fetch_array ($result,  0, PGSQL_NUM);
     $adv=$res[0];
    
     $sql="SELECT  email FROM actionjoueurpartie  where typeactionjoueurpartie='GagnerPartie' and id_partie=$parmax";
     $result = pg_query($sql) or die('Échec de la requête : ' . pg_last_error()); 
     $res = pg_fetch_array ($result,  0, PGSQL_NUM);
     $winner=$res[0];
    
    $sql ="SELECT Pseudo FROM joueurs WHERE email = '$winner'";
    $result = pg_query($sql) or die('Échec de la requête : ' . pg_last_error()); 
     $res = pg_fetch_array ($result,  0, PGSQL_NUM);
     $winner=$res[0];
    echo "<p> <b>Gagnant :</b> $winner<br><b>Adversaire :</b> $adv<br><b>Date :</b> $datedeb<br><b>Durée :</b> $diff[2] min<br><b>Nb bateaux :</b> $batx<br><b>Bateaux Perdus :</b>  $batperdu<br><b>Bateaux Detruits :</b> $batdet<p>";
         
        }
                    else {echo "<p style='margin-left: 19%; margin-top: 19%;'>Vous n'avez toujours pas joué de partie !</p>";}

					?>

				</div>

				<!-- Contient les trophées -->
				<div id="profil-bottom-right">
					<!-- Liste affichage dynamique des trophées-->
                    <!--<img src="hid.graph.php">-->
                <!--    <img src='pChart/img/graph.png' alt='Avatar'/>-->
					<h1>Vos Statistiques</h1>
					<?php

					/* Dans cette balise on interrogera la BDD pour demander les trophées du joueur $nom */
                    include("pChart/class/pData.class.php");
                    include("pChart/class/pDraw.class.php");
                    include("pChart/class/pPie.class.php");
                    include("pChart/class/pImage.class.php"); 
                    $max = $nbPartie;
                    $gan = $nbVictoire;
                    $per = $max - $gan;
                    /* Create and populate the pData object */
                    $MyData = new pData();   
                    // Les valeurs
                    $MyData->addPoints(array($gan,$per),"ScoreA");  
                    $MyData->setSerieDescription("ScoreA","Application A");

                    /* Define the absissa serie */
                    $MyData->addPoints(array("Gagne","Perdu"),"Labels");
                    $MyData->setAbscissa("Labels");

                    /* Create the pChart object */
                    $myPicture = new pImage(200,230,$MyData,TRUE);


                    /* Set the default font properties */ 
                    $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/UglyQua.ttf","FontSize"=>11,"R"=>0,"G"=>0,"B"=>0));

                    /* Create the pPie object */ 
                    $PieChart = new pPie($myPicture,$MyData);

                    /* Define the slice color */
                    // Couleur des elements du cam : 
                    
                    $PieChart->setSliceColor(1,array("R"=>165,"G"=>42,"B"=>42));
                    $PieChart->setSliceColor(0,array("R"=>30,"G"=>144,"B"=>255));
                    //$PieChart->setSliceColor(2,array("R"=>97,"G"=>113,"B"=>63));

                    /* Draw a splitted pie chart */ 
                    $PieChart->draw3DPie(100,125,array("WriteValues"=>TRUE,"DataGapAngle"=>10,"DataGapRadius"=>6,"Border"=>TRUE));

                    /* Write the legend */
                    $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/UglyQua.ttf","FontSize"=>11));
                    $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20));
                    //$myPicture->drawText(120,200,"Single AA pass",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));
                    $myPicture->drawText(100,200,"Nombre de parties : $max",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));

                    /* Write the legend box */ 
                    $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/UglyQua.ttf","FontSize"=>12,"R"=>0,"G"=>0,"B"=>0));
                    $PieChart->drawPieLegend(55,40,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));
                    $ficherVisi = "nb.txt";
                  $fic = fopen('nb.txt', "r+");
                  $num = fgets($fic);
                  $compteur = $num;
                  $compteur += 1;
                  file_put_contents('nb.txt', ""); // fichier à zero
                  rewind($fic); // Positionnement en haut du fic
                  fwrite($fic, $compteur);
                  fclose($fic);
                    /* Render the picture (choose the best way) */
                    if (file_exists("pChart/img/graph_$num.png")) {
                    unlink("pChart/img/graph_$num.png");}
                    $myPicture->Render("pChart/img/graph_$num.png");
                   
                 echo "<img id='img-stat' src='pChart/img/graph_$num.png' alt='Avatar'/>";
					?>

				</div>
			</div>
			<!-- <iframe width="0" height="0" src="https://www.youtube.com/embed/Yk_Gn4so5LE?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>-->
	</div>
</body>
<?php include('footer.php'); ?>
</html>
