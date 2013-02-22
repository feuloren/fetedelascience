<?php
require_once('aux.php');

// À modifier pour 2013
// Il vaudrait mieux organiser les ateliers en thèmes
// Comme les années d'avant
// Sauf que là on avait pas les données

$req = db_query("SELECT * FROM ateliers12");

$nav = '';

while($data = $req->fetch_assoc()) {
  $nav .= '<li><a href="/fetedelascience/ateliers/detail?id='. $data['ref_at'] .'">'. $data['titre'] .'</a></li>';
}

fds_entete("Village des sciences 2012");
?>

<div class="row-fluid">
  <div clas="span12">
    <div class="well">
        <h3>Le village des sciences</h3>
        <p>Du 10 au 14 octobre au centre Pierre Guillaumat...<br/>
        La semaine sera consacrée aux scolaires, pensez à vous inscrire.<br/>
        Accès libre pour tous le week-end.</p>
    </div>
  </div>
</div>
<div class="row-fluid">
  <div class="span12">
    <div class="well">
      <ul class="nav nav-list fds-list" id="listeconfs">
        <li class="nav-header">Liste des ateliers</li>
        <?php echo $nav; ?>
        <li class="divider"></li>
        <li class="fds-li-btn"><a href="/fetedelascience/inscriptFDS" class="btn btn-success">Inscription</a></li>
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
