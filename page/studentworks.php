<?php require_once( 'frame.php' ); ?>
<section class="content">
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading bg-<?= $KODAMA_THEME_COLOR; ?>">
        <h4><font class="col-white">Welcome<br>
          <small><font class="col-white">Welcome to kodama website!</font></small></font></h4>
      </div>
      <div class="panel-body">Welcome
        <?php if(!empty($username)) echo $username; ?>
        !<br>
        email:
        <?php if(!empty($useremail)) echo $useremail; ?>
        <br>
      </div>
    </div>
  </div>
</section>