<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
<title>Interface administrateur</title>
</head>

<body>

<h1><center>Bienvenue dans la page de gestion des actualités</center></h1>
<br><br>

<?php
require("_connection.php");
tx_connect();


$login = $_GET["login"];
$sessionid = $_GET["sessionid"];
if(verif($login,$sessionid))
{

	echo "<form method=GET action=\"lister_actualites.php\">";
		echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
		echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
		echo "<input type=\"submit\" value=\"Lister les actualites\"></form>";
	
	echo "<form method=GET action=\"ajouter_actualites.php\">";
		echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
		echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
		echo "<input type=\"submit\" value=\"Ajouter une actualite\"></form>";
	
	echo "<form method=GET action=\"supprimer_actualite.php\">";
		echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
		echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
		echo "<input type=\"submit\" value=\"Supprimer des actualites\"></form>";
	
	echo "<form method=GET action=\"modifier_actualites.php\">";
		echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
		echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
		echo "<input type=\"submit\" value=\"Modifier une actualite\"></form>";
	
	echo "<BR>";
		echo "<form method=GET action=\"index2.php\">";
		echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
		echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
		echo "<input type=\"submit\" value=\"Retour au menu admin\"></form>";


}
?>
</body>
</html>
