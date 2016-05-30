

<!DOCTYPE>

<html>

  <head>
    <meta charset= 'utf-8'>
    <title>Map</title>

        <link rel="stylesheet" href="style.css"/>
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
      
  </head>

<?php include('entete_accueil.php'); ?>

  <div id="partie">
    <?php
      setcookie('id_partie', $_SESSION['id_partie'], time() + 24*3600, null, null, false, true);
      setcookie('email', $_SESSION['email'], time() + 24*3600, null, null, false, true);
      setcookie('email_adversaire', $_SESSION['email_adversaire'], time() + 24*3600, null, null, false, true);
    ?>

    <body onload= "debut()">

      

      <!-- Affiché lorsque le joueur attend que l'autre joueur joue -->
      <div id="bloquer_fenetre"><span id="texte_bloquer">Votre adversaire est en train de jouer...</span></div>

    <div id="cadre-container">

          <div id= "cadre">
 
            <img id= "bateau1" src="images/bateau4.png" alt= "bateau1" onmouseover="afficher_carac_encours(bateau1)", onmouseleave="masquer_caracteristiques_encours()", ondblclick="zoom(bateau1)">
            <img id= "bateau2" src="images/bateau3.png" alt= "bateau2" ondblclick="zoom(bateau2)", onmouseover="afficher_carac_encours(bateau2)", onmouseleave="masquer_caracteristiques_encours()">
            <img id= "pirate1" src="images/Port_pirate.jpg" alt= "pirate">
            <img id= "pirate2" src="images/port_pirate_bas.jpg" alt= "pirate">
            <img id= "port1" src="images/sable.jpg" alt="port1"> 
            <img id= "port2" src="images/sable.jpg" alt="port2"> 
             
            <div id= "grille"><table>
              <thead>
                <tr>
                  <td></td>
                  <td>1</td>
                  <td>2</td>
                  <td>3</td>
                  <td>4</td>
                  <td>5</td>
                  <td>6</td>
                  <td>7</td>
                  <td>8</td>
                  <td>9</td>
                  <td>10</td> 
                  <td>11</td>
                  <td>12</td>
                  <td>13</td>
                  <td>14</td>
                  <td>15</td>
                  <td>16</td>
                  <td>17</td>
                </tr>
              </thead>    
              <tbody>
                <tr>
                  <td>A</td>
                  <td id="A1"></td>
                  <td id="A2"></td>
                  <td id="A3"></td>
                  <td id="A4"></td>
                  <td id="A5"></td>
                  <td id="A6"></td>
                  <td id="A7"></td>
                  <td id="A8"></td>
                  <td id="A9"></td>
                  <td id="A10"></td>
                  <td id="pirate_A11"></td>
                  <td id="pirate_A12"></td>
                  <td id="A13"></td>
                  <td id="A14"></td>
                  <td id="A15"></td>
                  <td id="A16"></td>
                  <td id="A17"></td>
                </tr>
                <tr>
                  <td>B</td>      
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td id="B10"></td>
                  <td id="pirate"></td>
                  <td id="pirate"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>                 
                </tr>     
                <tr>
                  <td>C</td>
                  <td id="case_port1_bord"></td>
                  <td id="case_port1"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td id="C10"></td>
                  <td id="C11"></td>
                  <td id="C12"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>D</td>      
                  <td id="case_port1_bord"></td>
                  <td id="case_port1"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>                  
                </tr>
                <tr>
                  <td>E</td>      
                  <td id="case_port1_bord"></td>
                  <td id="case_port1"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td id="case_port2"></td>                  
                </tr> 
                <tr>
                  <td>F</td>      
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td id="case_port2"></td>                  
                </tr>
                <tr>
                  <td>G</td>      
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td id="case_port2"></td>
                </tr>                         
                <tr>
                  <td>H</td>      
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td id="pirate"></td>
                  <td id="pirate"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr> 
              </tbody>                
            </table></div>

            <div id="caracteristiques_encours"></div>

          </div> 
          
          <div id= "actions_tour">
              
              <img id= "img_gouvernail" src= "images/gouvernail2.png" alt= "gouvernail">

             <div id= "texte_case_pirate">Vous ne pouvez pas vous déplacer sur un port</div>
              <div id="texte_case_occupee">Cette case est déjà occupée</div> 

              <div id= "construire_bateau">
                <img id= "new_bateau" src="images/bateau3.png" onmouseleave="masquer_caracteristiques_type()", onmouseover="afficher_carac_type(1);">
                <input type= "radio" id= "images/bateau3.png" name= "construire" value= "1" checked>
                <img id= "new_bateau" src="images/bateau4.png" onmouseleave="masquer_caracteristiques_type()", onmouseover="afficher_carac_type(2);">
                <input type= "radio" id= "images/bateau4.png" name= "construire" value= "2">             
                <img id= "new_bateau" src="images/bateau2.png" onmouseleave="masquer_caracteristiques_type()", onmouseover="afficher_carac_type(3);">
                <input type= "radio" id= "images/bateau2.png" name= "construire" value= "3">
                <img id= "new_bateau" src="images/bateau5.png" onmouseleave="masquer_caracteristiques_type()", onmouseover="afficher_carac_type(4);">
                <input type= "radio" id= "images/bateau5.png" name= "construire" value= "4">
                <div id="caracteristiques_type"></div><br>
                <br><input type= "button" id= "bouton_construire_bateau" value= "Construire">
              </div>
              
              <div id= "action_bateau">
                      Y : <select name= "Y1" id= "Y_bateau">
                              <option value= "A">A</option>
                              <option value= "B">B</option>    
                              <option value= "C">C</option>
                              <option value= "D">D</option>
                              <option value= "E">E</option>
                              <option value= "F">F</option>
                              <option value= "G">G</option>
                              <option value= "H">H</option>                  
                          </select> 
                      X : <select name= "X1" id= "X_bateau">
                              <option value= "1">1</option>
                              <option value= "2">2</option>    
                              <option value= "3">3</option>
                              <option value= "4">4</option>
                              <option value= "5">5</option>
                              <option value= "6">6</option>
                              <option value= "7">7</option>    
                              <option value= "8">8</option>
                              <option value= "9">9</option>
                              <option value= "10">10</option> 
                              <option value= "11">11</option>    
                              <option value= "12">12</option>
                              <option value= "13">13</option>
                              <option value= "14">14</option>                                                                    
                              <option value= "15">15</option>
                              <option value= "16">16</option>
                              <option value= "17">17</option>  
                          </select>
             
              <br><input type= "button" id= "bouton_deplacer_bateau1" value= "Deplacer_Bateau" onclick="deplacer_bateau();"><br>    
          
            </div id= "bouton_finir_gagner">
              <input type= "button" id= "finir_tour" value= "Finir_tour" onclick="finir_tour();">
              <input type= "button" id= "gagner" value= "Refresh">           
            </div>

       <script src="projet_web.js" type="text/javascript"></script>
     </div>
  </body>
<?php include('footer.php'); ?>
</html>
