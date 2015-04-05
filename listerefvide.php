<?php
session_start();
if(isset($_POST['ok'])) {
		$message="<p>Paramétrage du filtre :</p><ul><li>Toutes les références ...</li> ";
		$requete="SELECT *, categorie.nom AS nomcat, destination.nom AS nomdest FROM article,categorie,destination WHERE article.id_categorie=categorie.id_categorie AND article.id_destination=destination.id_destination";
		// compléter la requete
		$etou = $_POST['etou0'];
		if($etou != 'ignorer'){
			if($etou == 'et'){
				$requete .= " AND (article.reference NOT IN (SELECT reference FROM journal))"; 
				//$requete .= "'"; 
				$message .= "<li>Mais uniquement les références vides :".$reference."</li>"; 
			}
			else{
				$requete .= " AND (article.reference IN (SELECT reference FROM journal))"; 
				//$requete .= "'"; 
				$message .= "<li>Mais en excluant les références vides :".$reference."</li>"; 
			}
		}
		$etou = $_POST['etou1'];
		$connexion=mysqli_connect("localhost", $_SESSION['stocklogin'], $_SESSION['stockpwd'])
			or die('Connexion au serveur impossible'. mysqli_error($connexion));
		mysqli_select_db($connexion, $_SESSION['stockdb'])
			or die('Selection de la base impossible' . mysqli_error($connexion));
		if($etou != 'ignorer'){
			// chercher l'id de la catégorie
			$categorie = $_POST['categorie'];
			$result=mysqli_query($connexion, "SELECT id_categorie FROM categorie WHERE nom='$categorie'")
				or die('Requete SELECT impossible'. mysqli_error($connexion));
			$ligne=mysqli_fetch_assoc($result); 
			$id_categorie=$ligne['id_categorie'];
			// compléter la requete
			if($etou == 'et'){
				$requete .= ' AND ';
				$message .= "<li>Mais  uniquement dans la catégorie :".$categorie."</li>";
			}
			else{
				$requete .= ' AND NOT ';
				$message .= "<li>Mais en excluant la catégorie :".$categorie."</li>";
			}
			$requete .= " article.id_categorie='".$id_categorie."'"; 
		}
		$etou = $_POST['etou2'];
		if($etou != 'ignorer'){
			// chercher l'id de la destination
			$destination = $_POST['destination'];
			$result=mysqli_query($connexion, "SELECT id_destination FROM destination WHERE nom='$destination'")
				or die('Requete SELECT impossible'. mysqli_error($connexion));
			$ligne=mysqli_fetch_assoc($result); 
			$id_destination=$ligne['id_destination'];
			// compléter la requete
			if($etou == 'et'){
				$requete .= ' AND ';
				$message .= "<li>Mais  uniquement dans la destination :".$destination."</li>";
			}
			else{
				$requete .= ' AND NOT ';
				$message .= "<li>Mais en excluant la destination :".$destination."</li>";
			}
			$requete .= " article.id_destination=".$id_destination.""; 
		}
		$message .="</ul>";
		//lancer la requete
		$result=mysqli_query($connexion, $requete)
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		echo"<p><a href='refvide.php'>Retour au formulaire de consultation</a></br></p>";       		
		$maintenant=date("d/m/Y \-\- H \h i");
		echo "<p>Référence(s) vide(s) à la date du : ".$maintenant."</p>";
		echo "<p>$message</p>";
		echo "<table border width=80% cellpadding=6 >";
		echo "<tr><td align='center'>ID</td><td align='left'>Catégorie</td><td align='left'>Destination</td>
			<td align='left'>Référence</td><td align='left'>Désignation</td>td align='left'>UDV</td>td align='left'>Seuil</td><td align='right'>Créateur</td>
			<td align='right'>Date/heure création</td></tr>";
		while($ligne=mysqli_fetch_assoc($result)) {
			extract($ligne);
			$id=$ligne['id_article'];
			//$categorie="tbd";
			$categorie = $ligne ['nomcat'];
			//$destination="tbd";
			$destination = $ligne ['nomdest'];
			$reference=$ligne['reference'];
			$designation=$ligne['designation'];
			$createur=$ligne['createur_article'];
			$datecreation=$ligne['date_creation'];
			$udv=$ligne['udv'];
			$seuil=$ligne['seuilbas'];
			// préparation du tableau (ligne d'entête)			
			echo "<tr><td align='center'>$id</td><td align='left'>$categorie</td><td align='left'>$destination</td>
				<td align='left'>$reference</td><td align='left'>$designation</td><td align='left'>$udv</td><td align='left'>$seuilbas</td><td align='right'>$createur</td>
				<td align='right'>$datecreation</td></tr>"; 		
		}
		echo "</table>";

		mysqli_close($connexion);
}
?>
