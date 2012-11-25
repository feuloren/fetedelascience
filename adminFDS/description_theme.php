<?php
require("connect.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>F&ecirc;te de la science</title>
<link href="fds2004.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="760" border="0" align="left" cellpadding="0" cellspacing="0" id="complet">
  <tr>
    <td>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="fondtableau" id="principal">
      <tr>
        <td width="250" valign="top"><a href="index.html"><img src="images/logo_fds.gif" width="245" height="369" border="0" alt="Retour accueil"></a></td>
        <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>&nbsp;			</td>
          </tr>
          <tr>
            <td><img src="images/1x1.gif" width="100%" height="44"></td>
          </tr>
          <tr>
            <td><table width="500" border="0" cellpadding="0" cellspacing="0" id="texte">
              <tr>
                <td colspan="3">
				
				<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="haut">
                    <tr>
                      <td width="10" height="11" valign="bottom"><img src="images/coin_haut_gauche.gif" width="10" height="11"></td>
                      <td class="lignetitre">
					  
					  <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="9" height="20"><img src="images/titre_gauche.gif" width="9" height="20"></td>
                          <td class="titrepage">Description de l'atelier </td>
                          <td width="9" height="20"><img src="images/titre_droit.gif" width="9" height="20"></td>
                        </tr>
                      </table>
					  
					  </td>
                      <td width="10" valign="bottom"><img src="images/coin_haut_droite.gif" width="10" height="11"></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td width="1" valign="top" bgcolor="#192041"><img src="images/1x1.gif" width="1" height="1"></td>
                <td valign="top"  class="textencart">
				<div class="textencart">
                
				
				<?
				$rek_du_theme=$_GET[rektheme];
				//requete d'affichage du theme
				$sql="SELECT ref_at, porteur, type, photos, coorg, resume, titre, acteurs.nom, acteurs.prenom, acteurs.ref
                      FROM ateliers05, acteurs
				      WHERE ref_at='$rek_du_theme'
				      AND   ateliers.porteur= acteurs.ref
                     ";
							  
				             $rsql=mysql_query($sql,$connection);
							  //tant qu'il y a des theme on boucle
							 while ($r=mysql_fetch_array($rsql))
							 { 
							  //affiche la liste des thèmes des villages
							 	
							 	$la_ref=$r[ref_at];
							 	$le_nom=$r[nom];
							 	$le_prenom=$r[prenom];
							 	$les_coorg=$r[coorg];
							 	$le_type=$r[type];
							 	$le_resume=$r[resume];
							 	$strip_resume=stripslashes($le_resume);
							 	$le_titre=stripslashes($r[titre]);
							 	$la_photo=stripslashes($r[photos]);
							 	
							print("
				 <table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" id=\"tableauprogramme\">
                  <tr>
                    <td class=\"zonejaune\"><b>$la_ref : $le_titre</b></td>
                  </tr>
                  <tr>
                    <td align=\"center\">$le_type</td>
                  </tr>
                  <tr>
                    <td align=\"center\"><br>$le_prenom $le_nom, $les_coorg<br><img src=\"http://www.utc.fr/fetedelascience/Photos/$la_photo\" width=\"250\" vspace=\"15\"></td>
                  </tr>
                  <tr valign=\"top\">
                    <td ><br><span class='textencart'>$strip_resume</span></td>
                  </tr>
                  
                </table>
							      
							
							      ");
							 }
				 ?>
				
				</div>
				</td>
                <td width="1" valign="top" bgcolor="#192041"><img src="images/1x1.gif" width="1" height="1"></td>
              </tr>
              <tr>
                <td colspan="3"><table width="100%"  border="0" cellpadding="0" cellspacing="0" id="bas">
                    <tr>
                      <td width="10"><img src="images/coin_bas_gauche.gif" width="10" height="11"></td>
                      <td background="images/ligne_bas.gif"><img src="images/1x1.gif" width="1" height="11"></td>
                      <td width="10"><img src="images/coin_bas_droite.gif" width="10" height="11"></td>
                    </tr>
                </table>
                
                
                
                </td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><img src="images/1x1.gif" width="100%" height="25"></td>
          </tr>
          <tr>
            <td align="center" bgcolor="#FFFFFF"><a HREF="javascript:window.close()"><img src="images/close_window.gif" width="118" height="22" border="0"></a></td>
          </tr>
        </table></td>
      </tr>
</table>
       
    </td>
  </tr>
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="241" height="20" class="footer"><a href="index.html" class="liensjaune">Accueil</a> | <a href="contact.html" class="liensjaune">Nous
            contacter</a> | <a href="mentions_legales.html" class="liensjaune">mentions
            l&eacute;gales</a> </td>
        <td width="9"><img src="images/coin_footer.gif" width="9" height="20"></td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
