<div class="container">
  <div class="row">
    <h1>Paramètres Fête de la Science</h1>
    <form action="_actions.php" method="POST" class="form-horizontal">
    <input type="hidden" name="page" value="<?php echo $page_en_cours ?>"/>
    <input type="hidden" name="action" value="modifier" />
   <fieldset>
   <?php load_parametres();
         foreach($parametres as $nom => $val): ?>
   <div class="control-group">
    <label class="control-label" for="<?php echo $nom ?>"><?php echo $val[1] ?></label>
    <div class="controls">
      <input id="<?php echo $nom ?>" name="<?php echo $nom ?>" value="<?php echo $val[0] ?>" />
    </div>
   </div>
    <?php endforeach; ?>
    <div class="control-group">
      <div class="controls">
        <input type="submit" class="btn btn-primary" value="Enregistrer" />
      </div>
    </div>
   </fieldset>
    </form>
  </div>
</div>
