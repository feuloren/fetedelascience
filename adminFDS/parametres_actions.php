<?php

switch($action) {
case 'modifier':
  load_parametres();
  foreach($parametres as $nom => $val) {
    var_dump($_POST[$nom], $val[0]);
    if (isset($_POST[$nom]) && ($_POST[$nom] != $val[0])) {
      $q = "UPDATE `parametres` SET `valeur` = '$_POST[$nom]' WHERE `nom` = '$nom'";
      var_dump($q);
      tx_query($q);
    }
  }
  break;
default:
  die("Action demandée inexistante");
  break;
}
