<?php
require_once('aux.php');

// À modifier pour 2013
// Il vaudrait mieux organiser les ateliers en thèmes
// Comme les années d'avant
// Sauf que là on avait pas les données

$req = db_query("SELECT * FROM ateliers12");

$nav = '';
$body = '';

while($data = $req->fetch_assoc()) {
  $nav .= '<li><a href="#'. $data['ref_at'] .'">'. $data['titre'] .'</a></li>';
  $body .= '<section id="'. $data['ref_at'] .'" class="well
                                                         well-small">
  <div class="row-fluid">
    <div class="span9">
      <h3 style="display:inline;"><a href="/fetedelascience/ateliers/detail?id='. $data['ref_at'] .'">'. $data['titre'].'</a></h3>
    </div>
    <div class="span3">
      <span class="pull-right"><a href="#">Retour en haut <i class="icon
      icon-arrow-up"></i></a></span>
    </div>
  </div>
  ' . display_resps($data) . '<br/><br/>
  '. fds_parse_texte($data['sujet']) .'</section>';
}

fds_entete("Village des sciences 2012");
?>

<div class="row-fluid">
  <div class="span4">
    <div class="well">
      <ul class="nav nav-list" id="listeconfs">
        <li class="nav-header">Liste des ateliers</li>
        <?php echo $nav; ?>
        <li class="divider"></li>
        <li><a href="/fetedelascience/inscriptFDS" class="btn btn-success">Inscription</a></li>
      </ul>
    </div> <!-- /well -->
  </div>
  <div class="span8">
  <?php echo $body; ?>
  </div>
</div>

<?php
fds_basdepage();
?>