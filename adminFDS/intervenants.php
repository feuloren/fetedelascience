<?php
// Les différentes pages de l'interface admin sont très similaires
// La page conferences.php contient des commentaires sur les différents élements

include_once '_connection.php';
tx_connect();

$page_en_cours = "intervenants";
$template = "table.php";

$txt_bouton = "Ajouter un intervenant";

$req = tx_query("SELECT * FROM intervenants");
$num = mysql_num_rows($req);
if ($num === 0)
  $center = "Aucun intervenant enregistré";
else if ($num === 1)
  $center = "Un intervant enregistré";
else
  $center = $num . " intervenants enregistrés";

$jours = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
$mois = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre");

function get_disponibilites($id) {
  global $jours, $mois;
  $req = tx_query("SELECT * FROM disponibilites WHERE intervenant = $id ORDER BY jour");
  $dispos = "<br/>"; 
  $dispos .= "<span class='add-dispo' ref='$id'><i class='icon-plus'></i> Ajouter une disponibilité</span>";
  $dispos .= "<br/><br/>"; 
  while ($data = mysql_fetch_assoc($req)) {
    $date_ = strtotime($data['jour']);
    $nom_jour = $jours[date('w', $date_)];
    $nom_mois = $mois[date('n', $date_)];
    $heure = 'de '.substr($data['heureDebut'], 0, 5).' à '.substr($data['heureFin'], 0, 5);
    $dispos .= $nom_jour . ' ' . date('j', $date_) . ' ' . $nom_mois . ' ' . $data['periode'] . ' ' . $heure;
    $dispos .= " <i rel='tooltip' data-original-title='Supprimer cette disponibilité' class='icon-remove dispo-remove' ref='". $data["id"] ."'></i><br/>\n";
  }
  return $dispos;
}

function echo_table_body() {
  global $req;

  while($data = mysql_fetch_assoc($req)) {
    $ref = $data['id'];

    $contenu = array('Mail'           => $data['mail'],
                     'Téléphone'      => $data['telephone'],
                     'Disponibilités' => get_disponibilites($ref));
    $messages = array('collapse' => 'Voir les détails de cet intervenant',
                      'edit' => 'Modifier cet intervenant',
                      'remove' => 'Supprimer cet intervenant');
    create_row($ref, $data['nom'] . ' ' . $data['prenom'], $contenu, $messages);
  }
}

function echo_script() {
  echo "
  $(function() {
register_click('.add-dispo', function(ref) {
    $('#refInt').val(ref);
    $('#action').val('ajouter-dispo');
    $('#formIntervenant').hide();
    $('#formDispo').show();

    $('#modalAdd').modal('show');
});

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

           $('#formIntervenant').show();
           $('#formDispo').hide();

           $('#modalAdd').modal('show');
           $('#refInt').val(result.id);
           $('#nomInt').val(result.nom);
           $('#prenomInt').val(result.prenom);
           $('#telephoneInt').val(result.telephone);
           $('#mailInt').val(result.mail);
         });
  });

register_click('.dispo-remove', function(ref) {
    $.post(\"_actions.php\", {'page': page_actuelle, 'action': 'supprimer-dispo', 'ref': ref},
         function(text) {
            if (text == '') {
               document.location.reload(); 
            } else {
                alert(text);
            }
         });
});

  $('#dateDispo').datepicker({format : 'yyyy-mm-dd'});
  var modification = false;
  set_ref = function() {
      if ($.modification) return;

      $.post('_actions.php', {'page': page_actuelle, 'action': 'generer-ref'},
             function(text) {
                 if (text != '') {
                     $('#refInt').val(text);
                 }
              });
  };
  $('#heureDebut').timepicker({minuteStep: 15,
						template: false,
						showSeconds: false,
						showMeridian: false,
						defaultTime: '08:00 AM',
					});
   $('#heureFin').timepicker({minuteStep: 15,
						template: false,
						showSeconds: false,
						showMeridian: false,
						defaultTime: '13:00 PM',
				});
  set_ref();
});";

}

function echo_add_form() {
  echo <<<FORM
  <fieldset id="formIntervenant">
     <div class="control-group">
      <label class="control-label" for="refInt">Référence</label>
      <div class="controls">
        <input type="input-medium" class="input-xlarge" id="refInt" name="ref"/>
      </div>
    </div>
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
  <fielset id="formDispo" style="display:none;">
     <div class="control-group">
      <label class="control-label" for="dateDispo">Date</label>
      <div class="controls">
        <input type="input-medium" class="input-xlarge" id="dateDispo" name="date"/>
      </div>
    </div>
   <div class="control-group">
      <label class="control-label" for="periodeDispo">Période</label>
      <div class="controls bootstrap-timepicker">
        <select class="input-xlarge" id="periodeDispo" name="periode">
          <option value="matin">Matin</option>
          <option value="aprem">Après-Midi</option>
        </select>
      </div>
    </div>
   <div class="control-group">
      <label class="control-label" for="heureDebut">Heure début</label>
      <div class="controls bootstrap-timepicker">
        <input type="input-medium" id="heureDebut" name="heureDebut"
  class="input-xlarge" />
      </div>
    </div>
   <div class="control-group">
      <label class="control-label" for="heureFin">Heure fin</label>
      <div class="controls">
        <input type="input-medium" id="heureFin" name="heureFin" class="input-xlarge" />
      </div>
    </div>

  </fieldset>
FORM;
}

//Le fichier base se comporte un peu comme un template
//il appelle des fonctions qui doivent être définies avant l'include
include 'base.php';
?>
