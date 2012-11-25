<?php

$dossier_photos = "../images/photos2012/";
$extension = ".jpg";

function resize_and_save($file) {

}

switch ($action) {
case "ajouter":

  break;
case "supprimer":
  $fichier = str_replace("/", "", $_POST['fichier']);
  unlink($dossier_photos . $fichier . $extension);
  unlink($dossier_photos . $fichier . '-min' . $extension);
default:
  die("Action demandée inexistante");
  break;
}

?>