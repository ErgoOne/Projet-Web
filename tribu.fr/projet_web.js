/* DECLARATION DES VARIABLES GLOBALES */
var monemail = "";
var emailadversaire = "";
/* Bateau actif pour lequel l'action est effectuée */
var bateau_actif = "";
var tour = 0; /* Pour gérer qu'un joueur doit effectuer au moins une action avant de finir le tour */
var premier_tour = 0; /* Determiner s'il s'agit ou pas du premier tour pour gérer les dates des actions de l'adversaire */

/* Obtenir les emails des 2 joueurs de la partie */
function getInfoPartie(){  
    $.post('name.php',{job : 6 }, function(data){
        var res_info = data.split(",");
        monemail = res_info[0];
        emailadversaire = res_info[1];
    });
}

function debut(){
    /* Recup des emails des joueurs */
    getInfoPartie();
    /* Qui commence ? */
    var job = 3;
         if(job!=''){
            $.post('name.php',{job : job }, function(data){ // Tour fini
            });
            $("#bloquer_fenetre").show();
            var myvar = setInterval(function() {     
                var job=4;
                    if(job!=''){
                        /* is_tour_fini ? */
                        $.post('name.php',{job : job }, function(data){
                            if (data == monemail){ // C'est à moi de jouer
                                $("#bloquer_fenetre").hide();
                                clearInterval(myvar); /* Arreter le test de is_tour_fini car c'est à moi de jouer */
                                premier_tour = 1; /* Pour la gestion des dates des actions de l'adversaire */
                            }
                        });
                    }
              },1000);
        } else {
             alert("erreur");
        }    
    }


/* Masquer les fenêtres qui n'apparaissent qu'à certaines conditions */
$("#action_bateau").hide();
$("#texte_case_pirate").hide();
$("#texte_case_occupee").hide();
$("#bloquer_fenetre").hide();
$("#caracteristiques_encours").hide();
$("#caracteristiques_type").hide();


/* Tableau permettant de recenser les différentes cases pour pouoir effectuer les deplacements */    
var tableau_coord = {};
tableau_coord['A'] = "8%";
tableau_coord['B'] = "15%";
tableau_coord['C'] = "22%";
tableau_coord['D'] = "30%";
tableau_coord['E'] = "37%";
tableau_coord['F'] = "44%";
tableau_coord['G'] = "51%";
tableau_coord['H'] = "58%";
tableau_coord['1'] = "6%";
tableau_coord['2'] = "12%";
tableau_coord['3'] = "17%";
tableau_coord['4'] = "21%";
tableau_coord['5'] = "27%";
tableau_coord['6'] = "35%";
tableau_coord['7'] = "38%";
tableau_coord['8'] = "43%";
tableau_coord['9'] = "49%";
tableau_coord['10'] = "54%";
tableau_coord['11'] = "60%";
tableau_coord['12'] = "65%";
tableau_coord['13'] = "71%";
tableau_coord['14'] = "77%";
tableau_coord['15'] = "82%";
tableau_coord['16'] = "88%";
tableau_coord['17'] = "94%";

/* Tableau des cases interdites = ports et ports pirates */
var tab_interdit = ["A11", "A12", "B11", "B12", "C1", "C2", "D1", "D2", "E1", "E2", "H6", "H7", "E17", "F17", "G17"];

///* Tableau des cases occupées */
///* Valeurs ajoutées et supprimées au fur et à mesure de l'avancement de la partie */
var tab_occupe = {"D3" : "bateau1", "F16" : "bateau2"};

function reduire(param1){
    $("#"+param1).css("width", "35px");
    $("#"+param1).css("height", "35px");
}

function annuler(){
    reduire(bateau_actif);
    $("#action_bateau").hide();
    bateau_actif="";    
}

/* Au clic sur le bouton Construire */
$('input#bouton_construire_bateau').on('click',function(){   
    tour+= 1;
    var id_type = $("input[type=radio][name=construire]:checked").val();
    var chemin_image = $("input[type=radio][name=construire]:checked").attr("id");
    var posx = 38;
    var posy = 37;
    var job = 1;
    if(id_type!=''){
        $.post('name.php',{id_type: id_type, posx : posx, posy : posy, job : job } , function(data){
               /* Ajout de l'element à la partie */
                $("#cadre").prepend("<img id=bateau"+data+" src="+chemin_image+" onmouseover=afficher_carac_encours(bateau"+data+"); onmouseleave=masquer_caracteristiques_encours(); ondblclick=zoom(bateau"+data+");>");
                /* Creation css */
                var my_css_class = {
                    "position" : "absolute", 
                    "width" : "35px",
                    "height" : "35px",
                    "margin-left" : "10%",
                    "margin-top" : "10%",
                    "transition-property" : "width, height",
                    "transition-duration" : "2s",
                    "z-index" : "4"
                };
                $("#bateau"+data).css(my_css_class);
            });   

    } else {
        alert("Erreur");        
    }
});

/* Fonction permettant de déplacer un bateau suivant les coordonnées saisies */
function deplacer_bateau(){

            tour+= 1;
            var job = 2;

            var trouve_interdit = false; /* à true si la case demandée est un port et donc non valide */
            var trouve_occupe = false; /* à true si la case demandée est déjà occupée par un autre bateau */
            var i = 0;
            var nom_case1;
            /* Déplacement du bateau */
            /* Récupération des valeurs saisies */
            var input_X = $("select[id=X_bateau]").val();
            var input_Y = $("select[id=Y_bateau]").val();

            /* Récupération du nom de l'ancienne position pour la supprimer de tab_occupe si nécessaire */
            for (var nom_case1 in tab_occupe){
                if(tab_occupe[nom_case1] == bateau_actif){
                    var old_string_position1 = nom_case1;
                }
            }

            /* Construction du nom de la nouvelle case demandée */
            var string_position1 = input_Y + input_X;
        
            /* On vérifie que la case demandée n'est pas interdite (port) */
            while ((i < tab_interdit.length) && (trouve_interdit == false)){
                if (tab_interdit[i] == string_position1){
                    trouve_interdit = true;
                }
                i++;
            }

            /* On vérifie que la case demandée n'est pas déjà occupée */
            for (var nom_case1 in tab_occupe){
                if (nom_case1 == string_position1){
                    trouve_occupe = true;
                }
            }

            /* Si trouve_interdit = true c'est que le joueur souhaite se déplacer sur une case port ou interdite */
            if ((trouve_interdit == true) || (trouve_occupe == true)){
                if (trouve_interdit){
                    $("#texte_case_pirate").fadeIn('slow').delay(2000).fadeOut('slow');
                } else {
                    $("#texte_case_pirate").fadeIn('slow').delay(2000).fadeOut('slow');
                }
            } else {
                delete(tab_occupe[old_string_position1]); /* On supprime l'ancienne position */
                tab_occupe[string_position1] = bateau_actif; /* On ajoute la nouvelle position */
                var X_bateau = tableau_coord[input_X];
                var Y_bateau = tableau_coord[input_Y];
                $("#"+bateau_actif).css("margin-left", X_bateau);
                $("#"+bateau_actif).css("margin-top", Y_bateau);
            }

            /* Remettre l'image à sa taille normale */
            reduire(bateau_actif);
            $("#action_bateau").hide();

            /* Envoi des coordonnées à la bdd pour faire l'INSERT des nouvelles positions */
            var recup_id = bateau_actif.split("u"); /* Récupération de l'id du bateau */
            var id_bateau = recup_id[1];
            var recup_coord = X_bateau.split("%");
            var X_bat = recup_coord[0];
            var recup_coord = Y_bateau.split("%");
            var Y_bat = recup_coord[0];

        /* Envoi des coordonnées pour mettre à jour la base de données */
        if(id_bateau!=''){
            $.post('name.php',{posx : X_bat, posy : Y_bat, id_bateau: id_bateau, job : job });
        } else {
            alert("erreur");
        }
}

function zoom(param){
    /* Zoom de l'image sur laquelle le joueur a fait un double-click */
    MAJBateauActif(param);
    $("#"+bateau_actif).css("width", "75px");
    $("#"+bateau_actif).css("height", "75px");
    /* Affichage de la fenêtre de saisie des coordonnées */
    $("#action_bateau").fadeIn('slow');
}

/* Mettre à jour l'id du bateau actif */
function MAJBateauActif(param){
    bateau_actif = param.getAttribute("id");
}

/* Envoi de la fin du tour vers php */
function finir_tour(){
if (tour > 0){
    if (premier_tour == 0){
        /* sel1stdate() */
        $.post('name.php',{job : 7 }, function(date_action){
        });
    
        premier_tour++;
    } else {
        /* selastdate() */
        $.post('name.php',{job : 8 }, function(date_action){
        });
    }
    
    $("#bloquer_fenetre").show();
   
    /* Ecrire le nom de l'adversaire dans tes.txt */
    $.post('name.php',{email : emailadversaire, job : 5 }, function(data){ // Tour fini            
    });
        
    /* Verifier l'email contenu dans tes.txt pour savoir si c'est mon tour */
    var myvar = setInterval(function() {
            /* is_tour_fini() => est-ce mon nom qui est dans le fichier ? */
        var job4 = 4;
        if (job4 != ""){
            $.post('name.php',{job : job4 }, function(data){
                if (data == monemail){ // C'est à moi de jouer
                    clearInterval(myvar);
                    $("#bloquer_fenetre").hide();
                    alert("C'est à moi");
                     /* Arreter le test de is_tour_fini car c'est à moi de jouer */
                    /* Recuperation des dpcts effectués par l'adversaire pendant l'attente */
                    $.post('name.php',{date: date_action, emailadv : emailadversaire, job : 13 }, function(data){ // Tour fini
                        alert("Dpct "+data);
                        if (data != ""){
                            var res_dpct = data.split(",");
                            var iddpct = res_dpct[0]; /* Recup de l'ID */
                            var Xdpct = res_dpct[1]+"%"; /* Recup de la position en X */
                            var Ydpct = res_dpct[2]+"%"; /* Recup de la position en Y */
                            var chaine_nom = "#bateau"+iddpct; /* Construction de la chaine du bateau */
                            /* Chgt de la position avec les nouvelles coordonnées */
                            $(chaine_nom).css("margin-left", Xdpct);
                            $(chaine_nom).css("margin-top", Ydpct);
                            /* Mise à jour de tab_occupe */
                            /* Récupération du nom de l'ancienne position pour la supprimer de tab_occupe si nécessaire */
                            for (var nom_case in tab_occupe){ /* nom_case est la clef du tableau */
                                if(tab_occupe[nom_case] == chaine_nom){
                                    var old_string_position_dpct = nom_case;  
                                }
                            }

                            for (var key in tab_coord){ /* key est la clef du tableau */
                                if (tab_coord[key] == Xdpct){
                                    var X = key;
                                }
                                if (tab_coord[key] == Ydpct){
                                    var Y = key;
                                }
                            }

                            var newposition = X+Y;
                            alert("New pos : "+newposition);
                            /* Suppression de l'ancienne position de tab_occupe */
                            delete(tab_occupe[old_string_position_dpct]);
                            /* Saisie de la nouvelle position dans tab_occupe */
                            tab_occupe[newposition] = chaine_nom;
                            alert("Ancienne pos : "+old_string_position+ " Nouv pos : "+newposition);
                        } else {
                            alert("Pas de dpct");
                        }
                    });
                }
            });
        }
        
                    /* Recuperation du bateau construit */
                    $.post('name.php',{date: date_action, emailadv : emailadversaire , job: 14}, function(data){ // Tour fini
                        alert("Const "+data);
                        if (data != ""){
                            var res_const = data.split(",");
                            var idconst = res_const[0];
                            var Xconst = res_const[1]+"%";
                            var Yconst = res_const[2]+"%";
                            var chemin = res_const[3];
                            $("#cadre").prepend("<img id=bateau"+idconst+" src="+chemin+" onmouseover=afficher_carac_encours(bateau"+idconst+"); onmouseleave=masquer_caracteristiques_encours();>");
                            /* Creation css */
                            var my_css_class = {
                                "position" : "absolute", 
                                "width" : "35px",
                                "height" : "35px",
                                "margin-left" : "10%",
                                "margin-top" : "10%",
                                "transition-property" : "width, height",
                                "transition-duration" : "2s",
                                "z-index" : "4"
                            };
                            $("#bateau"+idconst).css(my_css_class);
                            $("#bateau"+idconst).css("margin-left", Xconst);
                            $("#bateau"+idconst).css("margin-top", Yconst);
                            var new_pos_const = Xconst+Yconst;
                            alert(new_pos_const);
                            tab_occupe[new_pos_const] = "#bateau"+idconst;
                        } else {
                            alert("Pas de construction");
                        }
                    });
                },1000);
            tour = 0;
            } else {
      alert("Vous devez effectuer au moins une action avant de finir votre tour");
   }
}

/* Afficher les caracteristiques d'un bateau au cours de la partie */
function afficher_carac_encours(nombateau){
        var job = 11;
        var id_chaine = nombateau.getAttribute("id");
        var id = id_chaine.split("u");
        var id_bat = id[1];
        if(job!=''){
            $.post('name.php',{id_bateau : id_bat, job : job }, function(data){ // Tour fini
                    var res = data.split(",");
                    var texte = "< Type : "+res[1]+ " | Vie restante : "+res[0]+ ">";
                    $("#caracteristiques_encours").text(texte);
                    $("#caracteristiques_encours").show();      
            });
        } else {
            alert("erreur");
        } 
}

/* Afficher les caracteristiques du type du bateau */
function afficher_carac_type(id_t){
    var job = 12;
    var id_type = parseInt(id_t);
    if(job!=''){
        $.post('name.php',{id_type : id_type, job : job }, function(data){ // Tour fini
            var res = data.split(",");          
            $("#caracteristiques_type").html("Coût : "+res[0]+ "<br>Resistance : "+res[1]+"<br>Equipage : "+res[2]+"<br>Degats : "+res[3]+"<br>Vie max : "+res[4]+"<br>Deplacement : "+res[5]);
            $("#caracteristiques_type").show();
        });
    } else {
        alert("erreur");
    }    
}

/* Masquer les caracteristiques d'un bateau pendant la partie */
function masquer_caracteristiques_encours(){
    $("#caracteristiques_encours").hide();
}

/* Masquer les caractéristiques d'un type de bateau pouvant être construit */
function masquer_caracteristiques_type(){
    $("#caracteristiques_type").hide();
}

function attaquer_bateau(){

    /* Recuperation de l'ID du bateau attaquant */
    var explode_att = bateau_actif.split("u");
    var id_bat_att = explode_att[1];

    /* Recuperation de l'ID du bateau cible */
    /* Récupération des valeurs saisies */
    var input_X_cible = $("select[id=X_bateau]").val();
    var input_Y_cible = $("select[id=Y_bateau]").val();
    var pos_cible = input_Y_cible+input_X_cible;
    var nom_bat_cible = tab_occupe[pos_cible];
    var explode_cible = nom_bat_cible.split("u");
    var id_bat_cible = explode_cible[1];

    $.post('name.php',{id_bateau_cible : id_bat_cible, id_bateau_attaquant : id_bat_att, job : 15 }, function(data){ // Tour fini
        if (data == 0){
            $("#nom_bat_cible").fadeOut("slow");
        } else { /* Le bateau n'est pas détruit */
            /* Risposte du bateau */
            $.post('name.php',{id_bateau_cible : id_bat_cible, id_bateau_attaquant : id_bat_att, job : 15 }, function(data){ // Tour fini       
            });
        }
    });
}