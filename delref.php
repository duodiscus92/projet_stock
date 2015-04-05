<?php
//session_start();
	include('stocklib.php');
	include('stock.conf.php');
	require('control_session.php');
	if($_SESSION['statut'] <4){
		echo("<h2>Votre statut ne vous autorise pas à modifier supprimer une référence </h2>");
		header ("Refresh: 3;URL=acceuil.php");
		exit();
}
?>
<?php
if(isset($_POST['ok'])){
	if($_POST['suprmod']=='ignorer'){
		// action :  ignorer
		echo '<script>alert("Vous n\'avez sélectionné aucune action");</script>';
		//msgbox($info. "Vous n'avez sélectionné aucune action");
	}
	else if($_POST['suprmod']=='supprimer'){
		// action : supprimer une référence
		if(!empty($_POST['refid'])){
			//on traite l'id et si la référence existe et est vide on supprime
			$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
				or die('Connexion au serveur impossible'. mysqli_error($connexion));
			mysqli_select_db($connexion, $_SESSION['stockdb'])
				or die('Selection de la base impossible' . mysqli_error($connexion));
			// preparation de l'id
			$refid = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['refid']));
			// verifier que la ref existe et qu'elle est vide
			$result=mysqli_query($connexion, "SELECT * FROM article WHERE id_article LIKE '$refid' AND (article.reference NOT IN (SELECT reference FROM journal))")
				or die('Requete SELECT impossible'. mysqli_error($connexion));
			if($row = mysqli_fetch_assoc($result)){
				// la reference existe et elle est  vide, on peut donc la supprimer
				$result=mysqli_query($connexion, "DELETE FROM article WHERE id_article ='$refid'")
					or die('Requete DELETE impossible'. mysqli_error($connexion));				
				echo '<script>alert("La référence a été supprimée avec succès");</script>';
				//msgbox($info. "La référence a été supprimée avec succès");
			}
			else{
				// soit la référence n'existe pas soit elle est non vide
				echo '<script>alert("Reference inexistante ou non vide");</script>';
				//msgbox($error. "Reference inexistante ou non vide");
			}
			mysqli_close($connexion);
		}
		else{
			msgbox($error . $msgtab['FILLALLITEM'][$lang]);
		}
	}
	else if(!empty($_POST['refid']) && (!empty($_POST['reference']) || !empty($_POST['designation']) 
			|| !empty($_POST['udv']) || !empty($_POST['seuilbas'])) ){
		// action  : modifier la référence
		// connexion à la bd
		$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		mysqli_select_db($connexion, $_SESSION['stockdb'])
			or die('Selection de la base impossible' . mysqli_error($connexion));
		// preparation de l'id
		$refid = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['refid']));
		// verifier que la référence existe déjà
		$result=mysqli_query($connexion, "SELECT * FROM article WHERE id_article LIKE '$refid'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		if($row = mysqli_fetch_assoc($result)){
			// la référence existe, on va procéder aux changements
			// on prépare la requete car son format est variable
			$requete = "UPDATE article SET ";
			// on complete la requete en fonction des champs renseignés
			if(!empty($_POST['reference'])){
				$ref = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['reference']));
				//$requete .= "reference=UPPER(".$ref.")";
				$virgule=',';
			}
			else{
				$virgule="";
			}
			if(!empty($_POST['designation'])){
				$designation = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['designation']));
				//$requete .= $virgule. " designation=UPPER(".$designation.")";
				$virgule=',';
			}
			else{
				virgule="";
			}
			if(!empty($_POST['udv'])){
				$udv = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['udv']));
				//$requete .= $virgule. " udv=".$udv."";
				$virgule=',';
			}
			else{
				$virgule="";
			}
			if(!empty($_POST['seuilbas'])){
				$seuilbas = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['seuilbas']));
				//$requete .= $virgule. " seuilbas=".$seuilbas."";
			}
			// quelque soient les champs renseignés ci-dessus, la requete doit être complétée comme ci-dessous
			$createur = $_SESSION['id'];
			//$requete .= ", date_creation=NOW(), createur_article='$createur' WHERE id_article=".$refid."";
			echo $requete;
			mysqli_query($connexion, $requete)
				or die('Requete UPDATE impossible'. mysqli_error($connexion));
			mysqli_close($connexion);
			echo '<script>alert("La référence a été modifiée avec succès");</script>';
			//msgbox($info. "La référence a été modifiée avec succès");
		}
		else {
			// la référence n'existe pas
			echo '<script>alert("La référence n\'existe pas");</script>';
			//msgbox($error. "La référence n'existe pas");
			//header ("Refresh: 3;URL=delref.php");
			//exit();
		}
	}
	else {
		//msgbox($error . $msgtab['FILLALLITEM'][$lang]);
		echo '<script>alert("Au moins un champ doit être renseigné (en plus de l\'identifiant)");</script>';
		//header ("Refresh: 3;URL=delref.php");
	}
}

?>
<!DOCTYPE html>

<html>
    <head>
        <meta http-equiv="content-type" content="text/xml; charset=utf-8" />
        <link rel="stylesheet" href="style.css" />
        <title>Modifier ou supprimer une référence</title>
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
		Attention ! pour supprimer une référence seul le champ Identidiant doit être renseigné (les autres sont ignorés)<br>
		Attention ! pour modifier une référence renseigner au moins : Identifiant et un autre champ (les champs vides ne sont pas modifiés)<br>
		Attention ! pour connaitre la valeur de l'identifiant d'une référence, consulter la liste des références<br>
		</p>
	    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="chg">
            <label for="suprmod">Action :</label>
            <input type="radio" name="suprmod" value="modifier" >Modifier la référence
            <input type="radio" name="suprmod" value="supprimer" >Supprimer la référence
            <input type="radio" name="suprmod" value="ignorer" checked="checked" >Ne rien faire</br>

            <label for="refid">Identifiant (*) :</label>
            <input type="number" id="refid" name="refid" size='65' maxlength='65' value="<?php if (isset($_POST['refid'])){echo $_POST['refid'];} ?>"</td></br>

            <label for="reference">Référence (*) :</label>
            <input type="text" id="reference" name="reference" size='65' maxlength='65' value="<?php if (isset($_POST['reference'])){echo $_POST['reference'];} ?>"</td></br>

            <label for="designation">Désignation (*) :</label>
            <input type="text" id="designation" name="designation" size='65' maxlength='65' value="<?php if (isset($_POST['designation'])){echo $_POST['designation'];} ?>"</td></br>
                       
            <label for="udv">UDV :</label>
            <input type="number" id="udv" name="udv" size='65' maxlength='65' value="<?php if (isset($_POST['udv'])){echo $_POST['udv'];} ?>"</td></br>
                       
            <label for="seuilbas">Seuil bas :</label>
            <input type="number" id="seuilbas" name="seuilbas" size='65' maxlength='65' value="<?php if (isset($_POST['seuilbas'])){echo $_POST['seuilbas'];} ?>"</td></br>

            <label for="bouton">Validation :</label>
            <input type="submit" name="ok" id="ok" value="Valider modification ou suppression" ></td>
        </form>
	</body>
</html>