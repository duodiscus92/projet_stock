<?php
//session_start();
	require('control_session.php');
	if($_SESSION['statut'] <2 || $_SESSION['modesystem']==0){
		echo("<h2>Non autorisé : en maintenance ou statut insuffisant</h2>");
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
        <title>Notice</title>
        <link rel="stylesheet" href="style.css" />        
    </head>
    
    <body>
    	<?php
			echo '<h1>Notice d\'utilisation pour : '.$_SESSION['stockname']. '</h1>';
			echo '<p> Session de : ' .$_SESSION['id']. ' --- Statut : '.$_SESSION['type_statut']. '</p>';
		?>

        <a href="acceuil.php">Retour au menu principal</a><br />
        <h2>Cette page est en cours de développement</h2>

        <h3>Introduction</h3>
		<p>
		Le système peut être utilisé aussi bien pour enregistrer des choses comme un planeur, un avion<br>
		que des vis ou des écrous des outils, des ressorts, des fournitures de bureau, des produits d'entretien.<br>
		Bref : notre patrimoine immobilisé ou notre actif circulant ou nos consommables etc.<br>
		Mais peut-être qu'on peut aussi l'utiliser pour gérer une bibliothèque ou une vidéothèque
		</p>
		<h3>Un système mulitutilisateurs</h3>
		<p>
		C'est une application WEB basée sur une architecture client-serveur, c'est à dire qu'avec<br>
		un simple navigateur, de n'importe quel point de la planète vous
		pourrez vous en servir (à condition de posséder un login, un mot de passe et les droits d'accès).
		</p>

		<p>A chaque utilisateur est attribué un statut qui lui ouvre des droits :<p>
		<ul>
		<li>TEMPORAIRE : c'est le statut qui est attribué à tout nouvel utilisateur avant que le SUPER UTILISATEUR<br> lui assigne un autre statut : ce statut n'offre aucun droit sauf celui de changer son mot de passe</li>
		<li>EXAMINATEUR : ne peut que examiner c'est à dire obtenir un état du stock</li>
		<li>GESTIONNAIRE : peut ajouter, retirer des références dans le stock + les mêmes droits que l'EXAMINATEUR</li>
		<li>ADMINISTRATEUR : peut paramétrer le système : définir les catégories, les sous-catégories, les destinations,<br> les sous-destination et créer de nouvelles références + les droits GESTIONNAIRE</li>
		<li>SUPER UTILISATEUR : peut fixer le statuts des autres + les droits ADMINISTRATEUR</li>
		</ul>

		<h3>Saisies guidées</h3>
		<p>
		Pour la plupart des opérations, il faut faire des sélections dans des listes déroulantes donc c'est simple et ça limite les erreurs de frappe.
		Exemple : vous voulez prélever dans le stock une référence à 5 exemplaires : le système vous propose dans une liste déroulante les références possibles
		et une fois que cette référence est sélectionnée, il vous propose dans une liste déroulante les quantité pouvant être retirées : vous ne pouvez pas prélever plus que
		le disponible.
		</p>
		
		<h3>Première connexion. Il faut s'identifier.</h3>
		<ul>
		<li>Nom</li>
		<li>Prénom</li>
		<li>Identidiant(login)</li>
		<li>Mot de passe</li>
		<li>email</li>
		</ul>
		<p>
		N'utilisez pas le mot de passe de vos sites sécurisés, utilisez un mot de passe spécifique pour cvvfr-stock
		car pour le moment le mot de passe n'est pas encore suffisamment sécurisé.
		</p>

		<h3>Opérations d'ajout dans le stock</h3>
		<ul>
		<li>on ajoute ou on retire des référence (une référence possède un label unique), donc il faut avoir créé les références au préalable</li> 
		<li>on précise la quantité ajoutée</li>
		<li>si possible, le prix (ça permettra de valoriser le stock), si inconnu mettre 0.00</li>
		<li>la date d'achat (si inconnue mettre 01/01/1900)</li>
		<li>une désignation : texte libre pour expliciter ce que c'est (les références sont souvent obscures)</li>
		</ul>
		
		<h3>Opération de retrait dans le stock</h3>
		<ul>
		<li>on précise la référence</li>
		<li>la quantité à retirer</li>
		</ul>

		<h3>Création d'une référence</h3>
		<p>Une référence sert à identifier un produit. Exemple : VP3215 pour une vidéo projecteur, RES-50-23 pour un ressort.<br>
		Ne confondez pas article et référence. Une référence est unique.<br>
		Mais vous pouvez mettre dans le stock autant d'articles que nécessaire portant cette référence.<br>
		Une référence est caractérisé par son label (VP3215, RES-50-23). Ce label doit donc forcément être unique.<br>
		</p>
		<ul>
		<li>Vous précisez son label (ça peut être une référence constructeur ou bien une référence que vous inventez</li>
		<li>une désignation : texte libre pour expliciter ce que c'est (les références sont souvent obscures)</li>
		<li>udv : unité de vente (certains articles sont achetés par UDV (exemple : boite de 100 vis l'UDV est égale à 100)</li>
		<li>la catégorie et la sous catégoerie à laquelle appartient la référence (il faut donc avoir créé les catégories et sous catégories au préalable)</li>
		<li>la destination et la sous destination à laquelle appartien la référence (il faut donc avoir créé les catégories et sous catégories au préalable)</li>
		</ul>
	
		<h3>Catégories et sous catégories</h3>
		<ul>
		<li>vous pouvez librement mais obligatoirement définir des catégories auxquelles appartiendront les références : exemple INFORMATIQUE</li>
		<li>vous pouvez librement mais facultativement définir des sous catégories auxquelles appartiendront les références : exemples ORDINATEUR, SAUVEGARDE, IMPRIMANTE, MULTIMEDIA</li>
		<li>NB: une sous catégorie est rattachée à une seule catégories (appelée catégorie mère)</li>
		</ul>

		<h3>Destination et sous destination</h3>
		<ul>
		<li>vous pouvez librement mais obligatoirement définir des destinations auxquelles appartiendront les références : exemple BATIMENTS</li>
		<li>vous pouvez librement mais facultativement définir des sous destinations auxquelles appartiendront les références : exemples SALLE PILOTE, CUISINE, BUREAU, SANITAIRE, LOCAL INFORMATIQUE</li>
		<li>NB: une sous destination est rattachée à une seule destination (appelée destination mère)</li>
		</ul>
		
		<h3>Afficher l'état du stock</h3>
		Par défaut c'est l'ensemble du stock qui est affiché.<br>
		Mais vous pouvez activer des filtres permettant de selectionner ou d'exclure des articles du stock</br>
		Ces sélection ou ces exclusions portent sur:</br>
		<ul>
		<li>Les références : on peut n'afficher que la partie du stock portant sur cette référence ou au contraire ne portant pas sur cette référence</li>
		<li>Les catégories (même principe que ci-dessus)</li>
		<li>Les destinations (même principe que ci-dessus)</li>
		</ul>

		<h3>Imprimer l'état du stock</h3>
		Pour le moment il faut se servir du menu "Imprimer" de votre navigateur".<br>
		Plus tard on pourra proposer des impressions au format PDF ...<br>
		
		<h3>Signalez tout dysfonctionnement</h3>
		<a href="mailto:jacques.ehrlich@neuf.fr"
			accesskey="m"
			title="[cvvfr-stock]Signalement d'un problème">
			Envoyer un message
		</a>
    </body>
</html>
