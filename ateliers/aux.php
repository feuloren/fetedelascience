<?php
function display_resps($data) {
  $toi = '';
  for($i = 1; $i<=2; $i++) {
    $nom = _auteur($data["resp$i"]);
    if ($nom != false and $nom != " ") {
      $toi .= "<span class=\"label label-info\">$nom</span>  ";
    }
  }
  return $toi;
}

?>