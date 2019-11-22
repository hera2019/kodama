<?php
require_once( 'frame.php' );
$title = 'Info';
$info = '';
$bg = $KODAMA_THEME_COLOR;
if ( isset( $_GET[ 'title' ] ) ) {
  $title = $_GET[ 'title' ];
}
if ( isset( $_GET[ 'info' ] ) ) {
  $info = $_GET[ 'info' ];
}
if ( isset( $_GET[ 'bg' ] ) && !empty( $_GET[ 'bg' ] ) ) {
  $bg = $_GET[ 'bg' ];
}
?>
<section class="content">
  <div class="container-fluid">
    <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12">
      <div class="card">
        <div class="header bg-<?= $bg; ?>">
          <h2>
            <?php if(!empty($title)) echo $title; ?>
            <small></small> </h2>
        </div>
        <div class="body">
          <?php if(!empty($info)) echo $info; ?>
        </div>
      </div>
    </div>
  </div>
</section>