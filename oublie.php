<?php
	include('stock.conf.php');
	include('stocklib.php');
?>
<?php
// le traitement du formulaire ci-dessous est ici
if(isset($_POST['id'])) {
	if(!empty($_POST['id']) && !empty($_POST['mail']) && !empty($_POST['stockname'])) {
		// connexion à la base indes
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
		// Verifier que le login existe pas deja
		$login = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['id']));
		$result=mysqli_query($connexion, "SELECT * FROM utilisateurs WHERE login LIKE '$login'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));

		if(($row = mysqli_fetch_assoc($result))&& ($row['mail'] == $_POST['mail'])){
				//$pwd = eqfi2934KDH;
				$pwd = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 20);
				echo "Votre nouveau password a été envoyé sur votre adresse mail.<br>";
				sendmail($_POST['mail'], "Nouveau mot de passe pour cvvfr-stock", "Votre nouveau mot de passe est: $pwd. Changez-le dès votre prochaine connexion."); 
				$pwd=sha1($pwd);
				//echo "UPDATE utilisateurs SET mdp=$pwd WHERE login=$login<br>";
				mysqli_query($connexion, "UPDATE utilisateurs SET mdp='$pwd' WHERE login='$login'")
					or die('Requete UPDATE impossible'. mysqli_error($connexion));
				header ("Refresh: 5;URL=login.php");
		}
		else {
			echo "Vous n'etes pas inscrits<br>";
			header ("Refresh: 5;URL=inscription.php");
		}
	}
}
else {
?>
<!DOCTYPE html>
<!-- le formulaire est traité par le code php ci-dessus -->
<html>

    <head>
        <!--<meta charset="utf-8" />-->
		<link rel="stylesheet" href="style.css" />
        <title>Mot de passe oublié</title>
    </head>
    <body>
        <h1>Mot de passe oublié : veuillez entrer les informations suivantes</h1>
        <form  action="oublie.php" method="POST">
        <!-- <form method="POST"> -->
        <table width='0%' border='0' cellspacing='0' cellpadding='2'>
        	<tr>
            <td style='text-align:right;font-weight:bold'>Nom du stock : </td> 
            <td> <input type="text" name="stockname" size='65' maxlength='65' placeholder='Exemple: bac-a-sable'</td>
            </tr>
            
        	<tr>
            <td style='text-align:right;font-weight:bold'>Identifiant : </td> 
            <td> <input type="text" name="id" size='65' maxlength='65' </td>
            </tr>

            <tr>
            <td style='text-align:right;font-weight:bold'>Email : </td> 
            <td> <input type="text" name="mail" size='65' maxlength='65'</td>
            </tr>

            <tr>
            <td colspan='1' style='text-align:center'> </td>
            <td> <input type="submit" value="Demander mot de passe" >
            <!--<input type="submit" value="Annuler" >  </td>-->
            </tr>
        </table>
        </form>
    </body>
</html>
<?php
}
?>