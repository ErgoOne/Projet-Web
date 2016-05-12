<!-- Sandre : 11/05/2015 -->
<!-- Alexis : 12/05/2016, ajout du corps de la page -->
<?php include('entete_connexion.php') ?>
		<div id="signin-frame">

	            <div id="signin-left"> <!-- Inscription !-->
	              <h2>Inscription</h2>
	              <!-- Placement du formulaire d'inscription !-->
	              <div id="signin--left">
	                <p>
	                Pseudonyme : <br/>
	                Mot de passe : <br/>
	                Confirmation du <br/>mot de passe : <br/>
	                Adresse mail : <br/>
	                Région : <br/>
	                </p>
	              </div>

	              <div id="signin--right">
	                <form method="post" action="hid.inscription.php"> <!-- TODO ! Renvoie vers une page de gestion !-->
	                  
	                  <p> <!-- Formulaire inscription -->
	                    <input type="text" name="pseudo"/><br/>
	                    <input type="password" name="password"/><br/>
	                    <br/><input  type="password" name="confPassword"/><br/>
	                    <input type="text" name="mail"/><br/>

	                    <select name="region">
	                        <option value="NorthAm">Amérique du Nord</option>
	                        <option value="SouthAm">Amérique du Sud</option>
	                        <option value="Africa">Afrique</option>
	                        <option value="Asia">Asie</option>
	                        <option value="EstEU">Europe de l'Est</option>
	                        <option value="WestEU">Europe de l'Ouest</option>
	                        <option value="PMOrient">Proche et Moyen Orient</option>
	                    </select>
	                    <br/>
	                    <input id="signin-valid" type="submit" value="Inscription" /> 
	                  </p>
	                </form>
	              </div>
	            </div>

	            <div id="signin-right"> <!-- Connexion !-->
	              <h2>Connexion</h2>

	              <div id="signin--left">
	                <p>
	                Pseudonyme : <br/>
	                Mot de passe : <br/>
	                </p>
	              </div>

	              <!-- Placement du formulaire de connexion !-->
	              <div id="signin--left">
	                <form method="post" action="hid.inscription.php"> <!-- Renvoie vers une page de gestion !-->
	                  
	                  <p> <!--Formulaire -->
	                    <input type="text" name="pseudo"/><br/>
	                    <input type="password" name="password"/><br/>
	                    <input id="signin-valid" type="submit" value="Connexion" /> 
	                  </p>
	                </form>
	              </div>

	              
		<!-- 12/05 Balise de php en cours d'intégration, gestion des erreurs -->
	            <?php
	              if(isset($_POST['ERROR'])) 
	                echo("<h1></h1>"); 
	            ?>

	            </div>
	        </div>
      	    </div>
	</div>
  </body>
<?php include('footer.php') ?>
</html>
