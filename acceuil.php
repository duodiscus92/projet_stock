<?php
	include('stocklib.php');
	require('control_session.php');
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Acceuil</title>
	    <script type = "text/javascript">
	      //<![CDATA[
	      function verifStatut(statut_requis){
		    var monStatut = <?php echo $_SESSION["statut"]; ?>;
		    if(monStatut < statut_requis){
		    	alert("Votre statut ne vous autorise pas cette opération !");
		      	<?php header ("Refresh: 5;URL=acceuil.php"); ?>
		      	//exit();
	      	}
	      }
	      //]]>
	     </script>
    </head>
    <body>
    	<?php
			echo '<h1>Menu principal de : '.$_SESSION['stockname'].'</h1>';
			echo '<p> Session de : ' .$_SESSION['id']. ' --- Statut : '.$_SESSION['type_statut']. '</p>';
		?>
	    <p>
	    	<h2>Opérations de consultation</h2>
	        <a href="consulter.php" onClick=verifStatut(2)>Consulter l'état du stock</a><br />
	        <a href="refvide.php" onClick=verifStatut(2)>Lister les références vides (pas de stock)</a><br />
	        <a href="notice.php" onClick=verifStatut(2)>Notice d'utilisation</a><br />
	    	<h2>Opérations de modification</h2>
	        <a href="deposer.php" onClick=verifStatut(3)>Déposer dans le stock</a><br />
	        <a href="prelever.php" onClick=verifStatut(3)>Prélever dans le stock</a><br />
	        <a href="creerref.php" onClick=verifStatut(4)>Créer une nouvelle réference</a><br />
	        <h2>Opérations spéciales </h2>
	        <a href="parametrer.php" onClick=verifStatut(4)>Paramétrer le stock</a><br />
	        <a href="chstatut.php" onClick=verifStatut(5)>Changer le statut des utilisateurs</a><br />
   	        <a href="chpwd.php" onClick=verifStatut(1)>Modifier son mot de passe</a><br />
	        <h2>Quitter cvvfr-shop</h2>
	        <a href="logout.php">Byebye</a>        
	    </p> 
    </body>
</html>