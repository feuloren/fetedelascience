<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>

		<title>Modifier un Acteur</title>

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

<span class="titre">Modifier un Acteur</span>


<?php
require_once("_connection.php");
/* Connection a la base de données */
$sql = tx_connect();
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
Si c est le cas, c est qu un formulaire a été rempli et doit Ãªtre traité */
if(isset($_GET["action_modifier_acteur"]))
{
	echo "<br><br><br>";
	/* récupération des valeurs à  insérer */

	$ref = $_GET["ref"];
	$nom_aut = $_GET["nom_aut"];
	$prenom_aut = $_GET["prenom_aut"];
	$tel_aut = $_GET["tel_aut"];
	$mail_aut = $_GET["mail_aut"];
	$immat_voit = $_GET["immat"];
	$puiss_voit = $_GET["puiss"];
	$auteur = $prenom_aut . " " . $nom_aut ;
	$branche = $_GET["branche"];
	$biographie = $_GET["biographie"];
	$photo = $_GET["photo"];
	if(isset($_GET["loginBDD"])) 
	{	
		$loginBDD = $_GET["loginBDD"];
	} 
	else 
	{
	$loginBDD="";
	}

	//echo "ref: $ref";

	/* Construction des requÃªtes de mise a jour */
	$reqaut = "update acteurs 
	set 	nom='$nom_aut',
		prenom='$prenom_aut',
		tel='$tel_aut',
		mail='$mail_aut',
		immat_voit='$immat_voit',
		puissance_voit='$puiss_voit',
		branche='$branche',
		biographie='$biographie',
		photo='$photo',
		login='$loginBDD'
	where ref='$ref'";


	/* Execution de la requete */
	
	tx_query($reqaut);
		
	/* liens retour */
	echo "<form method=GET action=\"modifier_acteur.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$ref\" name=\"ref\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour à  la sélection de l'acteur à  modifier\"></form>";

	echo "<form method=GET action=\"menu_acteurs.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$ref\" name=\"ref\">";			
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour au sommaire\"></form>";


} // fin if (isset($_GET["action"]))

// affichage du formulaire rempli de l'auteur a modifier
elseif (isset($_GET["choixok"]))
{
	echo "<br><br>";

	// reference de l'acteur a modifier
	$ref = $_GET["ref"];

	// création automatique de la référence de l'auteur
	$res_aut = tx_query("select nom, prenom, tel, mail, immat_voit, puissance_voit,branche,ref_acteur,login, biographie, photo from acteurs where ref='$ref'");
	$val_aut = mysql_fetch_array($res_aut);

	echo "<form method=GET action=\"modifier_acteur.php\">";
	echo "<table width=\"100%\" border=2 bordercolor=black class=texte>";
	//echo "<tr align=left><td>Reference</td><td><input type=\"text\" size=8 name=\"ref\" value=\"$ref\"></td></tr>";

	
	echo "<table class=texte>";
	echo "<tr><td>Référence: </td><td><input type=\"text\" size = 8 name=\"ref_aut\" disabled value=\"$val_aut[7]\"</td></tr>";
	echo "<tr><td>Nom : </td><td><input type=\"text\" size=25 name=\"nom_aut\" value=\"$val_aut[0]\"></td></tr>";
	
	echo "<tr><td>Prenom : </td><td><input type=\"text\" size=25 name=\"prenom_aut\" value=\"$val_aut[1]\"></td></tr>";
	echo "<tr><td>Telephone : </td><td><input type=\"text\" size=10 name=\"tel_aut\" value=\"$val_aut[2]\"></td></tr>";
	echo "<tr><td>E-mail : </td><td><input type=\"text\" size=25 name=\"mail_aut\" value=\"$val_aut[3]\"></td></tr>";
	echo "<tr><td>Immatriculation voiture : </td><td><input type=\"text\" size=15 name=\"immat\" value=\"$val_aut[4]\"></td></tr>";
	echo "<tr><td>Puissance voiture : </td><td><input type=\"text\" size=2 name=\"puiss\" value=\"$val_aut[5]\"></td></tr>";
	echo "<tr><td>Biographie : </td><td><input type=\"text\" size=10 name=\"biographie\" value=\" \"></td></tr>";
	echo "<tr><td>Chemin des photos : </td><td><input type=\"text\" size=10 name=\"photo\" value=\" \"></td></tr>";
	if($login == 'superadmin')
	{
		form_branche($val_aut[6]);
		echo "<tr><td>Login (admin + BRANCHE: adminGI, adminROB...): </td><td><input type=\"text\" size=25 name=\"loginBDD\" value=\"$val_aut[8]\"></td></tr>";
	}
	else 
	{
		echo "<input type=\"hidden\" value=\"$branche\" name=\"branche\">";
	}
	
	echo "</table>";
	//selected="selected"
	
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$ref\" name=\"ref\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Modifier\" name=\"action_modifier_acteur\">";
	echo "</form>";

	/* liens retour */
	echo "<form method=GET action=\"modifier_acteur.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$ref\" name=\"ref\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour a la selection de l'auteur a modifier\"></form>";

	echo "<form method=GET action=\"menu_acteurs.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour au sommaire\"></form>";

	mysql_close();
}
else
{
	
	echo "Choisissez l'auteur a modifier<br><br>";
	$query = "select ref, nom, prenom, ref_acteur from acteurs " . $sqlbr . " ORDER BY ref_acteur";
	$res = tx_query($query);

	echo "<form method=GET action=\"modifier_acteur.php\"><table>";
	$val = mysql_fetch_array($res);
	echo "<tr><td><input type=radio name=\"ref\" value=\"$val[0]\" checked></td><td>$val[3]: $val[1] $val[2]</td></tr>";


	/* Boucle sur les auteurs */
	while($val = mysql_fetch_array($res))
		echo "<tr><td><input type=radio name=\"ref\" value=\"$val[0]\"></td><td>$val[3]: $val[1] $val[2]</td></tr>";

	echo "</table>";

	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Valider\" name=\"choixok\">";
	echo "</form>";

	/* lien retour */
	echo "<form method=GET action=\"menu_acteurs.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour\"></form>";
}
}
?>

		</div>

	</body>
</html>
