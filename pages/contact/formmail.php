<?php
$subject = $_POST["title"];
$message = $_POST["comments"];
$nom = $_POST["realname"];
$email = $_POST["email"];
$TO = "matgrillere@gmail.com";
$header = "From: $nom <$email>" . "/r/n";

mail($TO, $subject, $message, $header);

Header("Location: /fetedelascience/contact/merci");
?>
