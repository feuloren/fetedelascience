<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
<title>Modifier une Actualité</title>
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
<span class="titre">Modifier une Actualité</span>

<?php
require_once("_connection.php");
/* Connection a la base de données */
tx_connect();
$login = $_GET["login"];
$sessionid = $_GET["sessionid"];
if(verif($login,$sessionid))
{

/* test si la variable $action envoyée lors de la validation du formulaire existe
Si c est le cas, c est qu un formulaire a été rempli et doit être traité */

if(isset($_GET["action"]))
{
	echo "<br><br><br>";
	/* récupération des valeurs à insérer */
	$ref_actu = $_GET["ref_actu"];				
	$titre = addslashes($_GET["titre"]);
	$sous_titre=addslashes($_GET["sous_titre"]);
	$date = $_GET["date"];
	$resume = addslashes($_GET["resume"]);
	$texte_complet = addslashes($_GET["texte_complet"]);
	$photo = $_GET["photo"];
	$photo_reduc = $_GET["photo_reduc"];
	
	/* Construction des requêtes de mise a jour */
	$reqactu = "UPDATE actualites SET 
		titre='$titre',
		sous_titre='$sous_titre',
		date='$date',
		resume='$resume',
		texte_complet='$texte_complet',
		photo='$photo',
		photo_reduc='$photo_reduc'
		WHERE ref_actu='$ref_actu';";

	/* Execution de la requete */
	tx_query($reqactu);
	//echo "<br>".$reqactu."<br><br>";
	echo "La modification a été prise en compte";
	echo "<br><br><br>";

	/* liens retour */
	echo "<form method=GET action=\"modifier_actualites.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour à la sélection de l'actualité à modifier\"></form>";
	
	echo "<form method=GET action=\"menu_actualites.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour au sommaire\"></form>";
} // fin if (isset($_GET["action"]))


// affichage du formulaire rempli de l actualite a modifier
elseif (isset($_GET["choixok"]))
{
	// reference de l actualite a modifier
	$ref_actu = $_GET["ref_actu"];
	
	// création automatique de la référence de la conférence
	$res = tx_query("select titre, sous_titre, date, resume, texte_complet, photo, photo_reduc from actualites where ref_actu = '$ref_actu';");
	$val = mysql_fetch_array($res);

	// début du formulaire
	echo "<br><br>";
	echo "<form method=GET action=\"modifier_actualites.php\">";
	echo "<table width=\"100%\" border=2 bordercolor=black class=texte>";
	echo "<tr align=left><td>Référence</td><td><input type=\"text\" size=10 name=\"ref_actu\" value=\"$ref_actu\" disabled></td></tr>";

	$titre = stripslashes($val[0]);
	echo "<tr align=left><td>Titre</td><td><textarea rows=1 cols=50 name=\"titre\">$titre</textarea></td></tr>";

	$sous_titre = stripslashes($val[1]);
	echo "<tr align=left><td>Sous titre</td><td><textarea rows=2 cols=50 name=\"sous_titre\">$sous_titre</textarea></td></tr>";

	$date=stripslashes($val[2]);
	echo "<tr align=left><td>Date</td><td><textarea rows=1 cols=50 name=\"date\">$date</textarea></td></tr>";

	$resume=stripslashes($val[3]);
	echo "<tr align=left><td>Résumé</td><td><textarea rows=3 cols=50 name=\"resume\" >$resume</textarea></td></tr>";

	$texte_complet=stripslashes($val[4]);
	echo "<tr align=left><td>Texte complet</td><td><textarea rows=5 cols=50 name=\"texte_complet\">$texte_complet</textarea></td></tr>";

	$photo=$val[5];
	echo "<tr align=left><td>Photo</td><td><textarea rows=1 cols=50 name=\"photo\">$photo</textarea></td></tr>";

	$photo_reduc=$val[6];
	echo "<tr align=left><td>Photo réduite</td><td><textarea rows=1 cols=50 name=\"photo_reduc\">$photo_reduc</textarea></td></tr>";
	echo "</table>";
		
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"hidden\" value=\"$ref_actu\" name=\"ref_actu\">";
	echo "<input type=\"submit\" value=\"Modifier\" name=\"action\">";
	
	echo "</form>";
	
	/* liens retour */
	echo "<form method=GET action=\"modifier_actualites.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Autre actualité à modifier\"></form>";
	
	echo "<form method=GET action=\"menu_actualites.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour\"></form>";
	
	mysql_close();
	}


// ###### affichage et selection des conférences 
//sess ok; logins ok
else
{
	/* Récupération du statut de l administrateur */
	
	//on va chercher toutes les actualites
	$sql_actu = "SELECT a.ref, a.titre, a.ref_actu 
				FROM actualites AS a
				ORDER BY a.ref_actu";
				


	echo "<br><br>Choisissez l'actualmité à modifier<br><br>";
	$res = tx_query($sql_actu);

	echo "<form method=GET action=\"modifier_actualites.php\"><table>";
	$val = mysql_fetch_array($res);
	$titre_actu=stripSlashes($val[1]);
	echo "<tr><td><input type=radio name=\"ref_actu\" value=\"$val[2]\" checked></td><td>$val[2] : $titre_actu</td></tr>";


	/* Boucle sur les actualites */
	while($val = mysql_fetch_array($res))
	{
		$titre_actu=stripSlashes($val[1]);
		echo "<tr><td><input type=radio name=\"ref_actu\" value=\"$val[2]\"></td><td>$val[2] : $titre_actu</td></tr>";
	}
	echo "</table>";

	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Valider\" name=\"choixok\">";
	echo "</form>";

	/* lien retour */
	echo "<form method=GET action=\"menu_actualites.php\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"submit\" value=\"Retour\"></form>";
}

}
?>

		</div>

	</body>
</html>
