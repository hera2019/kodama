<?php require_once( 'frame.php' ); ?>
<section class="content">
  <div class="container-fluid">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="card">
        <div class="header bg-<?= $KODAMA_THEME_COLOR; ?>">
          <h2>Welcome<small>Welcome to kodama website!</small></h2>
        </div>
        <div class="body">Welcome
          <?php if(!empty($username)) echo $username; ?>
          !<br>
          email:
          <?php if(!empty($useremail)) echo $useremail; ?>
          <br>
        </div>
      </div>
    </div>
  </div>
</section>