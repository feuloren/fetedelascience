<?php
// Fichier à inclure dans toutes les pages de la partie admin
// Contient les fonctions de connection à mysql
// des fonctions diverses
// et les 'constantes' utilisées souvent

// À activer quand on debug
error_reporting(E_ALL);

/* Nouvelle architecture mise en place en 2012:
 *
 * 4 pages listées dans la variable $pages
 * chacune s'occupe d'un type de donnée
 * Une grande partie est commune (html, css, js)
 * donc on a une page base.php qui sert de template
 *
 * Les actions (ajouter, modifier et supprimer côté serveur)
 * sont gérées par la page _actions.php
 * qui inclue le fichier ?_actions.php en fonction de l'identifiant
 * de page passé
 * TOUS les formulaires pointent vers _actions.php en POST
 * et on 2 champs obligatoires: page et action
 * Finalement on redirige (http) vers ?.php
 */

//fonction réalisant la connection à la BDD

include '../includes/conf.php';

function tx_connect()
{
  global $CONF;
	$res = mysql_connect($CONF['server'], $CONF['user'], $CONF['pass'])
		or die(mysql_error());
	mysql_select_db($CONF['base']) or die(mysql_error());
  mysql_set_charset("utf8");
	return $res;
}

function tx_query($query)
{
	$id = mysql_query($query)
		or die (mysql_error());
	return $id;
}


//on va stocker ici le tableau de toutes les branches
function form_branche($selected="")
{
  tx_connect();

  $query = "SELECT branche, nom FROM branches ORDER BY nom";
  $id = mysql_query($query) or die("Erreur de connection à la base de données");
  echo "<tr><td>Branche : </td><td><select name=\"branche\">";
  while($res = mysql_fetch_array($id))
    {
      if(strcmp($selected,$res[0]) == 0)
        {
          echo "<option value=\"$res[0]\" selected>$res[1]</option>";
        }
      else
        {
          echo "<option value=\"$res[0]\">$res[1]</option>";
        }
    }
  echo "</select></td>";
}






//À partir d'ici on va stocker les fonctions relatives à la gestion de sessions.
//cette fonction crée une nouvelle session et retourne l'identifiant de session
function new_session($login) {
	global $sessionid;
	session_name("fetescie");
	if($sessionid) session_destroy();

	//session_register("sessionid");
	$sessionid = md5(uniqid("fetescie$login"));

	//echo $sessionid;

	$res = mysql_query("UPDATE admin SET sessionId='$sessionid' WHERE login= '$login'");
	return $sessionid;
}




function verif($login, $sessionid) {
	$sql = "SELECT * FROM admin WHERE login = '$login' AND sessionId = '$sessionid'";
	$res = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($res) == 0)
	{
		$sql = "UPDATE admin SET sessionId = '0' WHERE login = '$login'";
		$res = mysql_query($sql) or die(mysql_error());
		echo "<a href='index.php'>Vous devez vous connecter avant d'accéder à cette page<br></a>";
		return FALSE;
	}
	else return TRUE;

}


//verifie si l'admin est inscrit, et le log si il ne l'est pas deja
//retourne 0 si tout se passe bien
//-1 si le login a echoue
//-2 si 'admin est deja logge
//-3 si l'authentification a rencontre des problemes
function log_admin($login, $passwd)
{
	if(isset($login) && isset($passwd))
	{
		$res = mysql_query("select sessionId from admin where login = '$login' and passwd = '$passwd'") or die(mysql_error());
		if(mysql_num_rows($res) > 0)
		{
			list($connecte) = mysql_fetch_row($res);
			if($connecte == 0)
			{
				return 0;
			}
			else return -2;
		} else return -1;
	} else return -3;
}

//delogge un utilisateur
//retourne TRUE si tout se passe bien, FALSE sinon
function log_out($login)
{
	mysql_query("update admin set sessionId = '0' where login= '$login'") or die(mysql_error());
	return TRUE;
}

function mysqlSecureText($text){
	return htmlentities(mysql_real_escape_string(stripslashes(html_entity_decode($text, ENT_COMPAT, 'UTF-8'))), ENT_COMPAT, 'UTF-8');
}

function unhtml($text){
	return html_entity_decode($text, ENT_COMPAT, 'UTF-8');
}

function _auteur($ref) {
  if ($ref === '' OR $ref === 0)
    return false;

	$req = tx_query("SELECT prenom, nom FROM acteurs WHERE ref=$ref");
  if (mysql_num_rows($req) == 0) {
    return false;
  } else {
    $data = mysql_fetch_assoc($req);
    return $data['prenom'] ." ".$data['nom'];
  }
}

function get_text($id) {
  if (in_array($id, array_keys($_POST)))
    return mysqlSecureText($_POST[$id]);
  else
    return '';
}

require_once 'auth.php';

//Les différentes pages
$pages = array("actus" => "Actualités",
               "conferences" => "Conférences",
               "ateliers" => "Ateliers",
               "acteurs" => "Acteurs");
$actions = array("ajouter", "supprimer", "modifier");
//$adminLogin = "a";
$adminPages = array("index", "conferences", "ateliers", "actus"); //debug
$adminBranches = array("GI", "ASSO", "GB", "GM", "UTC", "TSH", "GSU", "GSM", "CSTI", "GP", "ESCOM"); //debug

?>
