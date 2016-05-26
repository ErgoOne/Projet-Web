<!-- Sandre : 11/05/2016 -->
<!-- Alexis : 12/05/2016 -->
<?php include('entete_connexion.php') ?>
		<div id="signin-frame">

	            <div id="signin-left"> <!-- Inscription !-->
	              <h1>Inscription</h1>
	              <!-- Placement du formulaire d'inscription !-->
	              <div id="signin--left">
	                <p id="texte-inscription">
		                Pseudonyme : <br/>
		                Mot de passe : <br/>
		                Confirmation du <br/>mot de passe : <br/>
		                Adresse mail : <br/>
		                Région : <br/>
	                </p>

	                <?php
		              	if(isset($_SESSION['erreur']) && $_SESSION['erreur'] === 1){ /* Si un des champs n'est pas remplit */
		              		echo("<p style='color: red'>Veuillez remplir <br/>TOUT les champs !</p>");
		              		unset($_SESSION['erreur']);
	             	 	}
	            	  	else if(isset($_SESSION['erreur']) && $_SESSION['erreur'] === 2){ /* Si le pseudo est déjà pris ! */
		              		echo("<p style='color: red'>Le pseudonyme<br/>est déjà pris !</p>");
		              		unset($_SESSION['erreur']);
             			}
             			else if(isset($_SESSION['erreur']) && $_SESSION['erreur'] === 3){ /* Si le mail est déjà utilisé ! */
		              		echo("<p style='color: red'>L'adresse mail<br/>est déjà prise !</p>");
		              		unset($_SESSION['erreur']);
             			}
             			else if(isset($_SESSION['erreur']) && $_SESSION['erreur'] === 4){ /* Si le mail est déjà utilisé ! */
		              		echo("<p style='color: red'>Les mots de passe<br/>ne correspondent pas !</p>");
		              		unset($_SESSION['erreur']);
             			}
             			else if(isset($_SESSION['erreur']) && $_SESSION['erreur'] === 7){ /* Si le mail est déjà utilisé ! */
		              		echo("<p style='color: red'>Le format de l'adresse<br/>mail n'est pas valide !</p>");
		              		unset($_SESSION['erreur']);
             			}
             			else if(isset($_SESSION['erreur']) && $_SESSION['erreur'] === 8){
		              	echo("<p style='color: red'>Veuillez vous inscrire<br/>avant d'acceder à mon compte.</p>");
		              	}
		            ?>

	              </div>

	              <div id="signin--right">
	                <form method="post" action="hid.inscription.php"> <!-- TODO ! Renvoie vers une page de gestion !-->

	                  <p>
	                    <input type="text" name="pseudo"/><br/>
	                    <input type="password" name="password"/><br/>
	                    <br/><input  type="password" name="confPassword"/><br/>
	                    <input type="text" name="mail"/><br/>

	                    <select name="region">
	                        <option value="Amerique du nord">Amérique du Nord</option>
	                        <option value="Amerique du sud">Amérique du Sud</option>
	                        <option value="Afrique">Afrique</option>
	                        <option value="Asie">Asie</option>
	                        <option value="Europe de l Est">Europe de l'Est</option>
	                        <option value="Europe de l Ouest">Europe de l'Ouest</option>
	                        <option value="Proche et moyen Orient">Proche et Moyen Orient</option>
	                    </select>
	                    <br/>
	                    <input id="signin-valid" type="submit" value="Inscription" />
	                  </p>
	                </form>
	              </div>
	            </div>

	            <div id="signin-right"> <!-- Connexion !-->
	              <h1>Connexion</h1>

	              <div id="signin--left">
	                <p>
	                Pseudonyme : <br/>
	                Mot de passe : <br/>
	                </p>

	                <?php
		              if(isset($_SESSION['erreur']) && $_SESSION['erreur'] === 5){
		              	echo("<p style='color: red'>Veuillez remplir <br/>TOUT les champs !</p>");
		              	unset($_SESSION['erreur']);
		              }
		              else if(isset($_SESSION['erreur']) && $_SESSION['erreur'] === 6){
		              	echo("<p style='color: red'>Identifiants <br/>incorrects !</p>");
		              	unset($_SESSION['erreur']);
		              }
		              else if(isset($_SESSION['erreur']) && $_SESSION['erreur'] === 8){
		              	echo("<p style='color: red'>Veuillez vous connecter<br/>avant d'acceder à mon compte.</p>");
		              	unset($_SESSION['erreur']);
		              }
		            ?>
	              </div>

	              <!-- Placement du formulaire de connexion !-->
	              <div id="signin--right">
	                <form method="post" action="hid.connexion.php"> <!-- Renvoie vers une page de gestion !-->

	                  <p>
	                    <input type="text" name="pseudo"/><br/>
	                    <input type="password" name="password"/><br/>
	                    <input id="signin-valid" type="submit" value="Connexion" />
	                  </p>
	                </form>
	              </div>

	            </div>
	        </div>
	      </div>
				<iframe width="0" height="0" src="https://www.youtube.com/embed/Yk_Gn4so5LE?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>
		</div>
  </body>
<?php include('footer.php') ?>
</html>
