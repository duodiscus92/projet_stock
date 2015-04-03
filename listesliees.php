<?php
	echo("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n");
	/* On récupère si elle existe la valeur de la destination envoyée par le formulaire */
	$iddest = isset($_POST['destination'])?$_POST['destination']:null;
	//echo $_POST['destination'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
    <head>
        <meta http-equiv="content-type" content="text/xml; charset=utf-8" />
        <title>Listes liees</title>
    </head>
    <body>
	    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="chgsousdestination">
			<!-- <fieldset style="border: 3px double #333399"> -->
				<!-- <legend>Selectionnez une destination</legend> -->

		        <td style='text-align:right;font-weight:bold'> Destination : </td>	    
				<select name="destination" id="destination" onchange="document.forms['chgsousdestination'].submit();">
		     	<!-- <select onChange="maSelection('sousdestination', 'destination')" id="destination"> -->
		     	  	<option value="-1">- - - Choisissez une destination - - -</option>
			        <?php
						$connexion=mysqli_connect("localhost", "stock", "stock")
							or die('Connexion au serveur impossible'. mysqli_error($connexion));
						mysqli_select_db($connexion, "stock")
							or die('Selection de la base impossible' . mysqli_error($connexion));
						$result=mysqli_query($connexion, "SELECT DISTINCT nom FROM destination ORDER BY nom")
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
					// est-ce qu'on a selectionné une destination ?
				    if(isset($iddest) && $iddest != -1) {
						$nom=$_POST['destination'];
						// recherche de l'id de la destination selectionnées
						$connexion=mysqli_connect("localhost", "stock", "stock")
							or die('Connexion au serveur impossible'. mysqli_error($connexion));
						mysqli_select_db($connexion, "stock")
							or die('Selection de la base impossible' . mysqli_error($connexion));
						$result=mysqli_query($connexion, "SELECT * FROM destination WHERE nom LIKE '$nom'")
							or die('Requete SELECT impossible'. mysqli_error($connexion));
						$row = mysqli_fetch_assoc($result);
						$id_destination=$row['id_destination'];
						// recuperation des valeurs de liste deroulante dans la base de donnée
						$connexion=mysqli_connect("localhost", "stock", "stock")
							or die('Connexion au serveur impossible'. mysqli_error($connexion));
						mysqli_select_db($connexion, "stock")
							or die('Selection de la base impossible' . mysqli_error($connexion));
						$result=mysqli_query($connexion, "SELECT nom FROM sous_destination WHERE id_destination_mere = ". $id_destination . " ORDER BY nom")
							or die('Requete SELECT impossible'. mysqli_error($connexion));
						mysqli_close($connexion);      
					    echo "<td style='text-align:right;font-weight:bold'>Sous-destination : </td>";  
						echo "<td><select name='sousdestination'>";
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
				<br /><input type="submit" name="ok" id="ok" value="Envoyer" />
			<!-- </fieldset> -->
		</form>
	</body>
</html>
 




