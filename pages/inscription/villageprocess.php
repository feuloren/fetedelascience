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
	db_query("INSERT INTO `accompagnateurs`(`nom`, `prenom`, `mail`, `tel`, `etablissement`, `annee`, `date_creation`) VALUES ('%s','%s','%s','%s','%s','%s','%s')", $acnom, $acprenom, $acmail, $actelephone, $id_etablissement, get_annee(), $date_creation);
	$idacc = mysqli_insert_id($mysql_conn);
	
	//Insertion accompagnateur et reservation dans la table de jointure
	db_query("INSERT INTO `visite_acc`(`idresa`, `idaccompagnateur`, `annee`) VALUES ('%s','%s','%s')", $idresa, $idacc, get_annee());	
	return true;
}
}

//Initialisation i
$i = 0;
$mail_accompagnateur = '';
$TO_accompagnateur = '';

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
else if ($nombre === '' || $niveau === '') {
	header('Location: erreurinscription?id=4');
	die();
}
else {
//Ajout de l'etablissement si besoin
if ('-1' == $id_etablissement) {
	//Insertion d'un établissment dans la base au cas ou il n'existe pas
	db_query("INSERT INTO `etablissements`(`nom`, `telephone`, `mail`, `adresse`, `code_postal`, `ville`, `annee`) VALUES ('%s','%s','%s','%s','%s','%s','%s')", $etablissement, $telephone, $email, $rue, $cp, $ville, get_annee());
	$id_etablissement = mysqli_insert_id($mysql_conn);
}
	////Insertion d'une inscription au village
	db_query("INSERT INTO `village`(`idetab`, `niveau`, `nombre`, `date`, `heurearrive`, `heuredepart`, `annee`) VALUES ('%s','%s','%s','%s','%s','%s','%s')", $id_etablissement, $niveau, $nombre, $jour, $heurearrive, $heuredepart, get_annee());
	$idresa = mysqli_insert_id($mysql_conn);
	
	for ($i = 0; $i < $nb_acc; $i++) {
		add_intervenant($i);
		if (add_intervenant($i)){
			$mail_accompagnateur .= $acprenom.' '.$acnom.'  -  Téléphone : '.$actelephone.'\n';
			$TO_accompagnateur .= ', '.$acmail;
		}			
	}
	
if ('-1' != $id_etablissement) {
	//Recuperation de l'établissement au cas où il ai été choisis dans la liste
	$recup_etablissement = db_query("SELECT `id`, `nom`, `telephone`, `mail`, `adresse`, `code_postal`, `ville`, `fax` FROM `etablissements` WHERE id='%s'", $id_etablissement);
	$etablissement = recup_etablissement->fetch_assoc();
	$nom_etablissement = $etablissement['nom'];
	$rue = $etablissement['adresse'];
	$cp = $etablissement['code_postal'];
	$ville = $etablissement['ville'];
	$email = $etablissement['mail'];
	$fax = $etablissement['fax'];
	$telephone = $etablissement['telephone'];
}

//Rédaction du mail à l'intervenant +copie à la fds
$message = "Bonjour\n\n";
$message .= "Nous vous confirmons la validation de votre inscription à  la visite du Village de la\n";
$message .= "Technologie de la Fête de la Science pour le $jour entre $heurearrive et $heuredepart.\n\n";
$message .= "\nRappel des informations vous concernant : \n\n";
$message .= "$nom_etablissement\nAdresse : $rue $cp $ville\nTéléphone : $telephone\nFax : $fax\nE-mail : $email\n";
$message .= "Classe(s) : $niveau.\n";
$message .= "Nombre d'élèves : $nb_eleves.\n\n";
$message .= "Coordonnées du (des) accompagnateur(s) :\n $mail_accompagnateur";
$message .= "\nNous vous attendons à l'accueil du Village de la Technologie, situé dans Hall du batiment Pierre Guillaumat\n";
$message .= "(rue du Docteur Schweitzer à Compiègne). \n\n";
$message .= "Votre groupe sera pris en charge (nous constituerons des groupes de 15 à 20 personnes)\n";
$message .= "par un ou plusieurs guides pendant toute la durée de la visite.\n\n";
$message .= "Les pique-niques sont possibles sur place pour les groupes qui viennent pour la \n";
$message .= "journée du vendredi : des tentes seront dressées au Parc de Bayser. La surveillance des élèves \n";
$message .= "par les enseignants doit être continue (pas de \"quartier libre\", notamment au moment \n";
$message .= "du pique-nique).\n\n";
$message .= "Nous restons à votre disposition pour tous renseignements.\n";
$message .= "Nous vous remercions de votre intérêt pour la Fête de la Science.\n\n";
$message .= "L'équipe de la Fête de la Science\n\n";
$message .= "Téléphone : 03 44 23 49 94 - Fax : 03 44 23 52 19\n";
$message .= "Courriel : fete-de-la-science6@utc.fr\n";
$message .= "http://www.utc.fr/fetedelascience\n";
$messageV = "<html><body>" . nl2br(htmlspecialchars(stripslashes($message))) . "</body></html>";
$TOvillage = "$email $TO_accompagnateur, fete-de-la-science6@utc.fr" . "\r\n";

//Envoi des mails
$subject = "Demande d'inscription au village de la technologie ";
$header = "From: fete-de-la-science6@utc.fr" . "\r\n";
$header .= 'MIME-Version: 1.0' . "\r\n";
$header .= 'Content-type: text/html; charset=utf-8';

mail($TOvillage, $subject, $messageV, $header);
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
