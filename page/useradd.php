<!-- code by zmq -->
<?php
require_once( 'frame.php' );

$message = '';
?>
<section class="content">
  <div class="container-fluid">
    <div class="signup-box">
      <div class="card">
        <div class="body">
          <form id="sign_up" method="POST" action="../user/useraddproc.php">
            <div class="msg" style="padding-bottom: 2rem;"><font class="col-<?= $KODAMA_THEME_COLOR; ?>">Add User Info: <span class="col-rose-red">red</span> icon indicates required.</font></div>
            <div class="input-group"> <span class="input-group-addon"> <i class="material-icons col-rose-red">person</i> </span>
              <div class="form-line">
                <input value="" type="text" class="form-control" name="namesurname" placeholder="Name Surname" required autofocus>
              </div>
            </div>
            <div class="input-group"> <span class="input-group-addon"> <i class="material-icons">face</i> </span>
              <div class="form-line">
                <input value="" type="text" class="form-control" name="nickname" placeholder="Nickname">
              </div>
            </div>
            <div class="input-group"> <span class="input-group-addon"> <i class="material-icons col-rose-red">email</i> </span>
              <div class="form-line">
                <input value="" type="email" class="form-control" name="email" placeholder="Email Address" required>
              </div>
            </div>
            <div class="input-group"> <span class="input-group-addon"> <i class="material-icons col-rose-red">lock</i> </span>
              <div class="form-line">
                <input value="" type="password" class="form-control" name="password" minlength="6" placeholder="Password: empty means no modified" required>
              </div>
            </div>
            <div class="input-group"> <span class="input-group-addon"> <i class="material-icons col-rose-red">lock</i> </span>
              <div class="form-line">
                <input value="" type="password" class="form-control" name="confirm" minlength="6" placeholder="Confirm Password: empty means no modified" required>
              </div>
            </div>
            <div class="input-group"> <span class="input-group-addon"> <i class="material-icons">book</i> </span>
              <div class="form-line">
                <input value="" type="text" class="form-control" name="description" id="description" placeholder="Description">
              </div>
            </div>
            <button class="btn btn-block btn-lg bg-<?= $KODAMA_THEME_COLOR; ?> waves-effect" type="submit">Submit</button>
            <div class="row m-t-10 m-b--20">
              <?php if(!empty($message)): ?>
              <div class="alert alert-alert align-left">
                <p>
                  <?= $message; ?>
                </p>
              </div>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script src="../style/js/jquery.validate.js"></script> 
    <script src="../style/js/sign-up.js"></script> 
  </div>
</section>
