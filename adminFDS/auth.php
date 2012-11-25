<?php
require_once '_xml_parser.php';

define("CAS_URL", "http://cas.utc.fr/cas");
define("FDS_URL", "http://localhost/fetedelascience/adminFDS");
$adminLogin = "";
//$adminPages = array();
//$adminBranches = array();

session_start();

function is_admin($login) {
  tx_connect();
  $req = tx_query("SELECT `login` FROM `droits` WHERE `login` LIKE '" . mysqlSecureText($login) . "'");
  return mysql_num_rows($req) > 0;
}

if(isset($_GET["logout"])) {
  session_destroy();
  header("Location: ".CAS_URL."/logout?url=".FDS_URL);
  exit();
}

if(isset($_SESSION["loged"]) && $_SESSION["loged"] == 1) {
	// tout vas bien on est loged ;)
	if(isset($_SESSION['login'])) {
    if (is_admin($_SESSION['login']))
      $adminLogin = $_SESSION['login'];
    else
      die("Utilisateur non autorisé");
	} else {
		// On délogue par sécurité
		session_destroy();
		// On envoie sur le cas
		header("Location: ".CAS_URL."/login?service=".FDS_URL);
		exit();
	}
} else {
	// User not loged
	//1. Regardons si on a un retour de CAS.
	if(isset($_GET["ticket"])) {
		$validate = CAS_URL . "/serviceValidate?service=" . FDS_URL . "&ticket=". $_GET["ticket"];

    $data = file_get_contents($validate);
    $parsed = new XmlToArrayParser($data);
    if (isset($parsed->array['cas:serviceResponse']['cas:authenticationSuccess']['cas:user'])) {
      $_SESSION['login'] = $parsed->array['cas:serviceResponse']['cas:authenticationSuccess']['cas:user'];
      $_SESSION['loged'] = 1;
      header("Location: ".FDS_URL);
      exit();
    }
	} else {
		//2. On renvoie sur le cas
		session_destroy();
		header("Location: ".CAS_URL."/login?service=".FDS_URL);
		exit();
	}
}
