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
							<h1 > <?php echo($nom); ?> </h1><br/>
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
									echo("<a href='connexion.php'><input id='bouton-search' type='submit' value='Déconnexion'/></a>");
								}
								?>
							<p>
								<?php echo("Date de création : " .$date ."<br/>"); ?>
								<?php echo($region); ?><br/>
								<?php echo("Nombre de victoires : " . $nbVictoire); ?><br/>
								<?php echo("Nombre de parties : " . $nbPartie); ?><br/>
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

							echo("</select><br><input id ='bouton-search' type='submit' value='Voir Profil' /></p></form>");

							}
							else
								echo("<p>Votre liste d'amis est vide !</p>");

							?>
					</div>
			</div>

			<div id="profil-bottom">
				<!-- Contient le dernier carnet de bord -->
				<div id="profil-bottom-left">
					<!-- Liste affichage dynamique des 3 derniers carnets de bord -->

					<?php
						/* Dans cette balise on interrogera la BDD pour demander les derniers carnets de bord */
					?>

				</div>

				<!-- Contient les trophées -->
				<div id="profil-bottom-right">
					<!-- Liste affichage dynamique des trophées-->
                    <!--<img src="hid.graph.php">-->
                <!--    <img src='pChart/img/graph.png' alt='Avatar'/>-->
					<h1>Vos Statistiques</h1>
					<?php
                    if(isset($_GET['pseudo'])){$nom=$_GET['pseudo'];}
                    else {$nom=$_SESSION['email'];}
						/* Dans cette balise on interrogera la BDD pour demander les trophées du joueur $nom */
                    include("pChart/class/pData.class.php");
                    include("pChart/class/pDraw.class.php");
                    include("pChart/class/pPie.class.php");
                    include("pChart/class/pImage.class.php");
                     $dbconn = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
                            or die('Connexion impossible : ' . pg_last_error());

                    $max="SELECT COUNT(*) from actionjoueurpartie where email='".$nom."' and TypeActionJoueurPartie in('CreerPartie','RejoindrePartie')";
                    $result = pg_query($max) or die('Échec de la requête : ' . pg_last_error());
                    $res = pg_fetch_array ($result, 0, PGSQL_NUM);
                    $max=$res[0];
                    $gan="SELECT COUNT(*) from actionjoueurpartie where email='".$nom."' AND TypeActionJoueurPartie='GagnerPartie'";
                    $result = pg_query($gan) or die('Échec de la requête : ' . pg_last_error());
                    $res = pg_fetch_array ($result, 0, PGSQL_NUM);
                    $gan=$res[0];
                    $per=$max-$gan;
                    echo "$per=$max-$gan nom du select : $nom";
                    /*if($max==0) {$max==0 
                               // echo "<p> Vous n'avez toujours pas joué de partie pour vous afficher vos statistiques </p>";
                                }
                    */

                    //else {
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

                    /* Draw a solid background */
                    $Settings = array("R"=>85, "G"=>85, "B"=>85, "Dash"=>32, "DashR"=>85, "DashG"=>85, "DashB"=>85);
                    $myPicture->drawFilledRectangle(0,0,700,230,$Settings);

                    /* Draw a gradient overlay */
                    //$Settings = array("StartR"=>209, "StartG"=>150, "StartB"=>231, "EndR"=>111, "EndG"=>3, "EndB"=>138, "Alpha"=>50);
                    $myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
                    $myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100));

                    /* Add a border to the picture */
                    $myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));

                    /* Write the picture title */ 
                    $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/UglyQua.ttf","FontSize"=>10));
                    $myPicture->drawText(10,19,"Vos statistiques",array("R"=>255,"G"=>255,"B"=>255));

                    /* Set the default font properties */ 
                    $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/UglyQua.ttf","FontSize"=>11,"R"=>80,"G"=>80,"B"=>80));

                    /* Create the pPie object */ 
                    $PieChart = new pPie($myPicture,$MyData);

                    /* Define the slice color */
                    $PieChart->setSliceColor(0,array("R"=>22,"G"=>149,"B"=>22));
                    $PieChart->setSliceColor(1,array("R"=>155,"G"=>25,"B"=>25));
                    //$PieChart->setSliceColor(2,array("R"=>97,"G"=>113,"B"=>63));

                    /* Draw a simple pie chart */ 
                    //$PieChart->draw3DPie(120,125,array("SecondPass"=>FALSE));

                    /* Draw an AA pie chart */ 
                    //$PieChart->draw3DPie(340,125,array("DrawLabels"=>TRUE,"Border"=>TRUE));

                    /* Enable shadow computing */ 
                    //$myPicture->setShadow(TRUE,array("X"=>3,"Y"=>3,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

                    /* Draw a splitted pie chart */ 
                    $PieChart->draw3DPie(100,125,array("WriteValues"=>TRUE,"DataGapAngle"=>10,"DataGapRadius"=>6,"Border"=>TRUE));

                    /* Write the legend */
                    $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/UglyQua.ttf","FontSize"=>10));
                    $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20));
                    //$myPicture->drawText(120,200,"Single AA pass",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));
                    $myPicture->drawText(100,200,"Nombre de parties : $max",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));

                    /* Write the legend box */ 
                    $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/UglyQua.ttf","FontSize"=>10,"R"=>255,"G"=>255,"B"=>255));
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
