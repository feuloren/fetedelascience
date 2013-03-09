<?php
fds_entete("Erreur Inscription");
$id_erreur = $_GET["id"];
?>
<div class="row-fluid">
  <div class="span12 well well-large">
    <p>
			Votre demande d'inscription n'a pas pu être enregistrée
		</p>
<?php
if ($id_erreur == '1') {
	echo stripslashes('<p> Tout les champs relatifs à l\'enregistrement de votre établissement n\'ont pas été remplis </p>');
}
else if ($id_erreur == '2') {
	echo stripslashes('<p> Les champs relatifs au premier intervenant doivent absolument être remplis </p>');
}
else if ($id_erreur == '3') {
	echo stripslashes('<p> Vous devez choisir un établissement dans la liste ou en ajouter un </p>');
}
else if ($id_erreur == '4') {
	echo stripslashes('<p> Les champs "Nombre d\'élèves" et "Niveau" de votre inscription doit être remplis </p>');
}
else {
	echo stripslashes('<p> Désolé </p>');
}
?>
		<span class="pull-right">
			<a href="village" class="btn btn-primary">
        <i class="icon icon-arrow-left icon-white"></i> Retour à l'Inscription
			</a>
		</span>
	</div>
</div>

<?php
fds_basdepage();
?>
