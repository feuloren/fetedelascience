<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
<title>Ajouter une actualit�</title>
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
-->
</style>
</head>
<body bgcolor=white class=texte>
<div align="center">

<span class="titre">Ajouter une actualit�</span>

<?php

require_once("_connection.php");
$sql = tx_connect();
$login = $_GET["login"];
$sessionid = $_GET["sessionid"];
if(verif($login,$sessionid))
{
if(isset($_GET["action_ajouter_actualite"]))
{	
	echo "<br><br><br>";
	//on r�cup�re le login puis la branche de la actualite
	$login = $_GET["login"];

	//on r�cup�re les infos sur l'actualite
	$titre = addslashes($_GET["titre"]);
	$sous_titre = addslashes($_GET["sous_titre"]);
	$date = $_GET["date"];
	$resume = addslashes($_GET["resume"]);
	$texte_complet = addslashes($_GET["texte_complet"]);
	$photo = $_GET["photo"];
	$photo_reduc = $_GET["photo_reduc"];

	//cr�ation de la ref�rence de l'actualite
	$sql_max = "SELECT MAX(num_actu) FROM actualites";
	$res_sql_max = tx_query($sql_max);
	$data = mysql_fetch_array($res_sql_max);
	$res = $data[0];
	if ($res == 'NULL')
	{
		$res = 0;
	}
	$num = $res + 1;
	$ref_actu = "A-".$num; 		//C-xxyyyy
	

	/* Construction des requ�tes d insertion */
	$reqactu = "INSERT INTO actualites
					(`titre`,`public`,`materiel`,`comm_mat`,`resume`,`comm_public`,`ref_corres`,
							`refa1`,`refa2`,`refa3`,`num_conf`,`ref_conf`,`branche`)						
			VALUES (
				'$titre','$public','$mat','$comm_mat','$resume','$comm_pub','$ref_corres',
				'$ref_aut1','$ref_aut2','$ref_aut3','$num','$ref_conf ','$branche')";
			
	//echo "<br>$titre $public $mat $comm_mat $resume $comm_pub";
	//echo "<br><br>Requete conference: $reqconf<br>";
	/* Execution de la requete */
	tx_query($reqconf);
	//mysql_close();

	/* lien retour */
	echo "<br><br>";
	echo "<form method=GET action=\"ajouter_actualite.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour\"></form>";


	} // fin if (isset($_GET["action"]))

else
{
echo "<br><br>";
	$login = $_GET["login"];
	if($login == 'superadmin' || $login == 'adminFDS')
	{
		echo "<form method=GET action=\"ajouter_actualite.php\">";
		echo "<table width=\"600\" border=2 bordercolor=black class=texte>";
		echo "<tr align=left><td>Titre</td><td><textarea rows=1 cols=50 name=\"titre\"></textarea></td></tr>";
		echo "<tr align=left><td>Sous-titre</td><td><textarea rows=2 cols=50 name=\"sous_titre\"></textarea></td></tr>";
		echo "<tr align=left><td>Date</td><td><textarea rows=1 cols=50 name=\"date\"></textarea></td></tr>";
		echo "<tr align=left><td>R�sum�</td><td><textarea rows=3 cols=50 name=\"resume\"></textarea></td></tr>";
		echo "<tr align=left><td>Texte complet</td><td><textarea rows=5 cols=50 name=\"texte_complet\"></textarea></td></tr>";
		echo "<tr align=left><td>Photo</td><td><textarea rows=1 cols=50 name=\"photo\"></textarea></td></tr>";
		echo "<tr align=left><td>Photo r�duite</td><td><textarea rows=1 cols=50 name=\"photo_reduc\"></textarea></td></tr>";
	echo "</table>";

	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Ajouter\" name=\"action_ajouter_actualite\">";
	echo "</form>";

	/* lien retour */
	echo "<form method=GET action=\"menu_actualites.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour\"></form>";
	}		
}
?>
</div>
</body>
</html>
