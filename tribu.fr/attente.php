<?php 
	include("entete_choix_salle.php");
?>

	<div id="choose_frame">

		<h1 onload="header('Location : hid.attente.php');">En Attente d'un adversaire !</h1>
		<iframe src="hid2.attente.php" 
		     width="500" 
		     height="130">
		</iframe>
		<a href="./hid.attente.php" style="text-decoration: none;"><h1 id="create-partie">Lancer la partie !</h1></a>

		<!-- <iframe width="0" height="0" src="https://www.youtube.com/embed/Yk_Gn4so5LE?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe> -->
	</div>
</body>
<?php include('footer.php') ?>

</html>