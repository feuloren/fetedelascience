<div class="container">
  <div class="row">
    <div class="span4">
      <a class="btn btn-primary" data-target="#modalAdd" data-toggle="modal"><i class="icon-white icon-plus"></i> <?php echo $txt_bouton; ?></a>
      <a class="btn btn-success" href="_actions.php?page=<?php echo $page_en_cours; ?>&action=export-excel">Export Excel</a>
    </div>
    <div class="span3 offset1">
      <p><?php echo $center; ?></p>
    </div>
    <div class="span3 offset1">
      <input class="search-query" type="text" id="filtre" placeholder="Recherche" />
    </div>
  </div>

  <div class="row">
    <div class="alert alert-info">
      <a class="close" data-dismiss="alert" href="#">×</a>
      Cliquez sur un titre pour obtenir plus d'informations
    </div>
  </div>

  <div class="modal hide fade" id="modalAdd">
    <form method="POST" action="_actions.php" class="form-horizontal">
      <input type="hidden" name="page" value="<?php echo $page_en_cours; ?>"/>
      <input type="hidden" name="action" id="action" value="ajouter"/>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h3><?php echo $txt_bouton; ?></h3>
      </div>
      <div class="modal-body">
        <?php echo_add_form(); ?>
      </div>
      <div class="modal-footer">
        <button href="#" class="btn" data-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
    </form>
  </div>

  <div class="row">
    <table class="table table-bordered table-striped
                  table-condensed" id="items">
      <thead>
        <tr>
          <th class='cell-ref'>Référence</th>
          <th>Description</th>
          <th class='cell-icon'>Modifier</th>
          <th class='cell-icon'>Supprimer</th>
        </tr>
      </thead>
      <tbody>
        <?php echo_table_body(); ?>
      </tbody>
    </table>

  </div>
</div> <!-- fin container -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="/fetedelascience/js/bootstrap.min.js"></script>
<script src="/fetedelascience/js/tablefilter.js"></script>
<script src="/fetedelascience/js/bootstrap-datepicker.js"></script>
<script src="/fetedelascience/js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript">
    var page_actuelle = '<?php echo $page_en_cours; ?>';
    var register_click = function(what, call) {
        $(what).click(function(e) {
            call($(this).attr("ref"));
        });
    };

    $(function() {
        $('table i').tooltip();
        $('table h3').tooltip({placement: "right", selector: 'span'});
        $('#modalAdd').modal({show: <?php echo (isset($_GET['add']) ? 'true' : 'false'); ?>});
        $('table > .collapse').collapse();
        $('.alert').alert();
        register_click('.remove', function(ref) {
            $.post("_actions.php", {'page': page_actuelle, 'action': 'supprimer', 'ref': ref},
                   function(text) {
                       if (text=="")
                           document.location.reload();
                       else
                           alert("Une erreur s'est produite\n"+text+"\nMerci de contacter le responsable afin de corriger l'erreur");
                   });
        });

        filterTable('#items','#filtre');
    });

  <?php echo_script(); ?>
</script>
