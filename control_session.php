<?php
session_start(); // ici on continue la session
if ((!isset($_SESSION['id'])) || ($_SESSION['id'] == ''))
{
echo '<p>Vous devez vous <a href="login.php">connecter</a>.</p>'."\n";
exit();
}
?>