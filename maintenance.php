<?php
//session_start();
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
		if(($_POST['toup']) == 'test'){
			$_SESSION['modesystem'] = "TEST";
			msgbox($info."Le système a été mis en maintenance");
		}
		else if (($_POST['toup']) == 'prod'){
			$_SESSION['modesystem'] = "PROD";
			msgbox($info."Le système a étémis en production");
		}
		else
			msgbox($info. "Le mode n\'a pas été changé");
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
			echo '<h1>Mettre le système en maitenance ou en production : '.$_SESSION['stockname']. '</h1>';
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