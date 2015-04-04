<?php
//session_start();
	require('control_session.php');
	if($_SESSION['statut'] <2){
		echo("<h2>Votre statut ne vous autorise pas consulter les références vides</h2>");
		header ("Refresh: 3;URL=acceuil.php");
		exit();
}
?>
<!DOCTYPE html PUBLIC 
"-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- <!DOCTYPE html> -->

<html>
	<head>
	    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
        <title>Voir références</title>

        <link rel="stylesheet" href="style.css" />
        
		<script type = "text/javascript"
			src = "jquery-1.3.2.min.js"></script>
			
		<script type = "text/javascript"
			src = "jquery-ui-1.7.2.custom.min.js"></script>

    </head>
    
    <body>
    	<?php
			echo '<h1>Consulter les références vides (sans stock) : '.$_SESSION['stockname']. '</h1>';
			echo '<p> Session de : ' .$_SESSION['id']. ' --- Statut : '.$_SESSION['type_statut']. '</p>';
		?>
        <a href="acceuil.php">Retour au menu principal</a><br />
        <h2>Veuillez configurer le filtrage</h2>
	    <!--<form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="chg"> -->
	    <form action="listerefvide.php" method="post" id="chg">
            <label>Tout :</label>
	    	<td>Toutes les références</td></br>
            <p> </p>

			<fieldset>
            <label for="etou">Références vides :</label>
            <input type="radio" name="etou" value="et" >Uniquement
            <input type="radio" name="etou" value="ou" >Exclure
            <input type="radio" name="etou" value="ignorer" checked="checked" >Ignorer</br>
        	</fieldset>
            <p> </p>

			<fieldset>
            <label for="etou1">Filtre 2 :</label>
            <input type="radio" name="etou1" value="et" >Uniquement
            <input type="radio" name="etou1" value="ou" >Exclure
            <input type="radio" name="etou1" value="ignorer" checked="checked" >Ignorer</br>
            
            <!-- <p> </p> -->

            <label for="categorie">Catégorie :</label>
            <select name="categorie" id="categorie">
            <?php
				$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
					or die('Connexion au serveur impossible'. mysqli_error($connexion));
				mysqli_select_db($connexion, $_SESSION['stockdb'])
					or die('Selection de la base impossible' . mysqli_error($connexion));
				$result=mysqli_query($connexion, "SELECT nom FROM categorie ORDER BY nom")
					or die('Requete SELECT impossible'. mysqli_error($connexion));
 				while($ligne=mysqli_fetch_assoc($result)) {
					extract($ligne);
					echo"<option value='$nom'>$nom\n";
				}
				mysqli_close($connexion);            	
            ?>
            </select> </td></br>
        	</fieldset>

            <p> </p>
			<fieldset>
            <label for="etou2">Filtre 3 :</label>
            <input type="radio" id="etou2" name="etou2" value="et" />Uniquement
            <input type="radio" id="etou2" name="etou2" value="ou" />Exclure
            <input type="radio" id="etou2" name="etou2" value="ignorer" checked="checked" />Ignorer</br>

            <!-- <p> </p> -->
                        
            <label for="destination">Destination :</label>
            <select name="destination" id="destination">
            <?php
				$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
					or die('Connexion au serveur impossible'. mysqli_error($connexion));
				mysqli_select_db($connexion, $_SESSION['stockdb'])
					or die('Selection de la base impossible' . mysqli_error($connexion));
				$result=mysqli_query($connexion, "SELECT nom FROM destination ORDER BY nom")
					or die('Requete SELECT impossible'. mysqli_error($connexion));
 				while($ligne=mysqli_fetch_assoc($result)) {
					extract($ligne);
					echo"<option value='$nom'>$nom\n";
				}
				mysqli_close($connexion);            	
            ?>
            </select> </td></br>
            </fieldset>
            <br> </br>
            <br> </br>
 
        	<label for="bouton">Validation :</label>
            <input type="submit" name="ok" id="ok" value="Lancer l'examen des références vides" /></td>
        </form>
    </body>
</html>