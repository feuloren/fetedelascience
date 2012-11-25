<?php
// Les différentes pages de l'interface admin sont très similaires
// La page conferences.php contient des commentaires sur les différents élements

include_once '_connection.php';
tx_connect();

$page_en_cours = "ateliers";
$template = "table.php";

$txt_bouton = "Ajouter un atelier";

$refs_autorisees = array();
foreach ($adminBranches as $branche)
  $refs_autorisees[] = "`ref_at` LIKE '%-$branche%'";
$refs_autorisees = implode(" OR ", $refs_autorisees);

$req = tx_query("SELECT * FROM ateliers12 WHERE $refs_autorisees ORDER BY ref_at");
$num = mysql_num_rows($req);
if ($num === 0)
  $center = "Aucun atelier enregistré";
else if ($num === 1)
  $center = "Un atelier enregistré";
else
  $center = $num . " ateliers enregistrés";

function echo_table_body() {
  global $req;

  while($data = mysql_fetch_assoc($req)) {
    $ref = explode(" ", $data['ref_at']);
    $ref = $ref[0];

    $auteurs = auteurs($data);
    $contenu = array($auteurs[0]        => $auteurs[1],
                     'Sujet'            => nl2br(stripslashes($data['sujet'])),
                     'Résumé'           => nl2br(stripslashes($data['resume'])),
                     'Lieu'             => nl2br(stripslashes($data['lieu'])),
                     'Contraintes'      => nl2br(stripslashes($data['contraintes'])),
                     'Grilles'          => $data['grilles'],
                     'Tables'           => $data['tables'],
                     'Chaises'          => $data['chaises'],
                     'VidéoProjecteurs' => $data['videoprojs'],
                     'Écrans'           => $data['ecrans'],
                     'Ordinateurs'      => $data['ordinateurs'],
                     'Bancs'            => $data['bancs'],
                     'Électricte'       => $data['electricite'],
                     'Matériel en plus' => nl2br(stripslashes($data['materiel'])));
    $messages = array('collapse' => 'Voir les détails de cet atelier',
                      'edit' => 'Modifier cet atelier',
                      'remove' => 'Supprimer cet atelier');
    create_row($ref, stripslashes($data['titre']), $contenu, $messages);
  }
}

function auteurs($data) {
  $auteurs = array();
  for ($i=1;$i<=2;$i++) {
    $ref = $data["resp$i"];
    if ($ref != 0 and $ref != 295)
      $auteurs[] = _auteur($ref);
  }
  if (count($auteurs) == 0) {
    return array("Responsable", "Aucun Responsable !");
  }
  else if (count($auteurs) > 1) {
    return array("Responsables", implode(',', $auteurs));
  } else {
    return array("Responsable", $auteurs[0]);
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
           $('#refAt').val(result.ref_at);
           var branche = /V-([A-Z]{2,8})\d*/.exec(result.ref_at);
           $('#brancheAt').val(branche[1]);
           $('#auteur1').val(result.resp1);
           $('#auteur2').val(result.resp2);
           $('#titreAt').val(htmlDecode(result.titre));
           $('#sujetAt').val(htmlDecode(result.sujet));
           $('#resumeAt').val(htmlDecode(result.resume));
           $('#lieu').val(htmlDecode(result.lieu));
           $('#contraintesLoc').val(htmlDecode(result.contraintes));
           $('#grilles').val(result.grilles);
           $('#tables').val(result.tables);
           $('#chaises').val(result.chaises);
           $('#vp').val(result.videoprojs);
           $('#ecrans').val(result.ecrans);
           $('#ordies').val(result.ordinateurs);
           $('#bancs').val(result.bancs);
           if (result.electricte == 1) {
               $('#electricite').attr(\"checked\", \"checked\");
           }
           $('#electricite').val(result.electricite);
           $('#autre').val(result.materiel);
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
  global $adminBranches; //PHP sapu

  $req = tx_query("SELECT nom, prenom, ref FROM acteurs ORDER BY nom");
  $auteurs = '\n';
  while($data = mysql_fetch_assoc($req)) {
    $auteurs .= '<option value="' . $data['ref'] . '">' . $data['nom'] . ' ' . $data['prenom'] . '</option>\n';
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
      <label class="control-label" for="refAt">Référence</label>
      <div class="controls">
        <input type="input-medium" class="input-xlarge" id="refAt" name="ref"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="brancheAt">Département</label>
      <div class="controls">
        <select class="input-xlarge" id="brancheAt">
          ' . $branchesDispo . '
        </select>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="auteur1">Responsables</label>
      <div class="controls">
        <select class="input-xlarge" id="auteur1" name="resp1">
          ' . $auteurs . '
        </select>
        <select class="input-xlarge" id="auteur2" name="resp2">
          ' . $auteurs . '
        </select>
        <div class="help-block"><a href="auteurs.php?add" target="blank" class="btn btn-primary">Nouvel acteur</a></div>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="titreAt">Titre</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="titreAt" name="titre">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="sujetAt">Sujet</label>
      <div class="controls">
        <textarea class="input-xlarge" id="sujetAt" name="sujet"></textarea>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="resumeAt">Résumé</label>
      <div class="controls">
        <textarea class="input-xlarge" id="resumeAt" name="resume"></textarea>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="lieu">Lieu</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="lieu" name="lieu">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="contraintesLoc">Contraintes</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="contraintesLoc" name="contraintesLoc">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="grilles">Grilles</label>
      <div class="controls">
        <input type="text" class="input-mini" id="grilles" name="grilles" value="0"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="tables">Table</label>
      <div class="controls">
        <input type="text" class="input-mini" id="tables" name="tables" value="0"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="chaises">Chaises</label>
      <div class="controls">
        <input type="text" class="input-mini" id="chaises" name="chaises" value="0"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="vp">Vidéo-Projecteurs</label>
      <div class="controls">
        <input type="text" class="input-mini" id="vp" name="vp" value="0"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="ecrans">Écrans</label>
      <div class="controls">
        <input type="text" class="input-mini" id="ecrans" name="ecrans" value="0"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="ordis">Ordinateurs</label>
      <div class="controls">
        <input type="text" class="input-mini" id="ordis" name="ordis" value="0"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="bancs">Bancs</label>
      <div class="controls">
        <input type="text" class="input-mini" id="bancs" name="bancs" value="0"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="electricite">Électricité</label>
      <div class="controls">
        <input type="checkbox" id="electricite" name="electricite"/>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="autre">Autre Matériel</label>
      <div class="controls">
        <textarea class="input-xlarge" id="autre" name="autre"></textarea>
      </div>
    </div>
  </fieldset>
FORM;
}

//Le fichier base se comporte un peu comme un template
//il appelle des fonctions qui doivent être définies avant l'include
include 'base.php';
?>