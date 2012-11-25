<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />


<html>

<link href="../fds2004.css" rel="stylesheet" type="text/css">
<head>
<title>Demande de conférence</title>
</head>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<body link=blue vlink=blue alink=blue>


<p class=titrepage>Faire une demande de conférence dans votre établissement</p>
<img src="../images/1x1.gif" width="15" height="10" align="absmiddle"><br>

<p class=titreligne>Cliquez sur le créneau de la conférence (disponible) à laquelle vous souhaitez vous inscrire.</p>
<?
//initialisation
require_once("_connection.php");
tx_connect();

echo "<table width=100% border=0 cellpadding=0 cellspacing=2>";
echo "<tr align=middle width=100%><td width=20%>&nbsp;</td><td width=16% class=\"zonebleu\">Lundi 8 octobre</td><td width=16% class=\"zonebleu\">Mardi 9 octobre</td><td width=16% class=\"zonebleu\">Mercredi 10 octobre</td><td width=16% class=\"zonebleu\">Jeudi 11 octobre</td><td width=16% class=\"zonebleu\">Vendredi 12 octobre</td></tr></table>";
echo "<table width=100% border=0 cellpadding=0 cellspacing=2>";
echo "<tr align=middle><td width = 20% align=center class=\"titletable\">Titre</td><td width=5% class=\"zoneorange\">8h-10h</td><td width=5% class=\"zoneorange\">10h-12h</td><td width=5% class=\"zoneorange\">14h-16h</td><td width=5% class=\"zoneorange\">16h-18h</td>";
echo "<td width=4% class=\"zoneorange\">8h-10h</td><td width=4% class=\"zoneorange\">10h-12h</td><td width=4% class=\"zoneorange\">14h-16h</td><td width=4% class=\"zoneorange\">16h-18h</td>";
echo "<td width=4% class=\"zoneorange\">8h-10h</td><td width=4% class=\"zoneorange\">10h-12h</td><td width=4% class=\"zoneorange\">14h-16h</td><td width=4% class=\"zoneorange\">16h-18h</td>";
echo "<td width=4% class=\"zoneorange\">8h-10h</td><td width=4% class=\"zoneorange\">10h-12h</td><td width=4% class=\"zoneorange\">14h-16h</td><td width=4% class=\"zoneorange\">16h-18h</td>";
echo "<td width=4% class=\"zoneorange\">8h-10h</td><td width=4% class=\"zoneorange\">10h-12h</td><td width=4% class=\"zoneorange\">14h-16h</td><td width=4% class=\"zoneorange\">16h-18h</td></tr>";





$req = mysql_query("select titre,refa1,refa2,refa3,ref_conf from conferences");
while ($data = mysql_fetch_array($req))	{
	$titre = stripSlashes($data[0]);
	$ref_conf = $data[4];
// boucle concernant la liste des titres des conférences

	print ("<tr valign=middle><td class=\"zonebleu2\">
	<a href=\"#\" onClick=\"MM_openBrWindow('../recap_conf.php?rekconf=$ref_conf','recapitulatif','scrollbars=yes,width=760,height=420, top=10, left=10')\" class=\"liensjaune2\">$ref_conf : $titre</a>
	</td>"
	
	);

	for ($i=1 ; $i < 21 ; $i++)	{
		$dispo = 0;  // 0 = indisponible - 1 = libre - 2 = réservé
		$bool = true ; $j = 1;


		while($bool && $j<3)	{   // boucle sur les acteurs

		if ($data[$j] == 0)	$bool = false ;
		else	{
		// reccuperation de la reference de l acteur
			$reqa = "select ref_acteur from acteurs where ref='$data[$j]';";
			$resa = mysql_query($reqa);
			$dataa = mysql_fetch_array($resa);

		// verification des disponibilites de l acteur
			if ($i < 10)	$verif = "select c0$i from dispo where ref_acteur='$dataa[0]';";
			else	$verif = "select c$i from dispo where ref_acteur='$dataa[0]';";

			$resv = mysql_query($verif);
			$datav = mysql_fetch_array($resv);

			$statut = substr($datav[0],0,1);
			if (strcmp($statut,'0') == 0)
			{
				$bool = false ;
			}
			elseif (strcmp($statut,'1') == 0)
			{
				$dispo = 1	;
				$bool = false ;
			}
			elseif (strcmp($statut,'2') == 0)
			{
				if ($dispo == 1)
				{
					$bool = false ;
				}
				else
				{	
					$dispo = 2 ;
					$bool = false ;
				}
			}
			elseif (strcmp($statut,'C') == 0)
			{
				if ($dispo == 1 || $dispo == 2)
				{
					$bool = false	;
				}
				else
				{
					$dispo = 3;
					$bool = false ;
				}
			}
			$j++;

		}// fin else
		} // fin while
	if ($dispo == 0) echo "<td bgcolor=\"white\">&nbsp;</td>";
	elseif ($dispo == 1)	echo "<td align=\"center\" class=\"zoneyellow\"><a href=./demande_conference.php?creneau=$i&ref=$ref_conf&titre=$titre>Dispo</a></td>";
	elseif ($dispo == 2)	echo "<td align=\"center\" class=\"zonejaunefds\">Réservé</td>";
	elseif ($dispo == 3)	echo "<td align=\"center\" class=\"zoneorange\">Attribué</td>";
	
	}
	echo "</tr>";

}

echo "</table>";
?>


</body>
</html>
