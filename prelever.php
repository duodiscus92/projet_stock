<?php
//session_start();
	include('stocklib.php');
	include('stock.conf.php');
	require('control_session.php');
	if($_SESSION['statut'] <3){
		echo("<h2>Votre statut ne vous autorise pas à prélever dans le stock</h2>");
		header ("Refresh: 3;URL=acceuil.php");
		exit();
}
?>
<?php
	echo("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n");
	/* On récupère si elle existe la valeur de la reference envoyée par le formulaire */

	if(isset($_POST['reference'])){
		$iddest = $_POST['reference'];
	}
	else{
		$iddest = null;
	}
?>

<?php
if(isset($_POST['ok'])) {
	if(!empty($_POST['reference']) && !empty($_POST['selectquantite'])){
		//echo "Reference:" .$_POST['reference']."</br>";
		//echo "Quantité à prélever:" .$_POST['quantite']."</br>";		
		//echo 'Les champs sont renseignés ...<br>'; 
		// quantite à prélever
		$p = $_POST['selectquantite'];
		// retrouver l'id de la reference
		$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		mysqli_select_db($connexion, $_SESSION['stockdb']);
		$reference=$_POST['reference'];
		$result=mysqli_query($connexion, "SELECT * FROM journal WHERE reference='$reference'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		while($row = mysqli_fetch_assoc($result)){
			$id_mouvement=$row['id_mouvement'];
			$d = $row['quantite'];
			//echo "qté dispo: " .$d. " qté à prélever: " .$p. " !";
			sleep(2);
			// si quantité à disponible > quantité à prélever
			if($d > $p){
				$d = $d - $p;
				$result=mysqli_query($connexion, "UPDATE journal SET quantite ='$d' WHERE id_mouvement ='$id_mouvement'")
					or die('Requete SELECT impossible'. mysqli_error($connexion));
				break;
			}
			else {
				$p = $p - $d;
				$result=mysqli_query($connexion, "DELETE FROM journal WHERE id_mouvement ='$id_mouvement'")
					or die('Requete SELECT impossible'. mysqli_error($connexion));
			}
			// reference suivante
			$result=mysqli_query($connexion, "SELECT * FROM journal WHERE reference='$reference'")
				or die('Requete SELECT impossible'. mysqli_error($connexion));
		}
		msgbox($info . $msgtab['ITEMREMOVED'][$lang]);
	}
}
?>

<!DOCTYPE html PUBLIC 
"-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- <!DOCTYPE html> -->

<html>
	<head>
	    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
        <title>Prélever</title>
        <link rel="stylesheet" href="style.css" />
        
    </head>
    
    <body>
    	<?php
			echo '<h1>Prélever un article dans le stock : ' .$_SESSION['stockname'].'</h1>';
			echo '<p> Session de : ' .$_SESSION['id']. ' --- Statut : '.$_SESSION['type_statut']. '</p>';
		?>
        <a href="acceuil.php">Retour au menu principal</a><br />
        <h2>Veuillez entrer les informations suivantes</h2>
	    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="chg">
        	<label for="reference">Réference :</label>	    
			<select name="reference" id="reference" onchange="document.forms['chg'].submit();">
	     	  	<option value="-1">- - - Choisissez une réference - - -</option>
		        <?php
					$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
						or die('Connexion au serveur impossible'. mysqli_error($connexion));
					mysqli_select_db($connexion, $_SESSION['stockdb'])
						or die('Selection de la base impossible' . mysqli_error($connexion));
					$result=mysqli_query($connexion, "SELECT DISTINCT reference FROM journal,article WHERE journal.id_article=article.id_article ORDER BY reference")
						or die('Requete SELECT impossible'. mysqli_error($connexion));
					//mysqli_close($connexion);        	
					while($ligne=mysqli_fetch_assoc($result)) {
						extract($ligne);
						// astuce pour conserver le choix quand la page est reactualisée
						if(isset($iddest) && $iddest==$reference)
							echo"<option value='$reference'selected>$reference</option>";
						else
							echo"<option value='$reference'>$reference</option>";
					}
		        ?>
			</select></td></br>
			<?php
				// est-ce qu'on a selectionné une reference ?
			    if(isset($iddest) && $iddest != -1) {
					$nom=$_POST['reference'];
					// recherche de la quantité disponible dans la reference selectionnée
					$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
						or die('Connexion au serveur impossible'. mysqli_error($connexion));
					mysqli_select_db($connexion, $_SESSION['stockdb'])
						or die('Selection de la base impossible' . mysqli_error($connexion));
					$result=mysqli_query($connexion, "SELECT SUM(quantite) AS quantite_totale FROM journal WHERE reference='$nom'")
						or die('Requete SELECT impossible'. mysqli_error($connexion));
					$row = mysqli_fetch_assoc($result);
					$quantite_totale=$row['quantite_totale'];
					//echo $quantite_totale;
					mysqli_close($connexion);      
				    //echo "<td style='text-align:right;font-weight:bold'>Sous-categorie : </td>";  
	            	echo "<label for='selectquantite'>Quantité:</label>";	    
					echo "<td><select name='selectquantite' id='selectquantite'>";
					for ($i=1; $i<=$quantite_totale; $i++){
							echo"<option value='$i'>$i</option>\n";
					}
					echo "</select><td></br>";
				}
			?>
           
        	<label for="bouton">Validation :</label>
            <input type="submit" name="ok" id="ok" value="Valider le retrait du stock" ></td>
        </form>
    </body>
</html>