<?php

switch ($action) {
case 'supprimer':
  if (!isset($_POST['ref'])) die('Impossible de supprimer : pas de référence donnée');

  $ref = mysqlSecureText($_POST['ref']);
  tx_query("DELETE FROM `actualites` WHERE `ref_actu` = '$ref'");
  break;
case 'ajouter':
  $titre = get_text('titre');
  $soustitre = get_text('sous-titre');
  $date = get_text('date');
  $resume = get_text('resume');
  $texte = get_text('texte');

  $req = tx_query("SELECT MAX(`ref`) FROM `actualites`");
  $data = mysql_fetch_array($req);
  $ref = 'A-' . ($data[0] + 1);

  tx_query("INSERT INTO `actualites` (`titre`, `sous_titre`, `date`,
                                      `resume`, `texte_complet`, `ref_actu`)
            VALUES ('$titre', '$soustitre', '$date', '$resume', '$texte',
                    '$ref')");
  break;
default:
  die('Action demandée inexistante');
  break;
}

?>