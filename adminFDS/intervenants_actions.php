<?php

function parse_post_data() {
  $ref = intval($_POST['ref']);
  $data = array('ref' => $ref);
  if ($ref > 0) {
    $data['telephone'] = mysqlSecureText($_POST['telephone']);
    $data['nom']       = mysqlSecureText($_POST['nom']);
    $data['prenom']    = mysqlSecureText($_POST['prenom']);
    $data['mail']      = mysqlSecureText($_POST['mail']);
    return $data;
  } else {
    return False;
  }
}

switch ($action) {
case 'supprimer':
  if (!isset($_POST['ref'])) die('Impossible de supprimer : pas de référence donnée');

  $ref = intval($_POST['ref']);
  // On parse la référence pour voir si l'utilisateur connecté est autorisé à la supprimer
  if ($ref > 0)
    tx_query("DELETE FROM `intervenants` WHERE `id` = " . $ref);
  else {
    die("Vous n'êtes pas autorisé à supprimer cet atelier.");
  }

  break;
case 'generer-ref':
  $req = tx_query("SELECT MAX(id) FROM `intervenants`");
  $data = mysql_fetch_array($req);
  echo $data[0] + 1;
  break;
case 'pre-modif':
  $ref = intval($_POST['ref']);
  if ($ref > 0) {
    $req = tx_query("SELECT * FROM `intervenants` WHERE `id` = " . $ref);
    $data = mysql_fetch_assoc($req);
    echo json_encode($data);
  }
  break;
case 'ajouter': // ou modifier c'est pareil
  $data = parse_post_data();
  // faire un replace into au lieu de insert
  if ($data) {
    $chaine = "REPLACE `intervenants`
              (`id`, `nom`, `prenom`, `telephone`, `mail`, `annee`, `date_modification`)
              VALUES (".$data['ref'].", '".$data['nom']."', '".$data['prenom']."', '".$data['telephone']."',
                      '".$data['mail']."', '".get_annee()."', NOW())";
var_dump($chaine);
    tx_query($chaine);
  }
  break;
case 'supprimer-dispo':
  $ref = intval($_POST['ref']);
  if ($ref <= 0)
    die("Identifiant incorrect");
  else
    tx_query("DELETE FROM disponibilites  WHERE id = $ref");
  break;
case 'ajouter-dispo':
  $ref = intval($_POST['ref']);
  if ($ref <= 0) {
    die("Identifiant incorrect");
  }
  $date = $_POST['date'];
  if (!preg_match("/\d{4}-\d\d-\d\d/", $date)) {
    die("Format de date incorrect");
  }
  $periode = $_POST['periode'];
  if (!in_array($periode, array('matin', 'aprem'))) {
    die("Période incorrecte ('matin' ou 'aprem')");
  }
  $debut = $_POST['heureDebut'];
  if (!preg_match("/\d\d:\d\d/", $debut)) {
      die("Format de l'heure de début incorrect");
  }
  $fin = $_POST['heureFin'];
  if (!preg_match("/\d\d:\d\d/", $fin)) {
      die("Format de l'heure de fin incorrect");
  }
  tx_query("INSERT INTO `disponibilites` (`jour`, `periode`, `intervenant`, `heureDebut`, `heureFin`) VALUES
            ('$date', '$periode', $ref, '$debut', '$fin')");
  header("Location: $page.php");
  break;
case 'export-excel':
    die("Action non disponible");
  break;
default:
  die("Action demandée inexistante");
  break;
}

?>
