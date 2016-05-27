<!-- Sandre : 11/05/2015 -->
<?php include('entete_classement.php') ?>
			<div id="search-bar">
				<h2>Recherche</h2>
				<form action="hid.rechercher.php" method="post" id="formulaire">
					<input type="search" placeholder="Entrez un pseudo ici..." name="recherche"/>
					<input id="bouton-search" type="submit" value="Rechercher"/>
				</form>
				<?php
				if(isset($_SESSION['erreur']) && $_SESSION['erreur'] === 1){
					echo("<p style='color: red; text-align:center'>Veuillez renseignez un joueur valide !</p>");
					unset($_SESSION['erreur']);
				} else if(isset($_SESSION['erreur']) && $_SESSION['erreur'] === 2){ /* Si le pseudo est déjà pris ! */
					echo("<p style='color: red; text-align:center'>Veuillez remplir le champ !</p>");
					unset($_SESSION['erreur']);
				}
				?>
			</div>

			<div id="classement-container">
				<h1><img width="40px" height="40px"src="images/barre.png">  Classement <img width="40px" height="40px" src="images/barre.png"></h1>
				<table>
	        <thead>
	            <tr>
	                <th>Pseudo</th>
	                <th>Parties jouées</th>
									<th>Parties gagnées</th>
									<th>Points</th>
	            </tr>
	        </thead>
	        <tbody>

					<tr>
	        <?php
						if(empty($_GET['param'])){
							$base = pg_connect("host=localhost dbname=projet_web user=web_user password=123456")
								or die('Connexion impossible : ' . pg_last_error());
							if(!$base){
								header('Location: http://localhost/tribu.fr/dbb_error.html');
								exit();
							}
							$requetePseudo = "select count(*) from joueurs";
							$resultPseudo = pg_query($requetePseudo) or die('Echec de la requete');
							$total = pg_fetch_array ($resultPseudo, 0, PGSQL_NUM);
							$cpt = 0;

							$classement[4][$total] = array();
							$aide_class[$total] = array();

							while($cpt < $total[0]){
								$requetePseudo = "select pseudo, email from joueurs";
								$resultPseudo = pg_query($requetePseudo);
								$resP = pg_fetch_array ($resultPseudo, $cpt, PGSQL_NUM);

								$requetePJ = "select count(*) from actionjoueurpartie where email = '" . $resP[1] ."' and typeactionjoueurpartie != 'GagnerPartie'";
								$resultPJ = pg_query($requetePJ);
								$resJ = pg_fetch_array ($resultPJ, 0, PGSQL_NUM);

								$requetePG = "select count(*) from actionjoueurpartie where email = '" . $resP[1] ."' and typeactionjoueurpartie = 'GagnerPartie'";
								$resultPG = pg_query($requetePG);
								$resG = pg_fetch_array ($resultPG, 0, PGSQL_NUM);

								$points_totaux = ($resG[0] * 20) - (($resJ[0]-$resG[0]) * 5);

								$classement[0][$cpt] = $resP[0];
								$classement[1][$cpt] = $resJ[0];
								$classement[2][$cpt] = $resG[0];
								$classement[3][$cpt] = $points_totaux;
								$aide_class[$cpt] = $points_totaux;
								$cpt++;
							}

							$cpt=0;

							arsort($aide_class);

							foreach ($aide_class as $key => $val) {
								echo "<td> ".$classement[0][$key]."</td>";
								echo "<td> ".$classement[1][$key]."</td>";
								echo "<td> ".$classement[2][$key]."</td>";
								echo "<td> ".$classement[3][$key]."</td>";

								echo "</tr>";
							}
						} else {
							$base = pg_connect("host=localhost dbname=projet_web user=web_user password=123456")
								or die('Connexion impossible : ' . pg_last_error());
							if(!$base){
								header('Location: http://localhost/tribu.fr/dbb_error.html');
								exit();
							}
							$classement[4][1] = array();
							$requetePseudo = "select pseudo, email from joueurs where pseudo='".$_GET['param']."'";
							$resultPseudo = pg_query($requetePseudo);
							$resP = pg_fetch_array ($resultPseudo, 0, PGSQL_NUM);

							$requetePJ = "select count(*) from actionjoueurpartie where email = '" . $resP[1] ."' and typeactionjoueurpartie != 'GagnerPartie'";
							$resultPJ = pg_query($requetePJ);
							$resJ = pg_fetch_array ($resultPJ, 0, PGSQL_NUM);

							$requetePG = "select count(*) from actionjoueurpartie where email = '" . $resP[1] ."' and typeactionjoueurpartie = 'GagnerPartie'";
							$resultPG = pg_query($requetePG);
							$resG = pg_fetch_array ($resultPG, 0, PGSQL_NUM);

							$points_totaux = ($resG[0] * 20) - (($resJ[0]-$resG[0]) * 5);

							$classement[0][1] = $resP[0];
							$classement[1][1] = $resJ[0];
							$classement[2][1] = $resG[0];
							$classement[3][1] = $points_totaux;

							echo "<td> ".$classement[0][1]."</td>";
							echo "<td> ".$classement[1][1]."</td>";
							echo "<td> ".$classement[2][1]."</td>";
							echo "<td> ".$classement[3][1]."</td>";
							echo "</tr>";
							echo "</tbody>";
							echo "</table>";
							echo "<br><a href="."classement.php"."><input type='submit' value='Revenir au Classement Général'/></a>";
						}
          ?>
			</div>
			<!-- <iframe width="0" height="0" src="https://www.youtube.com/embed/Yk_Gn4so5LE?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe> -->
		</div>
  </body>
<?php include('footer.php') ?>
</html>
