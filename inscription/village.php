<?php
require_once('../include.php');
add_header('<link href="/fetedelascience/style/bootstrap-timepicker.min.css" rel="stylesheet"/>');
fds_entete("Inscription au Village");

$annee = get_annee();
$etablissements = db_query("SELECT id, nom, ville FROM etablissements WHERE annee LIKE '$annee'");
?>
<div class="row-fluid">
  <div class="span12 well well-large">
		<form method=POST action= "inscriptionclosed" >
                <h2>Informations sur l'établissement</h2>
                    Établissement: 
                    <select name="id_etablissement" id="etablissement">
                            <option value="-1">- Ajouter un établissement -</option>
                            <option value="-2" selected="selected">Choisissez dans la liste</option>
                            <?php
                                while($data = $etablissements->fetch_assoc()) {
                                    echo "<option value=\"" . $data["id"] . "\">" . $data["nom"] . " - " . $data["ville"] . "</option>";
                                }
                            ?>
                        </select>
				<table id="newEtab">
					<tr><td>Nom de l'établissement:</td>
					<td><input type=text name=etablissement size=50></td></tr>
					<tr><td>Adresse (nom de rue):</td>
					<td><input type=text name=rue size=50></td></tr>
					<tr><td>Code Postal :</td>
					<td><input type=text name=cp size=50></td></tr>
					<tr><td>Ville:</td>
					<td><input type=text name=ville size=50></td></tr>
					<tr><td>Fax:</td>
					<td><input type=text name=fax size=50></td></tr>
					<tr><td>Télephone:</td>
					<td><input type=text name=telephone size=50></td></tr>
					<tr><td>Email:</td>
					<td><input type=text name=email size=50></td></tr>
				</table>
				</br>
				<h2>
					Informations sur les accompagnateurs :
				</h2>
					<div class="row-fluid" id="listeIntervenants">
                    </div>
                    <a href="#listeIntervenants" id="btnAddIntervenant" class="btn btn-primary">Ajouter un Accompagnateur</a>
					<h2>
						Information complémentaire :
					</h2>
					<table>
						<tr>
							<td>Niveau: </td>
							<td><input type=text name=niveau size=50></td>
						</tr>
						<tr>
							<td>Nombre d'élèves:</td> 
							<td><input type=text name=nombre size=50></td>
						</tr>
					</table>
					<div class="row-fluid">
						<div class="span4">
							Jour de la visite:
								<select name=jour>
									<option value="2013-10-10" > Jeudi 10 Octobre 2013</option>
									<option value="2013-10-11" > Vendredi 11 Octobre 2013</option>
									<option value="2013-10-12" > Samedi 12 Octobre 2013</option>
									<option value="2013-10-13" > Dimanche 13 Octobre 2013</option>
								</select>
						</div>
						<div class="span4">
							Heure d'arrivée:  
							 <div class="input-append bootstrap-timepicker">
								<input id="timepicker1" type="text" class="input-small" name=heurearrive>
									<span class="add-on"><i class="icon-time"></i></span>
							</div>
						</div>
						<div class="span4">
							Heure départ: 
							<div class="input-append bootstrap-timepicker">
								<input id="timepicker2" type="text" class="input-small" name=heuredepart>
									<span class="add-on"><i class="icon-time"></i></span>
							</div>
						</div>
					</div>
                <br/><br/>
        <input id="nb_acc" type="hidden" name="nb_acc" value="0">
				<input type=submit value=Envoyer class="btn btn-success btn-large"> 
				<input type=reset value=Annuler class="btn btn-danger btn-large">
            </form>
    </div>
</div>

<?php
$script = <<<SCRIPT
    var add_intervenant = function() {
			var nb_intervenants = parseInt($('#nb_acc').val());
        var txt = '<div class="row-fluid">'+
                  '<div class="span3">'+
                  'Nom: <input type=text name=ac'+nb_intervenants+'nom size=50>'+
                  '</div>'+
                  '<div class="span3">'+
                  'Prénom: <input type=text name=ac'+nb_intervenants+'prenom size=50>'+
                  '</div>'+
                  '<div class="span3">'+
                  'Télephone: <input type=text name=ac'+nb_intervenants+'telephone size=50>'+
                  '</div>'+
                  '<div class="span3">'+
                  'E-mail: <input type=text name=ac'+nb_intervenants+'mail size=50>'+
                  '</div>'+
                  '</div>';
        $("#listeIntervenants").append(txt);
        $('#nb_acc').val(nb_intervenants + 1);
    }
    $(function() {
        $("#btnAddIntervenant").click(add_intervenant);
        add_intervenant();
        $("#newEtab").hide();
        $("#etablissement").change(function() {
            if ($(this).val() == -1)
                $("#newEtab").show();
            else
                $("#newEtab").hide();
        });
        $('#timepicker1').timepicker({minuteStep: 15,
				template: 'dropdown',
				showSeconds: false,
				showMeridian: false,
				defaultTime: '08:00 AM',
				});
				$('#timepicker2').timepicker({minuteStep: 15,
				template: 'dropdown',
				showSeconds: false,
				showMeridian: false,
				defaultTime: '13:00 PM',
				});
    });
SCRIPT;

$timepicker = "bootstrap-timepicker.min.js";
include_script($timepicker);

fds_basdepage($script);
?>
