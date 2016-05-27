<?php 
	function go_partie($id_partie){
		header("Location: http://localhost/tribu.fr/accueil.php");
		exit();
	}
?>

<?php include('entete_choix_salle.php'); ?>

	<div id="choose_frame">

		<h1>Rejoindre une partie !</h1>

		<div id="tableau_partie">
			<table id = "absolute_table" bgcolor = black>
				<thead>
					<tr id = "titre_colonne">
						<th width=149 nowrap bgcolor="#cc6600">Id_partie</th>
						<th width=149 nowrap bgcolor="#b35900">Capitaine</th>
						<th width=149 nowrap bgcolor="#cc6600">Date</th>
						<th width=149 nowrap bgcolor="#b35900">Rejoindre</th>
					</tr>
				</thead>
			</table>

			<?php /* connexion à la base de données */
				$base = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
				or die('Connexion impossible : ' . pg_last_error());

				/* On commence par compter le nombre de partie TOTALES */
				$requete = "select count(*) from actionjoueurpartie where id_partie not in (select id_partie from actionjoueurpartie where typeactionjoueurpartie='RejoindrePartie' or typeactionjoueurpartie = 'GagnerPartie')";

				$result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
				$res = pg_fetch_array ($result, 0, PGSQL_NUM);

				$total = $res[0];

				/*$requete = "select id_partie from actionjoueurpartie where typeactionjoueurpartie == 'CreerPartie' INNER JOIN actionjoueurpartie
				where ON table1.column_name=table2.column_name;";  on récupère les données des joueurs */

				$requete = "select id_partie from actionjoueurpartie where id_partie not in (select id_partie from actionjoueurpartie where typeactionjoueurpartie='RejoindrePartie' or typeactionjoueurpartie = 'GagnerPartie')";

				$result = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
				$res = pg_fetch_array ($result, 0, PGSQL_NUM);
				$i = 0;
				 
				if($total){
					echo '<table bgcolor = black>';
						echo '<thead>';
							echo '<tr>';
								echo '<th width=149 nowrap>id_partie</th>';
								echo '<th width=149 nowrap>id_ouragan</th>';
								echo '<th width=149 nowrap>nom_type</th>';
								echo '<th width=149 nowrap>submit</th>';
							echo '</tr>'."\n";
						echo '</thead>';

					while($i < $total){

						$res = pg_fetch_array ($result, $i, PGSQL_NUM); /* On passe à la prochaine partie */

						/* On vérifie que la partie n'a pas déjà été remplie par deux joueurs */
						
						/* On va aller chercher le nom du joueur ayant créé la partie et la date de la partie */
						/* On va donc aller chercher dans la table actionjoueurpartie l'email et la date */
						/* Or la actionjoueurpartie a un id unique composé du type, de l'email et de l'id partie */
						$requete = "select email, dateactionjoueurpartie from actionjoueurpartie where id_partie =" . $res[0]; /*$res[0] est l'id de la partie */

						$resultEmailDate = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
						$resED = pg_fetch_array ($resultEmailDate, 0, PGSQL_NUM);
						$email = $resED[0];
						$date = $resED[1];

						$requete = "select pseudo from joueurs where email = '" . $resED[0] ."'"; 
						$resultJ = pg_query($requete) or die('Échec de la requête : ' . pg_last_error());
						$resJ = pg_fetch_array ($resultJ, 0, PGSQL_NUM);

						echo '<tbody>';
							echo '<tr>';
								echo '<td bgcolor="#EFEFEF">'.$res[0].'</td>'; /* Id_partie */
								echo '<td bgcolor="#EFEFEF">'.$resJ[0].'</td>'; /* Nom du créateur */
								echo '<td bgcolor="#EFEFEF">'.$date.'</td>'; /* date */

								echo "<td bgcolor='#EFEFEF'><form method='post' action='hid.rejoindrejeu.php'>Aller à la <input name='Rejoindre' type='submit' value=". $res[0] ." /></form></td>"; /* rejoindre */
							echo '</tr>'."\n";
						$i++;

					}
						echo '</tbody>';
					echo '</table>'."\n";

				}
				else echo 'Pas d\'enregistrements dans cette table...'; 
			?>
		</div>

		<a href="hid.creerjeu.php"><h1>Créer une partie !</h1></a>

		<iframe width="0" height="0" src="https://www.youtube.com/embed/Yk_Gn4so5LE?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>
	</div>
</body>
<?php include('footer.php') ?>
</html>


