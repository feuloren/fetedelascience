
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />	
<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
<title>Ajouter un Acteur</title>

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
<span class="titre">Ajouter un Acteur</span>


<?php
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
	//$sqlbr = " WHERE branche = '$branche' AND num_acteur <>0";
}
/*else
{
	$sqlbr = " WHERE 1 ";
}*/

/* test si la variable $action envoy&eacutee lors de la validation du formulaire existe
Si c est le cas, c est qu un formulaire a été rempli et doit être traité */
if(isset($_GET["action_ajouter_acteur"]))
{
	echo "<br><br><br>";
	/* récupération des valeurs à  insérer */
	//$ref = $_GET["ref"];
	$nom_aut = $_GET["nom_aut"];
	$prenom_aut = $_GET["prenom_aut"];
	$tel_aut = $_GET["tel_aut"];
	$mail_aut = $_GET["mail"];
	$immat_voit = $_GET["immat_voit"];
	$puiss_voit = $_GET["puiss_voit"];
	$biographie = $_GET["biographie"];
	$photo = $_GET["photo"];
	$branche=$_GET["branche"];
	if(isset($_GET["loginBDD"])) 
	{	
		$loginBDD = $_GET["loginBDD"];
	} 
	else 
	{
	$loginBDD="";
	}
	//ref_acteur
	$sql_max_num ="SELECT MAX(num_acteur) FROM acteurs WHERE branche = '$branche'";
	$res_sql_max_num = tx_query($sql_max_num);
	$res = mysql_fetch_array($res_sql_max_num);
	$num_acteur=$res[0]+1;
	
	//num_acteur
	$ref_acteur = "A-".$branche.$num_acteur;
	
	$reqaut = "INSERT INTO acteurs (`nom`,`prenom`,`tel`,`mail`,`immat_voit`,`puissance_voit`,`branche`,`ref_acteur`,`num_acteur`,`login`,`biographie`,`photo`)
			VALUES ('$nom_aut','$prenom_aut','$tel_aut','$mail_aut','$immat_voit','$puiss_voit','$branche','$ref_acteur','$num_acteur','$loginBDD','$biographie','$photo') ;";
	tx_query($reqaut);
	echo "L'acteur $ref_acteur : $prenom_aut $nom_aut a été correctement ins&eacute;r&eacute; dans la base de donn&eacute;es";
	mysql_close();

	/* lien retour */
	echo "<br><br>";
	echo "<form method=GET action=\"menu_acteurs.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour\"></form>";


} // fin if (isset($_GET["action"]))

else
{
	echo "<br><br>";

	
	echo "<form method=GET action=\"ajouter_acteur.php\">";
	echo "<table width=\"100%\" border=2 bordercolor=black class=texte>";
	echo "<tr align=left><td>Acteur</td><td>";
	echo "<table class=texte><tr><td>Nom : </td><td><input type=\"text\" size=25 name=\"nom_aut\"></td></tr>";
		echo "<tr><td>Pr&eacute;nom : </td><td><input type=\"text\" size=25 name=\"prenom_aut\"></td></tr>";
		echo "<tr><td>T&eacute;l&eacute;phone : </td><td><input type=\"text\" size=10 name=\"tel_aut\"></td></tr>";
		echo "<tr><td>Immatriculation voiture : </td><td><input type=\"text\" size=15 name=\"immat_voit\"></td></tr>";
		echo "<tr><td>Puissance voiture : </td><td><input type=\"text\" size=2 name=\"puiss_voit\"></td></tr>";
		echo "<tr><td>Biographie : </td><td><input type=\"text\" size=25 name=\"biographie\"></td></tr>";
		echo "<tr><td>Chemin de la photo (à partir de /photos/) : </td><td><input type=\"text\" size=25 name=\"photo\"></td></tr>";
		echo "<tr><td>Adresse e-mail : </td><td><input type=\"text\" size=25 name=\"mail\"></td></tr>";
		//echo "<tr><td>Statut :</td><td><input type=\"text\" </td>";
	if ($login == 'superadmin')
	{
		form_branche();
		echo "<tr><td>Login (admin + BRANCHE: adminGI, adminROB...): </td><td><input type=\"text\" size=25 name=\"loginBDD\"</td></tr>";
		//echo "";
	}
	else 
	{
		echo "<input type =\"hidden\" value=\"$branche\" name=\"branche\" >";
	}
	
	
	echo "</table></table>";
	echo "<br><input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Ajouter\" name=\"action_ajouter_acteur\">";
	echo "</form>";

	/* lien retour */
	echo "<form method=GET action=\"menu_acteurs.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour\"></form>";

	mysql_close();
}
}
?>

		</div>

	</body>
</html>
