<?php
require_once('../include.php');
fds_entete("Contact");
?>
<div class="row-fluid">
  <div class="span12 well well-large">
    <p>
      Vous pouvez nous contacter <b>par téléphone : 03.44.23.49.94</b> <br/>
      Ou <b>par mail : fetedelascience AROBASE utc.fr</b> <br/>
      Ou grâce au formulaire ci dessous :
    </p>
  </div>
</div>
<div class="row-fluid">
  <div class="span12 well well-large">
		<form method=POST action= contact/formmail >
			<input type=hidden name=subject value=formmail>
				<table>
					<tr><td>Votre Nom:</td>
					<td><input type=text name=realname size=30></td></tr>
					<tr><td>Votre Email:</td>
					<td><input type=text name=email size=30></td></tr>
					<tr><td>Sujet:</td>
					<td><input type=text name=title size=30></td></tr>
					<tr><td>Votre message:</td>
					<td><textarea COLS=50 ROWS=6 name=comments></textarea></td></tr>
				</table>
				<br> 
				<input type=submit value=Envoyer class="btn btn-success btn-large"> 
				<input type=reset value=Annuler class="btn btn-danger btn-large">
			</form>
  </div>
</div>

<?php
fds_basdepage();
?>
