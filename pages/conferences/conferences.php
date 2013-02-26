<?php
require_once('aux.php');

$req = db_query("SELECT * FROM conferences11");

$nav = '';
// $body = '';

while($data = $req->fetch_assoc()) {
  $nav .= '<li><a href="/fetedelascience/conferences/detail?id='. $data['ref_conf'] .'">'. $data['titre'] .'</a></li>';
  /* $body .= '<section id="'. $data['ref_conf'] .'" class="well
                                                         well-small">
  <div class="row-fluid">
  <div class="span9"><h3>'. $data['titre'].'</h3></div>
  <div class="span3"><a href="#"><i class="icon
  icon-arrow-up"> </i>Retour en haut</a></div>
  </div>
  ' . display_acteurs($data) . '
  <span class="label label-success">' . $data['public1'] . '</span><br/><br/>
  '. fds_parse_texte($data['resume']) .'<br/><br/>
  '. display_materiel($data) .'</section>'; */
}

fds_entete("Conférences 2012");
?>

<div class="row-fluid">
  <div class="span12">
    <div class="well">
      <ul class="nav nav-list" id="listeconfs">
				<li><a href="/fetedelascience/inscription/conference" class="btn btn-success">Inscription aux conférences</a></li>
				<li class="divider"></li>
        <li class="nav-header">Liste des conférences</li>
        <?php echo $nav; ?>
      </ul>
    </div> <!-- /well -->
  </div>
</div>


<?php
fds_basdepage();
?>
