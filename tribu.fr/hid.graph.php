<?php
//session_start();
/* pChart library inclusions */
include("pChart/class/pData.class.php");
include("pChart/class/pDraw.class.php");
include("pChart/class/pPie.class.php");
include("pChart/class/pImage.class.php");
 $dbconn = pg_connect("host=localhost dbname=Projet_Web user=web_user password=123456")
        or die('Connexion impossible : ' . pg_last_error());
 
$max="SELECT COUNT(*) from actionjoueurpartie where email='".$_SESSION['graphuse']."' and TypeActionJoueurPartie in('GagnerPartie','RejoindrePartie')";
$result = pg_query($max) or die('Échec de la requête : ' . pg_last_error());
$res = pg_fetch_array ($result, 0, PGSQL_NUM);
$max=$res[0];
$gan="SELECT COUNT(*) from actionjoueurpartie where email='".$_SESSION['graphuse']."' AND TypeActionJoueurPartie='GagnerPartie'";
$result = pg_query($gan) or die('Échec de la requête : ' . pg_last_error());
$res = pg_fetch_array ($result, 0, PGSQL_NUM);
$gan=$res[0];
$per="SELECT COUNT(*) from actionjoueurpartie where email='".$_SESSION['graphuse']."'  and TypeActionJoueurPartie NOT IN ('GagnerPartie' ,'CreerPartie')";
$result = pg_query($per) or die('Échec de la requête : ' . pg_last_error());
$res = pg_fetch_array ($result, 0, PGSQL_NUM);
$per=$res[0];
echo "$per , $gan , $max";
/*if($max==0) {$max==0 
           // echo "<p> Vous n'avez toujours pas joué de partie pour vous afficher vos statistiques </p>";
            }
*/

//else {
/* Create and populate the pData object */
$MyData = new pData();   
// Les valeurs
$MyData->addPoints(array($gan,$per),"ScoreA");  
$MyData->setSerieDescription("ScoreA","Application A");
 
/* Define the absissa serie */
$MyData->addPoints(array("Gagne","Perdu"),"Labels");
$MyData->setAbscissa("Labels");
 
/* Create the pChart object */
$myPicture = new pImage(200,230,$MyData,TRUE);
 
/* Draw a solid background */
$Settings = array("R"=>85, "G"=>85, "B"=>85, "Dash"=>32, "DashR"=>85, "DashG"=>85, "DashB"=>85);
$myPicture->drawFilledRectangle(0,0,700,230,$Settings);
 
/* Draw a gradient overlay */
//$Settings = array("StartR"=>209, "StartG"=>150, "StartB"=>231, "EndR"=>111, "EndG"=>3, "EndB"=>138, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
$myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100));
 
/* Add a border to the picture */
$myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));
 
/* Write the picture title */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/UglyQua.ttf","FontSize"=>10));
$myPicture->drawText(10,19,"Vos statistiques",array("R"=>255,"G"=>255,"B"=>255));
 
/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/UglyQua.ttf","FontSize"=>11,"R"=>80,"G"=>80,"B"=>80));
 
/* Create the pPie object */ 
$PieChart = new pPie($myPicture,$MyData);
 
/* Define the slice color */
$PieChart->setSliceColor(0,array("R"=>22,"G"=>149,"B"=>22));
$PieChart->setSliceColor(1,array("R"=>155,"G"=>25,"B"=>25));
//$PieChart->setSliceColor(2,array("R"=>97,"G"=>113,"B"=>63));
 
/* Draw a simple pie chart */ 
//$PieChart->draw3DPie(120,125,array("SecondPass"=>FALSE));
 
/* Draw an AA pie chart */ 
//$PieChart->draw3DPie(340,125,array("DrawLabels"=>TRUE,"Border"=>TRUE));
 
/* Enable shadow computing */ 
//$myPicture->setShadow(TRUE,array("X"=>3,"Y"=>3,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
 
/* Draw a splitted pie chart */ 
$PieChart->draw3DPie(100,125,array("WriteValues"=>TRUE,"DataGapAngle"=>10,"DataGapRadius"=>6,"Border"=>TRUE));
 
/* Write the legend */
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/UglyQua.ttf","FontSize"=>10));
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20));
//$myPicture->drawText(120,200,"Single AA pass",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));
$myPicture->drawText(100,200,"Nombre de parties : $max",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));
 
/* Write the legend box */ 
$myPicture->setFontProperties(array("FontName"=>"pChart/fonts/UglyQua.ttf","FontSize"=>10,"R"=>255,"G"=>255,"B"=>255));
$PieChart->drawPieLegend(55,40,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));
 
/* Render the picture (choose the best way) */
if (file_exists("pChart/img/graph_".$_SESSION['graphuse'].".png")) {
unlink("pChart/img/graph_".$_SESSION['email'].".png");}
$myPicture->Render("pChart/img/graph_".$_SESSION['graphuse'].".png");//}
?>