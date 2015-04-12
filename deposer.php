<?php
//session_start();
	include('stocklib.php');
	include('stock.conf.php');
	require('control_session.php');
	if($_SESSION['statut'] <3){
		echo("<h2>Votre statut ne vous autorise pas à déposer dans le stock</h2>");
		header ("Refresh: 3;URL=acceuil.php");
		exit();
}
?>
<?php
if(isset($_POST['ok'])) {
	if(!empty($_POST['reference']) && !empty($_POST['prixha']) && !empty($_POST['prixttc']) && !empty($_POST['quantite'])&& !empty($_POST['dateha'])){
		//echo "Reference:" .$_POST['reference']."</br>";
		//echo "Prix HT:" .$_POST['prixha']."</br>";
		//echo "Prix TTC:" .$_POST['prixttc']."</br>";
		//echo "Quantité:" .$_POST['quantite']."</br>";		
		//echo "Date HA:" .$_POST['dateha']."</br>";
		//echo 'Destination : tous les champs doivent obligatoirement être renseignés ... <br>';
		//echo 'Les champs sont renseignés ...<br>'; 

		// retrouver l'id de la reference
		$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		mysqli_select_db($connexion, $_SESSION['stockdb'])
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		$reference=$_POST['reference'];
		$result=mysqli_query($connexion, "SELECT * FROM article WHERE reference LIKE '$reference'")
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		if($row = mysqli_fetch_assoc($result))
			$id_article=$row['id_article'];
		else {
			echo "La reference '.$reference. n\'existe pas ... <br>";
			//echo "Vous allez être redirigé vers le menu principal ...<br>";
			header ("Refresh: 3;URL=deposer.php");
			exit();
		}

		// preparer les valeurs à inserer dans la table
		$prixht=$_POST['prixha'];
		$prixttc=$_POST['prixttc'];
		$quantité=$_POST['quantite'];
		//$dateha=$_POST['dateha'];
		$dateha = date('Y-m-d H:i:s', strtotime($_POST['dateha']));
		$createur = $_SESSION['id'];
		//echo "créateur : '.$createur.'<br>";
		mysqli_query($connexion, "INSERT INTO journal VALUES('', '$id_article', '$prixht', '$prixttc', '$quantité', '$dateha', NOW(), '$createur')")
			or die('Requete INSERT impossible'. mysqli_error($connexion));
		mysqli_close($connexion);
		msgbox($info . $msgtab['ITEMSTORED'][$lang]);
		//header ("Refresh: 3;URL=deposer.php");
	}
	else {
		msgbox($error . $msgtab['FILLALLITEM'][$lang]);
		//header ("Refresh: 3;URL=deposer.php");		
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
        <title>Déposer</title>

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
	        /*
	        if(quantite.match(prixRE)){
		        prixttc = parseFloat(prixttc);
	        }
		    else {
		    	errors += "Quantité : Veuillez taper un nombre(max 2 ch. après la virgule)\n";
	    	}
	    	*/
			if(errors == ""){
		        //prixttc.innerHTML = prixht*tva/100;
	        }
		    else{
	        	alert(errors);
        	}
	      }	      
	      //]]>
	    </script>
    </head>
    
    <body>
    	<?php
			echo '<h1>Déposer un article dans le stock : '.$_SESSION['stockname']. '</h1>';
			echo '<p> Session de : ' .$_SESSION['id']. ' --- Statut : '.$_SESSION['type_statut']. '</p>';
		?>
        <a href="acceuil.php">Retour au menu principal</a><br />
        <h2>Veuillez entrer les informations suivantes</h2>
	    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" id="chg">
            <!-- <td style='text-align:right;font-weight:bold'>Catégorie mère : </td> -->
            <label for="reference">Référence :</label>
            <select name="reference" id="reference">
            <?php
				$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
					or die('Connexion au serveur impossible'. mysqli_error($connexion));
				mysqli_select_db($connexion, $_SESSION['stockdb'])
					or die('Selection de la base impossible' . mysqli_error($connexion));
				$result=mysqli_query($connexion, "SELECT reference,designation FROM article ORDER BY reference")
					or die('Requete SELECT impossible'. mysqli_error($connexion));
 				while($ligne=mysqli_fetch_assoc($result)) {
					extract($ligne);
					$reference_designation= "- " .$reference. " - " .$designation. " -";
					echo"<option value='$reference'>$reference_designation\n";
				}
				mysqli_close($connexion);            	
            ?>
            </select> </td></br>
            
            <label for="prixha">Prix d'achat (HT):</label>
            <input type="text" id="prixha" name="prixha" size='65' placeholder='Exemple: 235.95' maxlength='65' value="<?php if (isset($_POST['prixha'])){echo $_POST['prixha'];} ?>"</td></br>

			<label for="prixttc">Prix d'achat (TTC):</label>
            <input type="text" id="prixttc" name="prixttc" size='65' placeholder='Exemple: 5.5' maxlength='65' value="<?php if (isset($_POST['prixttc'])){echo $_POST['prixttc'];} ?>"</td></br>
			
            <label for="quantite">Quantité:</label>
            <input type="number" id="quantite" name="quantite" size='65' placeholder='Exemple: 123' maxlength='65' value="<?php if (isset($_POST['quantite'])){echo $_POST['quantite'];} ?>"</td></br>
              
            <label for="datePickerTab">Date d'achat (+) :</label>
			<div id = "datePickerTab">
				<input type = "text" name="dateha" id = "datePicker" />
			</div>
			<br></br>
			<br></br>
			<br></br>
			<br></br>
			<br></br>
            
        	<label for="bouton">Validation :</label>
        	<!-- <button type = "button" name="ok" id="ok"
                onclick = "calcTTC()">
          	Valider le dépot dans le stock
        	</button> -->
        	<!--<label for="bouton">Validation :</label> -->
            <input type="submit" name="ok" id="ok" value="Valider le dépot dans le stock" onclick = "filtrerVirguleFixe()"></td>
        </form>
    </body>
</html>