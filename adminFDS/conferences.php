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
// d'abord on crée la clause WHERE qui dépend des branches auxquelles
// l'administrateur connecté à accès
$refs_autorisees = array();
foreach ($adminBranches as $branche)
  $refs_autorisees[] = "`ref_conf` LIKE '%-$branche%'";
$refs_autorisees = implode(" OR ", $refs_autorisees);

$req = tx_query("SELECT ref_conf, titre, public1, materiel, resume, refa1, refa2, refa3 FROM conferences WHERE $refs_autorisees ORDER BY ref_conf");

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
    $ref = explode(" ", $data['ref_conf']);
    $ref = $ref[0];

    $auteurs = lister_auteurs($data);
    $contenu = array($auteurs[0] => $auteurs[1],
                     'Public' => $data['public1'],
                     'Matériel nécessaire' => $data['materiel'],
                     'Résumé' => nl2br(stripslashes($data['resume'])));
    $messages = array('collapse' => 'Voir les détails de cette conférence',
                      'edit' => 'Modifier cette conférence',
                      'remove' => 'Supprimer cette conférence');
    create_row($ref, stripslashes($data['titre']), $contenu, $messages);
  }
}

function lister_auteurs($data) {
  for ($i=1;$i<=3;$i++) {
    $ref = $data["refa$i"];
    if ($ref != 0)
      $auteurs[] = _auteur($ref);
  }
  if (count($auteurs) > 1) {
    return array("Auteurs", implode(',', $auteurs));
  } else {
    return array("Auteur", $auteurs[0]);
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
           $('#refConf').val(result.ref_conf);
           var branche = /V-([A-Z]{2,8})\d*/.exec(result.ref_conf);
           $('#brancheConf').val(branche[1]);
           $('#auteur1').val(result.refa1);
           $('#auteur2').val(result.refa2);
           $('#auteur3').val(result.refa3);
           $('#titreConf').val(htmlDecode(result.titre));
           $('#resumeConf').val(htmlDecode(result.resume));
           $('#publicConf').val(htmlDecode(result.public1));
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
  global $adminBranches; //PHP sapu

  $req = tx_query("SELECT nom, prenom, ref FROM acteurs ORDER BY nom");
  $options = '\n';
  while($data = mysql_fetch_assoc($req)) {
    $options .= '<option value="' . $data['ref'] . '">' . $data['nom'] . ' ' . $data['prenom'] . '</option>\n';
  }

  $req = tx_query("SELECT * FROM  branches");
  $branchesDispo = "\n";
  while($data = mysql_fetch_assoc($req)) {
    if (in_array($data['branche'], $adminBranches))
        $branchesDispo .= '<option value="' . $data['branche'] . '">' . $data['nom'] . '</option>\n';
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
      <label class="control-label" for="brancheConf">Département</label>
      <div class="controls">
        <select class="input-xlarge" id="brancheConf">
          ' . $branchesDispo . '
        </select>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="auteur1">Auteurs</label>
      <div class="controls">
        <select class="input-xlarge" id="auteur1" name="auteur1">
          ' . $options . '
        </select><br/>
        <select class="input-xlarge" id="auteur1" name="auteur2">
          ' . $options . '
        </select><br/>
        <select class="input-xlarge" id="auteur1" name="auteur3">
          ' . $options . '
        </select>
        <div class="help-block"><a href="auteurs.php?add" target="blank" class="btn btn-primary">Nouvel acteur</a></div>
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
        <input type="text" class="input-xlarge" id="publicConf" name="public">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="resumeConf">Matériel</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="materielConf" name="materiel">
      </div>
    </div>
  </fieldset>
FORM;
}

//Le fichier base se comporte un peu comme un template
//il appelle des fonctions qui doivent être définies avant l'include
include 'base.php';
?>
