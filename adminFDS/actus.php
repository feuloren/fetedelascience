<?php
// Les différentes pages de l'interface admin sont très similaires
// La page conferences.php contient des commentaires sur les différents élements

include_once '_connection.php';
tx_connect();

$page_en_cours = "actus";
$template = "table.php";

$txt_bouton = "Ajouter une actualité";

$req = tx_query("SELECT ref_actu, titre, sous_titre, date, resume, texte_complet FROM actualites ORDER BY ref DESC");
$num = mysql_num_rows($req);
if ($num === 0)
  $center = "Aucune actualité !";
else if ($num === 1)
  $center = "Une actualité publiée";
else
  $center = $num . " actualités publiées";

function echo_table_body() {
  global $req;

  while($data = mysql_fetch_assoc($req)) {
    $ref = explode(" ", $data['ref_actu']);
    $ref = $ref[0];

    $contenu = array('Sous-titre' => $data['sous_titre'],
                     'Date' => $data['date'],
                     'Résumé' => "<br/>".nl2br(stripslashes($data['resume'])),
                     'Texte complet' => "<br/>".nl2br(stripslashes($data['texte_complet'])));
    $messages = array('collapse' => 'Voir les détails de cette actualité',
                      'edit' => 'Modifier cette actualité',
                      'remove' => 'Supprimer cette actualité');
    create_row($ref, stripslashes($data['titre']), $contenu, $messages);
  }
}

function echo_script() {

}

function echo_add_form() {
  $req = tx_query("SELECT nom, prenom, ref FROM acteurs ORDER BY nom");
  $options = '\n';
  while($data = mysql_fetch_assoc($req)) {
    $options .= '<option value="' . $data['ref'] . '">' . $data['nom'] . ' ' . $data['prenom'] . '</option>\n';
  }

  echo <<<FORM
  <fieldset>
    <div class="control-group">
      <label class="control-label" for="titreAct">Titre</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="titreAct" name="titre">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="stitreAct">Sous-titre</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="stitreAct" name="sous-titre">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="dateAct">Date</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="dateAct" name="date">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="resumeAct">Résumé</label>
      <div class="controls">
        <textarea class="input-xlarge" id="resumeAct" name="resume"></textarea>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="texteAct">Texte complet</label>
      <div class="controls">
        <textarea class="input-xlarge" id="texteAct" name="texte"></textarea>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="photoAct">Illustration</label>
      <div class="controls">
        <input type="file" class="input-xlarge" id="photoAct" name="photo">
      </div>
    </div>
  </fieldset>
FORM;
}

//Le fichier base se comporte un peu comme un template
//il appelle des fonctions qui doivent être définies avant l'include
include 'base.php';
?>
