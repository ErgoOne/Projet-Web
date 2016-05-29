<?php 
	include("entete_choix_salle.php");
?>

	<div id="choose_frame">

		<h1 onload="header('Location : hid.attente.php');">En Attente d'un adversaire !</h1>
		<iframe src="hid2.attente.php" 
		     width="300" 
		     height="300">
		</iframe>

		<a id="create-partie" href="./hid.attente.php" style="margin-top: 150px;">Go.</a><br/>

		<!-- <iframe width="0" height="0" src="https://www.youtube.com/embed/Yk_Gn4so5LE?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe> -->
	</div>
</body>
<?php include('footer.php') ?>

</html>