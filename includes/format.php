<?php
function fds_parse_texte($text) {
  return nl2br(preg_replace(array("/\{\{(.+)\}\}/",
                                  "/\{b (.+) b\}/"),
                            array("<img src='/fetedelascience/$1' style='max-width:770px'>",
                                  "<b>$1</b>"),
                            $text));
}

function _auteur($ref) {
  if ($ref === 295 OR $ref === 0)
    return false;

	$req = db_query("SELECT prenom, nom FROM acteurs WHERE ref=$ref");
  if ($req->num_rows == 0) {
    return false;
  } else {
    $data = $req->fetch_assoc();
    return $data['prenom'] ." ".$data['nom'];
  }
}

?>