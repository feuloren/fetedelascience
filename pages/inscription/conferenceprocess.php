<?php
fds_entete("Inscription enregistrée");
var_dump($_POST);
//Récupération de l'id de l'établissement
$id_etablissement = $_POST["id_etablissement"];

// Récupération des données sur les établissements
$nom_etablissement = $_POST["etablissement"];
$rue = $_POST["rue"];
$cp = $_POST["cp"];
$ville = $_POST["ville"];
$email = $_POST["email"];
$fax = $_POST["fax"];
$telephone = $_POST["telephone"];

//Récupération responsable
$resp_nom = $_POST["resp-nom"];
$resp_prenom = $_POST["resp-prenom"];
$resp_telephone = $_POST["resp-telephone"];
$resp_mail = $_POST["resp-mail"];
$date_creation = date("Y-m-d H:i:s");

//Récupération des infos de la conférence
$conf = $_POST["conf"];
$niveau = $_POST["niveau"];
$nb_eleves = $_POST["nb_eleves"];
$id_dispo = $_POST["date"];

if ($conf == '-1') {
	header('Location: erreurconference?id=0');
	die();
}
else if (($id_etablissement == '-1') && ($nom_etablissement === '' || $rue === '' || $cp === '' || $ville === '' || $email === '' || $telephone === '')) {
	header('Location: erreurconference?id=1');
	die();
}
else if ($id_etablissement == '-2') {
	header('Location: erreurconference?id=3');
	die();
}
else if ($resp_nom === '' || $resp_prenom === '' || $resp_mail === '' || $resp_telephone === '') {
	header('Location: erreurconference?id=2');
	die();
}
else if ($nb_eleves === '' || $niveau === '') {
	header('Location: erreurconference?id=4');
	die();
}
else {
//Ajout de l'etablissement si besoin
if ($id_etablissement == '-1') {
	//Insertion d'un établissment dans la base au cas ou il n'existe pas
	db_query("INSERT INTO `etablissements`(`nom`, `telephone`, `mail`, `adresse`, `code_postal`, `ville`, `fax`, `annee`, `date_creation`) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s', NOW())", $nom_etablissement, $telephone, $email, $rue, $cp, $ville, $fax, get_annee());
	$id_etablissement = mysqli_insert_id($mysql_conn);
}

//Insertion d'un responsable
db_query("INSERT INTO `accompagnateurs`(`nom`, `prenom`, `mail`, `tel`, `etablissement`, `annee`, `date_creation`) VALUES ('%s','%s','%s','%s','%s','%s', NOW())", $resp_nom, $resp_prenom, $resp_mail, $resp_telephone, $id_etablissement, get_annee());
$idresp = mysqli_insert_id($mysql_conn);

//Insertion de la réservation
db_query("INSERT INTO `reservations` (`conference`, `disponibilite`, `etablissement`, `accompagnateur`, `niveau`, `nb_eleves`, `annee`) VALUES (%s, %s, %s, %s, '%s', %s, '%s')", $conf, $id_dispo ,$id_etablissement, $idresp, $niveau, $nb_eleves, get_annee());

//Recuperation titre et nom intervenant de la conférence
$recup_conf = db_query("SELECT `titre`, `intervenant` FROM `conferences` WHERE id='%s'", $conf);
$conference = $recup_conf->fetch_assoc();
$titre = $conference['titre'];
$id_intervenant = $conference['intervenant'];
$recup_intervenant = db_query("SELECT `nom`, `prenom`, `mail` FROM `intervenants` WHERE id='%s'", $id_intervenant);
$intervenant = $recup_intervenant->fetch_assoc();
$nom_intervenant = $intervenant['nom'];
$prenom_intervenant = $intervenant['prenom'];
$mail_intervenant = $intervenant['mail'];

if ('-1' != $id_etablissement) {
	//Recuperation de l'établissement au cas où il ai été choisis dans la liste
	$recup_etablissement = db_query("SELECT `id`, `nom`, `telephone`, `mail`, `adresse`, `code_postal`, `ville`, `fax` FROM `etablissements` WHERE id='%s'", $id_etablissement);
	$etablissement = $recup_etablissement->fetch_assoc();
	$nom_etablissement = $etablissement['nom'];
	$rue = $etablissement['adresse'];
	$cp = $etablissement['code_postal'];
	$ville = $etablissement['ville'];
	$email = $etablissement['mail'];
	$fax = $etablissement['fax'];
	$telephone = $etablissement['telephone'];
}

//Rédaction du mail de confirmation
$messageclient  = "Nous avons bien reçu votre demande de conférence<br/>";
$messageclient .= "$titre<br/>";
$messageclient .= "délivrée par $nom_intervenant $prenom_intervenant<br/>";
$messageclient .= "le $jour $periode <br/> pour la(les) classe(s) suivante(s) : $niveau de $nb_eleves élèves.<br/>";
$messageclient .= "Nous vous adresserons une réponse à cette demande très prochainement.<br/>";
$messageclient .= "<br/>";
$messageclient .= "Nous restons à votre disposition pour tous renseignements.<br/><br/>";
$messageclient .= "L'équipe de la Fête de la Science<br/><br/>";
$messageclient .= "Téléphone : 03 44 23 49 94 - Fax 03 44 23 52 19<br/>";
$messageclient .= "Courriel : fete-de-la-science6@utc.fr<br/>";
$messageclient .= "http://fete-de-la-science.utc.fr<br/>";
$messageC = "<html><body>" . nl2br(htmlspecialchars(stripslashes($messageclient))) . "</body></html>";
$TOclient = "$email,$resp_mail" . "\r\n";

//Rédaction du mail à l'intervenant +copie à la fds
$messageinter = "Bonjour,<br/><br/>L'établissement : ";
$messageinter .= "$nom_etablissemnt<br/>Adresse : $rue $cp $ville<br/>Téléphone : $telephone<br/>Fax : $fax<br/>E-mail : $email<br/><br/>";
$messageinter .= "aimerait que vous veniez faire la conférence : <br/>$titre<br/>";
$messageinter .= "le $jour $periode<br/>pour la(les) classe(s) suivante(s) : $niveau de $nb_eleves élèves.<br/>";
$messageinter .= "L'enseignant responsable est : $resp_nom $resp_prenom<br/>Téléphone : $resp_telephone<br/>E-mail : $resp_mail<br/><br/>";
$messageinter .= "Merci de bien vouloir nous donner votre réponse rapidement.<br/><br/>";
$messageinter .= "L'équipe de la Fête de la Science";
$messageinter .= "Téléphone : 03 44 23 49 94 - Fax 03 44 23 52 19<br/>";
$messageinter .= "Courriel : fete-de-la-science6@utc.fr<br/>";
$messageinter .= "http://fete-de-la-science.utc.fr<br/>";
$messageI = "<html><body>" . nl2br(htmlspecialchars(stripslashes($messageinter))) . "</body></html>";
$TOinter = "$mail_intervenant, fete-de-la-science6@utc.fr" . "\r\n";

//Envoi des mails
$subject = "Demande de conférence pour la Fete de la science";
$header = "From: fete-de-la-science6@utc.fr" . "\r\n";
$header .= 'MIME-Version: 1.0' . "\r\n";
$header .= 'Content-type: text/html; charset=utf-8';

mail($TOclient, $subject, $messageC, $header);
mail($TOinter, $subject, $messageI, $header);
}
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

