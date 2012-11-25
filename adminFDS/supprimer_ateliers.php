<html>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Suppression d'un Atelier</title>

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

	<body bgcolor=white class="texte">
		<div align=center>
		<p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p>
		<span class="titre">Supprimer un Atelier</span>
		<br><br><br><br>

	<?php

/* Connection a la base de données */
require_once("_connection.php");
tx_connect();
/* Récupération du login de l administrateur */
$login = $_GET["login"];
$sessionid = $_GET["sessionid"];
if(verif($login,$sessionid))
{
	if ($login != "superadmin")
	{
		$len = strlen($login);
		$branche = substr($login,5,$len-5);
	}



/* test si la variable $action envoyée lors de la validation du formulaire existe
Si c est le cas, c est qu un formulaire a été rempli et doit Ãªtre traité */
if(isset($_GET["action_supprimer_atelier"]))
{

	$nb=$_GET["nb"];

	for ($i=0; $i<$nb; $i++)
		if (isset($_GET["ref$i"]))
		{
			$ref = $_GET["ref$i"];
			echo "Référence de l'atelier a supprimer : $ref<br>";
			$sql ="delete from ateliers where ref_at='$ref';";
			tx_query($sql);
			echo $sql."<br>";
		}

		/* lien retour */
		echo "<br><form method=GET action=\"menu_ateliers.php\">";
		echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
		echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
		
		echo "<input type=\"submit\" value=\"Retour vers le menu de gestion des ateliers\"></form>";
}



else
{
	$i=0;
	
	if($login != 'superadmin')
	{
		$req = tx_query("SELECT ref_at, titre FROM ateliers WHERE ref_at LIKE 'V-$branche%' ORDER BY ref_at;");
	}
	else
	{
		$req = tx_query("SELECT ref_at, titre FROM ateliers ORDER BY ref_at;");
	}
	
	echo "Ateliers à supprimer :<br><br>";
	echo "<form method=GET action=\"supprimer_ateliers.php\"><table>";

	/* Récupération des infos afin de remplir le formulaire des ateliers a supprimer */
	while($atelier = mysql_fetch_array($req))
	{
		echo "<tr><td><input type=\"checkbox\" name=\"ref".$i."\" value=\"$atelier[0]\">$atelier[0] : $atelier[1]</td></tr>";
		$i++;
	}

	echo "</table><br><br><input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"hidden\" value=\"$i\" name=\"nb\">";
	echo "<input type=\"submit\" value=\"Supprimer\" name=\"action_supprimer_atelier\"></form>";

	/* lien retour */
	echo "<br><form method=GET action=\"menu_ateliers.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour\"></form>";

} // fin else

		mysql_close();



}



	?>
		</div>

	</body>
</html>
