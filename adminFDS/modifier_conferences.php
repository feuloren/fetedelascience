<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
<title>Modifier une Conférence</title>
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
<span class="titre">Modifier une Conférence</span>

<?php
require_once("_connection.php");
/* Connection a la base de données */
tx_connect();
$login = $_GET["login"];
$sessionid = $_GET["sessionid"];
if(verif($login,$sessionid))
{
if ($login != "superadmin")
		{
			$len = strlen($login);
			$branche = substr($login,5,$len-5);
		}

//formulaire pour ajouter un acteur

if(isset($_GET["ajouter_acteur"])) 
{
	//Formulaire insérant dans la bdd les données déja rentrées
	//on récupère le login puis la branche de la conférence
	$login = $_GET["login"];
	if ($login == "superadmin"){$branche = $_GET["branche"];$login2 = "admin" . $branche; }
	else{$len = strlen($login);	$branche = substr($login,5,$len-5);$login2=$login;}
	//puis la référence du correspondant
	$res = tx_query("SELECT ref FROM acteurs where login = '$login2'");
	$ref_array = mysql_fetch_array($res);
	$ref_corres = $ref_array[0];
	//on récupère le titre de la conférence et les références des auteurs.
	$titre = addslashes(($_GET["titre"]));
	$aut1 = $_GET["aut1"];
	$aut2 = $_GET["aut2"];
	$aut3 = $_GET["aut3"];
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
	
	
	
	if(isset($_GET["ref_conf"]))
	{
		$ref_conf = $_GET["ref_conf"];
		$reqconf = "UPDATE conferences SET
					titre='$titre',
					public='$public',
					materiel='$mat',
					comm_mat='$comm_mat',
					resume='$resume',
					comm_public='$comm_pub',
					refa1='$aut1',
					refa2='$aut2',
					refa3='$aut3',
					comm_public='$comm_pub'
					WHERE ref_conf='$ref_conf';";
	}
	else
	{
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
	$reqconf = "INSERT INTO conferences
					(`titre`,`public`,`materiel`,`comm_mat`,`resume`,`comm_public`,`ref_corres`,
							`refa1`,`refa2`,`refa3`,`num_conf`,`ref_conf`,`branche`)						
			VALUES (
				'$titre','$public','$mat','$comm_mat','$resume','$comm_pub','$ref_corres',
				'$ref_aut1','$ref_aut2','$ref_aut3','$num','$ref_conf ','$branche')";		
	}
	
	//echo "<br><br>Requete conference: $reqconf<br>";
	/* Execution de la requete */
	tx_query($reqconf);

	/* lien retour */
	echo "<br><br>";
	echo "<form method=GET action=\"modifier_conferences.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$ref_conf\" name=\"ref_conf\">";
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

//else
//{


/* test si la variable $action envoyée lors de la validation du formulaire existe
Si c est le cas, c est qu un formulaire a été rempli et doit être traité */

elseif(isset($_GET["action"]))
{
	echo "<br><br><br>";
	/* récupération des valeurs à insérer */
	$ref_conf = $_GET["ref_conf"];				
	$titre = $_GET["titre"];
	$aut1=$_GET["aut1"];
	$aut2=$_GET["aut2"];				
	$aut3=$_GET["aut3"];
	
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
	$comm_pub = $_GET["comm_pub"];
	$resume = $_GET["resume"];
	//matériel
	$mat = "";
	for ($i=0; $i < 5; $i++)	{
		if(isset($_GET["mat$i"]))	{
			$mattemp = $_GET["mat$i"];
			if ($mat != "")	$mat = $mat . ", ";
			$mat = $mat . $mattemp;
		}
	}
	$comm_mat = $_GET["comm_mat"];
	
	
	
	
	
	/* Construction des requêtes de mise a jour */
	$reqconf = "UPDATE conferences SET 
		titre='$titre',
		public='$public',
		materiel='$mat',
		comm_mat='$comm_mat',
		resume='$resume',
		comm_public='$comm_pub',
		refa1='$aut1',
		refa2='$aut2',
		refa3='$aut3' 
		WHERE ref_conf='$ref_conf';";

	/* Execution de la requete */
	tx_query($reqconf);

	/* liens retour */
	echo "<form method=GET action=\"modifier_conferences.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour à la sélection de la conference à modifier\"></form>";
	
	echo "<form method=GET action=\"menu_conferences.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour au sommaire\"></form>";
} // fin if (isset($_GET["action"]))


// affichage du formulaire rempli de la conference a modifier
elseif (isset($_GET["choixok"]))
{
	// si un auteur doit être ajouté
	if(isset($_GET["valider_ajouter_acteur"]))
	{
	// récupération des paramètres
	$nom_aut = $_GET["nom_aut"];		$prenom_aut = $_GET["prenom_aut"];
	$tel_aut = $_GET["tel_aut"];		$mail_aut = $_GET["mail"];
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
	tx_query($reqaut);
	echo "L'acteur $ref_acteur : $prenom_aut $nom_aut a été correctement insérée dans la base de données";
	}
	unset($_GET["ajouter_acteur"]);
	
	
	// reference de la conférence a modifier
	$ref_conf = $_GET["ref_conf"];
	
	// création automatique de la référence de la conférence
	$res = tx_query("select titre, public, materiel, comm_mat, resume, comm_public, ref_corres, refa1, refa2, refa3, ref, ref_conf, branche from conferences where ref_conf = '$ref_conf';");
	$val = mysql_fetch_array($res);
	$res_aut = mysql_query("select nom, prenom, tel, mail, immat_voit, puissance_voit,ref_acteur from acteurs where ref='$val[7]';");
	$val_aut = mysql_fetch_array($res_aut);
	$res_corres = mysql_query("select nom, prenom, ref_acteur from acteurs where ref='$val[6]';");
	$val_corres = mysql_fetch_array($res_corres);

	// début du formulaire
	echo "<br><br>";
	echo "<form method=GET action=\"modifier_conferences.php\">";
	echo "<table width=\"100%\" border=2 bordercolor=black class=texte>";
	echo "<tr align=left><td>Référence</td><td><input type=\"text\" size=8 name=\"ref_conf\" value=\"$val[11]\" disabled></td></tr>";

	//affichage des choix des trois auteurs	
	echo  "<tr align=left><td>Auteur(s)</td><td>";
	$req_list = "select ref,nom,prenom from acteurs WHERE num_acteur<>0 order by nom";
	$idq = tx_query($req_list);	
	if (($nbr = mysql_num_rows($idq))== 0);
	for($i=1;$i<=3;$i++)
	{
		$sname = 'aut'.$i;
		echo "<select name=\"$sname\">";
		echo mysql_num_rows($idq);
		echo "<option value=\"\"> </option>";
		while ($aut = mysql_fetch_array($idq) )
		{
			$prenom=$aut[2];
			$nom=$aut[1];
			$ref=$aut[0];
			$nomcomplet = $prenom . " " . $nom;
			if($ref==$val{$i+6}) //val[7]=ref_aut1 ...
			{
				echo "<option value=\"$ref\" selected> $nomcomplet </option>";
			}
			else
			{
				echo "<option value=\"$ref\"> $nomcomplet </option>";
			}
		}
		echo "</select><br>";
		$idq = tx_query($req_list);
	}		
	
	/*
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
	*/
	
	
	
	
	//affichage du bouton d'ajout d'auteurs
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	//echo "<input type=\"hidden\" value=\"ajout\" name=\"ajouter_acteur\">";
	echo "<input type=\"submit\" value=\"Ajouter acteur\" name=\"ajouter_acteur\">";

	$titre = stripslashes($val[0]);
	echo "<tr align=left><td>Titre</td><td><textarea rows=3 cols=50 name=\"titre\">$titre</textarea></td></tr>";
	
	//old: echo "<tr align=left><td>Public</td><td><input type=\"text\" size=30 name=\"public\" value=\"$val[1]\"></td></tr>";
	echo "<tr align=left><td>Public</td><td>";
	$public=$val[1];
	if((strchr($public,'primaire') != false))
		echo "<input type=\"checkbox\" name=\"public0\" value=\"primaire\" checked> Primaire";
	else echo "<input type=\"checkbox\" name=\"public0\" value=\"primaire\" > Primaire";
	if((strchr($public,'college') != false))
		echo "<input type=\"checkbox\" name=\"public1\" value=\"college\" checked> Collège";
	else echo "<input type=\"checkbox\" name=\"public1\" value=\"college\" > Collège";
	if((strchr($public,'lycee') != false))
		echo "<input type=\"checkbox\" name=\"public2\" value=\"lycee\" checked> Lycée";
	else echo "<input type=\"checkbox\" name=\"public2\" value=\"lycee\" > Lycée";
	
	$comm_pub=stripslashes($val[5]);
	echo "<tr align=left><td>Commentaires</td><td><textarea rows=3 cols=50 name=\"comm_pub\">$comm_pub</textarea></td></tr>";
	$resume=stripslashes($val[4]);
	echo "<tr align=left><td>Résumé</td><td><textarea rows=3 cols=50 name=\"resume\" >$resume</textarea></td></tr>";
	//echo "<tr align=left><td>Nom du correspondant</td><td><input type=\"text\" size=30 name=\"nom\" disabled value=$val_corres[0]></td></tr>";
	//echo "<tr align=left><td>Prénom du correspondant</td><td><input type=\"text\" size=30 name=\"prenom\" disabled value=$val_corres[1]></td></tr>";
	
	//old: echo "<tr align=left><td>Matériel</td><td><textarea rows=3 cols=50 name=\"mat\">$val[2]</textarea></td></tr>";
	echo "<tr align=left><td>Matériel</td><td>";
	$materiel=$val[2];
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
	
	
	
	$comm_mat=stripslashes($val[3]);
	echo "<tr align=left><td>Commentaires</td><td><textarea rows=3 cols=50 name=\"comm_mat\">$comm_mat</textarea></td></tr>";
	echo "</table>";
		
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"hidden\" value=\"$val[11]\" name=\"ref_conf\">";
	echo "<input type=\"submit\" value=\"Modifier\" name=\"action\">";
	
	echo "</form>";
	
	/* liens retour */
	echo "<form method=GET action=\"modifier_conferences.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour à la sélection de la conference à modifier\"></form>";
	
	echo "<form method=GET action=\"menu_conferences.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour au sommaire\"></form>";
	
	mysql_close();
	}


// ###### affichage et selection des conférences 
//sess ok; logins ok
else
{
	/* Récupération du statut de l administrateur */
	unset($_GET["ajouter_acteur"]);

	if($login == 'superadmin')
	{
		$sqlbr = "1";
	}
	else 
	{
		$sqlbr = "c.branche = '$branche'";
	}
	
	//on va ensuite chercher toutes les conférences dont le correspondant est de la bonne branche
	$sql_conf = "SELECT c.ref, c.titre, c.ref_conf 
				FROM conferences AS c
				WHERE " . $sqlbr . " ORDER BY c.ref_conf";
				


	echo "<br><br>Choisissez la conférence a modifier<br><br>";
	$res = tx_query($sql_conf);

	echo "<form method=GET action=\"modifier_conferences.php\"><table>";
	$val = mysql_fetch_array($res);
	$titre_conf=stripSlashes($val[1]);
	echo "<tr><td><input type=radio name=\"ref_conf\" value=\"$val[2]\" checked></td><td>$val[2] : $titre_conf</td></tr>";


	/* Boucle sur les conferences */
	while($val = mysql_fetch_array($res))
	{
		$titre_conf=stripSlashes($val[1]);
		echo "<tr><td><input type=radio name=\"ref_conf\" value=\"$val[2]\"></td><td>$val[2] : $titre_conf</td></tr>";
	}
	echo "</table>";

	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Valider\" name=\"choixok\">";
	echo "</form>";

	/* lien retour */
	echo "<form method=GET action=\"menu_conferences.php\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"submit\" value=\"Retour\"></form>";
}

}
?>

		</div>

	</body>
</html>
