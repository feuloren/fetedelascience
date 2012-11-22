<?php
$dossier = "images/photos2012/";
$files = scandir($dossier);

include_script('http://blueimp.github.com/JavaScript-Load-Image/load-image.min.js');
include_script('bootstrap-image-gallery.min.js');

add_header('
<!--[if lt IE 7]><link rel="stylesheet" href="http://blueimp.github.com/cdn/css/bootstrap-ie6.min.css"/><![endif]-->
<link rel="stylesheet" href="/fetedelascience/style/bootstrap-image-gallery.min.css"/>
');

fds_entete("Photos 2012");
?>

<div class="row-fluid">
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
    <ul id="gallery" data-toggle="modal-gallery"
        data-target="#modal-gallery" class="thumbnails">
  <?php
     foreach ($files as $file) {
     if ($file[0] != '.' AND $file != 'min') {
     echo "<li><a rel='gallery' href='/fetedelascience/".$dossier.$file."' title='' class='thumbnail'><img src='/fetedelascience/".$dossier.'min/'."$file'/></a></li>\n";
     }
     }
     ?>
    </ul>
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
