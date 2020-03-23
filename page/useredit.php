<head>
<!-- code by zmq -->
<?php
require_once( '../include/include_database.php' );
require_once( 'frame.php' );

$message = '';
$record = NULL;
$mod = 'update';
$ID = '';
if ( isset( $_GET[ 'mod' ] ) && !empty( $_GET[ 'mod' ] ) ) {
  $mod = $_GET[ 'mod' ];
}
$mod .= 'user';
if($mod == 'updateuser') { //not addcheckin
  if ( isset( $_GET[ 'ID' ] ) ) {
    $ID = $_GET[ 'ID' ];
    if (!empty($ID)) {
      $sql = 'SELECT * FROM usermanage WHERE ID=:ID';
      $statement = $connection->prepare( $sql );
      $statement->execute( [ ':ID' => $ID ] );
      $record = $statement->fetch( PDO::FETCH_OBJ );
      if ( $record == NULL ) {
        $message = "User not found.";
      }
    } else {
      $message = "User ID error.";
    }
  } else {
    $message = "Get user ID error.";
  }
}
?>
<!-- tempusdominus-bootstrap Datetime Picker Css -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="../style/css/tempusdominus-bootstrap-4.css" rel="stylesheet" />
<style>
.kodama-divtable, .kodama-divtable .intable {
    display:table;
    width:100%;
}
.kodama-divtable .input-group {
    width:80%;
}
.kodama-divtable .cell {
    display:table-cell;
}
.kodama-divtable .row {
    display:table-row;
}
.kodama-divtable .cell {
}
.kodama-divtable .merged{
  padding: 0;
}
.kodama-divtable .form-group {
  width: 90%;
}
.error {
  text-align: center;
}
</style>
</head>

<section class="content">
  <div class="container-fluid">
    <div class="signup-box">
      <div class="card">
        <div class="body">
          <form id="infoform" method="POST" action="../dataproc/user_proc.php">
            <div class="msg" style="padding-bottom: 2rem;"><font class="col-<?= $KODAMA_THEME_COLOR; ?>">User Info: <span class="col-rose-red">red</span> icon indicates required: <span class=\'bg-white\'><a href = "../page/usermanage.php">Click here choose another record.</a></span></font></div>                     
            <div  style="padding-left: 2rem; padding-right: 2rem;">
              <div id="message" class="alert-warning align-left col-white"><?= $message; ?></div>
            </div>
            <br>
            <div class="kodama-texthorli">
              <li class="form-group form-float"> <span class="input-group-addon"> <i class="material-icons col-rose-red">person</i> </span>
                <div class="form-line">
                  <input value="<?php if(!empty($record)) echo $record->username; ?>" type="text" class="form-control" name="username" id="username" required autofocus>
                  <label class="form-label">User Name</label>
                </div>
              </li>
              <br>
              <?php if($mod == 'updateuser'): ?>
              <li class="form-group form-float"> <span class="input-group-addon"> <i class="material-icons">lock</i> </span>
                <div class="form-line">
                  <input value="" type="password" autocomplete="new-password" class="form-control" name="password" id="password" minlength="6">
                  <label class="form-label">Password: empty means no modified</label>
                </div>
              </li>
              <li class="form-group form-float"> <span class="input-group-addon"> <i class="material-icons">lock</i> </span>
                <div class="form-line">
                  <input value="" type="password" class="form-control" name="confirm" id="confirm" minlength="6">
                  <label class="form-label">Confirm Password: empty means no modified</label>
                </div>
              </li>
              <?php else: ?>
              <li class="form-group form-float"> <span class="input-group-addon"> <i class="material-icons col-rose-red">lock</i> </span>
                <div class="form-line">
                  <input value="" type="password" autocomplete="new-password" class="form-control" name="password" id="password" minlength="6" required>
                  <label class="form-label">Password</label>
                </div>
              </li>
              <li class="form-group form-float"> <span class="input-group-addon"> <i class="material-icons col-rose-red">lock</i> </span>
                <div class="form-line">
                  <input value="" type="password" class="form-control" name="confirm" id="confirm" minlength="6" required>
                  <label class="form-label">Confirm Password</label>
                </div>
              </li>
              <?php endif; ?>
              <li class="form-group form-float"> <span class="input-group-addon"> <i class="material-icons col-rose-red">perm_identity</i> </span>
                <div class="form-line">
                  <input value="<?php if(!empty($record)) echo $record->name; ?>" type="text" class="form-control" name="name" id="name" required>
                  <label class="form-label">Name</label>
                </div>
              </li>
              <li class="form-group form-float"> <span class="input-group-addon"> <i class="material-icons col-rose-red">email</i> </span>
                <div class="form-line">
                  <input value="<?php if(!empty($record)) echo $record->email; ?>" type="email" class="form-control" name="email" id="email" required>
                  <label class="form-label">Email Address</label>
                </div>
              </li>
              <li class="input-group">
                <span class="input-group-addon"> <i class="material-icons col-green">pregnant_woman</i> </span>
                <div class="form-line" id="radio_genderfemale">
                  <input name="genderfemale" type="radio" id="radio_001" class="with-gap radio-col-blue" value="0" <?= empty($record) ? '' : ($record->genderfemale ? '' : 'checked="checked"'); ?> />
                  <label for="radio_001">男 Male</label>
                  <input name="genderfemale" type="radio" id="radio_002" class="with-gap radio-col-pink" value="1" <?= empty($record) ? '' : ($record->genderfemale ? 'checked="checked"' : ''); ?> />
                  <label for="radio_002">女 Female</label>
                </div>
              </li>
              <li class="form-group input-group form-float"><!-- input-group确保时间控件弹出来位置正确，否则会按card高度计算 -->
                <span class="input-group-addon"> <i class="material-icons col-green">today</i> </span>
                <div class="form-line form-group kodama-datepicker" id="time_001" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="off" class="form-control datetimepicker-input" data-target="#time_001" data-toggle="datetimepicker" name="birthday" id="time_birthday" style="text-align: left; width: 100%;" value="<?= empty($record) ? '' : $record->birthday; ?>" /><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                  <label class="form-label">Birthday</label>
                </div>
              </li>
              <li class="form-group form-float">
                <span class="input-group-addon"> <i class="material-icons col-green">local_phone</i> </span>
                <div class="form-line">
                  <input value="<?php if(!empty($record)) echo $record->phonenumber; ?>" type="text" class="form-control" name="phonenumber" id="phonenumber">
                  <label class="form-label">Phonenumber</label>
                </div>
              </li>
              <li class="form-group form-float">
                <span class="input-group-addon"> <i class="material-icons col-green">contacts</i> </span>
                <div class="form-line">
                  <input value="<?php if(!empty($record)) echo $record->contactaddress; ?>" type="text" class="form-control" name="contactaddress" id="contactaddress">
                  <label class="form-label">Contactaddress</label>
                </div>
              </li>
              <li class="input-group">
                <span class="input-group-addon"> <i class="material-icons col-green">speaker</i> </span>
                <div class="form-line" id="radio_isteacher">
                  <input name="isteacher" type="radio" id="radio_011" class="with-gap radio-col-grey" value="0" <?= empty($record) ? '' : ($record->isteacher ? '' : 'checked="checked"'); ?> />
                  <label for="radio_011">非教師</label>
                  <input name="isteacher" type="radio" id="radio_012" class="with-gap radio-col-green" value="1" <?= empty($record) ? '' : ($record->isteacher ? 'checked="checked"' : ''); ?> />
                  <label for="radio_012">教師</label>
                </div>
              </li>
              <li class="form-group form-float">
                <span class="input-group-addon"> <i class="material-icons col-green">format_list_numbered</i> </span>
                <div class="form-line">
                  <input value="<?php if(!empty($record)) echo $record->teachernumber; ?>" type="text" class="form-control" name="teachernumber" id="teachernumber">
                  <label class="form-label">Teachernumber</label>
                </div>
              </li>
              <li class="input-group clearfix">
                <span class="input-group-addon"> <i class="material-icons col-green">format_list_numbered</i> </span>
                <div class="form-line">
                  <select class="kodama-icon-select" name="userrights" id="select_userrights" style="border: none; width: 100%;">
                    <option value="0">-- Please select user rights --</option>
                    <?php
                    $sql = 'SELECT ID, name FROM userrights';
                    $statement = $connection->prepare($sql);
                    $statement->execute();
                    $recorduserrights = $statement->fetchAll( PDO::FETCH_OBJ );
                    foreach($recorduserrights as $recorduserrights): ?>
                    <option value="<?= $recorduserrights->ID ?>" <?= empty($record) ? '' : ($record->userrights == $recorduserrights->ID ? 'selected="selected"' : ''); ?>><?= $recorduserrights->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </li>
              <li class="form-group form-float">
                <span class="input-group-addon"> <i class="material-icons col-green">description</i> </span>
                <div class="form-line">
                  <input value="<?php if(!empty($record)) echo $record->description; ?>" type="text" class="form-control" name="description" id="description">
                  <label class="form-label">Description</label>
                </div>
              </li>
            </div>
            
            <button class="btn btn-block btn-lg bg-<?= $KODAMA_THEME_COLOR; ?> waves-effect" type="submit">Submit</button>
            <input type="hidden" name="mod" id="mod" value="<?= $mod; ?>" />
            <input type="hidden" name="ID" id="text_ID" value="<?= $ID; ?>" />
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="../style/js/jquery.validate.js"></script> 
<script src="../style/js/sign-up.js"></script>
<!-- tempusdominus-bootstrap Datetime Picker Css -->
<script src="../style/js/moment-with-locales.js"></script>
<script src="../style/js/tempusdominus-bootstrap-4.js"></script>
<script src="../style/js/kodama-datetimepicker.js"></script>