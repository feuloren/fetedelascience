<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
		<title>Liste des Ateliers par theme</title>

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
<br>
<br>
<?php	
require_once("_connection.php");	
/* Initialisation de la connection à la base */
$sql = tx_connect();

$sqlbr = "WHERE theme='1'";

$res = tx_query(
	"SELECT ref_at, porteur, titre, sujet
	FROM ateliers " . $sqlbr." ORDER BY ref_at;");

$nb_ateliers = mysql_num_rows($res);
echo "<center>$nb_ateliers atelier(s) disponible(s)</center>";

/* Boucle sur les conferences */
while($val = mysql_fetch_array($res) )
{
	echo "<br><br><table width=\"80%\" class=\"texte\"><tr bgcolor=\"##336699\"><td width=\"10%\">";
		echo "<font color=white>" . $val[0] . "</font></td><td>";
		echo "<font color=white>" . $val[2] . "</font></td></tr>";
	$res_act = tx_query("SELECT prenom, nom,ref_acteur FROM acteurs WHERE key_ref =\"$val[1]\"");
	$tab_act = mysql_fetch_array($res_act);
	$act_string = $tab_act[0] ." ".$tab_act[1] ;
	$ref_porteur = $tab_act[2];
	
	echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">Porteur de projet : $act_string, référencé sous $ref_porteur</td></tr>";		
	echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">Sujet: $val[3]</td></tr>";		
	echo "</td></tr></table>";
}

/* lien retour */    
echo "<form method=GET action=\"menu_ateliers.php\">";
echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
echo "<input type=\"hidden\" value = \"$sessionid\" name=\"sessionid\">";
echo "<input type=\"submit\" value=\"Retour\"></form>";

?>


	</div>

</body>
</html>
