            	<!-- -->
            	<label for="reference">Réference :</label>	    
				<select name="reference" id="reference" onchange="document.forms['chg'].submit();">
		     	  	<option value="-1">- - - Choisissez une réference - - -</option>
			        <?php
						$connexion=mysqli_connect("localhost", "stock", "stock")
							or die('Connexion au serveur impossible'. mysqli_error($connexion));
						mysqli_select_db($connexion, "stock")
							or die('Selection de la base impossible' . mysqli_error($connexion));
						$result=mysqli_query($connexion, "SELECT DISTINCT reference FROM journal ORDER BY reference")
							or die('Requete SELECT impossible'. mysqli_error($connexion));
						mysqli_close($connexion);        	
						while($ligne=mysqli_fetch_assoc($result)) {
							extract($ligne);
							echo"<option value='$reference'>$reference\n";
						}
			        ?>
				</select>
				<!-- -->
				<!--
				<?php
					// est-ce qu'on a selectionné une reference ?
				    if(isset($iddest) && $iddest != -1) {
						$nom=$_POST['reference'];
						// recherche de la quantité disponible dans la reference selectionnée
						$connexion=mysqli_connect("localhost", "stock", "stock")
							or die('Connexion au serveur impossible'. mysqli_error($connexion));
						mysqli_select_db($connexion, "stock")
							or die('Selection de la base impossible' . mysqli_error($connexion));
						$result=mysqli_query($connexion, "SELECT SUM(quantite) AS quantite_totale FROM journal WHERE reference='$reference'")
							or die('Requete SELECT impossible'. mysqli_error($connexion));
						$row = mysqli_fetch_assoc($result);
						$quantite_totale=$row['quantite_totale'];
						mysqli_close($connexion);      
					    echo "<td style='text-align:right;font-weight:bold'>Sous-categorie : </td>";  
		            	echo "<label for="selectquantite">Quantité:</label>";	    
						echo "<td><select name='selectquantite' id='selectquantite'>";
						for ($i=1; $i<=$quantite_totale; $i++){
								echo"<option value='$i>$i</option>\n";
						}
						echo "</select><td>";
					}
				?>
				-->		
