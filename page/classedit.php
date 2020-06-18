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
$mod .= 'classmanage';
if($mod == 'updateclassmanage') { //not addcheckin
  if ( isset( $_GET[ 'ID' ] ) ) {
    $ID = $_GET[ 'ID' ];
    if (!empty($ID)) {
      $sql = 'SELECT * FROM class WHERE ID=:ID';
      $statement = $connection->prepare( $sql );
      $statement->execute( [ ':ID' => $ID ] );
      $record = $statement->fetch( PDO::FETCH_OBJ );
      if ( $record == NULL ) {
        $message = "Class not found.";
      }
    } else {
      $message = "Class ID error.";
    }
  } else {
    $message = "Get class ID error.";
  }
}
?>
<section class="content">
  <div class="container-fluid">
    <div class="signup-box">
      <div class="card">
        <div class="body">
          <form id="infoform" method="POST" action="../dataproc/checkin_proc.php">
            <div class="msg" style="padding-bottom: 2rem;"><font class="col-<?= $KODAMA_THEME_COLOR; ?>">Class Info: <span class="col-rose-red">red</span> item indicates required: <span class=\'bg-white\'><a href = "../page/classmanage.php">Click here choose another record.</a></span></font></div>                     
            <div  style="padding-left: 2rem; padding-right: 2rem;">
              <div id="message" class="alert-warning align-left col-white"><?= $message; ?></div>
            </div>
            <div class="input-group"> <span class="input-group-addon"> <span class="col-rose-red">クラス名：</span> </span>
              <div class="form-line">
                <input value="<?php if(!empty($record)) echo $record->name; ?>" type="text" class="form-control" name="name" placeholder="Class Name" required autofocus>
              </div>
            </div>
            <div class="input-group clearfix">
              <span class="input-group-addon"> 担任教師： </span>
              <div class="form-line">
                <select class="kodama-icon-select" name="classteacherID" id="select_classteacherID" style="border: none; width: 100%;">
                  <option value="0">-- Please select teacher --</option>
                  <?php
                  $sql = 'SELECT ID, name FROM usermanage WHERE isteacher=1';
                  $statement = $connection->prepare($sql);
                  $statement->execute();
                  $recordteachers = $statement->fetchAll( PDO::FETCH_OBJ );
                  foreach($recordteachers as $recordteacher): ?>
                  <option value="<?= $recordteacher->ID ?>" <?= empty($record) ? '' : ($record->classteacherID == $recordteacher->ID ? 'selected="selected"' : ''); ?>><?= $recordteacher->name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="input-group">
              <span class="input-group-addon"> 注記： </span>
              <div class="form-line">
                <input value="<?php if(!empty($record)) echo $record->description; ?>" type="text" class="form-control" name="description" id="description" placeholder="Description">
              </div>
            </div>
            <button class="btn btn-block btn-lg bg-<?= $KODAMA_THEME_COLOR; ?> waves-effect" type="submit">確認</button>
            <input type="hidden" name="mod" id="mod" value="<?= $mod; ?>" />
            <input type="hidden" name="ID" id="ID" value="<?= empty($ID) ? '' : $ID; ?>" />
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="../style/js/jquery.validate.js"></script> 
<script src="../style/js/kodama-formajaxsubmit.js"></script>