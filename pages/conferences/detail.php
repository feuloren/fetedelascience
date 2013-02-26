<?php
if (!isset($_GET['id']) or empty($_GET['id'])) {
  header('Location: conferences');
  exit();
}

$conference = $_GET['id'];
var_dump($conference);
$req = db_query('SELECT * FROM conferences11 WHERE `ref_conf` = \'%s\'', $conference);

if ($req->num_rows === 1) {
  $data = $req->fetch_assoc();
  fds_entete('Détail de la conference '.$data['titre']);
} else {
  fds_entete('Cette conference n\'existe pas');
}

if ($req->num_rows === 0) {
?>

<div class="row-fluid">
  <div class="span12 well well-large center">
    <h3>Désolé, cette conférence n'existe pas</h3>
    <p>Il semble que l'identifiant de la conférence est incorrect<br/>
      Pour décrouvrir de véritables conférences cliquez sur le bouton ci-dessous.<br/><br/>
      <a href="/fetedelascience/conferences" class="btn btn-primary
      btn-large">Retour à la liste des conférences</a>
    </p>
  </div>
</div>

<?php } else {
require_once('aux.php');
?>

<div class="row-fluid">
  <div class="span12 well well-small">
    <h3 style="display:inline;"><?php echo $data['titre']; ?></h3>
    <span class="pull-right"><a href="/fetedelascience/conferences" class="btn btn-primary">
        <i class="icon icon-arrow-left icon-white"></i> Retour à la liste
    </a></span>
  </div>
</div>
<div class="row-fluid">
  <div class="span12 well well-small">
    <p>Responsables : <?php echo display_acteurs($data); ?></p>
    <p>
      <?php echo fds_parse_texte($data['resume']); ?>
    </p>
    <p>
			<?php echo display_materiel($data); ?>
    </p>
  </div>
</div>

<?php
}
fds_basdepage();
?>
