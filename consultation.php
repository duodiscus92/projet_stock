<?php
session_start();
if(isset($_POST['ok'])) {
		$message="<p>Paramétrage du filtre :</p><ul><li>Tout le stock</li> ";
		$requete="SELECT *,journal.date_creation AS jdcr , article.reference AS aref FROM journal,article,categorie WHERE  journal.id_article=article.id_article AND article.id_categorie=categorie.id_categorie";
		//$requeteprixtotal="SELECT SUM(prixttc) AS prix_total FROM journal,article WHERE  journal.reference=article.reference";
		//$requetequantitetotal="SELECT SUM(quantite) AS quantite_total FROM journal,article WHERE  journal.reference=article.reference";
		// compléter la requete
		$etou = $_POST['etou0'];
		$reference = $_POST['reference'];
		if($etou != 'ignorer'){
			if($etou == 'et'){
				$requete .= " AND article.reference='".$reference.""; 
				$requete .= "'"; 
				//$requeteprixtotal .= " AND journal.reference='".$reference.""; 
				//$requeteprixtotal .= "'"; 
				//$requetequantitetotal .= " AND journal.reference='".$reference.""; 
				//$requetequantitetotal .= "'"; 
				$message .= "<li>Mais uniquement dans la référence :".$reference."</li>"; 
			}
			else{
				$requete .= " AND NOT article.reference='".$reference.""; 
				$requete .= "'"; 
				//$requeteprixtotal .= " AND NOT journal.reference='".$reference.""; 
				//$requeteprixtotal .= "'"; 
				//$requetequantitetotal .= " AND NOT journal.reference='".$reference.""; 
				//$requetequantitetotal .= "'"; 
				$message .= "<li>Mais en excluant la référence :".$reference."</li>"; 
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
				//$requeteprixtotal .= ' AND '; 
				//$requetequantitetotal .= ' AND '; 
				$message .= "<li>Mais  uniquement dans la catégorie :".$categorie."</li>";
			}
			else{
				$requete .= ' AND NOT ';
				//$requeteprixtotal .= ' AND NOT'; 
				//$requetequantitetotal .= ' AND NOT'; 
				$message .= "<li>Mais en excluant la catégorie :".$categorie."</li>";
			}
			$requete .= " article.id_categorie='".$id_categorie."'"; 
			//$requeteprixtotal .= " article.id_categorie='".$id_categorie."'"; 
			//$requetequantitetotal .= " article.id_categorie='".$id_categorie."'"; 
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
				//$requeteprixtotal .= ' AND '; 
				//$requetequantitetotal .= ' AND '; 
				$message .= "<li>Mais  uniquement dans la destination :".$destination."</li>";
			}
			else{
				$requete .= ' AND NOT ';
				//$requeteprixtotal .= ' AND NOT'; 
				//$requetequantitetotal .= ' AND NOT'; 
				$message .= "<li>Mais en excluant la destination :".$destination."</li>";
			}
			$requete .= " article.id_destination=".$id_destination.""; 
			//$requeteprixtotal .= " article.id_destination=".$id_destination.""; 
			//$requetequantitetotal .= " article.id_destination=".$id_destination.""; 
		}
		$message .="</ul>";
		//lancer la requete
		//echo "Requete: " .$requete. " ";
		//echo "Requete: " .$requeteprixtotal. " ";
		//echo "Requete: " .$requetequantitetotal. " ";
		$result=mysqli_query($connexion, $requete)
			or die('Requete SELECT impossible'. mysqli_error($connexion));
		echo"<p><a href='consulter.php'>Retour au formulaire de consultation</a></br></p>";       		
		$maintenant=date("d/m/Y \-\- H \h i");
		$totalqte=0;
		$totalprixht=0;
		$totalprixttc=0;
		echo "<p>Etat du stock à la date du : ".$maintenant."</p>";
		echo "<p>$message</p>";
		echo "<table border width=80% cellpadding=6 >";
		echo "<tr><td align='center'>ID</td><td align='left'>Catégorie</td><td align='left'>Référence</td><td align='left'>Désignation</td><td align='right'>Prix unitaire HT
			</td><td align='right'>Prix unitaire TTC</td><td align='right'>Quantité</td><td align='right'>Prix total TTC
			</td><td align='right'>Date achat</td><td align='right'>Créateur</td><td align='right'>Date/heure création</td></tr>";
		while($ligne=mysqli_fetch_assoc($result)) {
			extract($ligne);
			$id=$ligne['id_mouvement'];
			$createur=$ligne['createur_mouvement'];
			$dateha=$ligne['dateha'];
			$createur=$ligne['createur_mouvement'];
			//$datecreation=$ligne['date_creation'];
			$datecreation=$ligne['jdcr'];
			$reference=$ligne['aref'];
			$quantite=$ligne['quantite'];
			$prixht=$ligne['prixht'];
			$prixht_justif=sprintf("%10.2f", $prixht);
			$prixttc=$ligne['prixttc'];
			$prixttc_justif=sprintf("%10.2f", $prixttc);
			$prixtotalttc=$prixttc*$quantite;
			$prixtotalttc_justif=sprintf("%10.2f", $prixtotalttc);
			$designation=$ligne['designation'];
			$categorie = $ligne ['nom'];
			// calcul des totaux
			$totalqte += $ligne['quantite'];
			$totalprixht += $ligne['prixht']*$ligne['quantite'];
			$totalprixttc += $ligne['prixttc']*$ligne['quantite'];
			// préparation du tableau (ligne d'entête)			
			echo "<tr><td align='center'>$id</td><td align='left'>$categorie</td><td align='left'>$reference</td><td align='left'>$designation</td><td align='right'>$prixht_justif
				</td><td align='right'>$prixttc_justif</td><td align='right'>$quantite</td><td align='right'>$prixtotalttc_justif
				</td><td align='right'>$dateha</td><td align='right'>$createur</td><td align='right'>$datecreation</td></tr>"; 		
		}
		echo "</table>";

		$totalprixttc_justif=sprintf("%10.2f", $totalprixttc);
		$totalprixht_justif=sprintf("%10.2f", $totalprixht);

		echo "<p>Quantité totale :" .$totalqte. "</p>";
		echo "<p>Prix HT total :" .$totalprixht_justif. "</p>";
		echo "<p>Prix TTC total :" .$totalprixttc_justif. "</p>";
		mysqli_close($connexion);
}
?>
