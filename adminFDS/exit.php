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
Ce script d�loggue l'utilisateur et permet 
de retourner �ventuellement � la page de 
login.
********************************************/

require("_connection.php");
tx_connect();

$login = $_GET["login"];
$sessionid = $_GET["sessionid"];
verif($login,$sessionid);

log_out($login);

echo "Vous �tes maintenant d�connect� du site d'administration de la 
f�te de la science.<br>";
echo "Vous pouvez <a href=\"index.php\" > retourner � la page de connexion </a>";
echo "ou bien fermer votre navigateur.";
	
?>

</html>
