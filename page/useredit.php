<!-- code by zmq -->
<?php
require_once( '../include/include_database.php' );
require_once( 'frame.php' );

$message = '';
$name = '';
$nickname = '';
$email = '';
$password = '';
$description = '';
if ( isset( $_GET[ 'ID' ] ) ) {
  $ID = $_GET[ 'ID' ];
  if ( $ID != null && $ID != '' && $ID > 0 ) {

    $sql = 'SELECT * FROM usermanage WHERE ID=:ID';
    $statement = $connection->prepare( $sql );
    $statement->execute( [ ':ID' => $ID ] );
    $record = $statement->fetch( PDO::FETCH_OBJ );
    if ( $record != NULL ) {
      $name = $record->name;
      $nickname = $record->nickname;
      $email = $record->email;
      $password = '';
      $description = $record->description;
    } else {
      $message = "User not found.";
    }
  } else {
    $message = "User ID error.";
  }
} else {
  $message = "Get user ID error.";
}
?>
<section class="content">
  <div class="container-fluid">
    <div class="signup-box">
      <div class="card">
        <div class="body">
          <form id="sign_up" method="POST" action="../user/usereditproc.php<?php if(!empty($ID)) echo "?ID=" . $ID; ?>">
            <div class="msg" style="padding-bottom: 2rem;"><font class="col-<?= $KODAMA_THEME_COLOR; ?>">Edit User Info: <span class="col-rose-red">red</span> icon indicates required. Password blank means no modification.</font></div>
            <div class="input-group"> <span class="input-group-addon"> <i class="material-icons col-rose-red">person</i> </span>
              <div class="form-line">
                <input value="<?php if(!empty($name)) echo $name; ?>" type="text" class="form-control" name="namesurname" placeholder="Name Surname" required autofocus>
              </div>
            </div>
            <div class="input-group"> <span class="input-group-addon"> <i class="material-icons">face</i> </span>
              <div class="form-line">
                <input value="<?php if(!empty($nickname)) echo $nickname; ?>" type="text" class="form-control" name="nickname" placeholder="Nickname">
              </div>
            </div>
            <div class="input-group"> <span class="input-group-addon"> <i class="material-icons col-rose-red">email</i> </span>
              <div class="form-line">
                <input value="<?php if(!empty($email)) echo $email; ?>" type="email" class="form-control" name="email" placeholder="Email Address" required>
              </div>
            </div>
            <div class="input-group"> <span class="input-group-addon"> <i class="material-icons">lock</i> </span>
              <div class="form-line">
                <input value="" type="password" class="form-control" name="password" minlength="6" placeholder="Password">
              </div>
            </div>
            <div class="input-group"> <span class="input-group-addon"> <i class="material-icons">lock</i> </span>
              <div class="form-line">
                <input value="" type="password" class="form-control" name="confirm" minlength="6" placeholder="Confirm Password">
              </div>
            </div>
            <div class="input-group"> <span class="input-group-addon"> <i class="material-icons">book</i> </span>
              <div class="form-line">
                <input value="<?php if(!empty($description)) echo $description; ?>" type="text" class="form-control" name="description" id="description" placeholder="Description">
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
col-rose-red