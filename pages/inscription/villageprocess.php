<?php
// Récupération des données sur les établissements
$etablissement = $_POST["etablissement"];
$rue = $_POST["rue"];
$cp = $_POST["cp"];
$ville = $_POST["ville"];
$email = $_POST["email"];
$fax = $_POST["fax"];
$telephone = $_POST["telephone"];

//Insertion d'un établissment dans la base au cas ou il n'existe pas
db_query("INSERT INTO `etablissements13`(`nom`, `telephone`, `mail`, `adresse`, `code_postal`, `ville`) VALUES ('%s','%s','%s','%s','%s','%s')", $etablissement, $telephone, $email, $rue, $cp, $ville);
$idetab = mysql_insert_id();

//Récupération des infos de l'inscription (jour, heure, etc)
$niveau = $_POST["niveau"];
$nombre = $_POST["nombre"];
$jour = $_POST["jour"];
$heurearrive = $_POST["heurearrive"];
$heuredepart = $_POST["heuredepart"];

////Insertion d'une inscription au village
db_query("INSERT INTO `village13`(`idetab`, `niveau`, `nombre`, `date`, `heurearrive`, `heuredepart`) VALUES ('%s','%s','%s','%s','%s','%s')", $idetab, $niveau, $nombre, 2001-01-01, 00:00:00, 00:00:00);
$idresa = mysql_insert_id();

for ($i = 0; $i <= 3; $i++) {
	
	//Récupération accompagnateur
	$acnom = $_POST["ac".$i."nom"];
	$acprenom = $_POST["ac".$i."prenom"];
	$actelephone = $_POST["ac".$i."prenom"];
	$acmail = $_POST["ac".$i."prenom"];
	
	//Insertion d'un accompagnateur
	db_query("INSERT INTO `accompagnateurs13`(`nom`, `prenom`, `mail`, `tel`) VALUES ('%s','%s','%s','%s')", $acnom, $acprenom, $actelephone, $acmail);
	$idacc = mysql_insert_id();
	
	//Insertion accompagnateur et reservation dans la table de jointure
	db_query("INSERT INTO `visite_acc`(`idresa`, `idaccompagnateur`) VALUES ('%s','%s')", $idresa, $idacc);
	
}

	
	

//Header("Location: /fetedelascience/");
?>
