<?php
fds_entete("Inscription aux conférences");

include_once('aux.php');

$etablissements = db_query("SELECT id, nom, ville FROM etablissements13");
$conferences = db_query("SELECT c.id, i.id AS id_int, c.titre, i.nom, i.prenom
                         FROM conferences13 c JOIN intervenants13 i ON c.intervenant = i.id");
?>
<div class="row-fluid">
  <div class="span12 well well-large">
		<form method="POST" action="conferenceprocess">
                <h2>Choix de la conférence</h2>
                    Conférence : <select name="conf" id="liste-confs" class="input-xxlarge">
                        <option value="-1" selected="selected">Choisissez une conférence</option>
                        <?php
                            while($data = $conferences->fetch_assoc()) {
                                echo '<option value="'.$data['id'].'">'.$data['titre'].' par '.nom_intervenant($data).'</option>';
                            }
                        ?>
                        </select><br/>
                    <div id="div-choix-date">Date : <select name="conf" id="liste-dispos" class="input-xxlarge"></select></div>
                <h2>Informations sur l'établissement</h2>
                    Établissement: 
                    <select name="id_etablissement" id="etablissement" class="input-xxlarge">
                            <option value="-1">- Ajouter un établissement -</option>
                            <option selected="selected">Choisissez un établissement</option>
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
					Informations sur le responsable :
				</h2>
                                Nom: <input type="text" name="resp-nom" size="50"><br/>
                                Prénom: <input type="text" name="resp-prenom" size="50"><br/>
                                Télephone: <input type="text" name="resp-telephone" size="50"><br/>
                                E-mail: <input type="text" name="resp-mail" size="50"><br/>
                <br/><br/>
				<input type=submit value=Envoyer class="btn btn-success btn-large"> 
				<input type=reset value=Annuler class="btn btn-danger btn-large">
            </form>
    </div>
</div>

<?php
$script = <<<'SCRIPT'
    $(function() {
        $("#newEtab").hide();
        $('#div-choix-date').hide();
        $("#etablissement").change(function() {
            if ($(this).val() == -1)
                $("#newEtab").show();
            else
                $("#newEtab").hide();
        });
        $("#liste-confs").change(function() {
            var id = $(this).val();
            if (id == -1)
                $('#div-choix-date').hide();
            else
            $.ajax('/fetedelascience/inscription/ajax?action=dispos&conf-id='+id,
                   {dataType : 'json',
                    success : function(data) {
                        $('#div-choix-date').show();
                        data.forEach(function(truc) {
                            var text = '<option value="'+truc.id+'"';
                            if (truc.res_id != null)
                                text += ' disabled="disabled">Reservé - ';
                            else
                                text += '>';
                            text += ''+truc.jour+' '+truc.periode+'</option>';
                            $('#liste-dispos').append(text);
                        });
                    }});
        });
    });
SCRIPT;

fds_basdepage($script);
?>
