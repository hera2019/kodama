<!-- Search Bar -->
<?php require_once('head.php'); ?>
<div class="search-bar">
  <div class="search-icon"> <i class="material-icons">search</i> </div>
  <input type="text" placeholder="START TYPING...">
  <div class="close-search"> <i class="material-icons">close</i> </div>
</div>
<!-- #END# Search Bar --> 

<!-- Top Bar -->
<nav class="navbar">
  <div class="container-fluid">
    <div class="navbar-header">
      <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
      <a href="javascript:void(0);" class="bars"></a>
      <a class="navbar-brand" href="javascript:window.location.href='../index.php';">
        <img src="../style/images/kodama-navilogo.png" width="173" height="36" style="margin: -8px 0 0 0;"/></a> </div>
    <div class="collapse navbar-collapse" id="navbar-collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown" style="margin: 10px 0 0 0; padding: 0;">
          <button type="text" class="bg-<?= $KODAMA_THEME_COLOR; ?> btn-circle-lg" data-trigger="focus" data-container="body" data-toggle="popover" data-placement="bottom" title="<?php if(!empty($username)) echo $username; ?>" data-content="<?php if(!empty($useremail)) echo $useremail; ?>">
          <img src="../style/images/user.png" width="60" height="60" alt="User" style="margin: -5px 0 0 -10px;"/></button>
          <i class="material-icons col-white" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i> 
          <ul class="dropdown-menu" style="height: 16rem;">
            <li class="header bg-<?= $KODAMA_THEME_COLOR; ?>" style="margin: 0rem 0.5rem;">
              <?php if(!empty($username)) echo $username; ?><small><?php if(!empty($useremail)) echo '<br>' . $useremail; ?></small></li>
            <li class="body">
              <ul class="menu">
                <li> <a href="../page/useredit.php?ID=<?php if(!empty($userid)) echo $userid; ?>">
                    <div class="icon-circle bg-light-blue"> <i class="material-icons">person</i> </div>
                    <div class="menu-info">
                      <h4>Edit</h4>
                      <p>
                          <i class="material-icons">info_outline</i> Edit user info
                      </p>
                    </div>
                  </a> </li>
                <li> <a href="../user/signout.php">
                    <div class="icon-circle bg-red"> <i class="material-icons">input</i> </div>
                    <div class="menu-info">
                      <h4>Sign Out</h4>
                      <p>
                          <i class="material-icons">info_outline</i> Sign out change to another user
                      </p>
                    </div>
                  </a> </li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
      </ul>
    </div>
  </div>
</nav>
<!-- #Top Bar -->
<?php require_once('foot.php'); ?>
<script src="../style/js/tooltips-popovers.js"></script>