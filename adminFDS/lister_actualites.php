<html> 	
<head> 	
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 	
<center>
<p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center> 		
<title>Liste des actualites</title>  		
<style type="text/css"> 		
<!-- 			
.titre { 				font-size:17px; 				font-weight:bold; 				font-family:Verdana; 				color:#336699; 			} 			
.texte { 				font-family:Verdana; 				font-size:12px; 			} 			
.pasdedonnees { 				font-size:14px; 				font-family:Verdana; 				color:#336699; 			} 		
--> 		
</style>  	
</head>  

<body bgcolor=white class=texte>  	
<div align="center"> 		 		
<span class="titre">Liste des actualités</span> 		<br><br>  
<?php
	require_once("_connection.php");
	/* Initialisation de la connection à la base */
	$sql = tx_connect();  		//gestion de session
 	$login = $_GET["login"];
 	$sessionid = $_GET["sessionid"];
 	if(verif($login,$sessionid))
 	{ 		//gestion du login
 		if($login == 'superadmin' || $login == 'adminFDS') 
		{
 			$res = tx_query(
 				"SELECT ref_actu, titre, sous_titre, date, resume
  				FROM actualites ORDER BY ref;");
  			$nb_conf = mysql_num_rows($res);
 			echo "<br><br><center>$nb_act actualité(s) disponible(s)</center><br><br>";
 	  		/* Boucle sur les actualites */
 			while($val = mysql_fetch_array($res))
 			{
 				echo "<br><br><table width=\"80%\" class=\"texte\"><tr bgcolor=\"#336699\" ><td width=\"10%\">";
 				echo "<font color=white>" . $val[0] . "</font></td><td>";
 				echo "<font color=white>" . $val[1] . "</font></td></tr>";
 				echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">$val[2]</td></tr>";
 				echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">Date : $val[3]</td></tr>";
 				echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">Résumé : $val[4]</td></tr>";
 				echo "</td></tr></table>";
 			}  		/* lien retour */
     			echo "<form method=GET action=\"menu_actualites.php\">";
 			echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
 			echo "<input type=\"hidden\" value = \"$sessionid\" name=\"sessionid\">";
 			echo "<input type=\"submit\" value=\"Retour\"></form>";
    	 	}
 	}
?>
</div>
</body> 
</html> 