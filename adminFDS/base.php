<?php

// Si l'identifiant de page n'existe pas (càd l'utilisateur demande directement la page base.php
// On renvoie une erreur
if (!isset($pages) or !isset($page_en_cours))
  die('Identifiant de page incorrect');

// L'utilisateur connecté est-il autorisé à accéder à cette page ?
if (!in_array($page_en_cours, $adminPages))
  die('Accès non autorisé'); // 403 ?

// Liste les pages auxquelles l'utilisateur à accès
function echo_menu() {
  global $pages, $page_en_cours, $adminPages;
  foreach($pages as $page=>$nom) {
    if (in_array($page, $adminPages)) {
      echo "<li";
      if ($page === $page_en_cours)
        echo " class='active'";
      echo "><a href='$page.php'>$nom</a></li>";
    }
  }
}

function create_row($ref, $title, $content, $messages) {
  //Retourne une ligne du tableau avec le contenu passé en param

  echo "<tr class='center-icon'>";
  //On ajoute une colonne avec la reference
  echo "<td class='cell-ref indexable' style='text-align: center;'>
              <span class='label label-info' style='font-size: 12px'>$ref</span>
            </td>";
  //une colonne qui contiendra le titre et le contenu
  echo "<td  style='padding-bottom: 20px;'>
              <h3 data-toggle='collapse' data-target='#details-$ref'>
                <span  class='indexable' rel='tooltip' data-original-title='{$messages['collapse']}'>$title</span>
              </h3>";

  //On va ajouter le détails
  echo "<div id='details-$ref' class='collapse'>";
  $i = 0;
  foreach($content as $type => $info) {
    if($i != 0) echo "<br/>\n";
    echo "<h4 style='display: inline;'>$type: </h4><span class='indexable'>$info</span>";
    $i++;
  }
  echo "</div>
        </td>\n";

  //Les actions (modifier, supprimer)
  echo "<td class='cell-icon'><i rel='tooltip' data-original-title='{$messages['edit']}' class='icon-pencil edit' ref='$ref'></i></td>\n";
  echo "<td class='cell-icon'><i rel='tooltip' data-original-title='{$messages['remove']}' class='icon-remove remove' ref='$ref'></i></td>\n";
  echo "</tr>\n";
}

?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Fête de la science - interface d'administration</title>
    <link href="/fetedelascience/style/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style>
      body {
        padding-top: 40px;
      }
      .row {
        padding-top: 10px;
      }
      .cell-icon {
        width: 80px;
      }
      .center-icon .cell-icon {
        text-align: center;
      }
      .search-query {
        height: 2em;
      }
      .cell-ref {
        width: 100px;
        text-align: center;
      }
      table h3 span {
        cursor: pointer;
      }
    </style>
  </head>

  <body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="index.php">Fête de la science</a>
          <div class="nav-collapse">
            <ul class="nav">
              <?php echo_menu(); ?>
            </ul>
            <div class="pull-right">
              <span class="label label-inverse">Utilisateur connecté: <?php echo $adminLogin; ?></span>
              <a class="btn" href="index.php?logout"><i class="icon-user"></i> Déconnexion</a>
            </div>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div> <!-- fin navbar -->

    <?php
    if(isset($template)) {
      include "templates/$template";
    } else {
      include 'templates/accueil.php';
    }
    ?>

  </body>
</html>
