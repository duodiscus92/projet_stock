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
	      function verifStatut(statut_requis, verifmode){
		    var monStatut = <?php echo $_SESSION["statut"]; ?>;
			var mode = <?php echo $_SESSION["modesystem"]; ?>;
			alert("Mode : "+mode );
			if(verifmode == 1){
				if(mode == 'TEST'){
					alert("Systeme en maintenance : ré-essayez ultérieurement");
					<?php header ("Refresh: 5;URL=acceuil.php"); ?>
				}
			}
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
	    	<h2>Opérations sur le stock</h2>
	        <a href="consulter.php" onClick=verifStatut(2,1)>Consulter l'état du stock</a><br />
	        <a href="deposer.php" onClick=verifStatut(3,1)>Déposer dans le stock</a><br />
	        <a href="prelever.php" onClick=verifStatut(3,1)>Prélever dans le stock</a><br />
	        <a href="delart.php" onClick=verifStatut(3,1)>Supprimer ou modifier un article</a><br />
	    	<h2>Opérations sur les références</h2>
	        <a href="refvide.php" onClick=verifStatut(2,1)>Consulter les références</a><br />
	        <a href="creerref.php" onClick=verifStatut(4,1)>Créer une nouvelle réference</a><br />
	        <a href="delref.php" onClick=verifStatut(4,1)>Supprimer ou modifier une réference</a><br />
			<h2>Opération sur mes propres paramètres</h2>
   	        <a href="chpwd.php" onClick=verifStatut(1,1)>Modifier mon mot de passe</a><br />
   	        <a href="acceuil.php" onClick=verifStatut(2,1)>Régler mes notifications (pas encore en service)</a><br />
	        <h2>Divers</h2>
	        <a href="notice.php" onClick=verifStatut(2,1)>Consulter la notice d'utilisation</a><br />
	        <a href="logout.php">Quitter (fermer la session)</a>        
	        <h2>Opérations spéciales </h2>
	        <a href="parametrer.php" onClick=verifStatut(4,1)>Paramétrer le stock</a><br />
	        <a href="chstatut.php" onClick=verifStatut(5,1)>Changer le statut des utilisateurs</a><br />
	        <a href="maintenance.php" onClick=verifStatut(5,0)>Mettre le système en maintenance ou en production</a><br />
	    </p> 
    </body>
</html>