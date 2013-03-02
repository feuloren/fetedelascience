<?php
fds_entete("Inscription au Village");
?>
<div class="row-fluid">
  <div class="span12 well well-large">
		<form method=POST action= inscription/villageprocess >
				<table>
					<tr><td>Etablissement:</td>
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
				<p>
					Informations sur les accompagnateurs :
				</p>
					<div class="row-fluid">
						<div class="span3">
							 Nom: <input type=text name=ac1nom size=50>
						</div>
						<div class="span3">
							Prénom: <input type=text name=ac1prenom size=50>
						</div>
						<div class="span3">
							Télephone: <input type=text name=ac1telephone size=50>
						</div>
						<div class="span3">
							E-mail: <input type=text name=ac1mail size=50>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span3">
							 Nom: <input type=text name=ac1nom size=50>
						</div>
						<div class="span3">
							Prénom: <input type=text name=ac1prenom size=50>
						</div>
						<div class="span3">
							Télephone: <input type=text name=ac1telephone size=50>
						</div>
						<div class="span3">
							E-mail: <input type=text name=ac1mail size=50>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span3">
							 Nom: <input type=text name=ac1nom size=50>
						</div>
						<div class="span3">
							Prénom: <input type=text name=ac1prenom size=50>
						</div>
						<div class="span3">
							Télephone: <input type=text name=ac1telephone size=50>
						</div>
						<div class="span3">
							E-mail: <input type=text name=ac1mail size=50>
						</div>
					</div>
					<p>
						Information complémentaire :
					</p>
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
								<select name="jour">
									<option value="Jour1" > Jour 1</option>
									<option value="Jour2" > Jour 2</option>
									<option value="Jour3" > Jour 3</option>
									<option value="Jour4" > Jour 4</option>
								</select>
						</div>
						<div class="span4">
							Heure d'arrivée: <input type=text name=nombre size=50>
						</div>
						<div class="span4">
							Heure départ: <input type=text name=nombre size=50>
						</div>
					</div>
				<input type=submit value=Envoyer> -
				<input type=reset value=Annuler>
			</form>
  </div>
</div>

<?php
fds_basdepage();
?>
