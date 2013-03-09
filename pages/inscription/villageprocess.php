<?php
fds_entete("Inscription enregistrée");

function add_intervenant($i) {
	//Récupération accompagnateur
	$acnom = $_POST["ac".$i."nom"];
	$acprenom = $_POST["ac".$i."prenom"];
	$actelephone = $_POST["ac".$i."telephone"];
	$acmail = $_POST["ac".$i."mail"];
	$date_creation = date("Y-m-d H:i:s");
	
	if ($acnom === '' || $acprenom === '' || $actelephone === '' || $acmail === '') {
		return false;
	}
	else {
	//Insertion d'un accompagnateur
	db_query("INSERT INTO `accompagnateurs13`(`nom`, `prenom`, `mail`, `tel`, `etablissement`, `date_creation`) VALUES ('%s','%s','%s','%s','%s','%s')", $acnom, $acprenom, $acmail, $actelephone, $id_etablissement, $date_creation);
	$idacc = mysqli_insert_id($mysql_conn);
	
	//Insertion accompagnateur et reservation dans la table de jointure
	db_query("INSERT INTO `visite_acc`(`idresa`, `idaccompagnateur`) VALUES ('%s','%s')", $idresa, $idacc);	
	return true;
}
}

//Initialisation i
$i = 0;

//Récupération de l'id de l'établissement
$id_etablissement = $_POST["id_etablissement"];

// Récupération des données sur les établissements
$etablissement = $_POST["etablissement"];
$rue = $_POST["rue"];
$cp = $_POST["cp"];
$ville = $_POST["ville"];
$email = $_POST["email"];
$fax = $_POST["fax"];
$telephone = $_POST["telephone"];

//Récupération des infos de l'inscription (jour, heure, etc)
$niveau = $_POST["niveau"];
$nombre = $_POST["nombre"];
$jour = $_POST["jour"];
$heurearrive = $_POST["heurearrive"];
$heuredepart = $_POST["heuredepart"];
$nb_acc = $_POST["nb_acc"];

if (($id_etablissement == '-1') && ($etablissement === '' || $rue === '' || $cp === '' || $ville === '' || $email === '' || $telephone === '')) {
	header('Location: erreurinscription?id=1');
	die();
}
else if ( $id_etablissement == '-2') {
	header('Location: erreurinscription?id=3');
	die();
}
else if (add_intervenant(0) != true) {
	header('Location: erreurinscription?id=2');
	die();
}
else if ($nombre === '') {
	header('Location: erreurinscription?id=4');
	die();
}
else {
//Ajout de l'etablissement si besoin
if ('-1' == $id_etablissement) {
	//Insertion d'un établissment dans la base au cas ou il n'existe pas
	db_query("INSERT INTO `etablissements13`(`nom`, `telephone`, `mail`, `adresse`, `code_postal`, `ville`) VALUES ('%s','%s','%s','%s','%s','%s')", $etablissement, $telephone, $email, $rue, $cp, $ville);
	$id_etablissement = mysqli_insert_id($mysql_conn);
}
	////Insertion d'une inscription au village
	db_query("INSERT INTO `village13`(`idetab`, `niveau`, `nombre`, `date`, `heurearrive`, `heuredepart`) VALUES ('%s','%s','%s','%s','%s','%s')", $id_etablissement, $niveau, $nombre, $jour, $heurearrive, $heuredepart);
	$idresa = mysqli_insert_id($mysql_conn);
	
	for ($i = 0; $i < $nb_acc; $i++) {
		add_intervenant($i);
	}
}
?>
<div class="row-fluid">
  <div class="span12 well well-large">
    <p>
			L'inscription de votre établissment a la fête de la science a bien été pris en compte.
			Merci de votre Inscription.
		</p>
		<span class="pull-right">
			<a href="/fetedelascience/" class="btn btn-primary">
        <i class="icon icon-arrow-left icon-white"></i> Retour à l'accueil
			</a>
		</span>
	</div>
</div>

<?php
fds_basdepage();
?>
