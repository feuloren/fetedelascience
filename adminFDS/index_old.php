<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<center><p><img src="./fds_logo_blanc.jpg" width="49" height="57"></p></center>
<title>Interface administrateur</title>
</head>

<body>

<h1><center>Bienvenue dans l'interface administrateur</center></h1>
<br><br>



<?php

/********************************************
Ce script est la page de login. Un nom d'utilisateur 
et un mot de passe sont demandés.
Si les informations sont incorrectes il faut recommencer.
Une fois que l'utilisateur a été reconnu on peut
accéder aux fonctionnalités.
********************************************/



require_once("_connection.php");
tx_connect();
if(isset($_POST["verif"]))
{
	$login = mysqlSecureText($_POST["login"]);
	$passwd = mysqlSecureText($_POST["passwd"]);

	$query = "SELECT * from admin where login='$login' and passwd ='$passwd'";
	$id = tx_query($query);
	$res = mysql_fetch_array($id);
	if(!$res)
	{
		echo "Erreur de mot de login ou de mot de passe, veuillez réessayer.";
	} 
	else
	{
		 new_session($login);
		 echo "<center><form method=GET action=\"index2.php\">";
		 echo "<input type=\"submit\" value=\"Administration\">";
		 echo "<input type=\"hidden\" value=\"$login\" name=\"login\">";
		 echo "<input type=\"hidden\" value=\"$sessionid\" name=\"sessionid\">";
		 echo "</form></center>";
	}
}
else
{
?>
<center>
  <form method="POST" action="index.php">
    <input type="hidden" name="verif" >
    <p>
      Login: <input type="text" size=12 name="login" ><br/>
      Password: <input type="password" size=12 name="passwd"><br/>
      <input type="submit" value = "Valider" ><br/>
    </p>
  </form>
</center>

<?php } ?>
</body>
</html>
