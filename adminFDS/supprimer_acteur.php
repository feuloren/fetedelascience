<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
		<title>Suppression d'un Acteur</title>

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

<span class="titre">Supprimer un Acteur</span>
<br><br><br><br>

<?php	
/* Connection a la base de données */
require_once("_connection.php");
tx_connect();
$login = $_GET["login"];
$sessionid = $_GET["sessionid"];
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
	$sqlbr = " WHERE num_acteur <> 0 ";
}


/* test si la variable $action envoyée lors de la validation du formulaire existe
Si c est le cas, c est qu un formulaire a été rempli et doit être traité */
if(isset($_GET["action_supprimer_auteur"]))
{
	$nb=$_GET["nb"];
	echo $nb;
	for ($i=0; $i<$nb; $i++)	
	if (isset($_GET["ref$i"]))	
	{
		$ref = $_GET["ref$i"];
		echo "Référence de l'acteur a supprimer : $ref<br>";
		$sql ="delete from acteurs where ref='$ref'";
		echo $sql;
		tx_query($sql);
	}
}
else
{
	$i=0;
	$query = "select ref, nom, prenom,ref_acteur from acteurs " . $sqlbr . " ORDER BY nom";
	$req = tx_query($query);
	echo "Acteur a supprimer :<br><br>";
	echo "<form method=GET action=\"supprimer_acteur.php\"><table>";
	
	/* Récupération des infos afin de remplir le formulaire des conférences a supprimer */
	while($aut = mysql_fetch_array($req))
	{
		echo "<tr><td><input type=\"checkbox\" name=\"ref".$i."\" value=\"$aut[0]\">$aut[3] : $aut[1] $aut[2]</td></tr>";
		$i++;
	}
	echo "</table><br><br><input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$i\" name=\"nb\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"submit\" value=\"Supprimer\" name=\"action_supprimer_auteur\">";
	echo "</form>";
} // fin else

mysql_close();

/* lien retour */
echo "<br><form method=GET action=\"menu_acteurs.php\">";
echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
echo "<input type=\"submit\" value=\"Retour\"></form>";
}
?>
</div>
</body>
</html>
