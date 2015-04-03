<?php
$stockindexlogin='stockindex';
$stockindexpwd='stockindex';
$stockindexdbname='stockindex';
$lang	="fr";
$msgtab = array(
	"ERR"  		=> array(
		  "fr"		=> "Erreur : ",
		  "en"		=> "Error : "
		  ),
	"WARN"  	=> array(
		  "fr"		=> "Attention : ",
		  "en"		=> "Warning : "
		  ),
	"INF"  		=> array(
		  "fr"		=> "Information : ",
		  "en"		=> "Information : "
		  ),
	"CONF"  	=> array(
		  "fr"		=> "Confirmez SVP : ",
		  "en"		=> "Please confirm : "
		  ),
	"UNCONSERV"  	=> array(
		  "fr"		=> "Connexion au serveur mysql impossible",
		  "en"		=> "Unable to connect to mysql Server"
		  ),
 
	"UNCONDB"		=> array(
		  "fr"		=> "Connexion à la base de données impossible",
		  "en"		=> "Unbale to connect database"
		  ),
 
	"UNQSELECT"		=> array(
		  "fr"		=> "Requête SELECT impossible",
		  "en"		=> "Unable to make a SELECT query "
		  ),
	"UNEXISTREF"	=> array(
		  "fr"		=> "La reference " .$reference. "n\'existe pas ...",
		  "en"		=> "Item " .$reference. "doesn't exist ..."
		  ),
	"ITEMSTORED"	=> array(
		  "fr"		=> "L'article a été ajouté au stock avec succès",
		  "en"		=> "Item was stored successfully into the stock"
		  ),
	"FILLALLITEM"	=> array(
		  "fr"		=> "Tous les champs doivent obligatoirement être renseignés",
		  "en"		=> "All fields must be filled"
		  )
);
$error=$msgtab['ERR'][$lang];
$warning=$msgtab['WARN'][$lang];
$info=$msgtab['INF'][$lang];
$confirm=$msgtab['CONF'][$lang];
?>
