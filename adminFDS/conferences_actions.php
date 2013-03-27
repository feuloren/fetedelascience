<?php

function parse_post_data() {
  $ref = intval($_POST['ref']);
  $data = array('ref' => $ref);
  if ($ref > 0) {
    $data['intervenant']  = intval($_POST['intervenant']);
    $data['titre']    = mysqlSecureText($_POST['titre']);
    $data['resume']   = mysqlSecureText($_POST['resume']);
    $data['public']   = $_POST['public'];
    $data['materiel'] = mysqlSecureText($_POST['materiel']);
    $data['branche'] = mysqlSecureText($_POST['branche']);
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
    tx_query("DELETE FROM `conferences` WHERE `id` = $ref");
  else {
    var_dump($matches);
    die("Vous n'êtes pas autorisé à supprimer cette conférence.");
  }

  break;

case 'generer-ref':
  $req = tx_query("SELECT MAX(id) FROM `conferences`");
  $data = mysql_fetch_array($req);
  echo $data[0] + 1;
  break;
case 'pre-modif':
  $ref = intval($_POST['ref']);
  if ($ref > 0) {
    $req = tx_query("SELECT * FROM `conferences` WHERE `id` = $ref");
    $data = mysql_fetch_assoc($req);
    echo json_encode($data);
  }
  break;
case 'ajouter': // ou modifier c'est pareil
  $data = parse_post_data();
  // faire un replace into au lieu de insert c'est magique
  if ($data) {
    $chaine = "REPLACE `conferences`
              (`id`, `titre`, `intervenant`, `public`, `materiel`, `resume`, `branche`, `annee`)
              VALUES (".$data['ref'].", '".$data['titre']."', ".$data['intervenant'].", '".$data['public']."', '".$data['materiel']."', '".$data['resume']."', '".$data['branche']."', '".get_annee()."')";
    tx_query($chaine);
  }
  break;
default:
  die("Action demandée inexistante");
  break;
}

?>