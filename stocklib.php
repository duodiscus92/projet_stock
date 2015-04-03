<?php
//function sendmail($mail, $sujet, $message_txt);
function sendmail($mail, $sujet, $message_txt)
{
	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
	{
		$passage_ligne = "\r\n";
	}
	else
	{
		$passage_ligne = "\n";
	}
	 
	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	//==========
	 
	 
	//=====Création du header de l'e-mail.
	$header = "From: \"cvvfr-stock\"<jacques.ehrlich@neuf.fr>".$passage_ligne;
	$header.= "Reply-to: \"cvvfr-stock\" <jacques.ehrlich@neuf.fr>".$passage_ligne;
	$header.= "MIME-Version: 1.0".$passage_ligne;
	$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
	//==========
	 
	//=====Création du message.
	$message = $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format texte.
	$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_txt.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	//==========
	 
	//=====Envoi de l'e-mail.
	mail($mail,$sujet,$message,$header);
	//==========
}
function msgbox($msg)
{
	$str=sprintf("<script type=\"text/javascript\"> alert(\"%s\" );</script>", $msg);
	echo $str;
}
 
?>

