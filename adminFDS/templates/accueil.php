<div class="container">
  <div class="row">
    <div class="span8 offset2 hero-unit">
      <h1>Bienvenue</h1>
      <p>Utilisez le menu supérieur pour administrer les différentes
        catégories<br/>
        Vous pouvez gérer les branches suivantes:
        <ul class="unstyled">
         <?php
           foreach($adminBranches as $branche) {
             echo "<li>$branche</li>";
           }
         ?>
        </ul>
      </p>
    </div>
  </div>
</div>
