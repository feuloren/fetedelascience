<?php
function display_acteurs($data) {
  $toi = '';
  for($i = 1; $i<=3; $i++) {
    $nom = _auteur($data["refa$i"]);
    if ($nom != false and $nom != " ") {
      $toi .= "<span class=\"label label-info\">$nom</span>  ";
    }
  }
  return $toi;
}

function display_materiel($data) {
  $toi = 'Matériel à prévoir : ';
  if ($data['materiel'] == '') {
    $toi .= 'Aucun';
  } else {
    $toi .= '<span class="label label-warning">' . $data['materiel'] . '</span>';
  }
  return $toi;
}
?>