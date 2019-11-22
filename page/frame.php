<!DOCTYPE html>
<html>
<?php require_once('../frame/head.php'); ?>
<?php
//主题色彩
$KODAMA_THEME_COLOR = 'rose-red';
if ( isset( $_COOKIE[ 'KODAMA_THEME_COLOR' ] ) ) {
  $KODAMA_THEME_COLOR = $_COOKIE[ 'KODAMA_THEME_COLOR' ];
  if(empty($KODAMA_THEME_COLOR)) {
    $KODAMA_THEME_COLOR = 'rose-red';
  }
}
?>
<body class="theme-<?= $KODAMA_THEME_COLOR; ?>">
<?php
if ( !require_once( '../user/checksign.php' ) ) {
  return;
}
?>
<!-- Page Loader -->
<div class="page-loader-wrapper">
  <div class="loader">
    <div class="preloader">
      <div class="spinner-layer pl-rose-red">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div>
        <div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>
    </div>
    <p>Please wait...</p>
  </div>
</div>
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Top Bar -->
<?php require_once('../frame/navibar.php'); ?>
<!-- #Top Bar -->
<section>
  <!-- Left Sidebar -->
  <?php require_once('../frame/leftsiderbar.php'); ?>
  <!-- #END# Left Sidebar -->
  <!-- Right Sidebar -->
  <?php require_once('../frame/rightsiderbar.php'); ?>
  <!-- #END# Right Sidebar -->
</section>
<?php require_once('../frame/foot.php'); ?>
</body>
</html>