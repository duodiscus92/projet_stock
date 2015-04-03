<!--
<?php
	echo("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n");
	/* On récupère si elle existe la valeur de la categorie envoyée par le formulaire */
	$iddest = isset($_POST['categorie'])?$_POST['categorie']:null;
	//echo $_POST['categorie'];
?>
<?php
if(isset($_POST['ok']) && isset($_POST['categorie']) && $_POST['categorie'] != "")
{
    $categorie_selectionnee = $_POST['categorie'];
    $souscategorie_selectionne = $_POST['souscategorie'];
?>
<p>Vous avez choisi la sous-categorie <?php echo($souscategorie_selectionne); ?> dans la categorie <?php echo($categorie_selectionnee); ?></p>
<?php
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <meta http-equiv="content-type" content="text/xml; charset=utf-8" />
        <title>Listes liees</title>
    </head>
    <body>
	    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="chgsouscategorie">
-->
		        <!-- <td style='text-align:right;font-weight:bold'> Categorie : </td> -->
            	<label for="categorie">Categorie (*):</label>	    
				<!-- <select name="categorie" id="categorie" onchange="document.forms['chgsouscategorie'].submit();"> -->
				<select name="categorie" id="categorie" onchange="document.forms['chg'].submit();">
		     	<!-- <select onChange="maSelection('souscategorie', 'categorie')" id="categorie"> -->
		     	  	<option value="-1">- - - Choisissez une categorie - - -</option>
			        <?php
						$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
							or die('Connexion au serveur impossible'. mysqli_error($connexion));
						mysqli_select_db($connexion, $_SESSION['stockdb'])
							or die('Selection de la base impossible' . mysqli_error($connexion));
						$result=mysqli_query($connexion, "SELECT DISTINCT nom FROM categorie ORDER BY nom")
							or die('Requete SELECT impossible'. mysqli_error($connexion));
						mysqli_close($connexion);        	
						while($ligne=mysqli_fetch_assoc($result)) {
							extract($ligne);
							// astuce pour conserver le choix quand la page est reactualisée
							if(isset($iddest) && $iddest==$nom)
								echo"<option value='$nom'selected>$nom</option>";
							else
								echo"<option value='$nom'>$nom</option>";
						}
			        ?>
				</select>
				<?php
					// est-ce qu'on a selectionné une categorie ?
				    if(isset($iddest) && $iddest != -1) {
						$nom=$_POST['categorie'];
						// recherche de l'id de la categorie selectionnées
						$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
							or die('Connexion au serveur impossible'. mysqli_error($connexion));
						mysqli_select_db($connexion, $_SESSION['stockdb'])
							or die('Selection de la base impossible' . mysqli_error($connexion));
						$result=mysqli_query($connexion, "SELECT * FROM categorie WHERE nom LIKE '$nom'")
							or die('Requete SELECT impossible'. mysqli_error($connexion));
						$row = mysqli_fetch_assoc($result);
						$id_categorie=$row['id_categorie'];
						// recuperation des valeurs de liste deroulante dans la base de donnée
						$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
							or die('Connexion au serveur impossible'. mysqli_error($connexion));
						mysqli_select_db($connexion, $_SESSION['stockdb'])
							or die('Selection de la base impossible' . mysqli_error($connexion));
						$result=mysqli_query($connexion, "SELECT nom FROM sous_categorie WHERE id_categorie_mere = ". $id_categorie . " ORDER BY nom")
							or die('Requete SELECT impossible'. mysqli_error($connexion));
						mysqli_close($connexion);      
					    echo "<td style='text-align:right;font-weight:bold'>Sous-categorie : </td>";  
						echo "<td><select name='souscategorie' id='souscategorie'>";
						if($ligne=mysqli_fetch_assoc($result)){
							do{ 
								extract($ligne);
								echo"<option value='$nom'>$nom</option>\n";
							}while($ligne=mysqli_fetch_assoc($result));
						}
						else{
							echo'<option value="-1">- - - Il n\'existe pas de sous categorie - - -</option>';
						}
						echo "</select>(facultative)<td>";
					}
				?>				
<!--				
			<br /><input type="submit" name="ok" id="ok" value="Envoyer" />
		</form>
	</body>
</html>
-->




