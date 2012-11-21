<?php
$req = db_query("SELECT * FROM conferences");

fds_entete("Photos 2012");
?>

<div class="row-fluid">
  <div class="span4">
    <div class="well">
      <ul class="nav nav-list">
        <li class="nav-header">Liste des conférences</li>
        <?php while($data = $req->fetch_assoc()) {
          echo '<li><a href="#">' .$data['titre'] . '</a></li>';
        }
        ?>
        <li class="divider"></li>
        <li><a href="/fetedelascience/inscriptFDS" class="btn btn-success">Inscription</a></li>
      </ul>
    </div> <!-- /well -->
  </div>
  <div class="span8">
  </div>
</div>

<?php
fds_basdepage();
?>
