<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
</head>

<body>

<h1><center>Sortir du site</center></h1>
<br><br>


</body>
<?php


/********************************************
Ce script déloggue l'utilisateur et permet 
de retourner éventuellement à la page de 
login.
********************************************/

require("_connection.php");
tx_connect();

$login = $_GET["login"];
$sessionid = $_GET["sessionid"];
verif($login,$sessionid);

log_out($login);

echo "Vous êtes maintenant déconnecté du site d'administration de la 
fête de la science.<br>";
echo "Vous pouvez <a href=\"index.php\" > retourner à la page de connexion </a>";
echo "ou bien fermer votre navigateur.";
	
?>

</html>
