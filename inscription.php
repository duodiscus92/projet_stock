<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
		<title>Inscription</title>
    </head>
    <body>
        <h1>Première inscription : veuillez fournir les informations suivantes</h1>
        <form action="gererinscription.php" method="POST">
        <!-- <form method="POST"> -->
        <table width='0%' border='0' cellspacing='0' cellpadding='2'>
        	<tr>
            <td style='text-align:right;font-weight:bold'>Nom du stock : </td> 
            <td> <input type="text" name="stockname" size='65' maxlength='65' placeholder='Exemple: bac-a-sable'</td>
            </tr>
            
        	<tr>
            <td style='text-align:right;font-weight:bold'>Nom : </td> 
            <td> <input type="text" name="nom" value='<?php echo $_SESSION["nom"]; ?>' size='65' maxlength='65' </td>
            </tr>
            
        	<tr>
            <td style='text-align:right;font-weight:bold'>Prénom : </td> 
            <td> <input type="text" name="prenom" size='65' maxlength='65' </td>
            </tr>
            
        	<tr>
            <td style='text-align:right;font-weight:bold'>Identifiant : </td> 
            <td> <input type="text" name="id" size='65' maxlength='65' </td>
            </tr>

            <tr>
            <td style='text-align:right;font-weight:bold'>Email : </td> 
            <td> <input type="text" name="mail" size='65' maxlength='65'</td>
            </tr>
                       
            <tr>
            <td style='text-align:right;font-weight:bold'>Password : </td> 
            <td> <input type="password" name="mdp1" size='65' maxlength='65'</td>
            </tr>
            
            <tr>
            <td style='text-align:right;font-weight:bold'>Répéter password : </td> 
            <td> <input type="password" name="mdp2" size='65' maxlength='65'</td>
            </tr>
            
            <tr>
            <td colspan='1' style='text-align:center'> </td>
            <td> <input type="submit" value="Valider" >
            <!--<input type="submit" value="Annuler" >  </td>-->
            </tr>
        </table>
        </form>
    </body>
</html>