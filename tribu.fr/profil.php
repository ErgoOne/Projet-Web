<?php include('entete_profil.php'); ?>
		
			<?php

				$base = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
		        or die('Connexion impossible : ' . pg_last_error());

				if(!$base){
					/* redirection si base de données innacessible */
					header('Location: http://localhost/tribu.fr/dbb_error.html');
					exit();
				}

				if(!isset($_GET["pseudo"])) /*Si l'utilisateur n'a pas précisé le pseudo alors il va sur sa page.*/
					$nom = $_SESSION['pseudo'];
				
				else
					$nom = $_GET["pseudo"];

				/* Récupération de la region, la date d'inscription, l'id avater et de l'email depuis la base de données */
				$requete = "select region, date_inscription, id_avatar, email from joueurs where pseudo='$nom'"; 
                $result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
                $res = pg_fetch_array ($result, 0, PGSQL_NUM); 
               	
                $region = $res[0];
                $date = $res[1];
                $id_avatar = "images/screenshot" . $res[2] . ".jpg";
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
								echo("<p>".$date."</p>");

								/* RAJOUTER BOUTON DECONNEXION */


							?>
							
						</div> 

						<div id="profil-top-left-text">
							<h1 > <?php echo($nom); ?> </h1><br/>
							<p>	<?php echo($region); ?><br/>
								<?php echo("Nombre de victoires : " . $nbVictoire); ?><br/>
								<?php echo("Nombre de parties : " . $nbPartie); ?><br/>
							</p>
							<a href="connexion.php"><input type='submit' value='Déconnexion'/></a>
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

							echo("</select><input type='submit' value='Valider' /></p></form>");
							
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

					<?php 
						/* Dans cette balise on interrogera la BDD pour demander les trophées du joueur $nom */
					?>

				</div>
			</div>
			<iframe width="0" height="0" src="https://www.youtube.com/embed/Yk_Gn4so5LE?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>
	</div>
</body>
<?php include('footer.php'); ?>
</html>
