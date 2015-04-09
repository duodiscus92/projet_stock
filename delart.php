<?php
//session_start();
	include('stocklib.php');
	include('stock.conf.php');
	require('control_session.php');
	if($_SESSION['statut'] <3){
		echo("<h2>Votre statut ne vous autorise pas à modifier supprimer un article </h2>");
		header ("Refresh: 3;URL=acceuil.php");
		exit();
}
?>
<?php
if(isset($_POST['ok'])){
	if($_POST['suprmod']=='ignorer'){
		// action :  ignorer
		//echo '<script>alert("Vous n\'avez sélectionné aucune action");</script>';
		msgbox($info. "Vous n'avez sélectionné aucune action");
	}
	else if($_POST['suprmod']=='supprimer'){
		// action : supprimer un article
		if(!empty($_POST['refid'])){
			//on traite l'id et si la référence existe et est vide on supprime
			$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
				or die('Connexion au serveur impossible'. mysqli_error($connexion));
			mysqli_select_db($connexion, $_SESSION['stockdb'])
				or die('Selection de la base impossible' . mysqli_error($connexion));
			// preparation de l'id
			$refid = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['refid']));
			// verifier que l'article existe 
			$result=mysqli_query($connexion, "SELECT * FROM journal WHERE id_mouvement LIKE '$refid'")
				or die('Requete SELECT impossible'. mysqli_error($connexion));
			if($row = mysqli_fetch_assoc($result)){
				$msg = "Vous allez supprimer l'article : ". $row['reference']."";
				msgbox($info.$msg);
				// l'article existe, on peut donc le supprimer
				$result=mysqli_query($connexion, "DELETE FROM journal WHERE id_mouvement ='$refid'")
					or die('Requete DELETE impossible'. mysqli_error($connexion));				
				//echo '<script>alert("L'article a été supprimé avec succès");</script>';
				msgbox($info. "L'article a été supprimé avec succès");
			}
			else{
				// L'article n'existe pas
				//echo '<script>alert("Article inexistant");</script>';
				msgbox($error. "Article inexistant");
			}
			mysqli_close($connexion);
		}
		else{
			msgbox($error . $msgtab['FILLALLITEM'][$lang]);
		}
	}
	else if(!empty($_POST['refid']) && (!empty($_POST['prixht']) || !empty($_POST['prixttc']) 
			|| !empty($_POST['quantite']) || !empty($_POST['dateha'])) ){
		// action  : modifier l'article
		// connexion à la bd
		$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		mysqli_select_db($connexion, $_SESSION['stockdb'])
			or die('Selection de la base impossible' . mysqli_error($connexion));
		// preparation de l'id
		$refid = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['refid']));
		// verifier que l'article existe
		$result=mysqli_query($connexion, "SELECT * FROM journal WHERE id_mouvement LIKE '$refid'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		if($row = mysqli_fetch_assoc($result)){
			$msg = "Vous allez modifier l'article : ". $row['reference']."";
			msgbox($info.$msg);
			// l'article existe, on va procéder aux changements
			// on prépare la requete car son format est variable
			$requete = "UPDATE journal SET ";
			// on complete la requete en fonction des champs renseignés
			if(!empty($_POST['prixht'])){
				$prixht = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['prixht']));
				$requete .= "prixht=".$prixht."";
				$virgule=',';
			}
			else{
				$virgule="";
			}
			if(!empty($_POST['prixttc'])){
				$prixttc = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['prixttc']));
				$requete .= $virgule. " prixttc=UPPER('$prixttc')";
				$virgule=',';
			}
			else{
				$virgule="";
			}
			if(!empty($_POST['quantite'])){
				$quantite = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['quantite']));
				$requete .= $virgule. " quantite=".$quantite."";
				$virgule=',';
			}
			else{
				$virgule="";
			}
			if(!empty($_POST['dateha'])){
				$dateha = date('Y-m-d H:i:s', strtotime($_POST['dateha']));
				//$dateha = mysqli_real_escape_string($connexion, htmlspecialchars($_POST['dateha']));
				$requete .= $virgule. " dateha='$dateha'";
			}
			// quelque soient les champs renseignés ci-dessus, la requete doit être complétée comme ci-dessous
			$createur = $_SESSION['id'];
			$requete .= ", date_creation=NOW(), createur_mouvement='$createur' WHERE id_mouvement=".$refid."";
			echo $requete;
			mysqli_query($connexion, $requete)
				or die('Requete UPDATE impossible'. mysqli_error($connexion));
			mysqli_close($connexion);
			//echo '<script>alert("L'aticle a été modifié avec succès");</script>';
			msgbox($info. "L'article a été modifié avec succès");
		}
		else {
			// L'article n'existe pas
			//echo '<script>alert("L'article n\'existe pas");</script>';
			msgbox($error. "L'article n'existe pas");
			//header ("Refresh: 3;URL=delref.php");
			//exit();
		}
	}
	else {
		msgbox($error . $msgtab['FILLONEITEM'][$lang]);
		//echo '<script>alert("Au moins un champ doit être renseigné (en plus de l\'identifiant)");</script>';
		//header ("Refresh: 3;URL=delref.php");
	}
}

?>
<!DOCTYPE html>

<html>
    <head>
        <meta http-equiv="content-type" content="text/xml; charset=utf-8" />
        <link rel="stylesheet" href="style.css" />
		<script type = "text/javascript"
			src = "jquery-1.3.2.min.js"></script>
		<script type = "text/javascript"
			src = "jquery-ui-1.7.2.custom.min.js"></script>
		<script type = "text/javascript">    
		//<![CDATA[
		
		$(init);
		
		function init(){
		  //$("h1").addClass("ui-widget-header");
		  
		  $("#datePicker").datepicker();
		  		  
		} // end init
		
		//]]>
		</script>
					
	    <script type = "text/javascript">
	      //<![CDATA[
	      function filtrerVirguleFixe(){
	        var prixht = document.getElementById("prixha").value;
	        var  prixttc = document.getElementById("prixttc").value;
	        var quantite = document.getElementById("quantité").value;

	        errors = "";
	        prixRE=/^[1-9]*[. ][0-9]{2}$/;
 
    	    if(prixht.match(prixRE)){
		        prixht = parseFloat(prixht);
	        }
		    else{
		    	errors += "Prix HT : Veuillez taper un nombre (max 2 ch. après la virgule)\n";
	    	}
	        if(prixttc.match(prixRE)){
		        prixttc = parseFloat(prixttc);
	        }
		    else {
		    	errors += "prixttc : Veuillez taper un nombre(max 2 ch. après la virgule)\n";
	    	}
	    </script>

        <title>Modifier ou supprimer un article</title>
    </head>
    <body>      
    	<?php
			echo '<h1>Modifier ou supprimer un article  pour : '.$_SESSION['stockname']. '</h1>';
			echo '<p> Session de : ' .$_SESSION['id']. ' ---  Statut : '.$_SESSION['type_statut']. '</p>';
		?>
        <a href="acceuil.php">Retour au menu principal</a><br />
        <h2>Veuillez entrer les informations suivantes</h2>
		<p> 
		Attention ! pour supprimer un article seul le champ Identidiant doit être renseigné (les autres sont ignorés)<br>
		Attention ! pour modifier un article renseigner au moins : Identifiant et un autre champ (les champs vides ne sont pas modifiés)<br>
		Attention ! pour connaitre la valeur de l'identifiant d'un article, consulter le stock<br>
		</p>
	    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="chg">
            <label for="suprmod">Action :</label>
            <input type="radio" name="suprmod" value="modifier" >Modifier l'article
            <input type="radio" name="suprmod" value="supprimer" >Supprimer l'article
            <input type="radio" name="suprmod" value="ignorer" checked="checked" >Ne rien faire</br>

            <label for="refid">Identifiant (*) :</label>
            <input type="number" id="refid" name="refid" size='65' maxlength='65' value="<?php if (isset($_POST['refid'])){echo $_POST['refid'];} ?>"</td></br>

            <label for="prixht">Prix d'achat (HT):</label>
            <input type="text" id="prixht" name="prixht" size='65' placeholder='Exemple: 235.95' maxlength='65' value="<?php if (isset($_POST['prixht'])){echo $_POST['prixht'];} ?>"</td></br>

			<label for="prixttc">Prix d'achat (TTC):</label>
            <input type="text" id="prixttc" name="prixttc" size='65' placeholder='Exemple: 5.5' maxlength='65' value="<?php if (isset($_POST['prixttc'])){echo $_POST['prixttc'];} ?>"</td></br>
			
            <label for="quantite">Quantité:</label>
            <input type="number" id="quantite" name="quantite" size='65' placeholder='Exemple: 123' maxlength='65' value="<?php if (isset($_POST['quantite'])){echo $_POST['quantite'];} ?>"</td></br>
              
            <label for="datePickerTab">Date d'achat :</label>
			<div id = "datePickerTab">
				<input type = "text" name="dateha" id = "datePicker" />
			</div>
			<br></br>
			<br></br>
			<br></br>
			<br></br>
			<br></br>
            <label for="bouton">Validation :</label>
            <input type="submit" name="ok" id="ok" value="Valider modification ou suppression" onclick = "filtrerVirguleFixe()"></td>
        </form>
	</body>
</html>