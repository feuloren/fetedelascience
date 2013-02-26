<?php

$mysql_conn = False;

/*
 * db_connect()
 * Crée une connection à la base de donnée
 */
function db_connect() {
  global $mysql_conn, $CONF;

  $mysql_conn = new mysqli($CONF['server'], $CONF['user'], $CONF['pass'], $CONF['base']);
  if ($mysql_conn->connect_errno) {
    echo "<b>Échec de la connexion à la base de donnée</b><br/>";
    if (DEBUG)
      echo $mysql_conn->connect_error;
    die();
  }
  $mysql_conn->set_charset('utf8');
}

/*
 * db_query
 * Envoie une requête au serveur de base de données
 * Pour des raisons de sécurité les entrées utilisateur ne doivent
 * pas être concaténées directement dans query mais passées la tableau
 * $args
 * Elles seront automatiquement échappées et les %s 
 */
function db_query($query) {
  global $mysql_conn;
  if ($mysql_conn === False)
    db_connect();

  // On prépare la requête
  $args = func_get_args();
  if ($args > 1) {
    array_shift($args); // On enlève $query
    $args = array_map(array($mysql_conn, 'real_escape_string'), $args);
    array_unshift($args, $query);
    $query = call_user_func_array('sprintf', $args);
  }
  $req = $mysql_conn->query($query);
  if (!$req) {
    if (DEBUG)
      die($mysql_conn->error);
    else
      die("<b>Une erreur d'accès à la base de données est survenue<br/>Merci de contacter le 5000.</b>");
  } else {
    return $req;
  }
}

$additional_scripts = '';
function include_script($file) {
  global $additional_scripts;
  $additional_scripts .= "<script src=\"";
  if(stripos($file, '//') === 0 or stripos($file, 'http') === 0) {
    $additional_scripts .= $file;
  } else {
    $additional_scripts .= "/fetedelascience/js/$file";
  }
  $additional_scripts .= "\"></script>\n";
}

$additional_headers = '';
function add_header($header) {
  global $additional_headers;
  $additional_headers .= "$header\n";
}

function fds_entete($titre) {
  global $additional_headers;
  require('includes/entete.php');
}

function fds_basdepage() {
  global $additional_scripts;
  require('includes/basdepage.php');
}

require_once('format.php');
