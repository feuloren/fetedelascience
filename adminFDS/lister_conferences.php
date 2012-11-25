<html> 	
<head> 	
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 	
<center>
<p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center> 		
<title>Liste des Conférences</title>  		
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
<span class="titre">Liste des conférences</span> 		<br><br>  
<?php
	require_once("_connection.php");
	/* Initialisation de la connection à la base */
	$sql = tx_connect();  		//gestion de session
 	$login = $_GET["login"];
 	$sessionid = $_GET["sessionid"];
 	if(verif($login,$sessionid))
 	{ 		//gestion du login
 		if($login == 'superadmin')
 			{$sqlbr = "";}
 		else
 		{
 			$len = strlen($login);
 			$branche = substr($login,5,$len-5);
 			//$query_link = "SELECT branche from acteurs where status = '$login'";
 			//$res_link = tx_query($query_link);
 			//$branche = mysql_fetch_array($res_link);
 			$sqlbr = "WHERE branche = '$branche'";
 		}
 		$res = tx_query(
 			"SELECT ref_conf, TITRE, PUBLIC, MATERIEL, RESUME, refa1, refa2, refa3
  			FROM conferences " . $sqlbr." ORDER BY ref_conf;");
  		$nb_conf = mysql_num_rows($res);
 		echo "<center>$nb_conf conférence(s) disponible(s)</center>";
 	  		/* Boucle sur les conferences */
 		while($val = mysql_fetch_array($res))
 		{
 			$res_act1 = tx_query("select prenom, nom from acteurs where ref='$val[5]';");
 			$val_act1 = mysql_fetch_array($res_act1);
 			if(($res_act2 = tx_query("select prenom, nom from acteurs where ref='$val[6]';"))) $val_act2 = mysql_fetch_array($res_act2);
 			if(($res_act3 = tx_query("select prenom, nom from acteurs where ref='$val[7]';"))) $val_act3 = mysql_fetch_array($res_act3);
 			echo "<br><br><table width=\"80%\" class=\"texte\"><tr bgcolor=\"#336699\" ><td width=\"10%\">";
 			echo "<font color=white>" . $val[0] . "</font></td><td>";
 			echo "<font color=white>" . $val[1] . "</font></td></tr>";
 			$act_string = $val_act1[0]." ".$val_act1[1];
 			if(isset($val_act2) && ($val_act2 != false))
  			{
	 			$tmp = ", ".$val_act2[0] . "  " . $val_act2[1] ;
 			      $act_string .= $tmp;
 			}
 			if(isset($val_act3) && ($val_act3 != false))
  			{
	 			$tmp = ", ".$val_act3[0] . "  " . $val_act3[1] ;
 				$act_string=$act_string.$tmp;
			}
 			echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">Auteur(s) : $act_string </td></tr>";
 			echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">Public : $val[2]</td></tr>";
 			echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">Matériel nécessaire : $val[3]</td></tr>";
 			echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">Résumé : $val[4]</td></tr>";
 			/*echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">Commentaires : $val[5]</td></tr>";*/
 			echo "</td></tr></table>";
 		}  		/* lien retour */
     		echo "<form method=GET action=\"menu_conferences.php\">";
 		echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
 		echo "<input type=\"hidden\" value = \"$sessionid\" name=\"sessionid\">";
 		echo "<input type=\"submit\" value=\"Retour\"></form>";
    	 } 
?>
</div>
</body> 
</html> 