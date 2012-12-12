<?php
include_script('http://blueimp.github.com/JavaScript-Load-Image/load-image.min.js');
include_script('bootstrap-image-gallery.min.js');

add_header('
   <!--[if lt IE 7]><link rel="stylesheet" href="http://blueimp.github.com/cdn/css/bootstrap-ie6.min.css"/><![endif]-->
   <link rel="stylesheet" href="/fetedelascience/style/bootstrap-image-gallery.min.css"/>
   ');

$photos = array('adn04.jpg' => 'L\'ADN, le nouveau gardien de la
sécurité alimentaire ',
                'auto-masseur.jpg' => 'La techologie au service du bien-être : l\'automasseur',
                'casseroles.jpg'=> 'Mais que se passe-t-il dans nos casseroles ? ',
                'clone-virtuel.jpg'=> 'Faites sourire votre clone virtuel',
                'clubs-techno.jpg'=> 'Les Clubs technologiques : quand les étudiants de l\'UTC animent des activités technologiques pour des enfants',
                'drone-Auryon.jpg'=> 'Le projet Auryon : des micro-drones et des hommes',
                'etoiles.jpg'=> 'La vie des étoiles',
                'filtration-lait.jpg'=> 'Du lait au fromage : la filtration dynamique',
                'lifters.jpg' => 'Les lifters version 200',
                'photoelasticite.jpg'=> 'La photoélasticité : comment voir les efforts mécaniques dans les objets transparents ?',
                'photomaton.jpg'=> 'Entrez dans notre photomaton et repartez avec votre buste en 3 dimensions',
                'pilote-auto.jpg'=> 'Y a-t-il un pilote dans l\'auto ?',
                'poudres.jpg'=> 'De la physique amusante sur les poudres',
                'simNav.jpg'=> 'SimNav : le simulateur de conduite de navire');

fds_entete("Photos 2004");
?>

<div class="row-fluid">
  <div class="span12 well well-large">
    <h3 style="display:inline;">Photos de l'édition 2004</h3>
    <span class="pull-right">
      <a href="/fetedelascience/archives" class="btn
      btn-primary"><i class="icon icon-arrow-left icon-white"></i>Retour aux archives</a>
    </span>
  </div>
</div>

<div id="row-fluid">
  <div id="span12">
    <ul id="gallery" data-toggle="modal-gallery"
        data-target="#modal-gallery" class="thumbnails">
  <?php foreach ($photos as $name => $desc) {
          echo '
      <li>
        <div class="thumbnail">
          <a rel="gallery" href="/fetedelascience/images/photos2004/'. $name  .'" title="">
            <img src="/fetedelascience/images/photos2004/min/'. $name  .'" alt="'. $name .'"/>
          </a>
          <p>'. $desc .'</p>
        </div>
      </li>';
       } ?>
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
