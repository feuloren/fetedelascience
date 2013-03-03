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
    tx_query("DELETE FROM `intervenants13` WHERE `id` = " . $ref);
  else {
    die("Vous n'êtes pas autorisé à supprimer cet atelier.");
  }

  break;
case 'generer-ref':
  $req = tx_query("SELECT MAX(id) FROM `intervenants13`");
  $data = mysql_fetch_array($req);
  echo $data[0] + 1;
  break;
case 'pre-modif':
  $ref = intval($_POST['ref']);
  if ($ref > 0) {
    $req = tx_query("SELECT * FROM `intervenants13` WHERE `id` = " . $ref);
    $data = mysql_fetch_assoc($req);
    echo json_encode($data);
  }
  break;
case 'ajouter': // ou modifier c'est pareil
  $data = parse_post_data();
  // faire un replace into au lieu de insert
  if ($data) {
    $chaine = "REPLACE `intervenants13`
              (`id`, `nom`, `prenom`, `telephone`, `mail`)
              VALUES (".$data['ref'].", '".$data['nom']."', '".$data['prenom']."', '".$data['telephone']."',
                      '".$data['mail']."')";
var_dump($chaine);
    tx_query($chaine);
  }
  break;
case 'supprimer-dispo':
  $ref = intval($_POST['ref']);
  if ($ref <= 0)
    die("Identifiant incorrect");
  else
    tx_query("DELETE FROM disponibilites13 WHERE id = $ref");
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
  tx_query("INSERT INTO `disponibilites13` (`jour`, `periode`, `intervenant`) VALUES
            ('$date', '$periode', $ref)");
  header("Location: $page.php");
  break;
case 'export-excel':
  require 'phpexcel/Classes/PHPExcel.php';
  require 'phpexcel/Classes/PHPExcel/Writer/Excel5.php';
  $xls = new PHPExcel;
  $feuille = $xls->getActiveSheet();

  $req = tx_query("SELECT * FROM `ateliers12`");

  // On commmence par écrire les en-têtes des colones
  $entetes = array("Référence", "Responsables", "Titre", "Sujet", "Résumé", "Lieu", "Contraintes", "Grilles", "Tables", "Chaises", "Vidéo Projecteurs", "Écrans", "Ordinateurs", "Bancs", "Electricte", "Materiel Autre", "Poster");
  foreach ($entetes as $key => $value) {
    $feuille->setCellValueByColumnAndRow($key, 1, $value);
    // centré et en gras
    $feuille->getStyleByColumnAndRow($key, 1)->applyFromArray(array("font" => array("bold" => true),
                                                                    "alignment" => array("horizontal" => "center", "vertical" => "center")));
  }

  //Puis les données
  $ligne = 2;
  while ($data = mysql_fetch_assoc($req)) {
    $feuille->setCellValueByColumnAndRow(0, $ligne, $data['ref_at']);
    if ($data['resp1'] != 295) {
      $auteurs = _auteur($data['resp1']);
      if ($data['resp2'] != 295)
        $auteurs .= "\n" . _auteur($data['resp2']);
    } elseif ($data['resp2'] != 295) {
      $auteurs = _auteur($data['resp2']);
    } else {
      $auteurs = "";
    }
    $feuille->setCellValueByColumnAndRow(1, $ligne, $auteurs);
    $feuille->setCellValueByColumnAndRow(2, $ligne, unhtml($data['titre']));
    $feuille->setCellValueByColumnAndRow(3, $ligne, unhtml($data['sujet']));
    $feuille->setCellValueByColumnAndRow(4, $ligne, unhtml($data['resume']));
    $feuille->setCellValueByColumnAndRow(5, $ligne, unhtml($data['lieu']));
    $feuille->setCellValueByColumnAndRow(6, $ligne, unhtml($data['contraintes']));
    $feuille->setCellValueByColumnAndRow(7, $ligne, $data['grilles']);
    $feuille->setCellValueByColumnAndRow(8, $ligne, $data['tables']);
    $feuille->setCellValueByColumnAndRow(9, $ligne, $data['chaises']);
    $feuille->setCellValueByColumnAndRow(10, $ligne, $data['videoprojs']);
    $feuille->setCellValueByColumnAndRow(11, $ligne, $data['ecrans']);
    $feuille->setCellValueByColumnAndRow(12, $ligne, $data['ordinateurs']);
    $feuille->setCellValueByColumnAndRow(13, $ligne, $data['bancs']);
    $feuille->setCellValueByColumnAndRow(14, $ligne, ($data['electricite'] === 1) ? "oui" : "non");
    $feuille->setCellValueByColumnAndRow(15, $ligne, unhtml($data['materiel']));
    $feuille->setCellValueByColumnAndRow(16, $ligne, $data['poster']);
    $feuille->getRowDimension($ligne)->setRowHeight(30);
    $ligne += 1;
  }

  // On met en forme correctement
  $column = 16;
  while ($column >= 0) {
    $feuille->getColumnDimensionByColumn($column)->setAutoSize(true);
    $column--;
  }

  header('Content-type: application/vnd.ms-excel');
  header('Content-Disposition:inline;filename=ateliers.xls ');
  $writer = new PHPExcel_Writer_Excel5($xls);
  $writer->save('php://output');
  break;
default:
  die("Action demandée inexistante");
  break;
}

?>
