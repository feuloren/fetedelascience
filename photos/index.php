<?php

require_once('../include.php');

// À renommer en 2012.php
// Lorsqu'on aura les photos de l'édition 2003
// Puis recréer une page photos.php
// Avec les images de l'édition 2013

$dossier = "images/photos2012/";
$files = scandir($dossier);

include_script('imageflow.packed.js');

add_header('<link rel="stylesheet" href="/fetedelascience/style/imageflow.css"/>');

fds_entete("Photos 2012");
?>

<div class="row-fluid" style="display:none;">
  <div class="span12 well well-large">
    <h3>Découvrez ci dessous des photos de la
    Fête de la science édition 2012</h3>
    <p>Du 10 au 14 octobre 2012, des enseignants-chercheur de l'UTC et
    de l'ESCOM, des passionnés de technique et des
    étudiants ont fait découvrir aux établissements scolaires puis au
    grand public les merveilles de la science.<br/>
    Tour d'horizon des ateliers présents; du simulateur de réalité
    virtuelle jusqu'à la chimie.
    </p>
  </div>
</div>

<div id="row-fluid">
  <div id="span12">
    <div id="gallery" class="imageflow">
  <?php
     foreach ($files as $file) {
     if ($file[0] != '.' AND $file != 'min') {
         echo "<img src='/fetedelascience/".$dossier."min2/$file' longdesc='/fetedelascience/".$dossier."$file' alt='' />\n";
     }
     }
     ?>
    </div>
  </div>
</div>

<div id="modal-gallery" class="modal modal-gallery modal-fullscreen hide fade" tabindex="-1">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h3 class="modal-title"></h3>
  </div>
  <div class="modal-body"><div class="modal-image"></div></div>
  <div class="modal-footer">
    <a class="btn btn-primary modal-next">Next <i class="icon-arrow-right icon-white"></i></a>
    <a class="btn btn-info modal-prev"><i class="icon-arrow-left icon-white"></i> Previous</a>
    <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000"><i class="icon-play icon-white"></i> Slideshow</a>
    <a class="btn modal-download" target="_blank"><i class="icon-download"></i> Download</a>
  </div>
</div>

<?php
fds_basdepage();
?>
