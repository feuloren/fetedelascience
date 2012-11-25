<html>

<head>
<title>Gestion des ateliers</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
</head>

<body>

<h1><center>Bienvenue dans la page de gestion des ateliers</center></h1>
<br><br>
<?php
require_once("_connection.php");
tx_connect();
$login = $_GET["login"];
$sessionid = $_GET["sessionid"];
verif($login,$sessionid);

	echo "<form method=GET action=\"lister_ateliers.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Lister les ateliers\"></form>";


	echo "<form method=GET action=\"ajouter_atelier.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Ajouter un atelier\"></form>";

	echo "<form method=GET action=\"modifier_atelier.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Modifier un atelier\"></form>";

	echo "<form method=GET action=\"supprimer_ateliers.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Supprimer des ateliers\"></form>";

	echo "<BR>";
	echo "<form method=GET action=\"index2.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour au menu admin\"></form>";

?>

</body>
</html>
