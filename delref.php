<?php
//session_start();
	require('control_session.php');
	if($_SESSION['statut'] <4){
		echo("<h2>Votre statut ne vous autorise pas à modifier supprimer une référence </h2>");
		header ("Refresh: 3;URL=acceuil.php");
		exit();
}
?>
<!--
	echo("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n");
	/* On récupère si elle existe la valeur de la categorie envoyée par le formulaire */

	if(isset($_POST['categorie'])){
		$iddest = $_POST['categorie'];
	}
	else if (isset($_POST['destination'])){
		$iddest = $_POST['destination'];
	}
	else{
		$iddest = null;
	}
-->
<?php
if(isset($_POST['ok'])){
	if(!empty($_POST['reference']) && !empty($_POST['categorie']) && !empty($_POST['destination']) && !empty($_POST['designation'])) {
		// connexion ? la bd
		$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		mysqli_select_db($connexion, $_SESSION['stockdb'])
			or die('Selection de la base impossible' . mysqli_error($connexion));
		// preparation de la reference et de la designation
		$ref = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['reference']));
		// verifier que la reference n'existe pas d?j?
		$result=mysqli_query($connexion, "SELECT * FROM article WHERE reference LIKE '$ref'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		if($row = mysqli_fetch_assoc($result)){
			echo "La référence '. $ref. ' éxiste dejà<br>";
			header ("Refresh: 3;URL=creerref.php");
			exit();
		}

		$designation = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['designation']));
		$udv = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['udv']));
		$seuilbas = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['seuilbas']));
		$createur = $_SESSION['id'];
	
		// recuperation de l'id categorie
		$cible=mysqli_real_escape_string($connexion, htmlspecialchars($_POST['categorie']));
		$result=mysqli_query($connexion, "SELECT * FROM categorie WHERE nom='$cible'")
			or die('Requete (categorie) SELECT impossible'. mysqli_error($connexion));
		if($row = mysqli_fetch_assoc($result)){
			$id_categorie=$row['id_categorie'];
		}
		else {
			echo "La catégorie '.$cible. n\'existe pas ... <br>";
			//echo "Vous allez être redirigé vers le menu principal ...<br>";
			header ("Refresh: 3;URL=creerref.php");
			exit();
		}
/**/
		// recuperation de l'id souscategorie
		if(!(isset($_POST['souscategorie']) && $_POST['souscategorie'] != "")){
			$id_souscategorie=0;
		}
		else{
			$cible=mysqli_real_escape_string($connexion, htmlspecialchars($_POST['souscategorie']));
			$result=mysqli_query($connexion, "SELECT * FROM sous_categorie WHERE nom='$cible'")
				or die('Requete (sous categorie) SELECT impossible'. mysqli_error($connexion));
			if($row = mysqli_fetch_assoc($result)){
				$id_souscategorie=$row['id_soucat'];
			}
			else {
				//echo "La sous catégorie '.$cible. n\'existe pas ... <br>";
				//echo "Vous allez être redirigé vers le menu principal ...<br>";
				//header ("Refresh: 3;URL=creerref.php");
				//exit();
			}
		}
/**/		
		// recuperation de l'id destination
		$cible=mysqli_real_escape_string($connexion, htmlspecialchars($_POST['destination']));
		$result=mysqli_query($connexion, "SELECT * FROM destination WHERE nom='$cible'")
			or die('Requete (destination) SELECT impossible'. mysqli_error($connexion));
		if($row = mysqli_fetch_assoc($result)){
			$id_destination=$row['id_destination'];
		}
		else {
			echo "La destination '.$cible. n\'existe pas ... <br>";
			//echo "Vous allez être redirigé vers le menu principal ...<br>";
			header ("Refresh: 3;URL=creerref.php");
			//exit();
		}
/**/
		// recuperation de l'id sousdestination
		if(!(isset($_POST['sousdestination']) && $_POST['sousdestination'] != "")){
			$id_sousdestination=0;
		}
		else {
			$cible=mysqli_real_escape_string($connexion, htmlspecialchars($_POST['sousdestination']));
			$result=mysqli_query($connexion, "SELECT * FROM sous_destination WHERE nom='$cible'")
				or die('Requete (sous destination) SELECT impossible'. mysqli_error($connexion));
			if($row = mysqli_fetch_assoc($result)){
				$id_sousdestination=$row['id_sousdest'];
			}
			else {
				//echo "La sous destination '.$cible. n\'existe pas ... <br>";
				//echo "Vous allez être redirigé vers le menu principal ...<br>";
				//header ("Refresh: 3;URL=creerref.php");
				//exit();
			}
		}
/**/	
		mysqli_query($connexion, "INSERT INTO article VALUES('', UPPER('$ref'), '$id_categorie', '$id_souscategorie', UPPER('$designation'), '$id_destination', '$id_sousdestination', '$udv', '$seuilbas', NOW(), '$createur')")
			or die('Requete INSERT impossible'. mysqli_error($connexion));
		mysqli_close($connexion);
		echo '<script>alert("La référence a été enregistrée avec succès");</script>';

	} 
	else {
		echo '<script>alert("Tous les champs obligatoires n\'ont pas été renseignés");</script>';
		header ("Refresh: 3;URL=creerref.php");
	}
}

?>
<!DOCTYPE html>

<html>
    <head>
        <meta http-equiv="content-type" content="text/xml; charset=utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Creer nouvel article</title>
    </head>
    <body>      
    	<?php
			echo '<h1>Modifier ou supprimer une référence existante pour : '.$_SESSION['stockname']. '</h1>';
			echo '<p> Session de : ' .$_SESSION['id']. ' ---  Statut : '.$_SESSION['type_statut']. '</p>';
		?>
        <a href="acceuil.php">Retour au menu principal</a><br />
        <h2>Veuillez entrer les informations suivantes</h2>
		<p> 
		Attention ! pour supprimer une référence celle-ci doit être vide (c'est à dire pas de stock sur cette référence)<br>
		Attention ! pour supprimer une référence seul le champ Identidiant doit être renseigné<br>
		Attention ! pour modifier une référence tous les champs doivent être renseignés<br>
		Attention ! pour connaitre la valeur de l'identifiant d'une référence, consulter la liste des références<br>
		</p>
	    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="chg">
            <label for="suprmod">Action :</label>
            <input type="radio" name="suprmod" value="modifier" >Modifier la référence
            <input type="radio" name="suprmod" value="supprimer" >Supprimer la référence
            <input type="radio" name="suprmod" value="ignorer" checked="checked" >Ne rien faire</br>

            <label for="reference">Identifiant :</label>
            <input type="number" id="refid" name="refid" size='65' maxlength='65' value="<?php if (isset($_POST['refid'])){echo $_POST['refid'];} ?>"</td></br>

            <label for="designation">Désignation :</label>
            <input type="text" id="designation" name="designation" size='65' maxlength='65' value="<?php if (isset($_POST['designation'])){echo $_POST['designation'];} ?>"</td></br>
                       
            <label for="udv">UDV :</label>
            <input type="number" id="udv" name="udv" size='65' maxlength='65' value="<?php if (isset($_POST['udv'])){echo $_POST['udv'];} ?>"</td></br>
                       
            <label for="seuilbas">Seuil bas :</label>
            <input type="number" id="seuilbas" name="seuilbas" size='65' maxlength='65' value="<?php if (isset($_POST['seuilbas'])){echo $_POST['seuilbas'];} ?>"</td></br>

            <label for="bouton">Validation :</label>
            <input type="submit" name="ok" id="ok" value="Valider suppression ou modification" ></td>
        </form>
	</body>
</html>