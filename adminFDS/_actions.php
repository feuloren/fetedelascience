<?php
include_once '_connection.php';
tx_connect();
/*
echo "<pre>";
var_dump($_POST);
echo "</pre>";
*/
if (!empty($_POST)) {
  $page = $_POST['page'];
  $action = $_POST['action'];
} else {
  $page = $_GET['page'];
  $action = $_GET['action'];
}

if (!in_array($page, array_keys($pages)))
  die("Identifiant de page incorrect");
if (!in_array($page, $adminPages))
  die('Accès non autorisé');

function verif_ref($ref) {
  global $adminBranches;
  return preg_match_all("/^[A-Z]-([A-Z]*)\d*$/", $ref, $matches) and in_array($matches[1][0], $adminBranches);
}

include_once "${page}_actions.php";

if ($action == 'ajouter' OR $action == 'modifier')
  header("Location: $page.php");
?>