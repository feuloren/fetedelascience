
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" />
<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
<title>Ajouter une Conférence</title>
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

<span class="titre">Ajouter une Conférence</span>

<?php

require_once("_connection.php");
$sql = tx_connect();
$login = $_GET["login"];
$sessionid = $_GET["sessionid"];
if(verif($login,$sessionid))
{

if(isset($_GET["ajouter_acteur"])) 
{
		
	//Formulaire insérant dans la bdd les données déja rentréest
	//on récupère le login puis la branche de la conférence
	$login = $_GET["login"];
	if ($login == "superadmin"){$branche = $_GET["branche"];$login2 = "admin" . $branche; }
	else{$len = strlen($login);	$branche = substr($login,5,$len-5);}
	//puis la référence du correspondant
	$res = tx_query("SELECT ref FROM acteurs where login = '$login2'");
	$ref_array = mysql_fetch_array($res);
	$ref_corres = $ref_array[0];
	//on récupère le titre de la conférence et les références des auteurs.
	$titre = addslashes(($_GET["titre"]));
	$ref_aut1 = $_GET["aut1"];
	$ref_aut2 = $_GET["aut2"];
	$ref_aut3 = $_GET["aut3"];
	$year = $_GET["year"];

	//public visé
	$public = "";
	for ($i=0; $i < 3; $i++)	
	{
		if(isset($_GET["public$i"]))	
		{
			$pubtemp = $_GET["public$i"];
			if ($public != "")	$public = $public . ", ";
			$public = $public . $pubtemp;
		}
	}
			
	//commentaires
	$comm_pub = addslashes(($_GET["comm_pub"]));
	$resume = addslashes(($_GET["resume"]));
	//matériel
	$mat = "";
	for ($i=0; $i < 5; $i++)	{
		if(isset($_GET["mat$i"]))	{
			$mattemp = $_GET["mat$i"];
			if ($mat != "")	$mat = $mat . ", ";
			$mat = $mat . $mattemp;
		}
	}
	$comm_mat = addslashes(($_GET["comm_mat"]));
	
	//création de la reférence de la conférence si elle n'existe pas encore
	$sql_max = "SELECT MAX(num_conf) FROM conferences WHERE branche = '$branche'";
	$res_sql_max = tx_query($sql_max);
	$data = mysql_fetch_array($res_sql_max);
	$res = $data[0];
	if ($res == 'NULL')
	{
		$res = 0;
	}
	$num = $res + 1;
	$ref_conf = "C-".$branche.$num; 		//C-xxyyyy
	

	$reqconf = "INSERT INTO conferences (`titre`,`public`,`materiel`,`comm_mat`,`resume`,`comm_public`,`ref_corres`,
							`refa1`,`refa2`,`refa3`,`num_conf`,`ref_conf`,`branche`,'annee')						
			VALUES ('$titre','$public','$mat','$comm_mat','$resume','$comm_pub','$ref_corres',
				'$ref_aut1','$ref_aut2','$ref_aut3','$num','$ref_conf ','$branche','$year')";		
	
	
	echo "<br><br>Requete conference: $reqconf<br>";
	/* Execution de la requete */
	tx_query($reqconf);
	//mysql_close();

	/* lien retour */
	echo "<br><br>";
	echo "<form method=GET action=\"ajouter_conference.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour\"></form>";


		
	//Formulaire pour ajouter l'acteur
	echo "<br><br>";
	//on récupère la réference de la conférence a modifier.
	/*$login = $_GET["login"];
	if ($login != "superadmin")	{		$len = strlen($login);		$branche = substr($login,5,$len-5);}*/
	
	// affichage du formulaire destiné à l'ajout d'un auteur
	echo "<form method=GET action=\"modifier_conferences.php\">";
	
		echo "<table width=\"100%\" border=2 bordercolor=black class=texte>";
		echo "<tr align=left><td>Acteur</td><td>";
		echo "<table class=texte><tr><td>Nom : </td><td><input type=\"text\" size=25 name=\"nom_aut\"></td></tr>";
		echo "<tr><td>Pr&eacute;nom : </td><td><input type=\"text\" size=25 name=\"prenom_aut\"></td></tr>";
		echo "<tr><td>T&eacute;l&eacute;phone : </td><td><input type=\"text\" size=10 name=\"tel_aut\"></td></tr>";
		echo "<tr><td>Immatriculation voiture : </td><td><input type=\"text\" size=15 name=\"immat_voit\"></td></tr>";
		echo "<tr><td>Puissance voiture : </td><td><input type=\"text\" size=2 name=\"puiss_voit\"></td></tr>";
		echo "<tr><td>Adresse e-mail : </td><td><input type=\"text\" size=25 name=\"mail\"></td></tr>";
		if($login == "superadmin")	{form_branche();}
		else 	{echo  "<input type=\"hidden\" value=\"$branche\" name=\"branche\">";}
		echo "</table></td></tr>";
		
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"hidden\" name=\"choixok\">";
	echo "<input type=\"hidden\" name=\"ref_conf\" value=\"$ref_conf\">";
	echo "<input type=\"submit\" value=\"Ajouter\" name=\"valider_ajouter_acteur\">";
	echo "</form>";
	
	
	$_GET["ajouter_acteur"]='';
	unset($_GET["ajouter_acteur"]);
	}
	
//fin du if(isset($_GET["ajouter_acteur"])) 



elseif(isset($_GET["action_ajouter_conference"]))
{
	
	echo "<br><br><br>";

	//on récupère le login puis la branche de la conférence
	$login = $_GET["login"];
	if ($login == "superadmin")
	{
		$branche = $_GET["branche"];
		$login2 = "admin" . $branche; 
	}
	else
	{
		$len = strlen($login);
		$branche = substr($login,5,$len-5);
	}
	
	//puis la référence du correspondant
	$res = tx_query("SELECT ref FROM acteurs where login = '$login2'");
	$ref_array = mysql_fetch_array($res);
	$ref_corres = $ref_array[0];
	
	//on récupère le titre de la conférence et les références des auteurs.
	$titre = addslashes(($_GET["titre"]));
	$ref_aut1 = $_GET["aut1"];
	$ref_aut2 = $_GET["aut2"];
	$ref_aut3 = $_GET["aut3"];
	$year = $_GET["year"];

	//création de la reférence de la conférence
	$sql_max = "SELECT MAX(num_conf) FROM conferences WHERE branche = '$branche'";
	$res_sql_max = tx_query($sql_max);
	$data = mysql_fetch_array($res_sql_max);
	$res = $data[0];
	if ($res == 'NULL')
	{
		$res = 0;
	}
	$num = $res + 1;
	$ref_conf = "C-".$branche.$num; 		//C-xxyyyy
	

	//public visé
	$public = "";
	for ($i=0; $i < 3; $i++)	
	{
		if(isset($_GET["public$i"]))	
		{
			$pubtemp = $_GET["public$i"];
			if ($public != "")	$public = $public . ", ";
			$public = $public . $pubtemp;
		}
	}
			
	//commentaires
	$comm_pub = addslashes(($_GET["comm_pub"]));
	$resume = addslashes(($_GET["resume"]));
	
	//matériel
	$mat = "";
	for ($i=0; $i < 5; $i++)	{
		if(isset($_GET["mat$i"]))	{
			$mattemp = $_GET["mat$i"];
			if ($mat != "")	$mat = $mat . ", ";
			$mat = $mat . $mattemp;
		}
	}
	$comm_mat = addslashes(($_GET["comm_mat"]));

	/* Construction des requêtes d insertion */
	$reqconf = "INSERT INTO conferences
					(`titre`,`public`,`materiel`,`comm_mat`,`resume`,`comm_public`,`ref_corres`,
							`refa1`,`refa2`,`refa3`,`num_conf`,`ref_conf`,`branche`,`annee`)						
			VALUES (
				'$titre','$public','$mat','$comm_mat','$resume','$comm_pub','$ref_corres',
				'$ref_aut1','$ref_aut2','$ref_aut3','$num','$ref_conf ','$branche','$year')";
			
	//echo "<br>$titre $public $mat $comm_mat $resume $comm_pub";
	echo "<br><br>Conférence ajoutée avec succès<br>";
	echo "annee ".$year;
	/* Execution de la requete */
	tx_query($reqconf);
	
	//ajout de l' auteur dans la table de dispo
	
	$tabdispo[0] = $ref_aut1;
	$tabdispo[1] = $ref_aut2;
	$tabdispo[2] = $ref_aut3;

    /*for($d = 0 $d < 3 $d++){
	
		$sql_dispo = "SELECT * FROM dispo WHERE ref_acteur = '$tabdispo[$d]'";
		
		$res_sql_dispo = tx_query($sql_dispo);
		$data_dispo= mysql_fetch_array($res_sql_dispo);
		
		if($datadispo == NULL) {
			$reqdispo = "INSERT INTO `dbwebscien`.`dispo` (
			`ref_acteur` ,`c01` ,`c02` ,`c03` ,`c04` ,`c05` ,`c06` ,`c07` ,`c08` ,`c09` ,`c10` ,`c11` ,`c12` ,`c13` ,`c14` ,`c15` ,`c16` ,`c17` ,`c18` ,`c19` ,`c20` ,`c21` ,`c22` ,`c23` ,`c24` ,`c25` ,`c26` ,`c27` ,`c28` ,`c29` ,`c30` ,`c31` ,`c32` ,`c33` ,`c34` ,`c35` ,`c36` ,`c37` ,`c38` ,`c39` ,`c40` ,`c41` ,`c42`
				)
			VALUES ('$tabdispo[$d]', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1'
			);";
			tx_query($reqdispo );
		}
	}*/

	
	//mysql_close();

	/* lien retour */
	echo "<br><br>";
	echo "<form method=GET action=\"ajouter_conference.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour\"></form>";


	} // fin if (isset($_GET["action"]))

else
{
	echo "<br><br>";
	$login = $_GET["login"];
	if($login == 'superadmin')
	{}
	else
	{
		$len = strlen($login);
		$branche = substr($login,5,$len-5);
		$req = tx_query("select nom,prenom from acteurs where login='$login'");
		$res = mysql_fetch_array($req);
		$nomc = $res[0];
		$prenomc=$res[1];
	}
	


	echo "<form method=GET action=\"ajouter_conference.php\">";
	echo "<table width=\"600\" border=2 bordercolor=black class=texte>";
	echo "<tr align=left><td>Auteur</td><td>";
	
	//on va selectionner toutes les propriétés des acteurs.
	$req_list = "select ref,nom,prenom from acteurs WHERE num_acteur<>0 order by nom";
	$idq = tx_query($req_list);
	
	//affichage de 3 listesbox et d'un bouton ajouter un auteur
	//quand on choisit de 1 à 3 auteurs ok
	// si on clique on peut ajouter un auteur ou plus
	// au retour, on doit selectionner un auteur ou plus dans la listebox
	
	if (($nbr = mysql_num_rows($idq))== 0);
	echo "<select name=\"aut1\">";
	echo mysql_num_rows($idq);
	echo "<option value=\"\"> </option>>";
	while ($aut = mysql_fetch_array($idq) )
	{
		$prenom=$aut["prenom"];
		$nom=$aut["nom"];
		$ref=$aut["ref"];
		$nomcomplet = $nom . " " . $prenom;
		//echo $auteur["nom"];
		echo "<option value=\"$ref\"> $nomcomplet </option>";
		//selected="selected"	
	}
	echo "</select><br>";
		
	$idq = tx_query($req_list);
	echo "<select name=\"aut2\">";
	echo "<option value=\"\"> </option>>";
	if (($nbr = mysql_num_rows($idq))== 0);
	//echo mysql_num_rows($idq);
	while ($aut = mysql_fetch_array($idq) )
	{
		$prenom=$aut["prenom"];
		$nom=$aut["nom"];
		$ref=$aut["ref"];
		$nomcomplet = $nom . " " . $prenom;
		//echo $auteur["nom"];
		echo "<option value=\"$ref\"> $nomcomplet </option>";
		//selected="selected"	
	}
	echo "</select><br>";
	
	$idq = tx_query($req_list);
	echo "<select name=\"aut3\">";
	echo "<option value=\"\"> </option>>";
	if (($nbr = mysql_num_rows($idq))== 0);
	//echo mysql_num_rows($idq);
	while ($aut = mysql_fetch_array($idq) )
	{
		$prenom=$aut["prenom"];
		$nom=$aut["nom"];
		$ref=$aut["ref"];
		$nomcomplet = $nom . " " . $prenom;
		//echo $auteur["nom"];
		echo "<option value=\"$ref\"> $nomcomplet </option>";
		//selected="selected"	
	}
	echo "</select><br>";
	
	//affichage du bouton d'ajout d'auteurs
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Ajouter acteur\" name=\"ajouter_acteur\">";
	



	echo "<tr align=left><td>Titre</td><td><textarea rows=3 cols=50 name=\"titre\"></textarea></td></tr>";
		echo "<tr align=left><td>Public</td><td>";
			echo "<input type=\"checkbox\" name=\"public0\" value=\"primaire\">Primaire";
			echo "<input type=\"checkbox\" name=\"public1\" value=\"college\">College";
			echo "<input type=\"checkbox\" name=\"public2\" value=\"lycee\">Lycee</td></tr>";
		echo "<tr align=left><td>Commentaires sur le public</td><td><textarea rows=3 cols=50 name=\"comm_pub\"></textarea></td></tr>";
		echo "<tr align=left><td>Résumé</td><td><textarea rows=3 cols=50 name=\"resume\"></textarea></td></tr>";
		
		//le nom du correspondant est celui de l'admin de branche connecté sauf si la personne connectée
		//est le superadmin, auquel cas les noms et prénoms du correspondant doivent être selectionnés.
		if ($login != "superadmin")
		{
		//echo "<tr align=left><td>Nom du correspondant</td><td><input type=\"text\" size=30 name=\"nom\" disabled value=\"$prenomc $nomc\"></td></tr>";
		}
		else
		{
		//connaître la branche suffit à connaître le correspondant
		form_branche();		
		}
		
		
		echo "<tr align=left><td>Matériel</td><td>";
			echo "<input type=\"checkbox\" name=\"mat0\" value=\"retroprojecteur\">retroprojecteur";
			echo "<input type=\"checkbox\" name=\"mat1\" value=\"videoprojecteur\">videoprojecteur";
			echo "<input type=\"checkbox\" name=\"mat2\" value=\"tableau\">tableau";
			echo "<input type=\"checkbox\" name=\"mat3\" value=\"ecran\">ecran";
			echo "<input type=\"checkbox\" name=\"mat4\" value=\"TV\">TV";
		echo "<tr align=left><td>Commentaires sur le matériel</td><td><textarea rows=3 cols=50 name=\"comm_mat\"></textarea></td></tr>";
		echo "<tr align=left><td>Année</td><td><select name=\"year\"><option value=\"2010\">2010</option>><option value=2011\"\">2011</option>>></select></td></tr>";
	echo "</table>";

	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	//echo "<input type=\"hidden\" value=\"$ref\" name=\"ref\">";
	echo "<input type=\"submit\" value=\"Ajouter\" name=\"action_ajouter_conference\">";
	echo "</form>";

	/* lien retour */
	echo "<form method=GET action=\"menu_conferences.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour\"></form>";

//mysql_close();
			
//echo "<form  method=GET action=\"menu_conferences.php\">";
//echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";

//echo "<input type=\"submit\" value=\"Ajouter acteur\"></form>";
			
			
}
}


?>
</div>
</body>
</html>
