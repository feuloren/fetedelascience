<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
<title>Suppression d'une Conf�rence</title>

<style type="text/css">
<!--
	.titre {
		font-size:17px;
		font-weight:bold;
		font-family:Verdana;
		color:#336699;inde
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

<span class="titre">Supprimer une Conf�rence</span>
<br><br><br><br>

<?php
require_once("_connection.php");

/* Connection a la base de donn�es */
tx_connect();

/* R�cup�ration du login et de l'id de session de l administrateur */
$login = $_GET["login"];
$sessionid = $_GET["sessionid"];
if(verif($login,$sessionid))
{

/* R�cup�ration du statut de l administrateur */
//$req = tx_query("select statut from acteurs where login='$login';");
//$res = mysql_fetch_array($req);
//$statut = $res[0];
/* R�cup�ration de la branche vis�e s il c est le superadmin qui est logg� */
//if (isset($_GET["ref"])) 	$branche = $_GET["ref"];
//else $branche = substr($statut,strlen($statut)-2,2);

if ($login != "superadmin")
{
	$len = strlen($login);
	$branche = substr($login,5,$len-5);
	$sqlbr = " WHERE branche = '$branche' ";
}
else
{
	$sqlbr = " WHERE 1 ";
}


/* test si la variable $action envoy�e lors de la validation du formulaire existe
Si c est le cas, c est qu un formulaire a �t� rempli et doit �tre trait� */
if(isset($_GET["action"]))
{

	/* R�cup�ration des infos */
	
	$sql_nb = "SELECT ref FROM conferences " . $sqlbr;
	$req = tx_query($sql_nb);
	$nb = mysql_num_rows($req);

	for ($i=0; $i<$nb; $i++)	
	{
		if (isset($_GET["ref$i"]))	
		{
			$ref = $_GET["ref$i"];
			echo "R�f�rence de la conf�rence � supprimer : $ref<br>";
			$sql_supprime = "DELETE FROM conferences WHERE ref = '$ref'";
			tx_query($sql_supprime);
		}
	}
}


else
{
$i=0;


$req = tx_query("select ref, titre, ref_conf from conferences ". $sqlbr. " AND num_conf > 0 ORDER BY 1;");

echo "Conf�rence � supprimer :<br><br>";
echo "<form method=GET action=\"supprimer_conference.php\"><table>";

/* R�cup�ration des infos afin de remplir le formulaire des conf�rences a supprimer */
while($conf = mysql_fetch_array($req))
{
	$titre_conf=stripSlashes($conf[1]);
	echo "<tr><td><input type=\"checkbox\" name=\"ref".$i."\" value=\"$conf[0]\">$conf[2] - $titre_conf</td></tr>";
	$i++;
}
echo "</table><br><br><input type=\"hidden\" value=\"$login\" name=\"login\">";
echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
echo "<input type=\"submit\" value=\"Supprimer\" name=\"action\">";
echo "</form>";


} // fin else

//mysql_close();

/* lien retour */
echo "<br>";
echo "<form method=GET action=\"menu_conferences.php\">";
echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
echo "<input type=\"submit\" value=\"Retour\"></form>";

}
?>
	</div>

</body>
</html>
