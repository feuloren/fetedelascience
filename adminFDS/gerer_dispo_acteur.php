<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
	<title>Gérer les disponibilités d'un Acteur</title>
</head>
<body bgcolor=white class=texte>
<div align="center">
<?
require_once("_connection.php");
/* Connection a la base de données */
$sql = tx_connect();
$login = $_GET["login"];
$sessionid = $_GET["sessionid"];
if(verif($login,$sessionid))
{

//création du tableau de dates.
//on va s'en servir pour l'affichage

$jours[0]="Lundi 19 septembre ";
$jours[1]="Mardi 20 septembre ";
$jours[2]="Mercredi 21 septembre ";
$jours[3]="Jeudi 22 septembre ";
$jours[4]="Vendredi 23 septembre ";
$jours[5]="Lundi 26 septembre ";
$jours[6]="Mardi 27 septembre ";
$jours[7]="Mercredi 28 septembre ";
$jours[8]="Jeudi 29 septembre ";
$jours[9]="Vendredi 30 septembre ";
$jours[10]="Lundi 3 octobre ";
$jours[11]="Mardi 4 octobre ";
$jours[12]="Mercredi 5 octobre ";
$jours[13]="Jeudi 6 octobre ";
$jours[14]="Vendredi 7 octobre ";
$jours[15]="Lundi 10 octobre ";
$jours[16]="Mardi 11 octobre ";
$jours[17]="Mercredi 12 octobre ";


$creneaux[0] = "Matin";
$creneaux[1] = "Après-midi";

$date_depart="0-30-09-2010";




//test du login
if ($login != "superadmin")
{
	$len = strlen($login);
	$branche = substr($login,5,$len-5);
	$sqlbr = " WHERE branche = '$branche' AND num_acteur <> 0";
}
else
{
	$sqlbr = " WHERE num_acteur <> 0 ";
}





if(isset($_GET["action_modifier_dispo_acteur"]))
{
$ref_acteur = $_GET["ref_acteur"];

$test_exist = "SELECT * FROM dispo WHERE ref_acteur='$ref_acteur'";
$id_test_exist = tx_query($test_exist);
if(mysql_num_rows($id_test_exist) == 0)
{
	$beg_query_mod = "INSERT INTO ";
	$end_query_mod =" ";
}
else
{
		$beg_query_mod="UPDATE";
	    $end_query_mod = " WHERE `ref_acteur`='$ref_acteur';";
}

$query_mod = $beg_query_mod . " dispo SET ref_acteur = '$ref_acteur',";


for($i=0;$i<42;$i++)
{
	$cre[$i]=$_GET["cre$i"];
	$ref_eta[$i] = $_GET["ref_eta$i"];
	$ref_conf[$i] = $_GET["ref_conf$i"];
	if(($cre[$i] ==0) || ($cre[$i] ==1))
	{
		if($i < 9)
		{
			$j = $i+1;
			$req = "c0" . $j;
		}
		else
		{ 	
			$j = $i+1;
			$req= "c".$j;
		}
		
		$qtemp = $req . " = ". "'$cre[$i]'";
		if($i<=40)
		{
			$qtemp .= ", ";
		}
		$query_mod .=$qtemp;
	}
	else
	{	
		if($i < 9)
		{
			$j=$i+1;
			$req = "c0" . $j;
		}
		else
		{ 	
			$j = $i+1;
			$req= "c".$j;
		}
		if($cre[$i] == 2)
		{
			$res_t = "2 " . $ref_eta[$i] . " " .$ref_conf[$i];
		}
		else
		{ // on est occupe
			$test = "SELECT $req FROM dispo WHERE ref_acteur='$ref_acteur'";
			$id_test = tx_query($test);
			$test_oc = mysql_fetch_array($id_test);
			$valeur = substr($test_oc[0],0,1);

			// envoie un mail si on vient de passer de reserve a occupe
			if(strcmp($valeur,'2') == 0)
			{
				// recuperation des mails
				$test_mail = "SELECT mail, mail_ac, nom, adresse, nom_ac, prenom_ac, tel_ac, crehor FROM etablissements WHERE ref='$ref_eta[$i]'";
				$id_test_mail = tx_query($test_mail);
				$test_mail = mysql_fetch_array($id_test_mail);
				$dest = $test_mail[0].", ".$test_mail[1];
				$destbis = "fete-de-la-science@utc.fr";

				// Récupération des noms des différents acteurs
				$test_act = "SELECT refa1,refa2,refa3,titre FROM conferences WHERE ref_conf='$ref_conf[$i]'";
        			$res_dest = tx_query($test_act);
        			$data_dest = mysql_fetch_array($res_dest);

        			$req_mail2 = "SELECT prenom,nom,mail,branche FROM acteurs WHERE ref='$data_dest[0]';";
        			$res_mail2 = tx_query($req_mail2);
        			$mail2 = mysql_fetch_array($res_mail2);
  				$confe = $mail2[0]." ".$mail2[1];
				$destbis .= ", ".$mail2[2];

        			$req_mail5 = "SELECT mail FROM acteurs WHERE login='admin$mail2[3]'";
        			$res_mail5 = tx_query($req_mail5);
        			$mail5 = mysql_fetch_array($res_mail5);
        			$destbis .= ", ".$mail5[0].",";

			        if ($data_dest[1] != 0)        {
					$req_mail3 = "SELECT mail,prenom,nom,mail  FROM acteurs WHERE ref='$data_dest[1]'";
   					$res_mail3 = tx_query($req_mail3);
      					$mail3 = mysql_fetch_array($res_mail3);
 					$confe .= ", ".$mail3[0]." ".$mail3[1];
					$destbis .= ", ".$mail3[2];
    				}
        			if ($data_dest[2] != 0)        {
                			$req_mail4 = "SELECT mail,prenom,nom,mail  FROM acteurs WHERE ref='$data_dest[2]'";
                			$res_mail4 = tx_query($req_mail4);
                			$mail4 = mysql_fetch_array($res_mail4);
 					$confe .= ", ".$mail4[0]." ".$mail4[1];
					$destbis .= ", ".$mail4[2];
        			}
					
		if ($i == 0)        $creneau = "Jeudi 30 septembre 2010, matin";
         elseif ($i == 1)        $creneau = "Jeudi 30 septembre 2010, après-midi";
         elseif ($i == 2)        $creneau = "Vendredi 1 octobre 2010, matin";
         elseif ($i == 3)        $creneau = "Vendredi 1 octobre 2010, après-midi";
         elseif ($i == 4)        $creneau = "Lundi 4 octobre 2010, matin";
         elseif ($i == 5)        $creneau = "Lundi 4 octobre 2010, après-midi";
         elseif ($i == 6)        $creneau = "Mardi 5 octobre 2010, matin";
         elseif ($i == 7)        $creneau = "Mardi 5 octobre 2010, après-midi";
         elseif ($i == 8)        $creneau = "Mercredi 6 octobre 2010, matin";
         elseif ($$i == 9)        $creneau = "Mercredi 6 octobre 2010, après-midi";
         elseif ($i== 10)        $creneau = "Jeudi 7 octobre 2010, matin";
         elseif ($i == 11)        $creneau = "Jeudi 7 octobre 2010, après-midi";
         elseif ($i == 12)        $creneau = "Vendredi 8 octobre 2010, matin";
         elseif ($i == 13)        $creneau = "Vendredi 8 octobre 2010, après-midi";
         elseif ($i == 14)        $creneau = "Lundi 11 octobre 2010, matin";
         elseif ($i == 15)        $creneau = "Lundi 11 octobre 2010, après-midi";
         elseif ($i == 16)        $creneau = "Mardi 12 octobre 2010, matin";
         elseif ($i == 17)        $creneau = "Mardi 12 octobre 2010, après-midi";
         elseif ($i == 18)        $creneau = "Mercredi 13 octobre 2010, matin";
         elseif ($i == 19)        $creneau = "Mercredi 13 octobre 2010, après-midi";
         elseif ($i == 20)        $creneau = "Jeudi 14 octobre 2010, matin";
         elseif ($i == 21)        $creneau = "Jeudi 14 octobre 2010, après-midi";
         elseif ($i == 22)        $creneau = "Vendredi 15 octobre 2010, matin";
         elseif ($i == 23)        $creneau = "Vendredi 15 octobre 2010, après-midi";
         elseif ($i == 24)        $creneau = "Lundi 18 octobre 2010, matin";
         elseif ($i == 25)        $creneau = "Lundi 18 octobre 2010, après-midi";
         elseif ($i == 26)        $creneau = "Mardi 19 octobre 2010, matin";
         elseif ($i == 27)        $creneau = "Mardi 19 octobre 2010, après-midi";
		 /*
         if ($i == 0)        $creneau = "Jeudi 5 novembre 2009, matin";
         elseif ($i == 1)        $creneau = "Jeudi 5 novembre 2009, après-midi";
         elseif ($i == 2)        $creneau = "Vendredi 6 novembre 2009, matin";
         elseif ($i == 3)        $creneau = "Vendredi 6 novembre 2009, après-midi";
         elseif ($i == 4)        $creneau = "Lundi 9 novembre 2009, matin";
         elseif ($i == 5)        $creneau = "Lundi 9 novembre 2009, après-midi";
         elseif ($i == 6)        $creneau = "Mardi 10 novembre 2009, matin";
         elseif ($i == 7)        $creneau = "Mardi 10 novembre 2009, après-midi";
         elseif ($i == 8)        $creneau = "Jeudi 12 novembre 2009, matin";
         elseif ($i == 9)        $creneau = "Jeudi 12 novembre 2009, après-midi";
         elseif ($i == 10)        $creneau = "Vendredi 13 novembre 2009, matin";
         elseif ($i == 11)        $creneau = "Vendredi 13 novembre 2009, après-midi";
         elseif ($i == 12)        $creneau = "Lundi 16 novembre 2009, matin";
         elseif ($i == 13)        $creneau = "Lundi 16 novembre 2009, après-midi";
         elseif ($i == 14)        $creneau = "Mardi 17 novembre 2009, matin";
         elseif ($i == 15)        $creneau = "Mardi 17 novembre 2009, après-midi";
         elseif ($i == 16)        $creneau = "Mercredi 18 novembre 2009, matin";
         elseif ($i == 17)        $creneau = "Mercredi 18 novembre 2009, après-midi";
         elseif ($i == 18)        $creneau = "Jeudi 19 novembre 2009, matin";
         elseif ($i == 19)        $creneau = "Jeudi 19 novembre 2009, après-midi";
         elseif ($i == 20)        $creneau = "Vendredi 20 novembre 2009, matin";
         elseif ($i == 21)        $creneau = "Vendredi 20 novembre 2009, après-midi";
         elseif ($i == 22)        $creneau = "Lundi 23 novembre 2009, matin";
         elseif ($i == 23)        $creneau = "Lundi 23 novembre 2009, après-midi";
         elseif ($i == 24)        $creneau = "Mardi 24 novembre 2009, matin";
         elseif ($i == 25)        $creneau = "Mardi 24 novembre 2009, après-midi";
         elseif ($i == 26)        $creneau = "Mercredi 25 novembre 2009, matin";
         elseif ($i == 27)        $creneau = "Mercredi 25 novembre 2009, après-midi";
         elseif ($i == 28)        $creneau = "Jeudi 26 novembre 2009, matin";
         elseif ($i == 29)        $creneau = "Jeudi 26 novembre 2009, après-midi";
         elseif ($i == 30)        $creneau = "Vendredi 27 novembre 2009, matin";
         elseif ($i == 31)        $creneau = "Vendredi 27 novembre 2009, après-midi";
         elseif ($i == 32)        $creneau = "Lundi 30 novembre 2009, matin";
         elseif ($i == 33)        $creneau = "Lundi 30 novembre 2009, après-midi";
         elseif ($i == 34)        $creneau = "Mardi 1 décembre 2009, matin";
         elseif ($i == 35)        $creneau = "Mardi 1 décembre 2009, après-midi";
         elseif ($i == 36)        $creneau = "Mercredi 2 décembre 2009, matin";
         elseif ($i == 37)        $creneau = "Mercredi 2 décembre 2009, après-midi";
         elseif ($i == 38)        $creneau = "Jeudi 3 décembre 2009, matin";
         elseif ($i == 39)        $creneau = "Jeudi 3 décembre 2009, après-midi";
         elseif ($i == 40)        $creneau = "Vendredi 4 décembre 2009, matin";
         elseif ($i == 41)        $creneau = "Vendredi 4 décembre 2009, après-midi";*/
				$sujet = "Confirmation de conférence";
				$sujetbis = "Copie - Confirmation de conférence";
        			$message = "Bonjour,\n\n";
        			$message .= "Votre demande de la conférence \n";
				      $message .= "$data_dest[3]\n";
        			$message .= "delivrée par ";
        			$message .= "$confe\n";
				      $message .= "le $creneau (précision horaire : $test_mail[7])\n";
        			$message .= "a été validée.\n\n";
        			$message .= "Le conférencier pourra être amené à prendre contact ";
				$message .= "avec vous pour préparer son intervention.\n";
				$message .= "Si vous souhaitez le joindre, merci de nous contacter à l'aide des coordonnées ";
				$message .= "ci-dessous. \n\n";
        			$message .= "Nous vous remercions de votre intérêt pour la Fête de la Science.\n\n";
        			$message .= "L'équipe de la Fête de la Science\n\n";
				$message .= "Téléphone : 03 44 23 49 94 - Fax 03 44 23 52 19\n";
				$message .= "Courriel : fete-de-la-science@utc.fr\n";
				$message .= "http://www.utc.fr/fetedelascience\n\n\n";
				$message .= "-------------------------------------------------\n";
				$message .= "Rappel des coordonnées de l'établissement : \n";
				$message .= "$test_mail[2]\n";
				$message .= "$test_mail[3]\n\n";
				$message .= "Correspondant : $test_mail[5] $test_mail[4]\n";
				$message .= "Tél. : $test_mail[6]\n";

        			$headers = 'From:fete-de-la-science@utc.fr';

        			mail($dest,$sujet,$message,$headers);
        			mail($destbis,$sujetbis,$message,$headers);
 				
			
			}

			$res_t = $ref_conf[$i] . " " .$ref_eta[$i];
		}
		$qtemp =$req . " = " . "'$res_t'";
		if($i<20)
		{
			$qtemp .= ", ";
		}
		$query_mod .=$qtemp;
	}
}

$query_mod.=$end_query_mod;
//echo "<br> Requete: $query_mod";
tx_query($query_mod);

/* liens retour */
echo "<form method=GET action=\"gerer_dispo_acteur.php\">";
echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";

echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
echo "<input type=\"submit\" value=\"Retour à la selection de l'auteur à modifier\"></form>";

echo "<form method=GET action=\"menu_acteurs.php\">";
echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
echo "<input type=\"submit\" value=\"Retour au sommaire\"></form>";



}



elseif (isset($_GET["choixok"]))
{
	echo "<br><br>";

	// reference de l'acteur dont on veut modifier les créneaux de disponibilités
	$ref_acteur = $_GET["ref_acteur"];
	
	$sql_a = "SELECT ref_acteur, prenom, nom FROM acteurs WHERE ref_acteur=\"$ref_acteur\" ";
	$res_a = tx_query($sql_a);
	$val_a = mysql_fetch_array($res_a);
	
	$sql_d = "SELECT * FROM dispo WHERE ref_acteur=\"$ref_acteur\" ";
	$res_d = tx_query($sql_d);
	$val_d = mysql_fetch_array($res_d);
	
	echo "Modifications des affectations pour l'acteur $ref_acteur: $val_a[1] $val_a[2]";
	echo "<br><br>";
	echo "<form method=GET action=\"gerer_dispo_acteur.php\">";
	echo "<table width=\"80%\" class=\"texte\">";
	
	
	$t_date_depart = explode("-",$date_depart);
	$nom_jour_depart = $t_date_depart[0];
	$num_jour_depart = $t_date_depart[1];
	$mois_jour_depart = $t_date_depart[2];
	$annee_jour_depart = $t_date_depart[3];
	
	
	for($i = 0;$i < 42;$i++)
	{	
		if($i%2 == 0)
		{	
			$n = $i/20;
			$m = $i%2;
			$nom_jour = $jours[$n];
			$num_jour = $num_jour_depart + $n;			
			echo "<tr bgcolor=\"#336699\" > <td><font color=white>".$jours[$i/2]."</font></td></tr>";
//			echo "<tr bgcolor=\"#336699\" > <td><font color=white>$nom_jour $num_jour $mois $annee_jour_depart</font></td></tr>";
		}
		$m=$i%2;
		echo "<tr bgcolor=\"#FFFF80\"><td width=\"30%\" bgcolor=\"#FFFF80\"> $creneaux[$m] </td>";
		
		$n_cren = $i +1;

		$v_cren = $val_d[$n_cren];
		$statut = substr($v_cren,0,1);
		echo "<td>";
			//echo $v_cren;
			if(strcmp($v_cren,'0') == 0 ||  (strcmp($statut,'C') != 0 && strcmp($v_cren,'1') != 0 && strcmp($statut,'2') != 0))
			{
				echo "<input type=\"radio\" name=cre$i value=0 checked> Acteur indisponible <br>";
			}
			else
			{
				echo "<input type=\"radio\" name=cre$i value=0> Acteur indisponible <br>";
			}
			
			if(strcmp($v_cren,'1') == 0)
			{
				echo "<input type=\"radio\" name=cre$i value=1 checked> Acteur libre <br>";
			}
			else
			{
				echo "<input type=\"radio\" name=cre$i value=1> Acteur libre <br>";
			}
			
			if(strcmp($statut ,'2') == 0)
			{
				echo "<input type=\"radio\" name=cre$i value=2 checked> Acteur réserve <br>";
			}
			else
			{
				echo "<input type=\"radio\" name=cre$i value=2> Acteur réserve <br>";
			}
			
			if(strcmp($statut,'C') == 0)
			{	
				echo "<input type=\"radio\" name=cre$i value=3 checked> Acteur occupé <br>";
			}
			else
			{
				echo "<input type=\"radio\" name=cre$i value=3> Acteur occupé <br>";
			}
			$sql_eta = "SELECT ref,nom,nom_ac,crehor FROM etablissements";
			$sql_conf = "SELECT ref_conf, titre FROM conferences";
			$res_eta = tx_query($sql_eta);
			$res_conf = tx_query($sql_conf);
			
			if(strcmp(substr($v_cren,0,1) ,'2') == 0)
			{
			$occ =explode(' ',$v_cren);
			$ref_conf = $occ[2];
			$ref_eta = $occ[1];
			}
			else
			{
			$occ =explode(' ',$v_cren);
			$ref_conf = $occ[0];
			$ref_eta = $occ[1];
			}
		
			//echo "les ref: $ref_conf, $ref_eta";
		
			echo "<select name=\"ref_conf$i\">";
			echo "<option value=\"\"></option>";
			while($tab_conf = mysql_fetch_array($res_conf))
			{
				if(strcmp($tab_conf[0],$ref_conf) == 0)
				{
					echo "<option value=\"$tab_conf[0]\" selected>$tab_conf[0]: $tab_conf[1]</option>";
				}
				else
				{
					echo "<option value=\"$tab_conf[0]\" >$tab_conf[0]: $tab_conf[1]</option>";
				}
			}
			echo "</select>";
			
			
			echo "<select name=\"ref_eta$i\">";
			echo "<option value=\"\"></option>";
			while($tab_eta = mysql_fetch_array($res_eta))
			{
				if (strcmp($tab_eta[0], $ref_eta) ==0)
				{ 
					echo "<option value=\"$tab_eta[0]\" selected >$tab_eta[0]: $tab_eta[1] ($tab_eta[2])</option>";
				}
				else
				{
					echo "<option value=\"$tab_eta[0]\" >$tab_eta[0]: $tab_eta[1] ($tab_eta[2])</option>";
				}
			}
			echo "</select>";
			echo "</td>";
		
		
	}	
	
	
	
	
	echo "</table>";

	
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$ref_acteur\" name=\"ref_acteur\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Modifier\" name=\"action_modifier_dispo_acteur\">";
	echo "</form>";

	/* liens retour */
	echo "<form method=GET action=\"gerer_dispo_acteur.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour a la selection de l'auteur a modifier\"></form>";

	echo "<form method=GET action=\"menu_acteurs.php\">";
	echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
	echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
	echo "<input type=\"submit\" value=\"Retour au sommaire\"></form>";


}





else
{
	echo "Choisissez l'auteur dont vous souhaitez gerer les disponibilites<br><br>";
	

	$query_d = "SELECT ref_acteur, prenom, nom FROM acteurs " . $sqlbr . " ORDER BY ref_acteur;";
	$id_query_d = tx_query($query_d);
	echo "<form method=GET action=\"gerer_dispo_acteur.php\"><table>";
	/* Boucle sur les auteurs */
	while($val_d = mysql_fetch_array($id_query_d))
	{			
		
		echo "<tr><td><input type=radio name=\"ref_acteur\" value=\"$val_d[0]\"></td><td>$val_d[0]: $val_d[2] $val_d[1]</td></tr>";
	}
	
	
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
