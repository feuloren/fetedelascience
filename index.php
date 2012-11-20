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
  require("includes/404.php");
}

?>