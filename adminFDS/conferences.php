<?php
include_once '_connection.php';
tx_connect();

/* Fonctions et variables obligatoires
$page_en_cours, $txt_bouton, $center
echo_table_headers(), echo_table_body(), echo_script(); echo_add_form()
*/

$page_en_cours = "conferences";
$template = "table.php";

$txt_bouton = "Ajouter une conférence";

// On prépare la requête sql

$req = tx_query("SELECT c.*, CONCAT(i.prenom, ' ', i.nom) as nom_intervenant
                 FROM conferences c
                 JOIN intervenants i ON c.intervenant = i.id
                 WHERE c.annee = '".get_annee()."'");

// On détermine le nombre d'ateliers dès maintenant car $center
// est utilisé avant que base.php n'appelle echo_table_body()
$num = mysql_num_rows($req);
if ($num === 0)
  $center = "Aucune conférence enregistrée";
else if ($num === 1)
  $center = "Une conférence enregistrée";
else
  $center = $num . " conférences enregistrées";

// Cette fonction est appelée par base.php
// Elle parcoure la requête effectuée plus haut et affiche les lignes
// du tableau
// Comme les colonnes du tableau sont toujours les mêmes on utilise
// la fonction create_row qui prend en paramètres
// la référence, le titre, le contenu (détails de la conférence)
// et les intitulés des boutons adaptés à la page courante
function echo_table_body() {
  global $req;

  while($data = mysql_fetch_assoc($req)) {
    $ref = $data['id'];

    $contenu = array('Intervenant' => $data['nom_intervenant'],
                     'Public' => $data['public'],
                     'Matériel nécessaire' => $data['materiel'],
                     'Résumé' => nl2br(stripslashes($data['resume'])));
    $messages = array('collapse' => 'Voir les détails de cette conférence',
                      'edit' => 'Modifier cette conférence',
                      'remove' => 'Supprimer cette conférence');
    create_row($ref, stripslashes($data['titre']), $contenu, $messages);
  }
}

// Appelée par base.php, permet d'ajouter du javascript spécifique à le page actuelle
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
           $('#refConf').val(result.id);
           $('#intervenantConf').val(result.intervenant);
           $('#brancheConf').val(result.branche);
           $('#titreConf').val(htmlDecode(result.titre));
           $('#resumeConf').val(htmlDecode(result.resume));
           $('#publicConf').val(result.public);
           $('#materielConf').val(htmlDecode(result.materiel));
         });
  });
  $('.btn').button();
  var modification = false;
  set_ref = function() {
      if ($.modification) return;

      var branche = $('#brancheConf').val()
      $.post('_actions.php', {'page': page_actuelle, 'action': 'generer-ref', 'branche': branche},
             function(text) {
                 if (text != '') {
                     $('#refConf').val(text);
                 }
              });
  };
  set_ref();
  $('#brancheConf').change(set_ref);
  $('.btn').button();
});";
}

// Appelée par base.php, crée les éléments de formulaires pour l'ajout d'un nouvel atelier
function echo_add_form() {
  $req = tx_query("SELECT id, CONCAT(prenom, ' ', nom) AS nom FROM intervenants ORDER BY prenom");
  $options = '\n';
  while($data = mysql_fetch_assoc($req)) {
    $options .= '<option value="' . $data['id'] . '">' . $data['nom'] . '</option>\n';
  }

  echo <<<FORM
  <fieldset>
    <div class="control-group">
      <label class="control-label" for="refConf">Référence</label>
      <div class="controls">
        <input type="input-medium" class="input-xlarge" id="refConf" name="ref"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="intervenantConf">Intervenant</label>
      <div class="controls">
        <select class="input-xlarge" id="intervenantConf" name="intervenant">
          ' . $options . '
        </select><br/>
        <div class="help-block"><a href="intervenants.php?add" target="blank" class="btn btn-primary">Nouvel Intervenant</a></div>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="titreConf">Titre</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="titreConf" name="titre">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="resumeConf">Résumé</label>
      <div class="controls">
        <textarea class="input-xlarge" id="resumeConf" name="resume"></textarea>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="resumeConf">Public</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="publicConf"
  name="public">
        <span class="help-block">Primaire et/ou Collège et/ou Lycée;
  séparés par des virgules</span>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="resumeConf">Matériel</label>
      <div class="controls">
        <textarea type="text" class="input-xlarge" id="materielConf" name="materiel"></textarea>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="brancheConf">Branche</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="brancheConf" name="branche">
      </div>
    </div>
  </fieldset>
FORM;
}

//Le fichier base se comporte un peu comme un template
//il appelle des fonctions qui doivent être définies avant l'include
include 'base.php';
?>
