<?php
fds_entete("Inscription enregistrée");

//Récupération de l'id de l'établissement
$id_etablissement = $_POST["id_etablissement"];

//Ajout de l'etablissement si besoin
if ('-1' == $id_etablissement) {
	
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
	$id_etablissement = mysqli_insert_id($mysql_conn);
}

//Récupération responsable
$resp_nom = $_POST["resp-nom"];
$resp_prenom = $_POST["resp-prenom"];
$resp_telephone = $_POST["resp-telephone"];
$resp_mail = $_POST["resp-mail"];
$date_creation = date("Y-m-d H:i:s");

//Insertion d'un responsable
db_query("INSERT INTO `accompagnateurs13`(`nom`, `prenom`, `mail`, `tel`, `etablissement`, `date_creation`) VALUES ('%s','%s','%s','%s','%s','%s')", $resp_nom, $resp_prenom, $resp_mail, $resp_telephone, $id_etablissement, $date_creation);
$idresp = mysqli_insert_id($mysql_conn);

//Récupération des infos de la conférence
$conf = $_POST["conf"];
$niveau = $_POST["niveau"];
$nb_eleves = $_POST["nb_eleves"];
$id_dispo = $_POST["date"];

//Récupération du jour et de la période
$result = db_query("SELECT `jour`, `periode` FROM `disponibilites13` WHERE id='%s'", $id_dispo);
$date = $result->fetch_assoc();
$jour = $date['jour'];
$periode = $date['periode'];

//Insertion de la réservation
db_query("INSERT INTO `reservations13`(`conference`, `jour`, `periode`, `etablissement`, `accompagnateur`, `niveau`, `nb_eleves`) VALUES ('%s','%s','%s','%s','%s','%s','%s')", $conf, $jour, $periode,$id_etablissement, $idresp, $niveau, $nb_eleves);

?>
<div class="row-fluid">
  <div class="span12 well well-large">
    <p>
			L'inscription de votre établissement a la conférence a bien été prise en compte.
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

