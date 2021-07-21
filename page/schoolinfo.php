<?php
require_once( 'frame.php' );
require_once( '../include/include_database.php' );

$message = '';
$record = NULL;
$mod = 'updateschool';
$ID = 1;
$sql = 'SELECT * FROM school WHERE ID=:ID';
$statement = $connection->prepare( $sql );
$statement->execute( [ ':ID' => $ID ] );
$record = $statement->fetch( PDO::FETCH_OBJ );
if ( $record == NULL ) {
  $message = "School info not found.";
}
?>
<section class="content">
  <div class="container-fluid">
    <div class="signup-box">
      <div class="card">
        <div class="body">
          <form id="infoform" method="POST" action="../dataproc/setting_proc.php">
            <div class="msg" style="padding-bottom: 2rem;"><font class="col-<?= $KODAMA_THEME_COLOR; ?>">School Info: <span class="col-rose-red">red</span> item indicates required.</font></div>
            <div  style="padding-left: 2rem; padding-right: 2rem;">
              <div id="message" class="alert-warning align-left col-white"><?= $message; ?></div>
            </div>
            <div class="input-group"> <span class="input-group-addon"> <span class="col-rose-red">学校名称：</span> </span>
              <div class="form-line">
                <input value="<?php if(!empty($record)) echo $record->name; ?>" type="text" class="form-control" name="name" placeholder="学校名称" required autofocus>
              </div>
            </div>
            <div class="input-group">
              <span class="input-group-addon"> 郵便番号： </span>
              <div class="form-line">
                <input value="<?php if(!empty($record)) echo $record->postcode; ?>" type="text" class="form-control" name="postcode" id="postcode" placeholder="郵便番号">
              </div>
            </div>
            <div class="input-group">
              <span class="input-group-addon"> 連絡先： </span>
              <div class="form-line">
                <input value="<?php if(!empty($record)) echo $record->address; ?>" type="text" class="form-control" name="address" id="postcode" placeholder="連絡先">
              </div>
            </div>
            <div class="input-group">
              <span class="input-group-addon"> 電話/ファックス： </span>
              <div class="form-line">
                <input value="<?php if(!empty($record)) echo $record->contact; ?>" type="text" class="form-control" name="contact" id="postcode" placeholder="電話/ファックス">
              </div>
            </div>
            <div class="input-group">
              <span class="input-group-addon"> 校長： </span>
              <div class="form-line">
                <input value="<?php if(!empty($record)) echo $record->master; ?>" type="text" class="form-control" name="master" id="postcode" placeholder="校長">
              </div>
            </div>
            <div class="input-group">
              <span class="input-group-addon"> ウェブサイト： </span>
              <div class="form-line">
                <input value="<?php if(!empty($record)) echo $record->website; ?>" type="text" class="form-control" name="website" id="postcode" placeholder="ウェブサイト">
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