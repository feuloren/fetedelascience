<?php

require_once('includes/conf.php');
require_once('includes/functions.php');

if (isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 'index';
}

if (isset($_GET['subpage'])) {
  $sub = $_GET['subpage'];
} else {
  $sub = $page;
}

$fichier = "pages/$page/$sub.php";
$fichier_alt = "pages/$page/$page.php";
if (is_file($fichier)) {
  require($fichier);
} else if (is_file($fichier_alt)) {
  require($fichier_alt);
} else {
  header("HTTP/1.0 404 Not Found");
  require("includes/404.php");
}

?>