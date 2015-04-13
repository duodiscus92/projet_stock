<?php
	include('stock.conf.php');
	include('stocklib.php');
?>

<?php
session_start();
$_SESSION['id'] = $_POST['id'];
$_SESSION['mdp'] = $_POST['mdp'];
$_SESSION['prenom'] = $_POST['prenom'];
$_SESSION['nom'] = $_POST['nom'];
$_SESSION['mail'] = $_POST['mail'];

//echo "traitement du formulaire d'inscription";
if(!empty($_POST['id']) && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['mdp1']) 
	&& !empty($_POST['mdp2']) && !empty($_POST['mail']) && !empty($_POST['stockname'])){
	// connexion à la base index	
	$connexion=mysqli_connect("localhost", $stockindexlogin, $stockindexpwd)
		or die('Connexion au serveur pour la base index impossible'. mysqli_error($connexion));
	mysqli_select_db($connexion, $stockindexdbname)
		or die('Selection de la base index impossible' . mysqli_error($connexion));
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
	
	$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
		or die('Connexion au serveur impossible'. mysqli_error($connexion));
	mysqli_select_db($connexion, $_SESSION['stockdb'])
		or die('Selection de la base impossible' . mysqli_error($connexion));
	// Verifier que le login n'existe pas deja
	$login = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['id']));
	$result=mysqli_query($connexion, "SELECT * FROM utilisateurs WHERE login LIKE '$login'")
		or die('Requete SELECT impossible'. mysqli_error($connexion));
	if($row = mysqli_fetch_assoc($result)){
		echo "L'utilisateur '. $login. ' est deja enregistre dans le systeme<br>";
		echo "Vous allez etre redirige vers le formulaire d'inscription ...<br>";
		header ("Refresh: 5;URL=inscription.php");
		exit();
	}

	$mdp1 = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['mdp1']));
	$mdp2 = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['mdp2']));
	if($mdp1 == $mdp2){
		// cryptage du mot de passe.
		$mdp1 = sha1($mdp1);
		$nom = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['nom']));
		$prenom = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['prenom']));
//		$login = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['id']));
		$mail = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['mail']));
		mysqli_query($connexion, "INSERT INTO utilisateurs VALUES('', '$nom', '$prenom', '$login', '$mdp1', '$mail', 1, NOW(),0,0,0,0)")
			or die('Requete INSERT impossible'. mysqli_error($connexion));
		echo "Inscription en cours, veuillez patienter ...<br>";
		sendmail($_SESSION['mailroot'], "[cvvfr-stock]Nouvel utilisateur", "Nom : $nom Prénom : $prenom Login : $login email : $mail."); 
		header ("Refresh: 2;URL=login.php");
		exit();
	}
	else {
		echo 'Les deux mots de passe que vous avez rentres ne correspondent pas...<br>';
		echo "Vous allez etre redirige vers le formulaire d'inscription ...<br>";
		header ("Refresh: 5;URL=inscription.php");	
		exit();	
	}
	mysqli_close($connexion);
}
else{
	echo 'Tous les champs doivent obligatoirement etre renseignes ... <br>';
	echo "Vous allez etre redirige vers le formulaire d'inscription ...<br>";
	//confirmmsg("Vous allez etre redirige vers le formulaire d'inscription");
	header ("Refresh: 5;URL=inscription.php");		
	exit();
}
?>