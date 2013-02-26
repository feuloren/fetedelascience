<?php
// Les différentes pages de l'interface admin sont très similaires
// La page conferences.php contient des commentaires sur les différents élements

include_once '_connection.php';
tx_connect();

$page_en_cours = "intervenants";
$template = "table.php";

$txt_bouton = "Ajouter un intervenant";

$req = tx_query("SELECT * FROM intervenants13");
$num = mysql_num_rows($req);
if ($num === 0)
  $center = "Aucun intervenant enregistré";
else if ($num === 1)
  $center = "Un intervant enregistré";
else
  $center = $num . " intervenants enregistrés";

function echo_table_body() {
  global $req;

  while($data = mysql_fetch_assoc($req)) {
    $ref = $data['id'];

    $contenu = array('Mail'           => $data['mail'],
                     'Téléphone'      => $data['telephone'],
                     'Disponibilités' => '');
    $messages = array('collapse' => 'Voir les détails de cet intervenant',
                      'edit' => 'Modifier cet intervenant',
                      'remove' => 'Supprimer cet intervenant');
    create_row($ref, $data['nom'] . ' ' . $data['prenom'], $contenu, $messages);
  }
}

function echo_script() {
  echo "
  $(function() {

function htmlDecode(value) {
    if (value) {
        return $('<div />').html(value).text();
    } else {
        return '';
    }
}

register_click('.edit', function(ref) {
      $.post(\"_actions.php\", {'page': page_actuelle, 'action': 'pre-modif', 'ref': ref},
         function(text) {
           var result = $.parseJSON(text);
           $.modification = true;

           $('#modalAdd').modal('show');
           $('#nomInt').val(result.nom);
           $('#prenomInt').val(result.prenom);
           $('#telephoneInt').val(result.telephone);
           $('#mailInt').val(result.mail);
         });
  });
  $('.btn').button();
  var modification = false;
  set_ref = function() {
      if ($.modification) return;

      var branche = $('#brancheAt').val()
      $.post('_actions.php', {'page': page_actuelle, 'action': 'generer-ref', 'branche': branche},
             function(text) {
                 if (text != '') {
                     $('#refAt').val(text);
                 }
              });
  };
  set_ref();
  $('#brancheAt').change(set_ref);
});";
}

function echo_add_form() {
  echo <<<FORM
  <fieldset>
   <div class="control-group">
      <label class="control-label" for="nomInt">Nom</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="nomInt" name="nom">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="prenomInt">Prénom</label>
      <div class="controls">
        <input type="text"class="input-xlarge" id="prenomInt" name="prenom"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="telephoneInt">Téléphone</label>
      <div class="controls">
        <input type="text"class="input-xlarge" id="telephoneInt" name="telephone"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="mailInt">Adresse mail</label>
      <div class="controls">
        <input type="text"class="input-xlarge" id="mailInt" name="mail"/>
      </div>
    </div>
  </fieldset>
FORM;
}

//Le fichier base se comporte un peu comme un template
//il appelle des fonctions qui doivent être définies avant l'include
include 'base.php';
?>
