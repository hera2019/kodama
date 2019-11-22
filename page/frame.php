<!DOCTYPE html>
<html>
<?php require_once('../include/include_function.php'); // function and const ?>
<?php require_once('../frame/head.php'); ?>

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