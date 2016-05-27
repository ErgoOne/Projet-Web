<!-- Sandre : 11/05/2015 -->
<?php include('entete_classement.php') ?>
			<div id="search-bar">
				<h2>Recherche</h2>
				<form action="" id="formulaire">
					<input class="champ" type="search" placeholder="Entrez un pseudo ici..."/>
					<input class="bouton" type="button" value="OK"/>
				</form>
			</div>

			<div id="classement-container">
				<h1><img width="40px" height="40px"src="images/barre.png">  Division 1  <img width="40px" height="40px" src="images/barre.png"></h1>
				<table>
	        <thead>
	            <tr>
	                <th>Pseudo</th>
	                <th>Parties jouées</th>
									<th>Parties gagnées</th>
									<th>Classement</th>
	            </tr>
	        </thead>
	        <tbody>
	        <?php
						$base = pg_connect("host=localhost dbname=projet_web user=web_user password=123456")
							or die('Connexion impossible : ' . pg_last_error());
						if(!$base){
							header('Location: http://localhost/tribu.fr/dbb_error.html');
							exit();
						}
						$requetePseudo = "select pseudo, motdepasse from joueurs";
						$requetePJ = "";
						$requetePG = "";
						$requeteC = "";
						$resultPseudo = pg_query($requetePseudo);
						$resultPJ = pg_query($requetePJ);
						$resultPG = pg_query($requetePG);
						$resultC = pg_query($requeteC);
						while($row = pg_fetch_array($resultPseudo)) {
          ?>
                <tr>
                    <td><?php echo $row['pseudo'];?></td>
										<td><?php echo $row[''];?></td>
										<td><?php echo $row[''];?></td>
										<td><?php echo $row[''];?></td>
                </tr>

          <?php
            }
          ?>
	        </tbody>
				</table>
			</div>
			<!-- <iframe width="0" height="0" src="https://www.youtube.com/embed/Yk_Gn4so5LE?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe> -->
		</div>
  </body>
<?php include('footer.php') ?>
</html>
