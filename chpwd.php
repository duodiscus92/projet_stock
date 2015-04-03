<?php
	require('control_session.php');
	if($_SESSION['statut'] <1){
		echo("<h2>Votre statut ne vous autorise pas à changer le mot de passe/h2>");
		header ("Refresh: 3;URL=acceuil.php");
		exit();
}
?>
<?php
// le traitement du formulaire ci-dessous est ici
if(isset($_POST['id'])) {
	if(!empty($_POST['id']) && !empty($_POST['oldmdp']) && !empty($_POST['mdp1']) && !empty($_POST['mdp2']) ){
		$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		mysqli_select_db($connexion, $_SESSION['stockdb'])
			or die('Selection de la base impossible' . mysqli_error($connexion));
			
		// Verifier que le login existe pas deja
		$login = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['id']));
		$result=mysqli_query($connexion, "SELECT * FROM utilisateurs WHERE login LIKE '$login'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
/**/
		if(($row_login = mysqli_fetch_assoc($result))){
			// Vérifier que l'ancien pass existe
			$oldmdp = sha1(mysqli_real_escape_string($connexion, htmlspecialchars($_POST['oldmdp'])));
			$result=mysqli_query($connexion, "SELECT * FROM utilisateurs WHERE mdp LIKE '$oldmdp' AND login LIKE '$login'")
				or die('Requete SELECT impossible'. mysqli_error($connexion));
			if(($row_mdp = mysqli_fetch_assoc($result))){
				// l'ancien mdp a été trouvé			
				$mdp1 = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['mdp1']));
				$mdp2 = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['mdp2']));
				if($mdp1 == $mdp2){
					// cryptage du mot de passe.
					$mdp1 = sha1($mdp1);
					mysqli_query($connexion, "UPDATE utilisateurs SET mdp='$mdp1' WHERE login='$login'")
						or die('Requete UPDATE impossible'. mysqli_error($connexion));
					echo "Mise à jour du nouveau mot de passe, veuillez patienter ...<br>";
					header ("Refresh: 2;URL=login.php");
					exit();
				}
				else {
					echo 'Les deux nouveaux mots de passe que vous avez rentres ne correspondent pas...<br>';
					header ("Refresh: 3;URL=chpwd.php");	
				}
			}
			else {
				echo "L'ancien mot de passe n'existe pas ... <br>";
				header ("Refresh: 3;URL=chpwd.php");	
			}
		}
		else {
			echo "Vous n'etes pas inscrits dans stock-cvvfr<br>";
			header ("Refresh: 5;URL=login.php");
		}
/**/
	}
	else{
		echo 'Tous les champs doivent etre renseignes <br>';
	}
}
else {
?><!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Changement de mot de passe </title>
    </head>
    <body>
    	<?php
			echo '<h1>Modifier votre mot de passe pour le stock : '.$_SESSION['stockname']. '</h1>';
			echo '<p> Session de : ' .$_SESSION['id']. ' ---  Statut : '.$_SESSION['type_statut']. '</p>';
		?>
        <a href="acceuil.php">Retour au menu principal</a><br />
        <h2>Veuillez entrer les informations suivantes</h2>
       <form action="chpwd.php" method="POST">
        <!-- <form method="POST"> -->
        <table width='0%' border='0' cellspacing='0' cellpadding='2'>
        	<tr>
            <td style='text-align:right;font-weight:bold'>Identifiant : </td> 
            <td> <input type="text" name="id" size='65' maxlength='65' </td>
            </tr>

            <tr>
            <td style='text-align:right;font-weight:bold'>Password actuel : </td> 
            <td> <input type="password" name="oldmdp" size='65' maxlength='65'</td>
            </tr>
            
            <tr>
            <td style='text-align:right;font-weight:bold'>Nouveau Password : </td> 
            <td> <input type="password" name="mdp1" size='65' maxlength='65'</td>
            </tr>
            
            <tr>
            <td style='text-align:right;font-weight:bold'>Répéter nouveau password : </td> 
            <td> <input type="password" name="mdp2" size='65' maxlength='65'</td>
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
<?php
}
?>