<!--
<?php
	echo("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n");
	/* On r�cup�re si elle existe la valeur de la destination envoy�e par le formulaire */
	$iddest = isset($_POST['destination'])?$_POST['destination']:null;
	//echo $_POST['destination'];
?>
<?php
if(isset($_POST['ok']) && isset($_POST['destination']) && $_POST['destination'] != "")
{
    $destination_selectionnee = $_POST['destination'];
    $sousdestination_selectionne = $_POST['sousdestination'];
?>
<p>Vous avez choisi la sous-destination <?php echo($sousdestination_selectionne); ?> dans la destination <?php echo($destination_selectionnee); ?></p>
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
	    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="chgsousdestination">
-->
		        <!-- <td style='text-align:right;font-weight:bold'> Destination : </td> -->	    
            	<label for="destination">Destination (*):</label>	 
            	<!-- ligne ci-dessus a valider si test de la feuille, neutraliser sinon -->   
				<!-- <select name="destination" id="destination" onchange="document.forms['chgsousdestination'].submit();"> -->
				<!-- ligne ci-dessous a valider si feuille integree avec autres feuilles, neutraliser sinon -->
				<select name="destination" id="destination" onchange="document.forms['chg'].submit();">
		     	<!-- <select onChange="maSelection('sousdestination', 'destination')" id="destination"> -->
		     	  	<option value="-1">- - - Choisissez une destination - - -</option>
			        <?php
						$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
							or die('Connexion au serveur impossible'. mysqli_error($connexion));
						mysqli_select_db($connexion, $_SESSION['stockdb'])
							or die('Selection de la base impossible' . mysqli_error($connexion));
						$result=mysqli_query($connexion, "SELECT DISTINCT nom FROM destination ORDER BY nom")
							or die('Requete SELECT impossible'. mysqli_error($connexion));
						mysqli_close($connexion);        	
						while($ligne=mysqli_fetch_assoc($result)) {
							extract($ligne);
							// astuce pour conserver le choix quand la page est reactualis�e
							if(isset($iddest) && $iddest==$nom)
								echo"<option value='$nom'selected>$nom</option>";
							else
								echo"<option value='$nom'>$nom</option>";
						}
			        ?>
				</select>
				<?php
					// est-ce qu'on a selectionn� une destination ?
				    if(isset($iddest) && $iddest != -1) {
						$nom=$_POST['destination'];
						// recherche de l'id de la destination selectionn�es
						$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
							or die('Connexion au serveur impossible'. mysqli_error($connexion));
						mysqli_select_db($connexion, $_SESSION['stockdb'])
							or die('Selection de la base impossible' . mysqli_error($connexion));
						$result=mysqli_query($connexion, "SELECT * FROM destination WHERE nom LIKE '$nom'")
							or die('Requete SELECT impossible'. mysqli_error($connexion));
						$row = mysqli_fetch_assoc($result);
						$id_destination=$row['id_destination'];
						// recuperation des valeurs de liste deroulante dans la base de donn�e
						$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
							or die('Connexion au serveur impossible'. mysqli_error($connexion));
						mysqli_select_db($connexion, $_SESSION['stockdb'])
							or die('Selection de la base impossible' . mysqli_error($connexion));
						$result=mysqli_query($connexion, "SELECT nom FROM sous_destination WHERE id_destination_mere = ". $id_destination . " ORDER BY nom")
							or die('Requete SELECT impossible'. mysqli_error($connexion));
						mysqli_close($connexion);      
					    echo "<td style='text-align:right;font-weight:bold'>Sous-destination : </td>";  
						echo "<td><select name='sousdestination' id='sousdestination'>";
						if($ligne=mysqli_fetch_assoc($result)){
							do{ 
								extract($ligne);
								echo"<option value='$nom'>$nom</option>\n";
							}while($ligne=mysqli_fetch_assoc($result));
						}
						else{
							echo'<option value="-1">- - - Il n\'existe pas de sous destination - - -</option>';
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
 




