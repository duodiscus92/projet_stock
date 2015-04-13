<?php
//session_start();
	include('stocklib.php');
	include('stock.conf.php');
	require('control_session.php');
	if($_SESSION['statut'] <5){
		echo("<h2>Votre statut ne vous autorise pas mettre le système en/hors maintenance/h2>");
		header ("Refresh: 3;URL=acceuil.php");
		exit();
}
?>

<?php
if(isset($_POST['ok'])) {
	if(!empty($_POST['toup'])){
		// recuperer le mode de fonctionnement 
		// connexion à la base index	
		$connexion=mysqli_connect("localhost", $stockindexlogin, $stockindexpwd)
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		mysqli_select_db($connexion, $stockindexdbname)
			or die('Selection de la base impossible' . mysqli_error($connexion));
		if(($_POST['toup']) == 'test'){
			$_SESSION['modesystem'] = "TEST";
			// inscrire le mode de fonctionnement dans la table
			$result=mysqli_query($connexion, "UPDATE modesystem SET operatingmode='TEST' WHERE id_modesystem=0")
				or die('Requete SELECT impossible'. mysqli_error($connexion));	
			msgbox($info."Le système a été mis en maintenance");
		}
		else if (($_POST['toup']) == 'prod'){
			$_SESSION['modesystem'] = "PROD";
			// inscrire le mode de fonctionnement dans la table
			$result=mysqli_query($connexion, "UPDATE modesystem SET operatingmode='PROD' WHERE id_modesystem=0")
				or die('Requete SELECT impossible'. mysqli_error($connexion));	
			msgbox($info."Le système a été mis en production");
		}
		else
			msgbox($info. "Le mode n\'a pas été changé");
		mysqli_close($connexion);
	}
}

?>

<!DOCTYPE html>

<html>
    <head>
        <meta http-equiv="content-type" content="text/xml; charset=utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Mettre en/hors maintenance</title>
    </head>
    <body>      
    	<?php
			echo '<h1>Mettre le système en maintenance ou en production : '.$_SESSION['stockname']. '</h1>';
			echo '<p> Session de : ' .$_SESSION['id']. ' ---  Statut : '.$_SESSION['type_statut']. '</p>';
			echo '<p> Système actuellement en : '.$_SESSION['modesystem']. '</p>';
		?>
        <a href="acceuil.php">Retour au menu principal</a><br />
        <h2>Veuillez sélectionner le mode de fonctionnement</h2>
	    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="chg">
			<fieldset>
				<label for="toup">Mode :</label>
				<input type="radio" name="toup" value="test"  >Maintenance
				<input type="radio" name="toup" value="prod" >Production
				<input type="radio" name="toup" value="ignorer" checked="checked">Conserver le mode en cours</br>
			</fieldset>
 	        <label for="bouton">Validation :</label>
            <input type="submit" name="ok" id="ok" value="Valider le changement de mode" ></td>
       	</form>
	</body>
</html>