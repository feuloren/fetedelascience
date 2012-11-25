
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
<title>Ajouter un atelier</title>
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
<span class="titre">Ajouter un atelier</span>
<?php


require_once("_connection.php");
$sql = tx_connect();
$login = $_GET["login"];
$sessionid = $_GET["sessionid"];
if(verif($login,$sessionid))
{ 

if(isset($_GET["ajouter_porteur"]))
{
	
	if ($login != "superadmin") 	{
			$len = strlen($login);
			$branche = substr($login,5,$len-5);
	}
	else{
			$branche=$_GET["branche"];
	}	
	$porteur = $_GET["porteur"];	
	$coorg = $_GET["coorg"];	
	$titre = addslashes(($_GET["titre"]));
	$sujet = addslashes(($_GET["sujet"]));	
	$resume = addslashes(($_GET["resume"]));
	if(isset($_GET["branche"])) {
		$branche = $_GET["branche"]; 
	}
	for($i=1;$i<9;$i++)
		$part[$i] = $_GET["part$i"];

	$photos = $_GET["photos"];		
	$localisation = addslashes(($_GET["localisation"]));	
	$locFinale = addslashes(($_GET["locFinale"]));
	$question1 = addslashes(($_GET["question1"]));	
	$reponse1 = addslashes(($_GET["reponse1"]));	
	$question2 = addslashes(($_GET["question2"]));
	$reponse2 = addslashes(($_GET["reponse2"]));	
	$question3 = addslashes(($_GET["question3"]));	
	$reponse3 = addslashes(($_GET["reponse3"]));
	$question4 = addslashes(($_GET["question4"]));	
	$reponse4 = addslashes(($_GET["reponse4"]));	
	$question5 = addslashes(($_GET["question5"]));
	$reponse5 = addslashes(($_GET["reponse5"]));
	$participants = $_GET["participants"];	
	$biographie = $_GET["biographie"];
	$lienmedia = $_GET["lienmedia"];
	$observations = addslashes(($_GET["observations"]));
	$uvpr =$_GET["uvpr"];	
	$divmatos = addslashes(($_GET["divmatos"]));

	//matériel
	$mat = "";		
	$listemob[0] = "tables";	
	$listemob[1] = "chaises";	
	$listemob[2] = "bancs";	
	$listemob[3] = "grilles";	
	$listemob[4] = "posters";
	
	$type_at = "";
	for($h=0; $h<4;$h++)
	{
		if(isset($_GET["type$h"]))
		{
			$typetemp = $_GET["type$h"];
			if ($type_at != "") 	$type_at = $type_at .", ";
			$type_at = $type_at . $typetemp;
		}
	}	
	
	
	for ($i=0; $i < 5; $i++)	
	{
		if(isset($_GET["mat$i"]))	
		{
			$mattemp = $_GET["mat$i"];
			if ($mat != "")	$mat = $mat . ", ";
			$mat = $mat . $mattemp;
		}
	}

	//alimentation
	$alimentation ="";
	for ($j=0; $j <4; $j++)
	{
		if(isset($_GET["ali$j"]))
		{
			$alitemp = $_GET["ali$j"];
			if($alimentation != "") $alimentation = $alimentation .", ";
			$alimentation = $alimentation . $alitemp;
		}
	}

	//mobilier
	$mobilier="";
	for ($k=0; $k <5; $k++)
	{
		//if(isset($_GET["mob$k"]))
		//{
			$mobtemp = $_GET["mob$k"];
			if($mobilier != "") $mobilier = $mobilier .", ";
			$mobilier = $mobilier . $mobtemp." ".$listemob[$k];
		//}
	}



	//création de la ref de l'atelier
	$ref_at = "V-" . $branche;
	$req_at = "SELECT * FROM ateliers WHERE ref_at LIKE '$ref_at%';";
	$res_at = tx_query($req_at);
	$nb_at = mysql_num_rows($res_at)+1;
	$ref_at = $ref_at . $nb_at;


	/* Construction des requêtes d insertion */
	$req_ins = "INSERT INTO ateliers
	 ( `ref_at` , `porteur` ,`titre`,  `sujet` , `type`, `coorg`, `resume`,
	`partenaire1` , `partenaire2` , `partenaire3` , `partenaire4` , `partenaire5` , `partenaire6` , `partenaire7` , `partenaire8` , 
	`localisation` , `locFinale`, `divmatos`,`uvpr`,
	`question1` , `reponse1` , `question2` , `reponse2` , `question3` , `reponse3` , `question4` , `reponse4` , `question5` , `reponse5` , 
	`liste_participants`, `biographie`  , `mobilier` , `materiel` , `alimentation` , `observations` , `photos`, `lienmedia`) 
		VALUES 
	('$ref_at','$porteur','$titre','$sujet','$type_at', '$coorg','$resume',
	'$part[1]','$part[2]','$part[3]','$part[4]','$part[5]','$part[6]','$part[7]','$part[8]',
	'$localisation','$locFinale','$divmatos','$uvpr',
	'$question1','$reponse1','$question2','$reponse2','$question3','$reponse3','$question4','$reponse4','$question5','$reponse5',
	'$participants', '$biographie' ,'$mobilier','$mat','$alimentation','$observations','$photos', '$lienmedia');";

	//echo $req_ins ;
	/* Execution de la requete */
	tx_query($req_ins);
	
	echo "<br><br>";
	$login = $_GET["login"];
	if ($login != "superadmin")
	{
		$len = strlen($login);
		$branche = substr($login,5,$len-5);
	}
	
	// affichage du formulaire destiné à l'ajout d'un auteur
	//a été récemment modifié...
	echo "<form method=GET action=\"modifier_atelier.php\">";
	
	echo "<table width=\"100%\" border=2 bordercolor=black class=texte>";
	echo "<tr align=left><td>Acteur</td><td>";
	echo "<table class=texte><tr><td>Nom : </td><td><input type=\"text\" size=25 name=\"nom_aut\"></td></tr>";
	echo "<tr><td>Pr&eacute;nom : </td><td><input type=\"text\" size=25 name=\"prenom_aut\"></td></tr>";
	echo "<tr><td>T&eacute;l&eacute;phone : </td><td><input type=\"text\" size=10 name=\"tel_aut\"></td></tr>";
	echo "<tr><td>Immatriculation voiture : </td><td><input type=\"text\" size=15 name=\"immat_voit\"></td></tr>";
	echo "<tr><td>Puissance voiture : </td><td><input type=\"text\" size=2 name=\"puiss_voit\"></td></tr>";
	echo "<tr><td>Adresse e-mail : </td><td><input type=\"text\" size=25 name=\"mail\"></td></tr>";
	if($login == "superadmin"){	form_branche();}
	else 	{echo  "<input type=\"hidden\" value=\"$branche\" name=\"branche\">";}
	echo "</table></td></tr>";
	//test
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$ref_at\" name=\"ref_at\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"hidden\" name=\"valider_ajouter_porteur\">";
	echo "<input type=\"submit\" value=\"Valider\" name=\"choixok\"></form>";
	
	$_GET["ajouter_porteur"]='';
	unset($_GET["ajouter_porteur"]);
}

elseif(isset($_GET["ajouter_partenaire"]))
{

	
	
	if ($login != "superadmin") 	{
			$len = strlen($login);
			$branche = substr($login,5,$len-5);
	}
	else {
			$branche=$_GET["branche"];
	}	
	$porteur = $_GET["porteur"];	
	$coorg = $_GET["coorg"];	
	$titre = addslashes(($_GET["titre"]));
	$sujet = addslashes(($_GET["sujet"]));	
	$resume = addslashes(($_GET["resume"]));
	if(isset($_GET["branche"])) {
		$branche = $_GET["branche"]; 
	}
	for($i=1;$i<9;$i++)
		$part[$i] = $_GET["part$i"];

	$photos = $_GET["photos"];		
	$localisation = addslashes(($_GET["localisation"]));	
	$locFinale = addslashes(($_GET["locFinale"]));
	$question1 = addslashes(($_GET["question1"]));	
	$reponse1 = addslashes(($_GET["reponse1"]));	
	$question2 = addslashes(($_GET["question2"]));
	$reponse2 = addslashes(($_GET["reponse2"]));	
	$question3 = addslashes(($_GET["question3"]));	
	$reponse3 = addslashes(($_GET["reponse3"]));
	$question4 = addslashes(($_GET["question4"]));	
	$reponse4 = addslashes(($_GET["reponse4"]));	
	$question5 = addslashes(($_GET["question5"]));
	$reponse5 = addslashes(($_GET["reponse5"]));
	$participants = $_GET["participants"];
	$biographie = $_GET["biographie"];
	$lienmedia = $_GET["lienmedia"];
	$observations = addslashes(($_GET["observations"]));
	$uvpr =$_GET["uvpr"];	
	$divmatos = addslashes(($_GET["divmatos"]));

	//matériel
	$mat = "";		
	$listemob[0] = "tables";	
	$listemob[1] = "chaises";	
	$listemob[2] = "bancs";	
	$listemob[3] = "grilles";	
	$listemob[4] = "posters";
	
	$type_at = "";
	for($h=0; $h<4;$h++)
	{
		if(isset($_GET["type$h"]))
		{
			$typetemp = $_GET["type$h"];
			if ($type_at != "") 	$type_at = $type_at .", ";
			$type_at = $type_at . $typetemp;
		}
	}	
	
	
	for ($i=0; $i < 5; $i++)	
	{
		if(isset($_GET["mat$i"]))	
		{
			$mattemp = $_GET["mat$i"];
			if ($mat != "")	$mat = $mat . ", ";
			$mat = $mat . $mattemp;
		}
	}

	//alimentation
	$alimentation ="";
	for ($j=0; $j <4; $j++)
	{
		if(isset($_GET["ali$j"]))
		{
			$alitemp = $_GET["ali$j"];
			if($alimentation != "") $alimentation = $alimentation .", ";
			$alimentation = $alimentation . $alitemp;
		}
	}

	//mobilier
	$mobilier="";
	for ($k=0; $k <5; $k++)
	{
		//if(isset($_GET["mob$k"]))
		//{
			$mobtemp = $_GET["mob$k"];
			if($mobilier != "") $mobilier = $mobilier .", ";
			$mobilier = $mobilier . $mobtemp." ".$listemob[$k];
		//}
	}



	//création de la ref de l'atelier
	$ref_at = "V-" . $branche;
	$req_at = "SELECT * FROM ateliers WHERE ref_at LIKE '$ref_at%';";
	$res_at = tx_query($req_at);
	$nb_at = mysql_num_rows($res_at)+1;
	$ref_at = $ref_at . $nb_at;

	/* Construction des requêtes d insertion */
	$req_ins = "INSERT INTO ateliers
	 ( `ref_at` , `porteur` ,`titre`,  `sujet` , `type`, `coorg`, `resume`,
	`partenaire1` , `partenaire2` , `partenaire3` , `partenaire4` , `partenaire5` , `partenaire6` , `partenaire7` , `partenaire8` , 
	`localisation` , `locFinale`, `divmatos`,`uvpr`,
	`question1` , `reponse1` , `question2` , `reponse2` , `question3` , `reponse3` , `question4` , `reponse4` , `question5` , `reponse5` , 
	`liste_participants`, `biographie`  , `mobilier` , `materiel` , `alimentation` , `observations` , `photos`, `lienmedia`) 
		VALUES 
	('$ref_at','$porteur','$titre','$sujet','$type_at', '$coorg','$resume',
	'$part[1]','$part[2]','$part[3]','$part[4]','$part[5]','$part[6]','$part[7]','$part[8]',
	'$localisation','$locFinale','$divmatos','$uvpr',
	'$question1','$reponse1','$question2','$reponse2','$question3','$reponse3','$question4','$reponse4','$question5','$reponse5',
	'$participants', '$biographie' ,'$mobilier','$mat','$alimentation','$observations','$photos', '$lienmedia');";

	//echo $req_ins ;
	/* Execution de la requete */
	tx_query($req_ins);
	

	echo "<br><br>";
	echo "<form method=GET action=\"modifier_atelier.php\">";
	echo "<table table width=\"100%\" border=2 bordercolor=black class=texte>";
	echo "<tr align=left><td width=25%>Nom : </td><td><input type=\"text\" size=50 name=\"nom\"></td></tr>";
	echo "<tr align=left><td width=25%>Logo (nom du fichier) : </td><td><input type=\"text\" size=50 name=\"logo\"></td></tr>";
	echo "<tr align=left><td width=25%>Coordonnées : </td><td><textarea rows=3 cols=50 name=\"coo\"></textarea></td></tr>";
	echo "<tr align=left><td width=25%>Commentaire : </td><td><textarea rows=3 cols=50 name=\"commentaires\"></textarea></td></tr>";
	echo "</table></td></tr>";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"hidden\" value=\"$ref_at\" name=\"ref_at\">";
	echo "<input type=\"hidden\" name=\"valider_ajouter_partenaire\">";
	echo "<input type=\"submit\" value=\"Valider\" name=\"choixok\">";
	echo "</form>";
	
	$_GET["ajouter_partenaire"]='';
	unset($_GET["ajouter_partenaire"]);
}









elseif(isset($_GET["ajouter_atelier"]))
{
	
	//new: récupération de la branche
	if ($login != "superadmin")
		{
			$len = strlen($login);
			$branche = substr($login,5,$len-5);
		}
	else
		{
			$branche=$_GET["branche"];
		}
	//endofnew	
	
	echo "<br><br><br>";
	/* récupération des valeurs à insérer */
	
	$porteur = $_GET["porteur"];	
	$coorg = $_GET["coorg"];	
	$titre = addslashes(($_GET["titre"]));	
	$sujet = addslashes(($_GET["sujet"]));
	$resume = addslashes(($_GET["resume"]));	
	if(isset($_GET["branche"]))
	{	$branche = $_GET["branche"]; }

	for($i=1;$i<9;$i++)
		$part[$i] = $_GET["part$i"];

	$photos = $_GET["photos"];	
	$localisation = addslashes(($_GET["localisation"]));	
	$locFinale = addslashes(($_GET["locFinale"]));
	$question1 = addslashes(($_GET["question1"]));	
	$reponse1 = addslashes(($_GET["reponse1"]));	
	$question2 = addslashes(($_GET["question2"]));
	$reponse2 = addslashes(($_GET["reponse2"]));	
	$question3 = addslashes(($_GET["question3"]));	
	$reponse3 = addslashes(($_GET["reponse3"]));
	$question4 = addslashes(($_GET["question4"]));	
	$reponse4 = addslashes(($_GET["reponse4"]));	
	$question5 = addslashes(($_GET["question5"]));
	$reponse5 = addslashes(($_GET["reponse5"]));
	$participants = $_GET["participants"];	
	$biographie = $_GET["biographie"];
	$numtheme= $_GET["numtheme"];
	$lienmedia = $_GET["lienmedia"];
	$observations = addslashes(($_GET["observations"]));
	$uvpr =$_GET["uvpr"];	
	$divmatos = addslashes(($_GET["divmatos"]));

	//matériel
	$mat = "";
	$listemob[0] = "tables";
	$listemob[1] = "chaises";
	$listemob[2] = "bancs";
	$listemob[3] = "grilles";
	$listemob[4] = "posters";
	
	$type_at = "";
	for($h=0; $h<4;$h++)
	{
		if(isset($_GET["type$h"]))
		{
			$typetemp = $_GET["type$h"];
			if ($type_at != "") 	$type_at = $type_at .", ";
			$type_at = $type_at . $typetemp;
		}
	}	
	
	
	for ($i=0; $i < 5; $i++)	
	{
		if(isset($_GET["mat$i"]))	
		{
			$mattemp = $_GET["mat$i"];
			if ($mat != "")	$mat = $mat . ", ";
			$mat = $mat . $mattemp;
		}
	}

	//alimentation
	$alimentation ="";
	for ($j=0; $j <4; $j++)
	{
		if(isset($_GET["ali$j"]))
		{
			$alitemp = $_GET["ali$j"];
			if($alimentation != "") $alimentation = $alimentation .", ";
			$alimentation = $alimentation . $alitemp;
		}
	}

	//mobilier
	$mobilier="";
	for ($k=0; $k <5; $k++)
	{
		//if(isset($_GET["mob$k"]))
		//{
			$mobtemp = $_GET["mob$k"];
			if($mobilier != "") $mobilier = $mobilier .", ";
			$mobilier = $mobilier . $mobtemp." ".$listemob[$k];
		//}
	}



	//création de la ref de l'atelier
	$ref_at = "V-" . $branche;
	$req_at = "SELECT * FROM ateliers WHERE ref_at LIKE '$ref_at%';";
	$res_at = tx_query($req_at);
	$nb_at = mysql_num_rows($res_at)+1;
	$ref_at = $ref_at . $nb_at;

	/* Construction des requêtes d insertion */
	$req_ins = "INSERT INTO ateliers
	 ( `ref_at` , `porteur` ,`titre`,  `sujet` , `type`, `coorg`, `resume`,
	`partenaire1` , `partenaire2` , `partenaire3` , `partenaire4` , `partenaire5` , `partenaire6` , `partenaire7` , `partenaire8` , 
	`localisation` , `locFinale`, `divmatos`,`uvpr`,
	`question1` , `reponse1` , `question2` , `reponse2` , `question3` , `reponse3` , `question4` , `reponse4` , `question5` , `reponse5` , 
	`liste_participants`, `biographie`  , `mobilier` , `materiel` , `alimentation` , `observations` , `photos`, `lienmedia`, `num_theme` ) 
		VALUES 
	('$ref_at','$porteur','$titre','$sujet','$type_at', '$coorg','$resume',
	'$part[1]','$part[2]','$part[3]','$part[4]','$part[5]','$part[6]','$part[7]','$part[8]',
	'$localisation','$locFinale','$divmatos','$uvpr',
	'$question1','$reponse1','$question2','$reponse2','$question3','$reponse3','$question4','$reponse4','$question5','$reponse5',
	'$participants', '$biographie' ,'$mobilier','$mat','$alimentation','$observations','$photos', '$lienmedia', '$numtheme');";
	//echo $req_ins ;
	tx_query($req_ins);

	/* lien retour */
	echo "L'atelier a été inséré avec succès<br><br>";
	echo "<form method=GET action=\"menu_ateliers.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour\"></form>";


	} // fin if (isset($_GET["action"]))

else
{
	//dans tous les cas affichage du formulaire d'ajout d'atelier
	echo "<br><br>";
	if ($login != "superadmin")
		{
			$len = strlen($login);
			$branche = substr($login,5,$len-5);
			$req=tx_query("SELECT ref_acteur FROM acteurs WHERE login=\"$login\"");
			$res = mysql_fetch_array($req);
			$ref = $res[0];
		}
	

	echo "<form method=GET action=\"ajouter_atelier.php\">";
	echo "<table width=100% border=2 bordercolor=black class=texte>";	
	
	//Porteur de projet, le porteur peut être n'importe quel acteur
	echo "<tr align=left><td>Porteur de projet</td><td>";
	$req_list = "select ref,nom,prenom from acteurs WHERE num_acteur<>0 order by nom";
	$idq = tx_query($req_list);
	if (($nbr = mysql_num_rows($idq))== 0);
	echo "<select name=\"porteur\">";
	echo mysql_num_rows($idq);
	echo "<option value=\"\"> </option>>";
	while ($aut = mysql_fetch_array($idq) )
	{
		$prenom=$aut["prenom"];
		$nom=$aut["nom"];
		$ref=$aut["ref"];
		$nomcomplet = $nom . " " . $prenom;
		echo "<option value=\"$ref\"> $nomcomplet </option>";
	}
	echo "</select><br>";
	//echo "</td>";
	
	//affichage du bouton d'ajout de porteur
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Ajouter un porteur\" name=\"ajouter_porteur\">";
	echo "</tr>";
	
	
	//branche si c'est le superadmin, sinon la branche est selectionnée automatiquement
	if($login == 'superadmin')
	{
		form_branche();
	}
	
	//Coorganisateurs
	echo "<tr align=left><td>Coorganisateurs</td><td><textarea rows=3 cols=50 name=\"coorg\"></textarea></td></tr>";
	
	//Informations diverses
	echo "<tr align=left><td>Titre</td><td><textarea rows=1 cols=50 name=\"titre\"></textarea></td></tr>";
	echo "<tr align=left><td>Sujet</td><td><textarea rows=3 cols=50 name=\"sujet\"></textarea></td></tr>";
	echo "<tr align=left><td>Résumé</td><td><textarea rows=3 cols=50 name=\"resume\"></textarea></td></tr>";
	echo "<tr align=left><td>Type</td><td>";
		echo "<input type=\"checkbox\" name=\"type0\" value=\"Exposition\" checked>Exposition";
		echo "<input type=\"checkbox\" name=\"type1\" value=\"Demonstration\">Demonstration";
		echo "<input type=\"checkbox\" name=\"type2\" value=\"Atelier\">Atelier";
		echo "<input type=\"checkbox\" name=\"type3\" value=\"Conference\">Conference</td></tr>";
	
	//partenaires
	echo "<tr><td>Partenaires</td><td>";
	for ($i=1;$i<9;$i++)	{
		$req_list = "select nom from partenaires order by nom";
		$idq = tx_query($req_list);	
	
		echo "<select name=\"part$i\">";
		echo "<option value=\"\"></option>";
		while ($partenaire = mysql_fetch_array($idq) )
		{
			$nom_p = $partenaire[0];
			$name = "";
			echo "<option value=\"$nom_p\">$nom_p</option>";	
		}
		echo "</select><br>";
	}
	//affichage du bouton d'ajout de partenaires
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Ajouter un partenaire\" name=\"ajouter_partenaire\">";
	echo "</td></tr>";

	//localisation
	echo "<tr align=left><td>Localisation souhaitée</td><td><textarea rows=2 cols=80 name=\"localisation\"></textarea></td></tr>";
	echo "<tr align=left><td>Localisation finale</td><td><textarea rows=2 cols=80 name=\"locFinale\"></textarea></td></tr>";
	
	//participants
	echo "<tr align=left><td>Participants</td><td><textarea rows=4 cols=80 name=\"participants\"></textarea></td></tr>";
	
	//biographie
	echo "<tr align=left><td>Biographie des participants </td><td><textarea rows=4 cols=80 name=\"biographie\"></textarea></td></tr>";
	
	//numero de theme
	echo "<tr align=left><td>Numéro du thème </td> <td><input type=\"text\" name=\"numtheme\" value=\"0\">  </td></tr>";
	
	//différents matériels
	echo "<tr align=left><td>Mobilier</td><td>";
		echo "<input type=\"text\" name=\"mob0\" value=\"0\"> tables <br>";
		echo "<input type=\"text\" name=\"mob1\" value=\"0\">chaises <br>";
		echo "<input type=\"text\" name=\"mob2\" value=\"0\">bancs <br>";
		echo "<input type=\"text\" name=\"mob3\" value=\"0\">grilles <br>";
		echo "<input type=\"text\" name=\"mob4\" value=\"0\">posters <br>";
	
	
	echo "<tr align=left><td>Alimentations</td><td>";
		echo "<input type=\"checkbox\" name=\"ali0\" value=\"eau\">eau";
		echo "<input type=\"checkbox\" name=\"ali1\" value=\"gaz\">gaz";
		echo "<input type=\"checkbox\" name=\"ali2\" value=\"électricité\">electricité";
		echo "<input type=\"checkbox\" name=\"ali3\" value=\"internet\">internet";

	echo "<tr align=left><td>Matériel</td><td>";
		echo "<input type=\"checkbox\" name=\"mat0\" value=\"retroprojecteur\">retroprojecteur";
		echo "<input type=\"checkbox\" name=\"mat1\" value=\"videoprojecteur\">videoprojecteur";
		echo "<input type=\"checkbox\" name=\"mat2\" value=\"tableau\">tableau";
		echo "<input type=\"checkbox\" name=\"mat3\" value=\"ecran\">ecran";
		echo "<input type=\"checkbox\" name=\"mat4\" value=\"TV\">TV";
	
	echo "<tr align=left><td>Remarques sur le matériel</td><td><textarea rows=3 cols=50 name=\"divmatos\"></textarea></td></tr>";
	
	
	//divers: observations, Q/R, photos,...
	echo "<tr align=left><td>UV PR</td><td><input type=\"text\" name=\"uvpr\"></td></tr>";
	
	echo "<tr align=left><td>Observations</td><td><textarea rows=3 cols=50 name=\"observations\"></textarea></td></tr>";
	
	echo "<tr align=left><td>Chemin des photos <br></td><td><textarea rows=3 cols=80 name=\"photos\"></textarea></td></tr>";
	
	echo "<tr align=left><td>Lien pour le media <br></td><td><textarea rows=3 cols=80 name=\"lienmedia\"></textarea></td></tr>";
	
	
	echo "<tr align=left><td>Question1</td><td><textarea rows=4 cols=80 name=\"question1\"></textarea></td></tr>";
	echo "<tr align=left><td>Réponse1</td><td><textarea rows=4 cols=80 name=\"reponse1\"></textarea></td></tr>";
	echo "<tr align=left><td>Question2</td><td><textarea rows=4 cols=80 name=\"question2\"></textarea></td></tr>";
	echo "<tr align=left><td>Réponse2</td><td><textarea rows=4 cols=80 name=\"reponse2\"></textarea></td></tr>";
	echo "<tr align=left><td>Question3</td><td><textarea rows=4 cols=80 name=\"question3\"></textarea></td></tr>";
	echo "<tr align=left><td>Réponse3</td><td><textarea rows=4 cols=80 name=\"reponse3\"></textarea></td></tr>";
	echo "<tr align=left><td>Question4</td><td><textarea rows=4 cols=80 name=\"question4\"></textarea></td></tr>";
	echo "<tr align=left><td>Réponse4</td><td><textarea rows=4 cols=80 name=\"reponse4\"></textarea></td></tr>";
	echo "<tr align=left><td>Question5</td><td><textarea rows=4 cols=80 name=\"question5\"></textarea></td></tr>";
	echo "<tr align=left><td>Réponse5</td><td><textarea rows=4 cols=80 name=\"reponse5\"></textarea></td></tr>";

	echo "</table>";


	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	if($login!='superadmin') echo "<input type=\"hidden\" value=\"$ref\" name=\"ref\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Ajouter\" name=\"ajouter_atelier\">";
	echo "</form>";

	/* lien retour */
	echo "<form method=GET action=\"menu_ateliers.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour\"></form>";
		
}
}

?>
</div>
</body>
</html>
