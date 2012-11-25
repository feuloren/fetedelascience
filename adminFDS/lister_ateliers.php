<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
		<title>Liste des Ateliers</title>

		<style type="text/css">
		<!--
			.titre {
				font-size:17px;
				font-weight:bold;
				font-family:Verdana;
				color:#336699;
			}
			.texte {
				font-family:Verdana;
				font-size:12px;
			}
			.pasdedonnees {
				font-size:14px;
				font-family:Verdana;
				color:#336699;
			}
		-->
		</style>

	</head>

<body bgcolor=white class=texte>

<div align="center">

<span class="titre">Liste des ateliers</span>
<br><br>

<?php	
require_once("_connection.php");	
/* Initialisation de la connection à la base */
$sql = tx_connect();

//gestion de session
$login = $_GET["login"];
$sessionid = $_GET["sessionid"];
if(verif($login,$sessionid))
{
//gestion du login
if($login == 'superadmin')
{$sqlbr = "";}
else
{
	$len = strlen($login);
	$branche = substr($login,5,$len-5);
	$sqlbr = "WHERE ref_at LIKE 'V-$branche%'";
}
$res = tx_query(
	"SELECT ref_at, porteur, titre, sujet, coorg
	FROM ateliers " . $sqlbr." ORDER BY ref_at;");

$nb_ateliers = mysql_num_rows($res);
echo "<center>$nb_ateliers atelier(s) disponible(s)</center>";

/* Boucle sur les ateliers */
while($val = mysql_fetch_array($res) )
{
	echo "<br><br><table width=\"80%\" class=\"texte\"><tr bgcolor=\"##336699\"><td width=\"10%\">";
		echo "<font color=white>" . $val[0] . "</font></td><td>";
		echo "<font color=white>" . stripSlashes($val[2]) . "</font></td></tr>";
	$res_act = tx_query("SELECT prenom, nom FROM acteurs WHERE ref =\"$val[1]\"");
	$tab_act = mysql_fetch_array($res_act);
	$act_string = $tab_act[0] ." ".$tab_act[1] ;
	
	echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">$act_string</td></tr>";		
	echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">$val[4]</td></tr>";		
	echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">Sujet : $val[3]</td></tr>";		
	echo "</td></tr></table>";
}

/* lien retour */    
echo "<form method=GET action=\"menu_ateliers.php\">";
echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
echo "<input type=\"hidden\" value = \"$sessionid\" name=\"sessionid\">";
echo "<input type=\"submit\" value=\"Retour\"></form>";
}
?>


	</div>

</body>
</html>
