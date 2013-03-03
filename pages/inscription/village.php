<?php
fds_entete("Inscription au Village");

$etablissements = db_query("SELECT id, nom, ville FROM etablissements13");
?>
<div class="row-fluid">
  <div class="span12 well well-large">
		<form method=POST action= inscription/villageprocess >
                <h2>Informations sur l'établissement</h2>
                    Établissement: 
                    <select name="id_etablissement" id="etablissement">
                            <option value="-1">- Ajouter un établissement -</option>
                            <option>- - - - -</option>
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
                    <a href="#listeIntervenants" id="btnAddIntervenant" class="btn btn-primary">Ajouter un Intervenant</a>
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
									<option value="Jour1" > Jour 1</option>
									<option value="Jour2" > Jour 2</option>
									<option value="Jour3" > Jour 3</option>
									<option value="Jour4" > Jour 4</option>
								</select>
						</div>
						<div class="span4">
							Heure d'arrivée: <input type=text name=heurearrive size=50>
						</div>
						<div class="span4">
							Heure départ: <input type=text name=heuredepart size=50>
						</div>
					</div>
                <br/><br/>
				<input type=submit value=Envoyer class="btn btn-success btn-large"> 
				<input type=reset value=Annuler class="btn btn-danger btn-large">
            </form>
    </div>
</div>

<?php
$script = <<<'SCRIPT'
    var nb_intervenants = 0;
    var add_intervenant = function() {
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
        nb_intervenants += 1;
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
    });
SCRIPT;

fds_basdepage($script);
?>
