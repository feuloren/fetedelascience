<?php

require_once('includes/conf.php');
require_once('includes/functions.php');

if (isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 'index';
}

$fichier = "pages/$page/$page.php";
if (is_file($fichier)) {
  require($fichier);
} else {
  header("HTTP/1.0 404 Not Found");
  require("includes/404.php");
}

?>