<?php
require_once('../include.php');

$subject = $_POST["title"];
$message = "<html><body>" . nl2br(htmlspecialchars(stripslashes($_POST["comments"]))) . "</body></html>";
$nom = $_POST["realname"];
$email = $_POST["email"];
$TO = "fetedelascience@utc.fr" . "\r\n";
$header = "From: $nom <$email>" . "\r\n";
$header .= 'MIME-Version: 1.0' . "\r\n";
$header .= 'Content-type: text/html; charset=utf-8';

mail($TO, $subject, $message, $header);

Header("Location: /fetedelascience/contact/merci");
?>
