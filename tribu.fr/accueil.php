<?php include('entete_accueil.php'); ?>
      <div id="first-accueil-container">
        <div id="video-accueil-container">
          <iframe width="530" height="300" src="https://www.youtube.com/embed/ytWz0qVvBZ0" frameborder="0" allowfullscreen></iframe>
        </div>
        <div onmouseover="playclip();" id="bouton-accueil-container">
          <a id="button-rejoin" href="choix_salle.php"><img id="logo-bouton-accueil" src="images/PetitLogo.png"/>   Rejoignez dès maintenant TRIBU</a>
        </div>
        <audio>
          <source src="sounds/cannon.mp3">
        </audio>
      </div>
      <div id="description-accueil-container">
        <h1>Description</h1>
        <p>Rejoignez l'aventure Tribu dès maintenant. Plongez dans un monde de batailles navales épiques et prenez la barre des navires légendaires du début du XVIIIe siècle dans un jeu en ligne free to play en défiant les marins les plus féroces et aguerris.
        <br><br>Battez-vous en haute mer dans des combats intenses et stratégiques afin de devenir le meilleur marin du monde. Pour rejoindre l'aventure rien de plus simple, <b>inscrivez-vous gratuitement</b> et en avant moussaillon !</p>
      </div>
      <div id="screenshot-accueil-container">
        <h1>Captures d'écran</h1>
        <ul>
             <li>
                  <a>
                  <img src="images/screenshot1.jpg" alt="" />
                  <strong>Screenshot 1</strong>
                  </a>
             </li>
             <li>
                  <a>
                  <img src="images/screenshot2.jpg" alt="" />
                  <strong>Screenshot 2</strong>
                  </a>
             </li>
             <li>
                  <a>
                  <img src="images/screenshot3.jpg" alt="" />
                  <strong>Screenshot 3</strong>
                  </a>
             </li>
             <li>
                  <a>
                  <img src="images/screenshot4.jpg" alt="" />
                  <strong>Screenshot 4</strong>
                  </a>
             </li>
        </ul>
        <!-- <iframe width="0" height="0" src="https://www.youtube.com/embed/QHWAzBZ2hDA?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe> -->
      </div>
		</div>
  </body>
<?php include('footer.php'); ?>
</html>
