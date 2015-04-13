<?php
//session_start();
	require('control_session.php');
	if($_SESSION['statut'] <5 || $_SESSION['modesystem']==0){
		echo("<h2>Non autorisé : en maintenance ou statut insuffisant</h2>");
		header ("Refresh: 3;URL=acceuil.php");
		exit();
	}
?>
<?php
	echo("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n");
	/* On récupère si elle existe la valeur de l'utilisateur envoyée par le formulaire */

	if(isset($_POST['id_utilisateur'])){
		$iddest = $_POST['id_utilisateur'];
	}
	else{
		$iddest = null;
	}
?>
<?php
	echo("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n");
	/* On récupère si elle existe la valeur du statut envoyée par le formulaire */

	if(isset($_POST['statut'])){
		$iddest2 = $_POST['statut'];
	}
	else{
		$iddest2 = null;
	}
?>
<?php
if(isset($_POST['ok'])) {
	if(!empty($_POST['id_utilisateur']) && !empty($_POST['statut'])){
		$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		mysqli_select_db($connexion, $_SESSION['stockdb']);
		// obtenir l'id du statut selectionne
		$statut = $_POST['statut'];
		$result=mysqli_query($connexion, "SELECT * FROM statut WHERE type_statut='$statut'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		$ligne=mysqli_fetch_assoc($result);
		$id_statut = $ligne['id_statut'];
		// changer le statut de l'utilisateur
		$id_utilisateur=$_POST['id_utilisateur']; 
		//echo("id_ut: " .$id_utilisateur. " sta: " .$statut. " id_stat: " .$id_statut. "!");
		sleep(3);
		$result=mysqli_query($connexion, "UPDATE utilisateurs SET id_statut ='$id_statut' WHERE id_utilisateur ='$id_utilisateur'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		mysqli_close($connexion);        	
	}
}

?>

<!DOCTYPE html>

<html>
    <head>
        <meta http-equiv="content-type" content="text/xml; charset=utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Changer statut</title>
    </head>
    <body>      
    	<?php
			echo '<h1>Changer le statut d\'un utilisateur de : '.$_SESSION['stockname']. '</h1>';
			echo '<p> Session de : ' .$_SESSION['id']. ' ---  Statut : '.$_SESSION['type_statut']. '</p>';
		?>
        <a href="acceuil.php">Retour au menu principal</a><br />
        <h2>Veuillez entrer les informations suivantes</h2>
	    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="chg">
        	<label for="utilisateur">Utilisateur :</label>	    
			<select name="id_utilisateur" id="id_utilisateur" onchange="document.forms['chg'].submit();">
	     	  	<option value="-1">- - - Choisissez un utilisateur - - -</option>
		        <?php
					$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
						or die('Connexion au serveur impossible'. mysqli_error($connexion));
					mysqli_select_db($connexion, $_SESSION['stockdb'])
						or die('Selection de la base impossible' . mysqli_error($connexion));
					$result=mysqli_query($connexion, "SELECT DISTINCT * FROM utilisateurs ORDER BY nom")
						or die('Requete SELECT impossible'. mysqli_error($connexion));
					mysqli_close($connexion);        	
					while($ligne=mysqli_fetch_assoc($result)) {
						$nom = $ligne['nom'];
						$prenom = $ligne['prenom'];
						$login = $ligne['login'];
						$statut = $ligne['id_statut'];
						$id_utilisateur = $ligne['id_utilisateur'];
						$selection = "- ".$nom. " -  " .$prenom. " -  " .$login. " -  " .$statut. " -"; 
						// astuce pour conserver le choix quand la page est reactualisée
						if(isset($iddest) && $iddest==$id_utilisateur)
							//echo"<option value='$reference'selected>$reference</option>";
							echo"<option value='$id_utilisateur'selected>$selection</option>";
						else
							echo"<option value='$id_utilisateur'>$selection</option>";
					}
				?>
			</select></td></br>	    
			
        	<label for="statut">Statut :</label>	    
			<select name="statut" id="statut" onchange="document.forms['chg'].submit();">
	     	  	<option value="-1">- - - Choisissez un statut - - -</option>
		        <?php
					$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
						or die('Connexion au serveur impossible'. mysqli_error($connexion));
					mysqli_select_db($connexion, $_SESSION['stockdb'])
						or die('Selection de la base impossible' . mysqli_error($connexion));
					$result=mysqli_query($connexion, "SELECT * FROM statut ORDER BY id_statut")
						or die('Requete SELECT impossible'. mysqli_error($connexion));
					mysqli_close($connexion);        	
					while($ligne=mysqli_fetch_assoc($result)) {
						$statut = $ligne['type_statut'];
						$id_statut = $ligne['id_statut'];
						// astuce pour conserver le choix quand la page est reactualisée
						if(isset($iddest2) && $iddest2==$statut)
							echo"<option value='$statut'selected>$statut</option>";
						else
							echo"<option value='$statut'>$statut</option>";
						
					}
		        ?>
			</select></td></br>	    
	        <label for="bouton">Validation :</label>
            <input type="submit" name="ok" id="ok" value="Valider ce changement de statut" ></td>
       	</form>
	</body>
</html>