<?php
//session_start();
require('control_session.php');
	if($_SESSION['statut'] <3 || $_SESSION['modesystem']==0){
		echo("<h2>Non autorisé : en maintenance ou statut insuffisant</h2>");
		header ("Refresh: 3;URL=acceuil.php");
		exit();
	}
?>
<?php
// traitement pour les categories et les sous-categories
if(isset($_POST['nom-souscategorie'])) {
	if(!empty($_POST['nom-souscategorie']) && !empty($_POST['categorie-mere'])){
		$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		mysqli_select_db($connexion, $_SESSION['stockdb'])
			or die('Selection de la base impossible' . mysqli_error($connexion));
		// V?rifier autorisations
		// ...
		// ...
		// verifier que la sous categorie n'existe pas d?j? pour cette categorie
		$nom = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['nom-souscategorie']));
		$mere = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['categorie-mere']));
		// retrouver l'id de la categorie mere
		$result=mysqli_query($connexion, "SELECT * FROM categorie WHERE nom LIKE '$mere'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		if($row = mysqli_fetch_assoc($result))
			$id_categorie=$row['id_categorie'];
		else {
			echo "La catégorie mère '.$mere. n\'existe pas ... <br>";
			//echo "Vous allez être redirigé vers le menu principal ...<br>";
			header ("Refresh: 3;URL=parametrer.php");
			exit();
		}
		$result=mysqli_query($connexion, "SELECT * FROM sous_categorie WHERE nom LIKE '$nom' AND id_categorie_mere	 LIKE '$id_categorie'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		if($row = mysqli_fetch_assoc($result)){
			echo "La sous-catégorie '. $nom. ' existe dej?<br>";
			header ("Refresh: 3;URL=parametrer.php");
			exit();
		}
		// preparer les valeurs ? inserer dans la table
		$nom = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['nom-souscategorie']));
		$createur = $_SESSION['id'];
		//echo "créateur : '.$createur.'<br>";
		mysqli_query($connexion, "INSERT INTO sous_categorie VALUES('', UPPER('$nom'), '$id_categorie', NOW(), '$createur')")
			or die('Requete INSERT impossible'. mysqli_error($connexion));
		echo "Enregistrement de la sous-catégorie en cours, veuillez patienter ...<br>";
		mysqli_close($connexion);
		header ("Refresh: 3;URL=parametrer.php");
		//exit();
	}
	else {
		echo 'Sous-catégorie : tous les champs doivent obligatoirement ^^etre renseignés ... <br>';
		header ("Refresh: 3;URL=parametrer.php");		
	}
}else if(isset($_POST['nom_categorie'])) {
	if(!empty($_POST['nom_categorie'])){
		$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		mysqli_select_db($connexion, $_SESSION['stockdb'])
			or die('Selection de la base impossible' . mysqli_error($connexion));
		// Vérifier autorisations
		// ...
		// verifier que la categorie n'existe pas déjà
		$nom = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['nom_categorie']));
		$result=mysqli_query($connexion, "SELECT * FROM categorie WHERE nom LIKE '$nom'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		if($row = mysqli_fetch_assoc($result)){
			echo "La catégorie '. $nom. ' existe déjà<br>";
			header ("Refresh: 3;URL=parametrer.php");
			exit();
		}
		// preparer les valeurs à inserer dans la table
		//$nom = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['nom-categorie']));
		$createur = $_SESSION['id'];
		//echo "createur : '.$createur.'<br>";
		mysqli_query($connexion, "INSERT INTO categorie VALUES('', UPPER('$nom'), NOW(), '$createur')")
			or die('Requete INSERT impossible'. mysqli_error($connexion));
		echo "Enregistrement de la catégorie en cours, veuillez patienter ...<br>";		
		mysqli_close($connexion);
		header ("Refresh: 3;URL=parametrer.php");
		//exit();
	}
	else {
		echo 'Catégorie : tous les champs doivent obligatoirement être renseignés ... <br>';
		header ("Refresh: 3;URL=parametrer.php");		
	}
// traitement pour les destinations et les sous-destinations
}else if(isset($_POST['nom-sousdestination'])) {
	if(!empty($_POST['nom-sousdestination']) && !empty($_POST['destination-mere'])){
		$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		mysqli_select_db($connexion, $_SESSION['stockdb'])
			or die('Selection de la base impossible' . mysqli_error($connexion));
		// Vérifier autorisations
		// ...
		// ...
		// verifier que la sous destination n'existe pas déjà pour cette destination
		$nom = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['nom-sousdestination']));
		$mere = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['destination-mere']));
		// retrouver l'id de la destination mere
		$result=mysqli_query($connexion, "SELECT * FROM destination WHERE nom LIKE '$mere'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		if($row = mysqli_fetch_assoc($result))
			$id_destination=$row['id_destination'];
		else {
			echo "La catégorie mère '.$mere. n\'existe pas ... <br>";
			//echo "Vous allez être redirigé vers le menu principal ...<br>";
			header ("Refresh: 3;URL=parametrer.php");
			exit();
		}
		$result=mysqli_query($connexion, "SELECT * FROM sous_destination WHERE nom LIKE '$nom' AND id_destination_mere	 LIKE '$id_destination'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		if($row = mysqli_fetch_assoc($result)){
			echo "La sous-destination '. $nom. ' existe déjà<br>";
			header ("Refresh: 3;URL=parametrer.php");
			exit();
		}
		// preparer les valeurs à inserer dans la table
		$nom = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['nom-sousdestination']));
		$createur = $_SESSION['id'];
		//echo "createur : '.$createur.'<br>";
		mysqli_query($connexion, "INSERT INTO sous_destination VALUES('', UPPER('$nom'), '$id_destination', NOW(), '$createur')")
			or die('Requete INSERT impossible'. mysqli_error($connexion));
		echo "Enregistrement de la sous-destination en cours, veuillez patienter ...<br>";
		mysqli_close($connexion);
		header ("Refresh: 3;URL=parametrer.php");
		//exit();
	}
	else {
		echo 'Sous-destination : tous les champs doivent obligatoirement être renseignés ... <br>';
		header ("Refresh: 3;URL=parametrer.php");		
	}
}else if(isset($_POST['nom_destination'])) {
	if(!empty($_POST['nom_destination'])){
		$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		mysqli_select_db($connexion, $_SESSION['stockdb'])
			or die('Selection de la base impossible' . mysqli_error($connexion));
		// Vérifier autorisations
		// ...
		// verifier que la destination n'existe pas déjà
		$nom = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['nom_destination']));
		$result=mysqli_query($connexion, "SELECT * FROM destination WHERE nom LIKE '$nom'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		if($row = mysqli_fetch_assoc($result)){
			echo "La destination '. $nom. ' existe déjà<br>";
			header ("Refresh: 3;URL=parametrer.php");
			exit();
		}
		// preparer les valeurs à inserer dans la table
		//$nom = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['nom-destination']));
		$createur = $_SESSION['id'];
		//echo "createur : '.$createur.'<br>";
		mysqli_query($connexion, "INSERT INTO destination VALUES('', UPPER('$nom'), NOW(), '$createur')")
			or die('Requete INSERT impossible'. mysqli_error($connexion));
		echo "Enregistrement de la destination en cours, veuillez patienter ...<br>";		
		mysqli_close($connexion);
		header ("Refresh: 3;URL=parametrer.php");
		//exit();
	}
	else {
		echo 'Destination : tous les champs doivent obligatoirement être renseignés ... <br>';
		header ("Refresh: 3;URL=parametrer.php");		
	}
}

?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Paramétrer</title>
    </head>
    <body>
    	<?php
			echo '<h1>Paramétrer : ' .$_SESSION['stockname'].'</h1>';
			echo '<p> Session de : ' .$_SESSION['id']. ' --- Statut : '.$_SESSION['type_statut']. '</p>';
		?>
	    <a href="acceuil.php">Retour au menu principal</a><br />
         
        <h2>Pour créer une nouvelle catégorie,
        veuillez entrer son nom, puis validez</h2>
        <form action="parametrer.php" method="POST">
        <!-- <form method="POST"> -->
        <table width='0%' border='0' cellspacing='0' cellpadding='2'>
        	<tr>
            <td style='text-align:right;font-weight:bold'>Nom : </td> 
            <td> <input type="text" name="nom_categorie"  size='65' maxlength='65' </td>
            </tr>

            <tr>
            <td colspan='1' style='text-align:center'> </td>
            <td> <input type="submit" value="Valider" >
            <!--<input type="submit" value="Annuler" >  </td>-->
            </tr>
        </table>
        </form>

        <h2>Pour créer une nouvelle sous-catégorie,
        veuillez entrer son nom, sélectionner la catégorie mère et valider</h2>
        <form action="parametrer.php" method="POST">
        <!-- <form method="POST"> -->
        <table width='0%' border='0' cellspacing='0' cellpadding='2'>
        	<tr>
            <td style='text-align:right;font-weight:bold'>Nom : </td> 
            <td> <input type="text" name="nom-souscategorie" size='65' maxlength='65' </td>
            </tr>
            
            <tr>
            <td style='text-align:right;font-weight:bold'>Catégorie mère : </td> 
            <td><select name="categorie-mere">
            <?php
				$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
					or die('Connexion au serveur impossible'. mysqli_error($connexion));
				mysqli_select_db($connexion, $_SESSION['stockdb'])
					or die('Selection de la base impossible' . mysqli_error($connexion));
				$result=mysqli_query($connexion, "SELECT DISTINCT nom FROM categorie ORDER BY nom")
					or die('Requete SELECT impossible'. mysqli_error($connexion));
 				while($ligne=mysqli_fetch_assoc($result)) {
					extract($ligne);
					echo"<option value='$nom'>$nom\n";
				}
				mysqli_close($connexion);            	
            ?>
            </select> </td>
            </tr>
            <tr>
            <td colspan='1' style='text-align:center'> </td>
            <td> <input type="submit" value="Valider" >
            <!--<input type="submit" value="Annuler" >  </td>-->
            </tr>
        </table>
        </form>
        
        <h2>Pour créer une nouvelle destination,
        veuillez entrer son nom, puis validez</h2>
        <form action="parametrer.php" method="POST">
        <!-- <form method="POST"> -->
        <table width='0%' border='0' cellspacing='0' cellpadding='2'>
        	<tr>
            <td style='text-align:right;font-weight:bold'>Nom : </td> 
            <td> <input type="text" name="nom_destination"  size='65' maxlength='65' </td>
            </tr>

            <tr>
            <td colspan='1' style='text-align:center'> </td>
            <td> <input type="submit" value="Valider" >
            <!--<input type="submit" value="Annuler" >  </td>-->
            </tr>
        </table>
        </form>

        <h2>Pour créer une nouvelle sous-destination,
        veuillez entrer son nom, sélectionner la destination mère et valider</h2>
        <form action="parametrer.php" method="POST">
        <!-- <form method="POST"> -->
        <table width='0%' border='0' cellspacing='0' cellpadding='2'>
        	<tr>
            <td style='text-align:right;font-weight:bold'>Nom : </td> 
            <td> <input type="text" name="nom-sousdestination" size='65' maxlength='65' </td>
            </tr>
            
            <tr>
            <td style='text-align:right;font-weight:bold'>Destination mère : </td> 
            <td><select name="destination-mere">
            <?php
				$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
					or die('Connexion au serveur impossible'. mysqli_error($connexion));
				mysqli_select_db($connexion, $_SESSION['stockdb'])
					or die('Selection de la base impossible' . mysqli_error($connexion));
				$result=mysqli_query($connexion, "SELECT DISTINCT nom FROM destination ORDER BY nom")
					or die('Requete SELECT impossible'. mysqli_error($connexion));
 				while($ligne=mysqli_fetch_assoc($result)) {
					extract($ligne);
					echo"<option value='$nom'>$nom\n";
				}
				mysqli_close($connexion);            	
            ?>
            </select> </td>
            </tr>
            <tr>
            <td colspan='1' style='text-align:center'> </td>
            <td> <input type="submit" value="Valider" >
            <!--<input type="submit" value="Annuler" >  </td>-->
            </tr>
        </table>
        </form>
    </body>
</html>