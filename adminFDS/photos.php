<?php
include_once '_connection.php';
$page_en_cours = 'photos';
$template = 'photos.php';

$dossier_photos = "../images/photos2012/";
$extension = ".jpg";

// on liste les photos
$photos = scandir($dossier_photos);

include_once 'base.php';
?>
