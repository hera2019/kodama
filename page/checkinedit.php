<!-- code by zmq -->
<?php
require_once( '../include/include_database.php' );
require_once( '../include/include_function.php' );
require_once( 'frame.php' );

$message = '';
$mod = 'update';
$ID = '';
if ( isset( $_GET[ 'mod' ] ) && !empty( $_GET[ 'mod' ] ) ) {
  $mod = $_GET[ 'mod' ];
}
$mod .= 'checkin';
if($mod == 'updatecheckin') { //not addcheckin
  //签到信息
  if ( isset( $_GET[ 'ID' ] ) && !empty( $_GET[ 'ID' ] ) ) {
    $ID = $_GET[ 'ID' ];
    //查找记录
    $sql = 'SELECT *, a.ID AS ID, s.studentnumber AS studentnumber, s.name AS studentname FROM attendance AS a LEFT JOIN student AS s ON a.studentID=s.ID WHERE a.ID=:ID';
    $statement = $connection->prepare( $sql );
    $statement->execute( [ ':ID' => $ID ] );
    $recordcheckin = $statement->fetch( PDO::FETCH_OBJ );
    if ( $recordcheckin == NULL ) {
      $message .= "Checkin record not found.";
    }
  }
}

//查询每日上课时间
$classtimenum = 1;
$classtime11 = '08:00:00';
$classtime12 = '12:00:00';
$minutes1 = 240;
$aheadperiod = 60;
$delayperiod = 60;
$allowlate = 0;
$allowearly = 0;
$sql = 'SELECT * FROM  classtime';
$statement = $connection->prepare( $sql );
$statement->execute();
$recordclasstime = $statement->fetch( PDO::FETCH_OBJ ); //只有一条记录不用fetchAll
if ( $recordclasstime != NULL && $recordclasstime->num != 0 ) {
  $classtimenum = $recordclasstime->num;
  $classtime11 = $recordclasstime->time11;
  $classtime12 = $recordclasstime->time12;
  $minutes1 = $recordclasstime->minutes1;
  $classtime21 = $recordclasstime->time21;
  $classtime22 = $recordclasstime->time22;
  $minutes2 = $recordclasstime->minutes2;
  $classtime31 = $recordclasstime->time31;
  $classtime32 = $recordclasstime->time32;
  $minutes3 = $recordclasstime->minutes3;
  $classtime41 = $recordclasstime->time41;
  $classtime42 = $recordclasstime->time42;
  $minutes4 = $recordclasstime->minutes4;
  $aheadperiod = $recordclasstime->aheadperiod;
  $delayperiod = $recordclasstime->delayperiod;
  $allowlate = $recordclasstime->allowlate;
  $allowearly = $recordclasstime->allowearly;
}

function IsBetweenTime( $start, $end, $time, $bstart, &$blate ) { //$bstart上课开始时间，还是下课时间
  global $aheadperiod, $delayperiod, $allowlate, $allowearly;
  $date = date( 'H:i:s', strtotime( $time ) );
  $curTime = strtotime( $date ); //当前时分秒
  $assignstart = strtotime( $start ); //获得指定秒钟时间戳，00:00:00
  $assignend = strtotime( $end ); //获得指定秒钟时间戳，01:00:00
  $result = false;
  $blate = false;
  if ( $curTime > $assignstart - $aheadperiod * 60 
      && $curTime < $assignend + $delayperiod * 60 ) {
    $result = true;
    if($bstart) {
      if ( $curTime - $assignstart > $allowlate * 60 ) {
        $blate = true;
      }
    } else {
      if ( $assignend - $curTime > $allowearly * 60 ) {
        $blate = true;
      }
    }
  }
  return $result;
}

function GetClassIndex( $time, $bstart, &$blate ) {
  global $classtimenum, $aheadperiod, $delayperiod, $allowlate, $allowearly,
          $classtime11, $classtime12,
          $classtime21, $classtime22,
          $classtime31, $classtime32,
          $classtime41, $classtime42;
  $classindex = 0;
  $blate = false;
  //判断第几课时段
  if ( $classtimenum >= 4 ) {
    $isBetweenTime = IsBetweenTime( $classtime41, $classtime42, $time, $bstart, $blate );
    if ( $isBetweenTime ) {
      $classindex = 4;
    }
  }
  if ( $classtimenum >= 3 ) {
    $isBetweenTime = IsBetweenTime( $classtime31, $classtime32, $time, $bstart, $blate );
    if ( $isBetweenTime ) {
      $classindex = 3;
    }
  }
  if ( $classtimenum >= 2 ) {
    $isBetweenTime = IsBetweenTime( $classtime21, $classtime22, $time, $bstart, $blate );
    if ( $isBetweenTime ) {
      $classindex = 2;
    }
  }
  if ( $classtimenum >= 1 ) {
    $isBetweenTime = IsBetweenTime( $classtime11, $classtime12, $time, $bstart, $blate );
    if ( $isBetweenTime ) {
      $classindex = 1;
    }
  }
  return $classindex;
}

function GetProperty( $time1, $time2 ) {
  $date1 = date( 'YYYY-MM-DD', strtotime( $time1 ) );
  $date2 = date( 'YYYY-MM-DD', strtotime( $time2 ) );
  if(strtotime( $date1 ) != strtotime( $date2 )) { //判断是否同一天
    return 0;
  }
  
  $property = 0;
  $blate1 = false;
  $blate2 = false;
  $classindex1 = GetClassIndex($time1, true, $blate1);
  $classindex2 = GetClassIndex($time2, false, $blate2);
  if($classindex1 != 0 && $classindex2 != 0 && $classindex1 == $classindex2) { //判断是否同一堂课
    $property = 1; //出席
    if($blate1 || $blate2) {
      $property = 6; //迟到早退
    }
  }
  
  return $property;
}
?><head>
<!-- tempusdominus-bootstrap Datetime Picker Css -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="../style/css/tempusdominus-bootstrap-4.css" rel="stylesheet" />
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
          <form id="infoform" method="POST" action="../dataproc/checkin_proc.php">
            <div class="msg" style="padding-bottom: 2rem;"><font class="col-<?= $KODAMA_THEME_COLOR; ?>">
              <?php
              if($mod == 'updatecheckin') {
                if(empty($ID)) {
                  echo 'Please choose a student first. <span class=\'bg-white\'><a href = "../page/studenttable.php">Click here choose a student.</a></span>';
                } else {
                  echo 'Edit Student Info: <span class="col-rose-red">red</span> icon indicates required.';
                }
              } else {
                echo 'Add Student Info: <span class="col-rose-red">red</span> icon indicates required.';
              }
              ?></font></div>            
            <div  style="padding-left: 2rem;">
              <div id="message" class="alert-warning align-left col-white" style="line-height: 23px; width: 100%;"><?= $message; ?></div>
              <div id="xhr_progressgrd" class="progress" style="width: 0%;">
                <div id="xhr_progress" class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; display: inline;">
                </div>
              </div>
            </div>
            <hr>
            <div class="kodama-texthorli">
              <?php if($mod == 'updatecheckin'): ?>
              <li class="input-group">
                <span class="input-group-addon">Student Number: </span>
                <div class="form-line">
                  <input value="<?= empty($recordcheckin) ? '' : $recordcheckin->studentnumber; ?>" type="text" class="form-control" name="" disabled>
                </div>
              </li>
              <li class="input-group">
                <span class="input-group-addon">Student Name: </span>
                <div class="form-line">
                  <input value="<?= empty($recordcheckin) ? '' : $recordcheckin->studentname; ?>" type="text" class="form-control" name="" disabled>
                </div>
              </li>
              <?php elseif($mod == 'addcheckin'): ?>
              <li class="input-group-select clearfix">
                <span class="input-group-addon">Student Name: </span>
                <div class="form-line">
                  <select class="kodama-icon-select" name="" disabled>
                    <option value="<?= empty($recordcheckin) ? '' : $recordcheckin->studentID; ?>" <?= empty($recordcheckin) ? '' : ($recordcheckin->studentID == "1" ? ' selected="selected"' : ''); ?>><?= empty($recordcheckin) ? '' : $recordcheckin->studentname; ?></option>
                  </select>
                </div>
              </li>
              <?php endif; ?>
              <li class="input-group">
                <span class="input-group-addon">Class 1 Start: </span>
                <div class="form-line form-group kodama-datetimepicker" id="time_011" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_011" data-toggle="datetimepicker" name="time11" style="text-align: left; width: 100%;" value="<?= empty($recordcheckin) ? '' : $recordcheckin->time11; ?>" id="time11"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
              </li>
              <li class="input-group">
                <span class="input-group-addon">Class 1 End: </span>
                <div class="form-line form-group kodama-datetimepicker" id="time_012" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_012" data-toggle="datetimepicker" name="time12" style="text-align: left; width: 100%;" value="<?= empty($recordcheckin) ? '' : $recordcheckin->time12; ?>" id="time12"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
              </li>
              <li class="input-group">
                <span class="input-group-addon">Class 2 Start: </span>
                <div class="form-line form-group kodama-datetimepicker" id="time_021" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_021" data-toggle="datetimepicker" name="time21" style="text-align: left; width: 100%;" value="<?= empty($recordcheckin) ? '' : $recordcheckin->time21; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
              </li>
              <li class="input-group">
                <span class="input-group-addon">Class 2 End: </span>
                <div class="form-line form-group kodama-datetimepicker" id="time_022" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_022" data-toggle="datetimepicker" name="time22" style="text-align: left; width: 100%;" value="<?= empty($recordcheckin) ? '' : $recordcheckin->time22; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
              </li>
              <li class="input-group">
                <span class="input-group-addon">Class 3 Start: </span>
                <div class="form-line form-group kodama-datetimepicker" id="time_031" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_031" data-toggle="datetimepicker" name="time31" style="text-align: left; width: 100%;" value="<?= empty($recordcheckin) ? '' : $recordcheckin->time31; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
              </li>
              <li class="input-group">
                <span class="input-group-addon">Class 3 End: </span>
                <div class="form-line form-group kodama-datetimepicker" id="time_032" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_032" data-toggle="datetimepicker" name="time32" style="text-align: left; width: 100%;" value="<?= empty($recordcheckin) ? '' : $recordcheckin->time32; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
              </li>
              <li class="input-group">
                <span class="input-group-addon">Class 4 Start: </span>
                <div class="form-line form-group kodama-datetimepicker" id="time_041" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_041" data-toggle="datetimepicker" name="time41" style="text-align: left; width: 100%;" value="<?= empty($recordcheckin) ? '' : $recordcheckin->time41; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
              </li>
              <li class="input-group">
                <span class="input-group-addon">Class 4 End: </span>
                <div class="form-line form-group kodama-datetimepicker" id="time_042" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_042" data-toggle="datetimepicker" name="time42" style="text-align: left; width: 100%;" value="<?= empty($recordcheckin) ? '' : $recordcheckin->time42; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
              </li>
              <li class="input-group">
                <span class="input-group-addon">Record time: </span>
                <div class="form-line form-group kodama-datetimepicker" id="time_001" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_001" data-toggle="datetimepicker" name="recordtime" style="text-align: left; width: 100%;" value="<?= empty($recordcheckin) ? '' : $recordcheckin->recordtime; ?>"><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
              </li>
              <li class="input-group-select clearfix">
                <span class="input-group-addon">Property: </span>
                <div class="form-line">
                  <select class="kodama-icon-select" name="property" id="property">
                    <option value="0" <?= empty($recordcheckin) ? '' : ($recordcheckin->property == "0" ? ' selected="selected"' : ''); ?>>不明</option>
                    <option value="1" <?= empty($recordcheckin) ? '' : ($recordcheckin->property == "1" ? ' selected="selected"' : ''); ?>>出席</option>
                    <option value="2" <?= empty($recordcheckin) ? '' : ($recordcheckin->property == "2" ? ' selected="selected"' : ''); ?>>欠席</option>
                    <option value="3" <?= empty($recordcheckin) ? '' : ($recordcheckin->property == "3" ? ' selected="selected"' : ''); ?>>公欠</option>
                    <option value="4" <?= empty($recordcheckin) ? '' : ($recordcheckin->property == "4" ? ' selected="selected"' : ''); ?>>休学</option>
                    <option value="5" <?= empty($recordcheckin) ? '' : ($recordcheckin->property == "5" ? ' selected="selected"' : ''); ?>>一時帰国</option>
                    <option value="6" <?= empty($recordcheckin) ? '' : ($recordcheckin->property == "6" ? ' selected="selected"' : ''); ?>>遅刻早退</option>
                    <option value="7" <?= empty($recordcheckin) ? '' : ($recordcheckin->property == "7" ? ' selected="selected"' : ''); ?>>休校日</option>
                  </select>
                </div>
              </li>
            </div>
            
            <button class="btn btn-block btn-lg bg-<?= $KODAMA_THEME_COLOR; ?> waves-effect" type="submit">Submit</button>
            <input type="hidden" name="mod" id="mod" value="<?= $mod; ?>" />
            <input type="hidden" name="ID" id="ID" value="<?= empty($ID) ? '' : $ID; ?>" />
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
<script src="../style/js/kodama-formajaxsubmit.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('#time_011').change(function() {
    timechanged();
  });
  $('#time_011').on('change.datetimepicker', function(e) {
    timechanged();
  });
  $('#time_012').change(function() {
    timechanged();
  });
  $('#time_012').on('change.datetimepicker', function(e) {
    timechanged();
  });
  
  function timechanged() {
    let strtime11 = $("#time11").val();
    let strtime12 = $("#time12").val();
    let property = getProperty(strtime11, strtime12);
    $('#property').val(property);
    //let classid = $("#classID").val(); //可以用，这就是selected的值
    //$('#classteacherID').val(teacherid); //设置select的值
  }
  
  function getProperty(strtime1, strtime2) {    
    let time11 = new Date(strtime1); //毫秒级 / 1000
    let time12 = new Date(strtime2);
    let strdate1 = time11.toDateString();
    let strdate2 = time12.toDateString();
    if(strdate1 != strdate2 ) { //判断是否同一天
      return 0;
    }

    let property = 0;
    let blate1 = [ false ];
    let blate2 = [ false ];
    let classindex1 = getClassIndex(strtime1, true, blate1);
    let classindex2 = getClassIndex(strtime2, false, blate2);
    if(classindex1 != 0 && classindex2 != 0 && classindex1 == classindex2) { //判断是否同一堂课
      property = 1; //出席
      if(blate1[0] || blate2[0]) {
        property = 6; //迟到早退
      }
    }
    return property;
    
    function getClassIndex( time, bstart, blate ) {
      let classtimenum = <?= $classtimenum; ?>;
      let aheadperiod = <?= $aheadperiod; ?>;
      let delayperiod = <?= $delayperiod; ?>;
      let allowlate = <?= $allowlate; ?>;
      let allowearly = <?= $allowearly; ?>;
      let classtime11 = "<?= $classtime11; ?>";
      let classtime12 = "<?= $classtime12; ?>";
      let classtime21 = "<?= $classtime21; ?>";
      let classtime22 = "<?= $classtime22; ?>";
      let classtime31 = "<?= $classtime31; ?>";
      let classtime32 = "<?= $classtime32; ?>";
      let classtime41 = "<?= $classtime41; ?>";
      let classtime42 = "<?= $classtime42; ?>";
      let classindex = 0;
      blate[0] = false;
      //判断第几课时段
      if ( classtimenum >= 4 ) {
        var betweenTime = isBetweenTime( classtime41, classtime42, time, bstart, blate );
        if ( betweenTime ) {
          classindex = 4;
        }
      }
      if ( classtimenum >= 3 ) {
        betweenTime = isBetweenTime( classtime31, classtime32, time, bstart, blate );
        if ( betweenTime ) {
          classindex = 3;
        }
      }
      if ( classtimenum >= 2 ) {
        betweenTime = isBetweenTime( classtime21, classtime22, time, bstart, blate );
        if ( betweenTime ) {
          classindex = 2;
        }
      }
      if ( classtimenum >= 1 ) {
        betweenTime = isBetweenTime( classtime11, classtime12, time, bstart, blate );
        if ( betweenTime ) {
          classindex = 1;
        }
      }
      return classindex;
      
      function isBetweenTime( start, end, time, bstart, blate ) { //bstart上课开始时间，还是下课时间        
        let curTime = new Date(time); //毫秒级 / 1000
        let y = curTime.getFullYear();
        let m = curTime.getMonth();
        let d = curTime.getDate();
        let strstart = y + '-' + (m + 1) + '-' + d + ' ' + start;
        let strend = y + '-' + (m + 1) + '-' + d + ' ' + end;
        let assignstart = new Date( strstart ); //获得指定秒钟时间戳，00:00:00
        let assignend = new Date( strend ); //获得指定秒钟时间戳，01:00:00
        let result = false;
        let curTimeValue = curTime.getTime();
        let assignstartValue = assignstart.getTime();
        let assignendValue = assignend.getTime();
        
        if(bstart) {
          if ( curTimeValue > assignstartValue - aheadperiod * 60 * 1000 && curTimeValue < assignendValue ) {
            result = true;
            if ( curTimeValue - assignstartValue > allowlate * 60 * 1000 ) {
              blate[0] = true;
            }
          }
        } else {
          if ( curTimeValue > assignstartValue && curTimeValue < assignendValue + delayperiod * 60 * 1000 ) {
            result = true;
            if ( assignendValue - curTimeValue > allowearly * 60 * 1000 ) {
              blate[0] = true;
            }
          }
        }
        return result;
      }
    }
  }
});
</script>