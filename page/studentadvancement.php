<?php require_once( 'frame.php' ); ?>
<section class="content">
  <div class="container-fluid">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="width: 100%; margin: 2rem 0px 0px 0px; padding: 0px;">
      <div class="card">
        <div class="header bg-<?= $KODAMA_THEME_COLOR; ?>">
          <h2>Welcome<small>Welcome to kodama website!</small> </h2>
        </div>
        <div class="body">
          Welcome <?php if(!empty($username)) echo $username; ?>!<br>
          email: <?php if(!empty($useremail)) echo $useremail; ?><br>
        </div>
      </div>
    </div>
  </div>
</section>