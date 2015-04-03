<?php
//session_start();
	include('stocklib.php');
	include('stock.conf.php');
	//require('control_session.php');

	//echo('helloworld');
	//infomsg($msg['FILLALLITEM'][$lang]);
	//echo '<script type="text/javascript">'
	//echo 'alert("hello")';
	//echo '</script>';
function monmsg($msg)
{
	$str=sprintf("<script type=\"text/javascript\"> alert(\"Information : %s\" );</script>", $msg);
	echo $str;

}
	//echo '<script>alert("La référence a été enregistrée avec succès");</script>';
	//$msg='hello';
	//$str=sprintf("<script type=\"text/javascript\"> alert(\"Information : %s\" );</script>", $msg);
	//echo $str;
	$glop='glop';
	monmsg($glop);

?>