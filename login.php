<?php
include('stock.conf.php');
session_start();
if(isset($_POST['id'])){
if(!empty($_POST['id']) && !empty($_POST['mdp']) && !empty($_POST['stockname']) ){
	$_SESSION['id'] = $_POST['id'];
	$_SESSION['mdp'] = $_POST['mdp'];
	
	// connexion à la base index	
	$connexion=mysqli_connect("localhost", $stockindexlogin, $stockindexpwd)
		or die('Connexion au serveur impossible'. mysqli_error($connexion));
	mysqli_select_db($connexion, $stockindexdbname)
		or die('Selection de la base impossible' . mysqli_error($connexion));
	// vérifier que le stock demandé existe
	$stockname=mysqli_real_escape_string($connexion, htmlspecialchars($_POST['stockname']));
	$result=mysqli_query($connexion, "SELECT * FROM stockliste WHERE stockname='$stockname'")
		or die('Requete SELECT impossible'. mysqli_error($connexion));	
	if($row = mysqli_fetch_assoc($result)){	
		$_SESSION['stocklogin']=$row['stocklogin'];
		$_SESSION['stockpwd']=$row['stockpwd'];
		$_SESSION['stockdb']=$row['stockdb'];
		$_SESSION['stockname']=$row['stockname'];
		$_SESSION['mailroot']=$row['mailroot'];
	}
	else {
		echo "Le stock demandé n'existe pas<br>";
		echo "Veuillez choisir un autre stock<br>";
		echo "Vous allez être redirigé vers le formulaire de connexion ...<br>";
		header ("Refresh: 3;URL=login.php");
		exit();
	}
	mysqli_close($connexion);	
	// connexion au stock selectionné		
	$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
		or die('Connexion au serveur impossible'. mysqli_error($connexion));
	mysqli_select_db($connexion, $_SESSION['stockdb'])
		or die('Selection de la base impossible' . mysqli_error($connexion));
	// Verifier que le login et le mot de passe sont conformes n'existe pas deja
	$login = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['id']));
	$result=mysqli_query($connexion, "SELECT * FROM utilisateurs WHERE login LIKE '$login'")
		or die('Requete SELECT impossible'. mysqli_error($connexion));
	if(!(($row = mysqli_fetch_assoc($result)) && (mysqli_real_escape_string($connexion, sha1(htmlspecialchars($_POST['mdp']))) == $row["mdp"]))){
		echo "Erreur sur l'identifiant et/ou le mot de passe<br>";
		echo "Vous allez être redirigé vers le formulaire de connexion ...<br>";
		header ("Refresh: 3;URL=login.php");
		exit();
	}
	else {
		$_SESSION['statut'] = $row['id_statut'];
		// Obtenir le statut sous forme litteral
		$statut = $_SESSION['statut'];
		$result=mysqli_query($connexion, "SELECT * FROM statut WHERE id_statut LIKE '$statut'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		if($row = mysqli_fetch_assoc($result)){
			$_SESSION['type_statut'] = $row['type_statut'];
		}
		// recuperer le mode de fonctionnement 
		// connexion à la base index	
		$connexion=mysqli_connect("localhost", $stockindexlogin, $stockindexpwd)
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		mysqli_select_db($connexion, $stockindexdbname)
			or die('Selection de la base impossible' . mysqli_error($connexion));
		// selectionner le mode de fonctionnement dans la table
		$result=mysqli_query($connexion, "SELECT operatingmode FROM modesystem WHERE id_modesystem=0")
			or die('Requete SELECT impossible'. mysqli_error($connexion));	
		if($row = mysqli_fetch_assoc($result))	
			$_SESSION['modesystem']=$row['operatingmode'];
		mysqli_close($connexion);
		header ("Refresh: 1;URL=acceuil.php");
		exit();
	}
	mysqli_close($connexion);
}
else {
	echo 'Tous les champs doivent obligatoirement être renseignés ... <br>';
	echo "Vous allez être redirigé vers le formulaire de connexion ...<br>";
	header ("Refresh: 3;URL=login.php");		
	exit();
}
}
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="style.css" />
        <title>Connexion</title>
    </head>
    <body>
    	<h1>Bienvenue sur simple-stock - Système de gestion multi-stock</h1>
        <p>Veuillez entrer le nom du stock, votre login et votre mot de passe pour accéder au stock désiré</p>
        <!-- <form action="acceuil.php" method="POST"> -->
        <form action="login.php" method="POST">
        <table width='0%' border='0' cellspacing='0' cellpadding='2'>
        	<tr>
            <td style='text-align:right;font-weight:bold'>Nom du stock : </td> 
            <td> <input type="text" name="stockname" size='65' maxlength='65' placeholder='Exemple: cvvfr-stock'</td>
            </tr>
            
        	<tr>
            <td style='text-align:right;font-weight:bold'>Identifiant : </td> 
            <td> <input type="text" name="id" size='65' maxlength='65' </td>
            </tr>
            
            <tr>
            <td style='text-align:right;font-weight:bold'>Password : </td> 
            <td> <input type="password" name="mdp" size='65' maxlength='65'</td>
            </tr>
            
            <tr>
            <td colspan='1' style='text-align:center'> </td>
            <td> <input type="submit" value="Valider" >  </td>
            </tr>
        </table>
        <p>
	        <a href="inscription.php">Pas encore inscrit ?</a><br />
	        <a href="oublie.php">Mot de passe oublié ?</a><br />
	    </p>    
        </form>
        <p>Copyright J. Ehrlich 2015</p>
    </body>
</html>