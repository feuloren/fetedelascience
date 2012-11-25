<?php
 
/********************************************
Ce script, qui est du pur PHP, contient des 
fonctionnalitÃ©s utilisÃ©es en interne dans le site
comme l'encapsulation de certaines fonctions 
MySQL, certains formulaires et les fonctions de 
session
********************************************/

//script destiné a contenir différentes fonctions utilisées souvent
//error_reporting(E_ALL);


//fonction réalisant la connection à la BDD
function tx_connect()
{
	$res = mysql_connect("mysql.utc.fr","webscien","Tavobazu")
		or die("Erreur de connection a la base de données");
	mysql_select_db("dbwebscien");
	return $res;
}

function tx_query($query)
{
	$id = mysql_query($query)
		or die ("La requête $query n'a pas pu être exécutée correctement");
	return $id;
}


//on va stocker ici le tableau de toutes les branches
function form_branche($selected="")
{
mysql_connect("mysql.utc.fr","webscien","Tavobazu")
		or die("Erreur de connection a la base de données");
	mysql_select_db("dbwebscien");
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






//Ã  partir d'ici on va stocker les fonctions relatives à la gestion de sessions.
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



?>
