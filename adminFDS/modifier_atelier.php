<html>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

		<title>Modifier un atelier</title>

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
<span class="titre">Modifier un atelier</span>

<?php
require_once("_connection.php");

/* Connection a la base de données */
$sql = tx_connect();
$login = $_GET["login"];
$sessionid =$_GET["sessionid"];
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
	if(isset($_GET["branche"]))
	{	$branche = $_GET["branche"]; 
	}
	for($i=1;$i<9;$i++)
		$part[$i] = $_GET["part$i"];

	$photos = $_GET["photos"];	
	$lienmedia = $_GET["lienmedia"];		
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
	$observations = addslashes(($_GET["observations"]));
	$uvpr =$_GET["uvpr"];	
	$divmatos = addslashes(($_GET["divmatos"]));

	//matériel
	$mat = "";		$listemob[0] = "tables";	$listemob[1] = "chaises";	
	$listemob[2] = "bancs";	$listemob[3] = "grilles";	$listemob[4] = "posters";
	
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



	//création de la ref de l'atelier si elle n'existe pas encore
	if(isset($_GET["ref_at"]))
	{
		$ref_at = $_GET["ref_at"];
		$req_ins = "UPDATE ateliers SET
			ref_at='$ref_at',
			porteur='$porteur' ,
			sujet='$sujet' ,
			type='$type_at' ,
			partenaire1='$part[1]' ,
			partenaire2='$part[2]' ,
			partenaire3='$part[3]' ,
			partenaire4='$part[4]' ,
			partenaire5='$part[5]' ,
			partenaire6='$part[6]' ,
			partenaire7='$part[7]' ,
			partenaire8='$part[8]' ,
			localisation='$localisation' ,
			locFinale='$locFinale',
			question1='$question1' ,
			reponse1='$reponse1' ,
			question2='$question2' ,
			reponse2='$reponse2' ,
			question3='$question3' ,
			reponse3='$reponse3' ,
			question4='$question4' ,
			reponse4='$reponse4' ,
			question5='$question5' ,
			reponse5='$reponse5' ,
			liste_participants='$participants' ,
			biographie='$biographie' ,
			mobilier='$mobilier' ,
			materiel='$mat' ,
			alimentation='$alimentation' ,
			observations='$observations' ,
			photos='$photos',
			lienmedia='$lienmedia',			
			coorg='$coorg',
			resume='$resume',
			titre='$titre',
			uvpr='$uvpr',
			divmatos='$divmatos'
			WHERE ref_at='$ref_at';";
	}
	else
	{
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
		`liste_participants` , `biographie` ,`mobilier` , `materiel` , `alimentation` , `observations` , `photos`, `lienmedia` ) 
		VALUES 
		('$ref_at','$porteur','$titre','$sujet','$type_at', '$coorg','$resume',
		'$part[1]','$part[2]','$part[3]','$part[4]','$part[5]','$part[6]','$part[7]','$part[8]',
		'$localisation','$locFinale','$divmatos','$uvpr',
		'$question1','$reponse1','$question2','$reponse2','$question3','$reponse3','$question4','$reponse4','$question5','$reponse5',
		'$participants','$biographie','$mobilier','$mat','$alimentation','$observations','$photos','$lienmedia');";
	}
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
	$lienmedia = $_GET["lienmedia"];
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
	$observations = addslashes(($_GET["observations"]));
	$uvpr =$_GET["uvpr"];	
	$divmatos = addslashes(($_GET["divmatos"]));

	//matériel
	$mat = "";		$listemob[0] = "tables";	$listemob[1] = "chaises";	
	$listemob[2] = "bancs";	$listemob[3] = "grilles";	$listemob[4] = "posters";
	
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


	//création de la ref de l'atelier si elle n'existe pas encore
	if(isset($_GET["ref_at"]))
	{
		$ref_at = $_GET["ref_at"];
		$req_ins = "UPDATE ateliers SET
			ref_at='$ref_at',
			porteur='$porteur' ,
			sujet='$sujet' ,
			type='$type_at' ,
			partenaire1='$part[1]' ,
			partenaire2='$part[2]' ,
			partenaire3='$part[3]' ,
			partenaire4='$part[4]' ,
			partenaire5='$part[5]' ,
			partenaire6='$part[6]' ,
			partenaire7='$part[7]' ,
			partenaire8='$part[8]' ,
			localisation='$localisation' ,
			locFinale='$locFinale',
			question1='$question1' ,
			reponse1='$reponse1' ,
			question2='$question2' ,
			reponse2='$reponse2' ,
			question3='$question3' ,
			reponse3='$reponse3' ,
			question4='$question4' ,
			reponse4='$reponse4' ,
			question5='$question5' ,
			reponse5='$reponse5' ,
			liste_participants='$participants' ,
			biographie='$biographie' ,
			mobilier='$mobilier' ,
			materiel='$mat' ,
			alimentation='$alimentation' ,
			observations='$observations' ,
			photos='$photos',
			lienmedia='$lienmedia',
			coorg='$coorg',
			resume='$resume',
			titre='$titre',
			uvpr='$uvpr',
			divmatos='$divmatos'
			WHERE ref_at='$ref_at';";
	}
	else
	{
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
		`liste_participants` , `biographie` ,`mobilier` , `materiel` , `alimentation` , `observations` , `photos`, `lienmedia` ) 
		VALUES 
		('$ref_at','$porteur','$titre','$sujet','$type_at', '$coorg','$resume',
		'$part[1]','$part[2]','$part[3]','$part[4]','$part[5]','$part[6]','$part[7]','$part[8]',
		'$localisation','$locFinale','$divmatos','$uvpr',
		'$question1','$reponse1','$question2','$reponse2','$question3','$reponse3','$question4','$reponse4','$question5','$reponse5',
		'$participants','$biographie','$mobilier','$mat','$alimentation','$observations','$photos','$lienmedia');";
	}
	
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



/* test si la variable $action envoyée lors de la validation du formulaire existe
Si c est le cas, c est qu un formulaire a été rempli et doit Ãªtre traité */
elseif(isset($_GET["action_modifier_atelier"]))
	{
		echo "<br><br><br>";
		/* récupération des valeurs  */
		
		//infos générales
		$ref_at = $_GET["ref_at"];
		$porteur = $_GET["porteur"];
		$coorg = $_GET["coorg"];
		$titre = addslashes(($_GET["titre"]));
		$sujet = addslashes(($_GET["sujet"]));
		$resume = addslashes(($_GET["resume"]));
		
		//partenaires
		for($i=1;$i<=8;$i++)
			$part[$i] = $_GET["part$i"];
		
		//localisations
		$localisation = addslashes(($_GET["localisation"]));
		$locFinale = addslashes(($_GET["locFinale"]));
		
		//type d'atelier
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
		
		//matériel
		$mat = "";
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
		$mobilier = $_GET["mobilier"];
		
		$participants = $_GET["participants"];
		$biographie = $_GET["biographie"];
		$observations = addslashes(($_GET["observations"]));
		$divmatos = addslashes(($_GET["divmatos"]));
		$uvpr = $_GET["uvpr"];
		$observations = addslashes(($_GET["observations"]));
		$photos = $_GET["photos"];
		$lienmedia = $_GET["lienmedia"];
	
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
		
	
	
		/* Construction des requÃªtes d insertion */
		$req_upd = "UPDATE ateliers SET
			ref_at='$ref_at',
			porteur='$porteur' ,
			sujet='$sujet' ,
			type='$type_at' ,
			partenaire1='$part[1]' ,
			partenaire2='$part[2]' ,
			partenaire3='$part[3]' ,
			partenaire4='$part[4]' ,
			partenaire5='$part[5]' ,
			partenaire6='$part[6]' ,
			partenaire7='$part[7]' ,
			partenaire8='$part[8]' ,
			localisation='$localisation' ,
			locFinale='$locFinale',
			question1='$question1' ,
			reponse1='$reponse1' ,
			question2='$question2' ,
			reponse2='$reponse2' ,
			question3='$question3' ,
			reponse3='$reponse3' ,
			question4='$question4' ,
			reponse4='$reponse4' ,
			question5='$question5' ,
			reponse5='$reponse5' ,
			liste_participants='$participants' ,
			biographie='$biographie' ,
			mobilier='$mobilier' ,
			materiel='$mat' ,
			alimentation='$alimentation' ,
			observations='$observations' ,
			photos='$photos',
			lienmedia='$lienmedia',
			coorg='$coorg',
			resume='$resume',
			titre='$titre',
			uvpr='$uvpr',
			divmatos='$divmatos'
			WHERE ref_at='$ref_at';";
	
	
		/* Execution de la requete */
		tx_query($req_upd);
		//mysql_close();
		//echo $req_upd ;
	
	
		/* lien retour */
		echo "<br><br>";
		echo "<form method=GET action=\"modifier_atelier.php\">";
		echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
		echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
		echo "<input type=\"submit\" value=\"Retour a la selection de l atelier a modifier\"></form>";
	
		/* lien retour */
		echo "<form method=GET action=\"menu_ateliers.php\">";
		echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
		echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
		echo "<input type=\"submit\" value=\"Retour vers le menu de gestion des ateliers\"></form>";
		} // fin if (isset($_GET["action_modifier_atelier"]))

// affichage du formulaire rempli de l atelier a modifier
elseif (isset($_GET["choixok"]))
{
	//on va inserer le porteur / partenaire dans la base de donnees a cet endroit du script
	//en provenance de ajouter_atelier pour le moment
	//EXPERIMENTAL
	if(isset($_GET["valider_ajouter_porteur"]))
		{
		// récupération des paramètres
		$nom_aut = $_GET["nom_aut"];		$prenom_aut = $_GET["prenom_aut"];
		$tel_aut = $_GET["tel_aut"];			$mail_aut = $_GET["mail"];
		$immat_voit = $_GET["immat_voit"];	$puiss_voit = $_GET["puiss_voit"];
		$branche=$_GET["branche"];
	
		//num_acteur
		$sql_max_num ="SELECT MAX(num_acteur) FROM acteurs WHERE branche = '$branche'";
		$res_sql_max_num = tx_query($sql_max_num);
		$res = mysql_fetch_array($res_sql_max_num);
		$num_acteur=$res[0]+1;
	
		//ref_acteur
		$ref_acteur = "A-".$branche.$num_acteur;
		
		//on fabrique la requête d'insertion
		$reqaut = "INSERT INTO acteurs (`nom`,`prenom`,`tel`,`mail`,`immat_voit`,`puissance_voit`,`branche`,`ref_acteur`,`num_acteur`)
				VALUES ('$nom_aut','$prenom_aut','$tel_aut','$mail_aut','$immat_voit','$puiss_voit','$branche','$ref_acteur','$num_acteur')	;";
		//echo $reqaut;
		tx_query($reqaut);
		echo "<br>L'acteur $ref_acteur : $prenom_aut $nom_aut a été correctement ins&eacute;r&eacute; dans la base de donn&eacute;es<br>";
		
		}
		unset($_GET["ajouter_porteur"]);

		if(isset($_GET["valider_ajouter_partenaire"]))
		{
		//récupération des paramêtres
		$nom= $_GET["nom"];
		$logo= $_GET["logo"];
		$coordonnees = $_GET["coo"];
		$commentaire = $_GET["commentaires"];
	
		$reqaut = "INSERT INTO `partenaires` ( `nom` , `logo` , `coordonnees` , `commentaires` )
			VALUES ('$nom','$logo','$coordonnees','$commentaire')	;";
		tx_query($reqaut);
	
		echo "Le partenaire $nom a ete correctement ins&eacute;r&eacute; dans la base de donn&eacute;es";
	
		}
		unset($_GET["ajouter_partenaire"]);
	//END OF EXPERIMENTAL

	
	// dans tous les cas, on affiche le formulaire prérempli de l'atelier que l'on souhaite modifier
	echo "<br><br>";
	// reference de l atelier a modifier
	$ref_at = $_GET["ref_at"];

	$query  = "SELECT * FROM ateliers WHERE ref_at = '$ref_at'";
	$res_at = tx_query($query);
	$val_at = mysql_fetch_array($res_at,MYSQL_ASSOC);

	echo "<form method=GET action=\"modifier_atelier.php\">";
	echo "<table width=100% border=2 bordercolor=black class=texte>";
	echo "<tr align=left><td>Porteur de projet</td><td>";
	
	//porteur de projet
	$req_listp = "SELECT prenom,nom,ref,ref_acteur FROM acteurs ORDER BY nom";
	$idqp = tx_query($req_listp);
	echo "<select name=\"porteur\">";
	echo "<option value=\"\"></option>";
	while ($port = mysql_fetch_array($idqp) )
	{
		$ref_port = $port[2];
		$nom_port = $port[0] . " " . $port[1]; 
		if ((strcmp($val_at["porteur"],$ref_port)==0))
			echo "<option value=\"$ref_port\" selected>$nom_port</option>";
		else	echo "<option value=\"$ref_port\">$nom_port</option>";
	}
	echo "</select><br>";
	
	//affichage du bouton d'ajout de porteur
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Ajouter un porteur\" name=\"ajouter_porteur\">";
	echo "</tr>";

	//coorganisateurs
	$coorg = $val_at["coorg"];
	echo "<tr align=left><td>Coorganisateurs</td><td><textarea rows=3 cols=50 name=\"coorg\">$coorg</textarea></td></tr>";
	
	//titre
	$titre = stripslashes($val_at["titre"]);
		
	echo "<tr align=left><td>Titre</td><td><textarea rows=1 cols=50 name=\"titre\">$titre</textarea></td></tr>";
	
	//sujet
	$sujet = stripslashes($val_at["sujet"]);
	echo "<tr align=left><td>Sujet</td><td><textarea rows=3 cols=50 name=\"sujet\">$sujet</textarea></td></tr>";
	
	//resume
	$resume = stripslashes($val_at["resume"]);
	echo "<tr align=left><td>Résumé</td><td><textarea rows=3 cols=50 name=\"resume\">$resume</textarea></td></tr>";
	
	//type(s) d'atelier
	$type = $val_at["type"];
	echo "<tr align=left><td>Type</td><td>";
		if ((strchr($type, 'Exposition')!= false))
			echo "<input type=\"checkbox\" name=\"type0\" value=\"Exposition\" checked>Exposition";
		else	 echo "<input type=\"checkbox\" name=\"type0\" value=\"Exposition\">Exposition";
		if ((strchr($type, 'Demonstration') !=false))
			echo "<input type=\"checkbox\" name=\"type1\" value=\"Demonstration\" checked>Demonstration";
		else	 echo "<input type=\"checkbox\" name=\"type1\" value=\"Demonstration\">Demonstration";
		if ((strchr($type, 'Atelier')!=false))
			echo "<input type=\"checkbox\" name=\"type2\" value=\"Atelier\" checked>Atelier";
		else	 echo "<input type=\"checkbox\" name=\"type2\" value=\"Atelier\">Atelier";
		if ((strchr($type, 'Conference') !=false))
			echo "<input type=\"checkbox\" name=\"type3\" value=\"Conference\" checked>Conference";
		else	 echo "<input type=\"checkbox\" name=\"type3\" value=\"Conference\">Conference";


	//Partenaires
	echo "<tr><td>Partenaires</td><td>";
	for ($i=1;$i<=8;$i++)	
	{
		$req_list = "select nom from partenaires order by nom";
		$idq = tx_query($req_list);
		echo "<select name=\"part$i\">";
		echo "<option value=\"\"></option>";
		while ($partenaire = mysql_fetch_array($idq) )
		{
			$nom_p = $partenaire[0];
			$tmp = "partenaire".$i;
			$part = $val_at[$tmp];
			echo $part;
			if ((strcmp($part,$nom_p)==0))
				echo "<option value=\"$nom_p\" selected>$nom_p</option>";
			else	echo "<option value=\"$nom_p\">$nom_p</option>";
		}
		echo "</select><br>";
	}
	
	//affichage du bouton d ajout de partenaires
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Ajouter un partenaire\" name=\"ajouter_partenaire\">";
	echo "</td></tr>";
	
	//localisations
	$loc = stripslashes($val_at["localisation"]);
	$locF = stripslashes($val_at["locfinale"]);
	echo "<tr align=left><td>Localisation souhaitée</td><td><textarea rows=2 cols=80 name=\"localisation\">$loc</textarea></td></tr>";
	echo "<tr align=left><td>Localisation finale</td><td><textarea rows=2 cols=80 name=\"locFinale\">$locF</textarea></td></tr>";
	
	//participants
	$participants = $val_at["liste_participants"];
	echo "<tr align=left><td>Participants</td><td><textarea rows=4 cols=80 name=\"participants\">$participants</textarea></td></tr>";
	
	
	//biographie des membres du stand
	$biographie = $val_at["biographie"];
	echo "<tr align=left><td>Biographie des membres du stand</td><td><textarea rows=4 cols=80 name=\"biographie\">$biographie</textarea></td></tr>";
	
	
	//mobilier
	$mobilier = $val_at["mobilier"];
	echo "<tr align=left><td>Mobilier</td><td>";
	echo "<textarea rows=2 cols=80 name=\"mobilier\">$mobilier</textarea></td></tr>";
	
	
	//alimentation
	$alimentation = $val_at["alimentation"];
	echo "<tr align=left><td>Alimentations</td><td>";
		if((strchr($alimentation,'eau') != false))	
			echo "<input type=\"checkbox\" name=\"ali0\" value=\"eau\" checked>eau";
		else echo "<input type=\"checkbox\" name=\"ali0\" value=\"eau\">eau";
		if((strchr($alimentation,'gaz') != false))	
			echo "<input type=\"checkbox\" name=\"ali1\" value=\"gaz\" checked>gaz";
		else echo "<input type=\"checkbox\" name=\"ali1\" value=\"gaz\">gaz";
		if((strchr($alimentation,'electricité') != false))
			echo "<input type=\"checkbox\" name=\"ali2\" value=\"electricité\" checked>electricité";
		else echo "<input type=\"checkbox\" name=\"ali2\" value=\"electricité\">electricité";
		if((strchr($alimentation,'internet') != false))
			echo "<input type=\"checkbox\" name=\"ali3\" value=\"internet\" checked>internet";
		else echo "<input type=\"checkbox\" name=\"ali3\" value=\"internet\">internet";
		
	
	//materiel
	$materiel = $val_at["materiel"];
	echo "<tr align=left><td>Matériel</td><td>";
		if((strchr($materiel,'retroprojecteur') != false))
			echo "<input type=\"checkbox\" name=\"mat0\" value=\"retroprojecteur\" checked>retroprojecteur";
		else echo "<input type=\"checkbox\" name=\"mat0\" value=\"retroprojecteur\">retroprojecteur";
		if((strchr($materiel,'videoprojecteur') != false))
			echo "<input type=\"checkbox\" name=\"mat1\" value=\"videoprojecteur\" checked>videoprojecteur";
		else echo "<input type=\"checkbox\" name=\"mat1\" value=\"videoprojecteur\">videoprojecteur";
		if((strchr($materiel,'tableau') != false))
			echo "<input type=\"checkbox\" name=\"mat2\" value=\"tableau\" checked>tableau";
		else echo "<input type=\"checkbox\" name=\"mat2\" value=\"tableau\">tableau";
		if((strchr($materiel,'ecran') != false))
			echo "<input type=\"checkbox\" name=\"mat3\" value=\"ecran\" checked>ecran";
		else echo "<input type=\"checkbox\" name=\"mat3\" value=\"ecran\">ecran";
		if((strchr($materiel,'TV') != false))
			echo "<input type=\"checkbox\" name=\"mat4\" value=\"TV\" checked>TV";
		else echo "<input type=\"checkbox\" name=\"mat4\" value=\"TV\">TV";
	
	//remarques sur le matériel
	$divmatos = stripslashes($val_at["divmatos"]);
	echo "<tr align=left><td>Remarques sur le matériel</td><td><textarea rows=3 cols=50 name=\"divmatos\">$divmatos</textarea></td></tr>";
	
	
	//divers: observations, Q/R, photos,...
	$uvpr = $val_at["uvpr"];
	echo "<tr align=left><td>UV PR</td><td><input type=\"text\" name=\"uvpr\" value=\"$uvpr\"></td></tr>";
	
	$obs = stripslashes($val_at["observations"]);
	echo "<tr align=left><td>Observations</td><td><textarea rows=3 cols=50 name=\"observations\">$obs</textarea></td></tr>";
	
	$photos = $val_at["photos"];
	echo "<tr align=left><td>Chemin des photos <br></td><td><textarea rows=3 cols=80 name=\"photos\">$photos</textarea></td></tr>";
	
	$lienmedia = $val_at["lienmedia"];
	echo "<tr align=left><td>Lien pour les médias <br></td><td><textarea rows=3 cols=80 name=\"lienmedia\">$lienmedia</textarea></td></tr>";
	
	//question1 => question5, reponse1=>reponse5
	for($i=1;$i <= 5;$i++)
	{
		$assocQ = "question".$i;
		$assocR = "reponse".$i;
		$vq = stripslashes($val_at[$assocQ]);
		$vr = stripslashes($val_at[$assocR]);
		echo "<tr align=left><td>Question $i</td><td><textarea rows=4 cols=80 name=\"question$i\">$vq</textarea></td></tr>";
		echo "<tr align=left><td>Réponse $i</td><td><textarea rows=4 cols=80 name=\"reponse$i\">$vr</textarea></td></tr>";
	}


	echo "</table>";
	echo "<input type=\"hidden\" value=\"$ref_at\" name=\"ref_at\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Modifier\" name=\"action_modifier_atelier\">";
	echo "</form>";


	/* liens retour */
	echo "<form method=GET action=\"modifier_atelier.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour a la selection de l atelier a modifier\"></form>";
	
	echo "<form method=GET action=\"menu_ateliers.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour au menu de gestion des ateliers\"></form>";
	
//	mysql_close();
}
else
{	
	/* Récupération du login de l administrateur */
	$login = $_GET["login"];
	if ($login != "superadmin")
	{
		$len = strlen($login);
		$branche = substr($login,5,$len-5);
		$sql_br = "WHERE ref_at LIKE 'V-$branche%'";
	}
	else
	{
		$sql_br = "";
	}

	echo "<br><br>Choisissez l'atelier à modifier<br><br>";
	$sql = "SELECT ref_at, titre FROM ateliers " . $sql_br  ." ORDER BY ref_at;";
	$res = tx_query($sql);
	echo "<form method=GET action=\"modifier_atelier.php\"><table>";
	$val = mysql_fetch_array($res);
	echo "<tr><td><input type=radio name=\"ref_at\" value=\"$val[0]\" checked></td><td>$val[0]: $val[1]</td></tr>";


	/* Boucle sur les ateliers */
	while($val = mysql_fetch_array($res)) {
		$titre = stripslashes($val[1]);
		echo "<tr><td><input type=radio name=\"ref_at\" value=\"$val[0]\"></td><td>$val[0]: $titre</td></tr>";
	}
	echo "</table><br><br>";

	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\" Valider\" name=\"choixok\">";
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
