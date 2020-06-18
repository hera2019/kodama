<head>
<!-- code by zmq -->
<?php
require_once( '../include/include_database.php' );
require_once( '../include/include_function.php' );
require_once( 'frame.php' );

$message = '';
//遍历表列
$sql = 'SELECT * FROM classtime';
$statement = $connection->prepare( $sql );
$statement->execute();
$recordclasstime = $statement->fetch( PDO::FETCH_OBJ );
if ( $recordclasstime == NULL ) {
  $message = "Classtime info not found.";
}
?>
<!-- tempusdominus-bootstrap Datetime Picker Css -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="../style/css/tempusdominus-bootstrap-4.css" rel="stylesheet" />
<link href="../style/css/bootstrap-spinner.css" rel="stylesheet" />
<style>
  .kodama-texthorli .input-group .input-group-addon,
  .kodama-texthorli .input-group-select .input-group-addon {
    width: 40%;
  }
  .kodama-texthorli .input-group .form-line, 
  .kodama-texthorli .input-group-select .form-line {
    width: 40%;
  }
  .input-group-spinner {
    display: inline-block;
    padding-top: 6px;
    vertical-align: middle;
  }
</style>
</head>

<section class="content">
  <div class="container-fluid">
    <div class="signup-box">
      <div class="card">
        <div class="body">
          <form id="infoform" method="POST" action="../dataproc/setting_proc.php">
            <div class="msg" style="padding-bottom: 2rem;"><font class="col-<?= $KODAMA_THEME_COLOR; ?>">Edit class time info: before modifiy this info, please build クラスのスケジュール first!</font></div>            
            <div  style="padding-left: 2rem;">
              <div id="message" class="alert-warning align-left col-white" style="line-height: 23px; width: 100%;"><?= $message; ?></div>
            </div>
            <hr>
            <div class="kodama-texthorli">
              <!-- 循环自动添加控件 -->
              <li class="input-group-select clearfix">
                <span class="input-group-addon">課程数量選択：</span>
                <div class="form-line">
                  <select class="kodama-icon-select" name="num" id="classnum">
                    <option value="1" <?= empty($recordclasstime) ? '' : ($recordclasstime->num == "1" ? ' selected="selected"' : ''); ?>>1限</option>
                    <option value="2" <?= empty($recordclasstime) ? '' : ($recordclasstime->num == "2" ? ' selected="selected"' : ''); ?>>2限</option>
                    <option value="3" <?= empty($recordclasstime) ? '' : ($recordclasstime->num == "3" ? ' selected="selected"' : ''); ?>>3限</option>
                    <option value="4" <?= empty($recordclasstime) ? '' : ($recordclasstime->num == "4" ? ' selected="selected"' : ''); ?>>4限</option>
                  </select>
                </div>
              </li>
              <br>
              <li class="input-group">
                <span class="input-group-addon">1限目：</span>
                <div class="form-line form-group kodama-timepicker" id="time_011" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_011" data-toggle="datetimepicker" name="time11" style="text-align: left; width: 100%;" value="<?= empty($recordclasstime) ? '' : $recordclasstime->time11; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>                
                <span class="input-group-spinner">から</span>
              </li>
              <li class="input-group">
                <span class="input-group-addon">1限目：</span>
                <div class="form-line form-group kodama-timepicker" id="time_012" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_012" data-toggle="datetimepicker" name="time12" style="text-align: left; width: 100%;" value="<?= empty($recordclasstime) ? '' : $recordclasstime->time12; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
                <span class="input-group-spinner">まで</span>
              </li>
              <li class="input-group">
                <span class="input-group-addon">2限目：</span>
                <div class="form-line form-group kodama-timepicker" id="time_021" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_021" data-toggle="datetimepicker" name="time21" id="time21" style="text-align: left; width: 100%;" value="<?= empty($recordclasstime) ? '' : $recordclasstime->time21; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
                <span class="input-group-spinner">から</span>
              </li>
              <li class="input-group">
                <span class="input-group-addon">2限目：</span>
                <div class="form-line form-group kodama-timepicker" id="time_022" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_022" data-toggle="datetimepicker" name="time22" id="time22" style="text-align: left; width: 100%;" value="<?= empty($recordclasstime) ? '' : $recordclasstime->time22; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
                <span class="input-group-spinner">まで</span>
              </li>
              <li class="input-group">
                <span class="input-group-addon">3限目：</span>
                <div class="form-line form-group kodama-timepicker" id="time_031" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_031" data-toggle="datetimepicker" name="time31" id="time31" style="text-align: left; width: 100%;" value="<?= empty($recordclasstime) ? '' : $recordclasstime->time31; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
                <span class="input-group-spinner">から</span>
              </li>
              <li class="input-group">
                <span class="input-group-addon">3限目：</span>
                <div class="form-line form-group kodama-timepicker" id="time_032" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_032" data-toggle="datetimepicker" name="time32" id="time32" style="text-align: left; width: 100%;" value="<?= empty($recordclasstime) ? '' : $recordclasstime->time32; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
                <span class="input-group-spinner">まで</span>
              </li>
              <li class="input-group">
                <span class="input-group-addon">4限目：</span>
                <div class="form-line form-group kodama-timepicker" id="time_041" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_041" data-toggle="datetimepicker" name="time41" id="time41" style="text-align: left; width: 100%;" value="<?= empty($recordclasstime) ? '' : $recordclasstime->time41; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
                <span class="input-group-spinner">から</span>
              </li>
              <li class="input-group">
                <span class="input-group-addon">4限目：</span>
                <div class="form-line form-group kodama-timepicker" id="time_042" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_042" data-toggle="datetimepicker" name="time42" id="time42" style="text-align: left; width: 100%;" value="<?= empty($recordclasstime) ? '' : $recordclasstime->time42; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
                <span class="input-group-spinner">まで</span>
              </li>
              <li class="input-group spinner" data-trigger="spinner">
                <span class="input-group-addon">アーリーチェックイン時間: </span>
                <div class="form-line">
                  <input value="<?= empty($recordclasstime) ? '' : $recordclasstime->aheadperiod; ?>" type="text" class="form-control" name="aheadperiod" data-rule="quantity" />
                </div>
                <span class="input-group-spinner">
                    <a href="javascript:;" class="spin-up" data-spin="up"><i class="glyphicon glyphicon-chevron-up"></i></a>
                    <a href="javascript:;" class="spin-down" data-spin="down"><i class="glyphicon glyphicon-chevron-down"></i></a>
                </span>
                <span class="input-group-spinner">分</span>
              </li>
              <li class="input-group spinner" data-trigger="spinner">
                <span class="input-group-addon">チェックイン時間の遅延: </span>
                <div class="form-line">
                  <input value="<?= empty($recordclasstime) ? '' : $recordclasstime->delayperiod; ?>" type="text" class="form-control" name="delayperiod">
                </div>
                <span class="input-group-spinner">
                    <a href="javascript:;" class="spin-up" data-spin="up"><i class="glyphicon glyphicon-chevron-up"></i></a>
                    <a href="javascript:;" class="spin-down" data-spin="down"><i class="glyphicon glyphicon-chevron-down"></i></a>
                </span>
                <span class="input-group-spinner">分</span>
              </li>
              <li class="input-group spinner" data-trigger="spinner">
                <span class="input-group-addon">遅い時間に許可時間: </span>
                <div class="form-line">
                  <input value="<?= empty($recordclasstime) ? '' : $recordclasstime->allowlate; ?>" type="text" class="form-control" name="allowlate">
                </div>
                <span class="input-group-spinner">
                    <a href="javascript:;" class="spin-up" data-spin="up"><i class="glyphicon glyphicon-chevron-up"></i></a>
                    <a href="javascript:;" class="spin-down" data-spin="down"><i class="glyphicon glyphicon-chevron-down"></i></a>
                </span>
                <span class="input-group-spinner">分</span>
              </li>
              <li class="input-group spinner" data-trigger="spinner">
                <span class="input-group-addon">アーリーチェックアウトを許可時間: </span>
                <div class="form-line">
                  <input value="<?= empty($recordclasstime) ? '' : $recordclasstime->allowearly; ?>" type="text" class="form-control" name="allowearly">
                </div>
                <span class="input-group-spinner">
                    <a href="javascript:;" class="spin-up" data-spin="up"><i class="glyphicon glyphicon-chevron-up"></i></a>
                    <a href="javascript:;" class="spin-down" data-spin="down"><i class="glyphicon glyphicon-chevron-down"></i></a>
                </span>
                <span class="input-group-spinner">分</span>
              </li>
            </div>
            
            <button class="btn btn-block btn-lg bg-<?= $KODAMA_THEME_COLOR; ?> waves-effect" type="submit">確認</button>
            <input type="hidden" name="mod" id="mod" value="updateclasstime" />
            <input type="hidden" name="ID" id="ID" value="<?= empty($recordclasstime) ? '' : $recordclasstime->ID; ?>" />
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="../style/js/jquery.validate.js"></script>
<!-- tempusdominus-bootstrap Datetime Picker Css -->
<script src="../style/js/moment-with-locales.js"></script>
<script src="../style/js/tempusdominus-bootstrap-4.js"></script>
<script src="../style/js/jquery.spinner.js"></script>
<script src="../style/js/kodama-datetimepicker.js"></script>
<script src="../style/js/kodama-formajaxsubmit.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  let classnum = <?= empty($recordclasstime) ? 1 : $recordclasstime->num; ?>;
  enableTimeControl(classnum);
  $('#classnum').change(function(){ //选中
    let classnum = $("#classnum").val(); //可以用，这就是selected的值
    enableTimeControl(classnum);
  });
  function enableTimeControl(classnum) {
    if(classnum == 1) {
      $("#time21").prop( "disabled", true );
      $("#time22").prop( "disabled", true );
      $("#time31").prop( "disabled", true );
      $("#time32").prop( "disabled", true );
      $("#time41").prop( "disabled", true );
      $("#time42").prop( "disabled", true );
    } else if(classnum == 2) {
      $("#time21").prop( "disabled", false );
      $("#time22").prop( "disabled", false );
      $("#time31").prop( "disabled", true );
      $("#time32").prop( "disabled", true );
      $("#time41").prop( "disabled", true );
      $("#time42").prop( "disabled", true );
    } else if(classnum == 3) {
      $("#time21").prop( "disabled", false );
      $("#time22").prop( "disabled", false );
      $("#time31").prop( "disabled", false );
      $("#time32").prop( "disabled", false );
      $("#time41").prop( "disabled", true );
      $("#time42").prop( "disabled", true );
    } else if(classnum == 4) {
      $("#time21").prop( "disabled", false );
      $("#time22").prop( "disabled", false );
      $("#time31").prop( "disabled", false );
      $("#time32").prop( "disabled", false );
      $("#time41").prop( "disabled", false );
      $("#time42").prop( "disabled", false );
    }
  }
});
</script>