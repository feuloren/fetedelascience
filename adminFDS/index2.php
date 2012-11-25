<html>  
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
	<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>  
</head> 
<title>Interface administrateur</title>   
<body>  
<h1><center>Bienvenue dans l'interface administrateur</center></h1> <br><br>   </body> 
<?php  
/******************************************** 
Ce script est le menu général, il est possible de tout administrer avec. 
Les boutons donnent accès à toutes les  fonctionnalités. 
********************************************/   
require("_connection.php"); 
tx_connect();   
$login = $_GET["login"]; 
$sessionid = $_GET["sessionid"]; 
if(verif($login,$sessionid)) {  	 	
echo "<form method=GET action=\"menu_conferences.php\">";
 		echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
 		echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
 		echo "<input type=\"submit\" value=\"Gérer les conférences\"></form>";

 	 	echo "<form method=GET action=\"menu_acteurs.php\">";
 		echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
 		echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
 		echo "<input type=\"submit\" value=\"Gérer les acteurs\"></form>";

 		echo "<form method=GET action=\"menu_ateliers.php\">";
 		echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
 		echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
 		echo "<input type=\"submit\" value=\"Gérer les ateliers\"></form>";

 		if($login == 'superadmin' || $login == 'adminFDS') 
		{
 		echo "<form method=GET action=\"menu_actualites.php\">";
 		echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
 		echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
 		echo "<input type=\"submit\" value=\"Gérer les actualites\"></form>";
		}


 		echo "<br><br>";
 	 	echo "<form method=GET action=\"exit.php\">";
 		echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
 		echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
 		echo "<input type=\"submit\" value=\"Déconnexion\"></form>"; 	

}   ?> 
</html>
