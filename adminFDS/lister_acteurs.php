<html>
	<head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
		<title>Liste des Acteurs</title>

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
<span class="titre">Liste des acteurs</span>
<br><br>

<?php

//initialisation
require_once("_connection.php");
tx_connect();

//recuperation des variables
$login = $_GET["login"];
$sessionid = $_GET["sessionid"];

//verification de session
if(verif($login,$sessionid))
{

//test du login
if ($login != "superadmin")
{
	$len = strlen($login);
	$branche = substr($login,5,$len-5);
	$sqlbr = " WHERE branche = '$branche' AND num_acteur <>0";
}
else
{
	$sqlbr = " WHERE 1 ";
}

//construction puis execution de la requete
$query = "SELECT ref_acteur, prenom, nom, tel, mail, immat_voit, puissance_voit, ref FROM acteurs " . $sqlbr . " ORDER BY nom";
$res = tx_query($query);

/* Boucle sur les acteurs */
while($val = mysql_fetch_array($res))
{
	//$res_conf = mysql_query("select titre from conferences where ref='$val[0]';");
	//$val_conf = mysql_fetch_array($res_conf);
	$query_conf = "SELECT ref_conf, titre FROM conferences WHERE refa1 = '$val[7]' OR refa2= '$val[7]' OR refa3 = '$val[7]' ORDER  BY ref_conf ";
	$res_conf = tx_query($query_conf);
	
	echo "<br><br><table width=\"80%\" class=\"texte\">";
	echo "<tr><td width=\"10%\"></td><td bgcolor=\"#336699\" color=\"white\"><font color=white>Auteur : $val[1] $val[2], référencé sous $val[0]</font></td></tr>";
	
	if(mysql_num_rows($res_conf) == 1)
	{
		$val_conf = mysql_fetch_array($res_conf);
		$titre_conf=stripSlashes($val_conf[1]);
		echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">Associé à  la conférence $val_conf[0] : $titre_conf</td></tr>";
	}
	elseif(($index = mysql_num_rows($res_conf)) > 1)
	{
		echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">Associé aux conférences <br>";
		while($val_conf = mysql_fetch_array($res_conf))
		{
			echo "$val_conf[0] : $val_conf[1] ";
			$index--;
			if($index >0)
			{
				echo "<br> ";
			}
		}
		echo "</td></tr>";
		
	}
	
	echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">N° de téléphone : $val[3]</td></tr>";
	echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">E-mail : $val[4]</td></tr>";
	echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">Immatriculation : $val[5]</td></tr>";
	echo "<tr><td width=\"10%\"></td><td bgcolor=\"#FFFF80\">Puissance de la voiture : $val[6]</td></tr>";
	echo "</td></tr></table>";
}

/* lien retour */
echo "<form method=GET action=\"menu_acteurs.php\">";
echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
echo "<input type=\"submit\" value=\"Retour\"></form>";



}
?>
</div>
</body>
</html>
