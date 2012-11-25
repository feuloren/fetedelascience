<?php

function parse_post_data() {
  $ref = $_POST['ref'];
  $data = array('ref' => $ref);
  if (verif_ref($ref)) {
    $data['auteur1']     = intval($_POST['resp1']);
    $data['auteur2']     = intval($_POST['resp2']);
    $data['titre']       = mysqlSecureText($_POST['titre']);
    $data['sujet']       = mysqlSecureText($_POST['sujet']);
    $data['resume']      = mysqlSecureText($_POST['resume']);
    $data['lieu']        = mysqlSecureText($_POST['lieu']);
    $data['contraintes'] = mysqlSecureText($_POST['contraintesLoc']);
    $data['grilles']     = intval($_POST['grilles']);
    $data['vp']          = intval($_POST['vp']);
    $data['tables']      = intval($_POST['tables']);
    $data['grilles']     = intval($_POST['grilles']);
    $data['ecrans']      = intval($_POST['ecrans']);
    $data['ordis']       = intval($_POST['ordis']);
    $data['chaises']     = intval($_POST['chaises']);
    $data['bancs']       = intval($_POST['bancs']);
    $data['autre']       = mysqlSecureText($_POST['autre']);
    $data['electricite'] = isset($_POST['electricite']) ? 1 : 0;
    $data['poster']      = "";
    return $data;
  } else {
    return False;
  }
}

switch ($action) {
case 'supprimer':
  if (!isset($_POST['ref'])) die('Impossible de supprimer : pas de référence donnée');

  $ref = mysqlSecureText($_POST['ref']);
  // On parse la référence pour voir si l'utilisateur connecté est autorisé à la supprimer
  if (verif_ref($ref))
    tx_query("DELETE FROM `ateliers12` WHERE `ref_at` = '$ref'");
  else {
    var_dump($matches);
    die("Vous n'êtes pas autorisé à supprimer cet atelier.");
  }

  break;
case 'generer-ref':
  $branche = $_POST['branche'];
  if (in_array($branche, $adminBranches)) {
    /* L'idée est de récupérer la plus grande ref existante pour cette branche
     * on sélectione donc tous les ateliers de cette branche
     * Puis on extrait le numéro (dernière partie)
     * Enfin on converti en INTEGER (c'est le +0) pour que MAX fonctionne comme attendu
     */
    $offset = 2 + strlen($branche) + 1;
    $req = tx_query("SELECT MAX(SUBSTRING(`ref_at`, $offset, 2)+0) FROM `ateliers12` WHERE `ref_at` LIKE '%-$branche%'");
    $data = mysql_fetch_array($req);
    echo 'V-' . $branche . ($data[0] + 1);
  }
  break;
case 'pre-modif':
  $ref = $_POST['ref'];
  if (verif_ref($ref)) {
    $req = tx_query("SELECT * FROM `ateliers12` WHERE `ref_at` LIKE '$ref'");
    $data = mysql_fetch_assoc($req);
    echo json_encode($data);
  }
  break;
case 'ajouter': // ou modifier c'est pareil
  $data = parse_post_data();
  // faire un replace into au lieu de insert
  if ($data) {
    $chaine = "REPLACE `ateliers12`
              (`ref_at`, `resp1`, `resp2`, `titre`, `sujet`, `resume`,
               `lieu`, `contraintes`, `grilles`, `tables`, `chaises`, `videoprojs`,
               `ecrans`, `ordinateurs`, `bancs`, `electricite`, `materiel`, `poster`)
              VALUES ('".$data['ref']."', ".$data['auteur1'].", ".$data['auteur2'].",
                      '".$data['titre']."', '".$data['sujet']."',
                      '".$data['resume']."', '".$data['lieu']."',
                      '".$data['contraintes']."', ".$data['grilles'].",
                      ".$data['tables'].", ".$data['chaises'].",
                      ".$data['vp'].", ".$data['ecrans'].",
                      ".$data['ordis'].", ".$data["bancs"].", ".$data['electricite'].",
                      '".$data['autre']."', '".$data['poster']."')";
    tx_query($chaine);
  }
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