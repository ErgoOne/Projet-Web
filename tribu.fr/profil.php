<?php include('entete_profil.php'); ?>
		
			<?php
				if(!isset($_GET["pseudo"])){ /*Si l'utilisateur n'a pas précisé le pseudo alors il va sur sa page.*/
					$nom = $_SESSION["pseudo"];
				}
				else
					$nom = $_GET["pseudo"];
			?>

			<div id="profil-top">
					<!-- Contient photo/DateInscription/Pseudo/Région/Nombre de parties et victoires-->
					<div id="profil-top-left">
						<div id="profil-top-left-img">
							<?php
								echo("<img height='250px' width='250px' src='images/screenshot3.jpg' alt='' />");
							?>
							
						</div>

						<div id="profil-top-left-text">
							<p> Pseudo<br/>
								Région<br/>
								Nombre de victoires : X<br/>
								Nombre de parties : Y<br/></p>
						</div>
					</div>

					<!-- Contient la liste d'amis -->
					<div id="profil-top-right">
						<h2>Liste d'amis</h2>


						<!-- Liste affichage dynamique d'amis-->
						<?php
							/* Dans cette balise on interrogera la BDD pour demander qui est amis avec l'utilisateur ayant le pseudo $nom */
							/* Voir si on peut utiliser une liste déroulante */
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
