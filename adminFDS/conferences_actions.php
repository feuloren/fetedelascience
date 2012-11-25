<?php

function parse_post_data() {
  $ref = $_POST['ref'];
  $data = array('ref' => $ref);
  if (verif_ref($ref)) {
    $data['auteur1']  = intval($_POST['auteur1']);
    $data['auteur2']  = intval($_POST['auteur2']);
    $data['auteur3']  = intval($_POST['auteur3']);
    $data['titre']    = mysqlSecureText($_POST['titre']);
    $data['resume']   = mysqlSecureText($_POST['resume']);
    $data['public']   = mysqlSecureText($_POST['public']);
    $data['materiel'] = mysqlSecureText($_POST['materiel']);
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
    tx_query("DELETE FROM `conferences` WHERE `ref_conf` = '$ref'");
  else {
    var_dump($matches);
    die("Vous n'êtes pas autorisé à supprimer cette conférence.");
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
    $req = tx_query("SELECT MAX(SUBSTRING(`ref_conf`, $offset, 2)+0) FROM `conferences` WHERE `ref_conf` LIKE '%-$branche%'");
    $data = mysql_fetch_array($req);
    echo 'V-' . $branche . ($data[0] + 1);
  }
  break;
case 'pre-modif':
  $ref = $_POST['ref'];
  if (verif_ref($ref)) {
    $req = tx_query("SELECT * FROM `conferences` WHERE `ref_conf` LIKE '$ref'");
    $data = mysql_fetch_assoc($req);
    echo json_encode($data);
  }
  break;
case 'ajouter': // ou modifier c'est pareil
  $data = parse_post_data();
  // faire un replace into au lieu de insert c'est magique
  if ($data) {
    $chaine = "REPLACE `conferences`
              (`titre`, `public1`, `materiel`, `comm_mat`, `resume`, `comm_public`,
               `ref_corres`, `refa1`, `refa2`, `refa3`, `ref`, `num_conf`, `ref_conf`,
               `branche`, `annee`)
              VALUES ('".$data['titre']."', '".$data['public']."', '".$data['materiel']."',
                      '', '".$data['resume']."', '',
                      0, ".$data['auteur1'].",
                      ".$data['auteur2'].", ".$data['auteur3'].",
                      0, 0, '".$data['ref']."', 'ABC', 2012)";
    echo "<pre>".$chaine."</pre>";
    tx_query($chaine);
  }
  break;
default:
  die("Action demandée inexistante");
  break;
}

?>