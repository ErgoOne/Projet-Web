/* DECLARATION DES VARIABLES GLOBALES */

/* Bateau actif pour lequel l'action est effectuée */

var partie = getCookie("id_partie");
var monemail = getCookie("email");
var emailadversaire = getCookie("email_adversaire");

function debut(){
    /* Qui commence ? */
    var job = 3;
    
    alert(monemail + " " + emailadversaire);
         if(job!=''){
            $.post('name.php',{job : job}, function(data){ // Tour fini
            });

            $("#bloquer_fenetre").show();
            var myvar = setInterval(function() { 
                
                var job=4;
                    if(job!=''){
                        /* is_tour_fini ? */
                        $.post('name.php',{job : job}, function(data){
                            if (data == email){ // C'est à moi de jouer
                                $("#bloquer_fenetre").hide();
                                clearInterval(myvar); /* Arreter le test de is_tour_fini car c'est à moi de jouer */
                                $.post('name.php',{job : 10, partie : partie }, function(data){ // Tour fini 
                                });
                           
                            }
                        });
                    } 
              },1000);
        } else {
             alert("erreur");
        }    

}



var bateau_actif = "bateau1";
var tour = 1; /* Ce n'est pas à moi de jouer */

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
tableau_coord['4'] = "22%";
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

/* Au clic sur le bouton Construire */
$('input#bouton_construire_bateau').on('click',function(){   
    
    var id_type = $("input[type=radio][name=construire]:checked").val();
    var chemin_image = $("input[type=radio][name=construire]:checked").attr("id");
    var posx = 38;
    var posy = 37;
    var job = 1;
    var partie = readCookie('id_partie');

    if(id_type!=''){
        $.post('name.php',{id_type: id_type, posx : posx, posy : posy, job : job} , function(data){
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

            var job = 2;

            var trouve_interdit = false; /* à true si la case demandée est un port et donc non valide */
            var trouve_occupe = false; /* à true si la case demandée est déjà occupée par un autre bateau */
            var i = 0;
            var nom_case;
            /* Déplacement du bateau */
            /* Récupération des valeurs saisies */
            var input_X = $("select[id=X_bateau]").val();
            var input_Y = $("select[id=Y_bateau]").val();

            /* Récupération du nom de l'ancienne position pour la supprimer de tab_occupe si nécessaire */
            for (var nom_case1 in tab_occupe){
                if(tab_occupe[nom_case] == bateau_actif){
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

    $("#bloquer_fenetre").show();
   
    /* Ecrire la date actuelle ici aussi ??? */
    $.post('name.php',{job : 10 }, function(data){ /* Ecrire la date actuelle dans date.txt */
    });

    /* Ecrire le nom de l'adversaire dans tes.txt */
    $.post('name.php',{email : emailadversaire, job : 5 }, function(data){ // Tour fini            
    });
        
    /* Verifier l'email contenu dans tes.txt pour savoir si c'est mon tour */
    var myvar = setInterval(function() {
            /* is_tour_fini() => est-ce mon nom qui est dans le fichier ? */
            $.post('name.php',{job : 4 }, function(data){
                if (data == monemail){ // C'est à moi de jouer
                    $("#bloquer_fenetre").hide();
                    clearInterval(myvar); /* Arreter le test de is_tour_fini car c'est à moi de jouer */
                    //refreshMap();
                    $.post('name.php',{emailadv : emailadversaire, job : 13 }, function(data){ // Tour fini
                        if (data != ""){
                            var res = data.split(",");
                            var iddpct = res[0];
                            var Xdpct = res[1]+"%";
                            var Ydpct = res[2]+"%";
                            var chaine_nom = "#bateau"+iddpct;
                            alert(+iddpct+Xdpct+Ydpct+chaine_nom);
                            /* Chgt de la position avec les nouvelles coordonnées */
                            $(chaine_nom).css("margin-left", Xdpct);
                            $(chaine_nom).css("margin-top", Ydpct);
                            /* Mise à jour de tab_occupe */
                            /* Récupération du nom de l'ancienne position pour la supprimer de tab_occupe si nécessaire */
                            for (var nom_case1 in tab_occupe){
                                if(tab_occupe[nom_case] == chaine_nom){
                                    var old_string_position = nom_case1;
                                }
                            }
                            var newposition = tableau_coord[Xdpct]+tableau_coord[Y];
                            tab_occupe[newposition] = chaine_nom;
                        }
                    });
                    $.post('name.php',{emailadv : emailadversaire , job: 14}, function(data){ // Tour fini
                        if (data != ""){
                            var res = data.split(",");
                            $("#cadre").prepend("<img id=bateau8 src=images/bateau2.png onmouseover=afficher_carac_encours(bateau8); onmouseleave=masquer_caracteristiques_encours();>");
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
                            $("#bateau8").css(my_css_class);
                        }
                    });
                }
            });
    },1000);
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

/* Mettre la carte à jour */
// function refreshMap(){
//     // Recupérer les dpcts de l'adversaire
//         $.post('name.php',{emailadv : emailadversaire, job : 13 }, function(data){ // Tour fini
//             alert("toto dpct");
//             // if (data != ""){
//             //     var res_dpct = data.split(" ");
//             //     var id_dpct = res_dpct[0];
//             //     var X_dpct = res_dpct[1]+"%";
//             //     var Y_dpct = res_dpct[2]+"%";
//                 // alert(id_dpct+" "+X_dpct+" "+Y_dpct);
//                  /* Modifier le css du bateau dont l'id est récupéré */
//                 // $("#bateau"+id_dpct).css("margin-left", X_dpct);
//                 // $("#bateau"+id_dpct).css("margin-top", Y_dpct);                
                 
//         });
//     // Recuperer les constructions de l'adversaire
//         $.post('name.php',{emailadv : emailadversaire , job: 14}, function(data){ // Tour fini
//                 alert("toto const");
//                 // alert(data);
//                 // var res_const = data.split(" ");
//                 // var id_const = res_const[0];
//                 // var X_const = res_const[1]+"%";
//                 // var Y_const = res_const[2]+"%";
//                 // var chemin_image = res_const[3];
//                 /* Creation du bateau sur ma map */
//                 // $("#cadre").prepend("<img id=bateau"+data+" src="+chemin_image+" onmouseover=afficher_carac_encours(bateau"+data+"); onmouseleave=masquer_caracteristiques_encours();>");
//                 // /* Creation css */
//                 // var my_css_class = {
//                 //     "position" : "absolute", 
//                 //     "width" : "35px",
//                 //     "height" : "35px",
//                 //     "margin-left" : "10%",
//                 //     "margin-top" : "10%",
//                 //     "transition-property" : "width, height",
//                 //     "transition-duration" : "2s",
//                 //     "z-index" : "4"
//                 // };
//                 // $("#bateau"+id_const).css(my_css_class);
//                 // $("#bateau"+id_const).css("margin-left", X_const);
//                 // $("#bateau"+id_const).css("margin-top", Y_const);       
//         });
// }

