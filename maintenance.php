<?php
//session_start();
	require('control_session.php');
	if($_SESSION['statut'] <5){
		echo("<h2>Votre statut ne vous autorise pas mettre le syst�me en/hors maintenance/h2>");
		header ("Refresh: 3;URL=acceuil.php");
		exit();
}
?>

<?php
if(isset($_POST['ok'])) {
	if(!empty($_POST['moup'])){
		if(($_POST['moup']) == 'maint'){
			$_SESSION['modesystem'] = "m";
			msgbox($info."Le syst�me a �t� mis en maintenance");
		}
		else if (($_POST['moup']) == 'prod'){
			$_SESSION['modesystem'] = "p";
			msgbox($info."Le syst�me a �t� mis en production");
		}
		else
			msgbox($info. "Le mode n\'a pas �t� chang�");
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
			echo '<h1>Mettre le syst�me en maitenance ou en production : '.$_SESSION['stockname']. '</h1>';
			echo '<p> Session de : ' .$_SESSION['id']. ' ---  Statut : '.$_SESSION['type_statut']. '</p>';
			echo '<p> Syst�me actuellement en : '.$_SESSION['modesystem']. '</p>';
		?>
        <a href="acceuil.php">Retour au menu principal</a><br />
        <h2>Veuillez s�lectionner le mode de fonctionnement</h2>
	    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="chg">
			<fieldset>
				<label for="moup">Mode :</label>
				<input type="radio" name="moup" value="maint"  >Maintenance
				<input type="radio" name="moup" value="prod" >Production
				<input type="radio" name="moup" value="ignorer" checked="checked">Conserver le mode en cours</br>
			</fieldset>
 	        <label for="bouton">Validation :</label>
            <input type="submit" name="ok" id="ok" value="Valider le changement de mode" ></td>
       	</form>
	</body>
</html>