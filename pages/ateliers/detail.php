<?php
if (!isset($_GET['id']) or empty($_GET['id'])) {
  header('Location: ateliers');
  exit();
}

$atelier = $_GET['id'];
$req = db_query('SELECT * FROM ateliers12 WHERE `ref_at` LIKE \'%s\'', $atelier);

if ($req->num_rows === 1) {
  $data = $req->fetch_assoc();
  fds_entete('Détail de l\'atelier '.$data['titre']);
} else {
  fds_entete('Cet atelier n\'existe pas');
}

if ($req->num_rows === 0) {
?>

<div class="row-fluid">
  <div class="span12 well well-large center">
    <h3>Désolé, cet atelier n'existe pas</h3>
    <p>Il semble que l'identifiant de l'atelier est incorrect<br/>
      Pour décrouvrir de véritables atelier cliquez sur le bouton ci-dessous.<br/><br/>
      <a href="/fetedelascience/ateliers" class="btn btn-primary
      btn-large">Retour à la liste des ateliers</a>
    </p>
  </div>
</div>

<?php } else {
require_once('aux.php');
?>

<div class="row-fluid">
  <div class="span12 well well-small">
    <h3 style="display:inline;"><?php echo $data['titre']; ?></h3>
    <span class="pull-right"><a href="/fetedelascience/ateliers" class="btn btn-primary">
        <i class="icon icon-arrow-left icon-white"></i> Retour à la liste
    </a></span>
  </div>
</div>
<div class="row-fluid">
  <div class="span12 well well-small">
    <p>Responsables : <?php echo display_resps($data); ?></p>
    <p>
      <?php echo fds_parse_texte($data['sujet']); ?>
    </p>
    <p>
      <?php echo fds_parse_texte($data['resume']); ?>
    </p>
  </div>
</div>

<?php
}
fds_basdepage();
?>
