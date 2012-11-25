<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />


<html>

<head>
<title>Demande de conférence</title>
<link href="fds2004.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="texte">
  <tr>
    <td colspan="3"><table width="100%"  border="0" cellpadding="0" cellspacing="0" id="haut">
        <tr>
          <td width="10" height="11" valign="bottom"><img src="../images/coin_haut_gauche.gif" width="10" height="11"></td>
          <td background="../images/ligne_haut.gif" class="lignetitre2">
		  <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="9" height="20"><img src="../images/titre_gauche.gif" width="9" height="20"></td>
              <td width="408" class="titrepage">Faire une demande de conf&eacute;rence
                dans votre &eacute;tablissement</td>
              <td width="10" height="20"><img src="../images/titre_droit.gif" width="9" height="20"></td>
              </tr>
          </table></td>
          <td width="10" valign="bottom"><img src="../images/coin_haut_droite.gif" width="10" height="11"></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td width="1" bgcolor="#192041"><img src="images/1x1.gif" width="1" height="1"></td>
    <td valign="top"  class="textencart"><div class="textencart">
        <p class="texterouge"><br>
          <?
if(isset($_GET["soumettre"]))        
        {


        require_once("_connection.php");
        tx_connect();

        $eta = $_GET["etablissement"];
        $adr = $_GET["adr"];
        $tel = $_GET["tel"];
        $fax = $_GET["fax"];
        $mail = $_GET["mail"];
        $classes = $_GET["classes"];
        $nbeleves = $_GET["nbeleves"];
        $nom_resp = $_GET["nom_resp"];
        $prenom_resp = $_GET["prenom_resp"];
        $tel_resp = $_GET["tel_resp"];
        $mail_resp = $_GET["mail_resp"];

        $titre = $_GET["titre"];
        $ref_conf = $_GET["ref_conf"];
        $nocreneau = $_GET["nocreneau"];
        $creneau = $_GET["creneau"];


        if ($eta=='' || $adr == '' || $tel=='' || $mail=='' || $classes=='' || $nbeleves=='' ||
                $nom_resp=='' || $tel_resp=='')
                die ("Formulaire incomplet : tous les renseignements marqués d'une * sont obligatoires.");

 	// ajout de l etablissement dans la base de donnees
                // formation de la reference de l etablissement
                $req = tx_query("SELECT * FROM etablissements");
                $nbeta = mysql_num_rows($req)+1;
                if ($nbeta < 10)  $ref = "ETA-00".$nbeta ;
                elseif ($nbeta < 100) $ref = "ETA-0".$nbeta;
                else      $ref = "ETA-".$nbeta;

                // insertion dans la base de donnees
                $ins = "INSERT INTO `etablissements` (`ref`,`nom`,`tel`,`fax`,`mail`,`adresse`,`nom_ac`,`prenom_ac`,`tel_ac`,`mail_ac`,`nb_clas`,`nb_elev`)
                                VALUES ( '$ref', '$eta', '$tel', '$fax', '$mail', '$adr', '$nom_resp', '$prenom_resp', '$tel_resp', '$mail_resp', '$classes', '$nbeleves')";
                tx_query($ins);

	// mise a jour de la table des disponibilit&eacute;s
		$req = tx_query("SELECT titre,refa1,refa2,refa3 FROM conferences WHERE ref_conf='$ref_conf'");
		$data = mysql_fetch_array($req);
		$titre = $data[0];
		$j = 1; $bool = true;

		while($bool && $j<4)	{   // boucle sur les acteurs

			if ($data[$j] == 0)	
				$bool = false ;
			else
			{
			// r&eacute;cuperation de la reference de l acteur
				$reqa = "SELECT ref_acteur FROM acteurs WHERE ref='$data[$j]'";
				$resa = tx_query($reqa);
				$dataa = mysql_fetch_array($resa);

				if ($nocreneau < 10)	$c = "c0" . $nocreneau;
				else	$c = "c" . $nocreneau ;
				$maj = "UPDATE `dispo` SET $c='2 $ref $ref_conf' where ref_acteur='$dataa[0]';";
				//echo $maj;
				tx_query($maj);
			}// fin else
			$j++;
		} // fin while


        // Cr&eacute;ation du mail &agrave; envoyer aux destinataires
        $destbis = "fete-de-la-science@utc.fr,";
        $destclient = $mail_resp.",".$mail;

        // R&eacute;cup&eacute;ration du mail des diff&eacute;rents acteurs
        $res_dest = tx_query("SELECT refa1,refa2,refa3,branche FROM conferences WHERE ref_conf='$ref_conf'");
        $data_dest = mysql_fetch_array($res_dest);

        $req_mail1 = "SELECT mail FROM acteurs WHERE login='admin$data_dest[3]'";
        $res_mail1 = tx_query($req_mail1);
        $mail1 = mysql_fetch_array($res_mail1);
        $destbis .= $mail1[0].",";

        $req_mail2 = "SELECT mail,prenom,nom FROM acteurs WHERE ref='$data_dest[0]'";
        $res_mail2 = tx_query($req_mail2);
        $mail2 = mysql_fetch_array($res_mail2);
        $dest .= $mail2[0];
	$confe = $mail2[1]." ".$mail2[2];

        if ($data_dest[1] != 0)        {
                $req_mail3 = "SELECT mail,prenom,nom  FROM acteurs WHERE ref='$data_dest[1]'";
                $res_mail3 = tx_query($req_mail3);
                $mail3 = mysql_fetch_array($res_mail3);
                $dest .= ",".$mail3[0];
	$confe .= ", ".$mail3[1]." ".$mail3[2];
        }
        if ($data_dest[2] != 0)        {
                $req_mail4 = "SELECT mail,prenom,nom  FROM acteurs WHERE ref='$data_dest[2]'";
                $res_mail4 = tx_query($req_mail4);
                $mail4 = mysql_fetch_array($res_mail4);
                $dest .= ",".$mail4[0];
	$confe .= ", ".$mail4[1]." ".$mail4[2];
        }
	
	// redaction des mails
        $sujet = "Demande de conférence pour la fête de la science";
        $sujetbis = "Copie - Demande de conférence pour la fête de la science";

        $messageclient = "Bonjour,\n\n";
	$messageclient .= "Nous avons bien reçu votre demande de la conférence\n";
	$messageclient .= "  $ref_conf - $titre\n";
	$messageclient .= "délivrée par $confe.\n\n";
	$messageclient .= "Nous vous adresserons une réponse à cette demande très prochainement.\n";
	$messageclient .= "\n";
	$messageclient .= "Nous restons à votre disposition pour tous renseignements.\n\n";
	$messageclient .= "L'équipe de la Fête de la Science\n\n";
	$messageclient .= "Téléphone : 03 44 23 49 94 - Fax 03 44 23 52 19\n";
	$messageclient .= "Courriel : fete-de-la-science@utc.fr\n";
	$messageclient .= "http://fete-de-la-science.utc.fr\n";

        $message = "Bonjour,\n\nL'établissement : ";
        $message .= "$eta\nAdresse : $adr\nTéléphone : $tel\nFax : $fax\nE-mail : $mail\n\n";
        $message .= "aimerait que vous veniez faire la conférence : \n     $ref_conf - $titre\n";
        $message .= "le $creneau, \npour la(les) classe(s) suivante(s) : $classes.\n";
        $message .= "Nombre d'élèves : $nbeleves.\n\n";
        $message .= "L'enseignant responsable est : $prenom_resp $nom_resp\nTéléphone : $tel_resp\nE-mail : $mail_resp\n\n";
        $message .= "Merci de bien vouloir nous donner votre réponse rapidement.\n\n";
        $message .= "L'équipe de la Fête de la Science\n\n";
	$message .= "Téléphone : 03 44 23 49 94 - Fax 03 44 23 52 19\n";
	$message .= "Courriel : fete-de-la-science@utc.fr\n";
	$message .= "http://fete-de-la-science.utc.fr\n";
        $headers = 'From:fete-de-la-science@utc.fr';

        mail($dest,$sujet,$message,$headers);
        mail($destbis,$sujetbis,$message,$headers);
        mail($destclient,$sujet,$messageclient,$headers);

        echo "<center class=lignetitre>Votre demande a ét&eacute; prise en compte.<br>Vous recevrez bient&ocirc;t une r&eacute;ponse &agrave; cette demande.</center>";

}
else        {
?>
</p>
        <p class=titreligne>Remplissez ce formulaire int&eacute;gralement pour
          effectuer votre demande : tous les renseignements marqués d'une <font color='#FF0000'> *</font> sont obligatoires.</p>
        <form name="demande" action="demande_conference.php">
          <table width=75% border="0" cellpadding="0" cellspacing="0" class="fondtableau">
            <tr>
              <td><center>
                  <p class=titre>Informations sur l'&eacute;tablissement</p>
              </center></td>
            </tr>
          </table>
          <table width=75% border="0" cellpadding="0" cellspacing="0" class="fondtableau">
            <tr>
              <td width=20%>Etablissement : </td>
              <td><input type="text" size=50 name="etablissement"><font color='#FF0000'> *</font></td>
            </tr>
            <tr>
              <td width=20%>Adresse : </td>
              <td><textarea rows=3 cols=37 name="adr"></textarea><font color='#FF0000'> *</font></td>
            </tr>
            <tr>
              <td width=20%>T&eacute;l&eacute;phone : </td>
              <td><input type="text" size=20 name="tel"><font color='#FF0000'> *</font></td>
            </tr>
            <tr>
              <td width=20%>Fax : </td>
              <td><input type="text" size=20 name="fax"></td>
            </tr>
            <tr>
              <td width=20%>E-mail : </td>
              <td><input type="text" size=20 name="mail"><font color='#FF0000'> *</font></td>
            </tr>
          </table>
          <br>
          <table width=75% border="0" cellpadding="0" cellspacing="0" class="fondtableau">
            <tr>
              <td><center>
                  <p class=titre>Informations sur la conf&eacute;rence choisie</p>
              </center></td>
            </tr>
          </table>
          <table width=75% border="0" cellpadding="0" cellspacing="0" class="fondtableau">
            <?
         $ref_conf = $_GET["ref"];
         $nocreneau = $_GET["creneau"];

         require_once("_connection.php");
         tx_connect();
         $req = tx_query("SELECT titre FROM conferences WHERE ref_conf='$ref_conf'");
         $data_req = mysql_fetch_array($req);
         $titre = stripslashes($data_req[0]);

         echo "<tr><td width=20%>Conf&eacute;rence choisie : </td><td>$titre </td></tr>";
         echo "<tr><td width=20%>Cr&eacute;neau horaire : </td><td>";
         if ($nocreneau == 1)        $creneau = "Lundi 8 octobre 2007, 8h - 10h";
         elseif ($nocreneau == 2)        $creneau = "Lundi 8 octobre 2007, 10h - 12h";
         elseif ($nocreneau == 3)        $creneau = "Lundi 8 octobre 2007, 14h - 16h";
         elseif ($nocreneau == 4)        $creneau = "Lundi 8 octobre 2007, 16h - 18h";
         elseif ($nocreneau == 5)        $creneau = "Mardi 9 octobre 2007, 8h - 10h";
         elseif ($nocreneau == 6)        $creneau = "Mardi 9 octobre 2007, 10h - 12h";
         elseif ($nocreneau == 7)        $creneau = "Mardi 9 octobre 2007, 14h - 16h";
         elseif ($nocreneau == 8)        $creneau = "Mardi 9 octobre 2007, 16h - 18h";
         elseif ($nocreneau == 9)        $creneau = "Mercredi 10 octobre 2007, 8h - 10h";
         elseif ($nocreneau == 10)        $creneau = "Mercredi 10 octobre 2007, 10h - 12h";
         elseif ($nocreneau == 11)        $creneau = "Mercredi 10 octobre 2007, 14h - 16h";
         elseif ($nocreneau == 12)        $creneau = "Mercredi 10 octobre 2007, 16h - 18h";
         elseif ($nocreneau == 13)        $creneau = "Jeudi 11 octobre 2007, 8h - 10h";
         elseif ($nocreneau == 14)        $creneau = "Jeudi 11 octobre 2007, 10h - 12h";
         elseif ($nocreneau == 15)        $creneau = "Jeudi 10 octobre 2007, 14h - 16h";
         elseif ($nocreneau == 16)        $creneau = "Jeudi 11 octobre 2007, 16h - 18h";
         elseif ($nocreneau == 17)        $creneau = "Vendredi 12 octobre 2007, 8h - 10h";
         elseif ($nocreneau == 18)        $creneau = "Vendredi 12 octobre 2007, 10h - 12h";
         elseif ($nocreneau == 19)        $creneau = "Vendredi 12 octobre 2007, 14h - 16h";
         elseif ($nocreneau == 20)        $creneau = "Vendredi 12 octobre 2007, 16h - 18h";
         echo "$creneau</td></tr>";

        echo "<input type=\"hidden\" value=\"$ref_conf\" name=\"ref_conf\">";
        echo "<input type=\"hidden\" value=\"$titre\" name=\"titre\">";
        echo "<input type=\"hidden\" value=\"$nocreneau\" name=\"nocreneau\">";
        echo "<input type=\"hidden\" value=\"$creneau\" name=\"creneau\">";

 ?>
          </table>
          <br>
          <table width=75% border="0" cellpadding="0" cellspacing="0" class="fondtableau">
            <tr>
              <td><center>
                  <p class=titre>Informations compl&eacute;mentaires</p>
              </center></td>
            </tr>
          </table>
          <table width=75% border="0" cellpadding="0" cellspacing="0" class="fondtableau">
            <tr>
              <td width=20%>Classe(s) : </td>
              <td><input type="text" size=30 name="classes"><font color='#FF0000'> *</font></td>
            </tr>
            <tr>
              <td width=20%>Nombre d'&eacute;l&egrave;ves : </td>
              <td><input type="text" size=30 name="nbeleves"><font color='#FF0000'> *</font></td>
            </tr>
          </table>
          <br>
          <i><u>Enseignant responsable</u></i><br><br>
          <table width=75% border="0" cellpadding="0" cellspacing="0" class="fondtableau">
            <tr>
              <td width=20%>Nom : </td>
              <td><input type="text" size=30 name="nom_resp"><font color='#FF0000'> *</font></td>
            </tr>
            <tr>
              <td width=20%>Pr&eacute;nom : </td>
              <td><input type="text" size=30 name="prenom_resp"></td>
            </tr>
            <tr>
              <td width=20%>T&eacute;l&eacute;phone : </td>
              <td><input type="text" size=30 name="tel_resp"><font color='#FF0000'> *</font></td>
            </tr>
            <tr>
              <td width=20%>E-mail : </td>
              <td><input type="text" size=30 name="mail_resp"></td>
            </tr>
	    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
            <tr>
              <td>&nbsp;</td>
              <td align="center" valign="middle"><input type="submit" value="Soumettre la demande" name="soumettre"></td>
            </tr>
          </table>
        </form>
        <?  }  ?>
</div></td>
    <td width="1" bgcolor="#192041"><img src="images/1x1.gif" width="1" height="1"></td>
  </tr>
  <tr>
    <td colspan="3"><table width="100%"  border="0" cellpadding="0" cellspacing="0" id="bas">
        <tr>
          <td width="10"><img src="../images/coin_bas_gauche.gif" width="10" height="11"></td>
          <td background="../images/ligne_bas.gif"><img src="images/1x1.gif" width="1" height="11"></td>
          <td width="10"><img src="../images/coin_bas_droite.gif" width="10" height="11"></td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>

