<?php
require_once( 'frame.php' );
require_once( '../include/include_database.php' );
require_once( '../include/include_function.php' );
?>
<head>
<!-- code by zmq -->
<?php
$message = '';
  
$sql = 'SELECT *, s.ID AS ID, s.studentnumber AS studentnumber, s.name AS name, c.name AS classname FROM student AS s LEFT JOIN class AS c ON s.classID = c.ID';
$statement = $connection->prepare($sql);
$statement->execute();
$recordstudent = $statement->fetchAll( PDO::FETCH_OBJ );
$student_class = array(array());
foreach($recordstudent as $recordstudent) {
  $student_class[$recordstudent->classname][$recordstudent->ID] = $recordstudent->studentnumber . ': ' . $recordstudent->name;
}

//签到属性
$sql = 'SELECT ID, description FROM attendproperty ORDER BY ID ASC';
$statement = $connection->prepare($sql);
$statement->execute();
$recordattendproperty = $statement->fetchAll(PDO::FETCH_OBJ);

//查询每日上课时间
$classtimenum = 1;
$classtime11 = '08:00:00';
$classtime12 = '12:00:00';
$lessons1 = 4;
$aheadperiod = 60;
$delayperiod = 60;
$allowlate = 5;
$allowearly = 5;
$sql = 'SELECT * FROM  classtime';
$statement = $connection->prepare( $sql );
$statement->execute();
$recordclasstime = $statement->fetch( PDO::FETCH_OBJ ); //只有一条记录不用fetchAll
if ( $recordclasstime != NULL && $recordclasstime->num != 0 ) {
  $classtimenum = $recordclasstime->num;
  $classtime11 = $recordclasstime->time11;
  $classtime12 = $recordclasstime->time12;
  $lessons1 = $recordclasstime->lessons1;
  $classtime21 = $recordclasstime->time21;
  $classtime22 = $recordclasstime->time22;
  $lessons2 = $recordclasstime->lessons2;
  $classtime31 = $recordclasstime->time31;
  $classtime32 = $recordclasstime->time32;
  $lessons3 = $recordclasstime->lessons3;
  $classtime41 = $recordclasstime->time41;
  $classtime42 = $recordclasstime->time42;
  $lessons4 = $recordclasstime->lessons4;
  $aheadperiod = $recordclasstime->aheadperiod;
  $delayperiod = $recordclasstime->delayperiod;
  $allowlate = $recordclasstime->allowlate;
  $allowearly = $recordclasstime->allowearly;
}
?>
<!-- tempusdominus-bootstrap Datetime Picker Css -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="../style/css/tempusdominus-bootstrap-4.css" rel="stylesheet" />
<!-- Multi Select Css -->
<link href="../style/css/multi-select.css" rel="stylesheet">
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
            <div  style="padding-left: 2rem; padding-right: 2rem;">
              <div id="message" class="alert-warning align-left col-white"><?= $message; ?></div>
            </div>
            <div class="kodama-texthorli">
              <?php
              if(isset($student_class)) {
                echo '<li class="" style="padding: 1rem 2rem;">
                        <span class="text-left">学生を選択：</span>
                        <select id="optgroup" class="ms" multiple="multiple">';
                foreach($student_class as $key => $value) {
                  echo '<optgroup label="' . $key . '">';
                  foreach($value as $key2 => $value2) {
                    echo '<option value="' . $key2 . '">' . $value2 . '</option>';
                  }
                  echo '</optgroup>';
                }
                echo '</select>
                    </li>
                    <br>';
              }
              ?>
              <hr>
              <li class="input-group">
                <span class="input-group-addon">日時：</span>
                <div class="form-line form-group kodama-datetimepicker" id="time_011" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="off" class="form-control datetimepicker-input" data-target="#time_011" data-toggle="datetimepicker" name="time11" style="text-align: left; width: 100%;" value="<?= empty($recordcheckin) ? '' : $recordcheckin->time11; ?>" id="time11" required><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
                <span class="input-group-spinner">から</span>
              </li>
              <li class="input-group">
                <span class="input-group-addon">日時：</span>
                <div class="form-line form-group kodama-datetimepicker" id="time_012" data-target-input="nearest" style="margin-bottom: 0;">
                  <input type="text" autocomplete="off" class="form-control datetimepicker-input" data-target="#time_012" data-toggle="datetimepicker" name="time12" style="text-align: left; width: 100%;" value="<?= empty($recordcheckin) ? '' : $recordcheckin->time12; ?>" id="time12" required><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                </div>
                <span class="input-group-spinner">まで</span>
              </li>
              <li class="input-group">
                <span class="input-group-addon">限目番号：</span>
                <div class="form-line">
                  <input value="<?= empty($recordcheckin) ? '' : $recordcheckin->classindex1; ?>" type="text" class="form-control" name="classindex1" id="classindex1" readonly="readonly">
                </div>
              </li>
              <li class="input-group-select clearfix">
                <span class="input-group-addon">属性：</span>
                <div class="form-line">
                  <select class="kodama-icon-select" name="property1" id="property1">
                    <?php foreach($recordattendproperty as $recordattendproperty1) : ?>
                    <option value="<?= $recordattendproperty1->ID; ?>" <?= empty($recordcheckin) ? '' : ($recordcheckin->property1 == $recordattendproperty1->ID ? ' selected="selected"' : ''); ?>><?= $recordattendproperty1->description; ?></option>
                  <?php endforeach; ?>
                  </select>
                </div>
              </li>
            </div>
            
            <button class="btn btn-block btn-lg bg-<?= $KODAMA_THEME_COLOR; ?> waves-effect" type="submit">確認</button>
            <input type="hidden" name="mod" id="mod" value="addcheckin" />
            <input type="hidden" name="ID" id="ID" value="" />
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
<!-- Multi Select Plugin Js -->
<script src="../style/js/jquery.multi-select.js"></script>
<script src="../style/js/jquery.quicksearch.js"></script>
<script src="../style/js/kodama-datetimepicker.js"></script>
<script src="../style/js/kodama-formajaxsubmit.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  var _kodama_students = {
    studentID: {},
  };
  //Multi-select
  $('#optgroup').multiSelect({
    selectableOptgroup: true,
    //selectableHeader: "<div class='bg-light-blue'>Selectable items</div>",
    //selectionHeader: "<div class='bg-light-green'>Selection items</div>",
    selectableFooter: "<div class='bg-light-blue'>Click select item</div>",
    selectionFooter: "<div class='bg-light-green'>Click deselect item</div>",
    selectableHeader: "<div><input type='text' class='search-input' id='optgroup-selectable-search-input' style='width: 100%;' autocomplete='off' placeholder='Search...'></div><div class='bg-light-blue'>Selectable items: </div>",
    selectionHeader: "<div><input type='text' class='search-input' id='optgroup-selection-search-input' style='width: 100%;' autocomplete='off' placeholder='Search...'></div><div class='bg-light-green'>Selection items: </div>",
    afterInit: function(ms) {
      var that = this,
          $selectableSearch = $('#optgroup-selectable-search-input'),//that.$selectableUl.prev(),
          $selectionSearch = $('#optgroup-selection-search-input'),//that.$selectionUl.prev(),
          selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
          selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

      that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
      .on('keydown', function(e) {
        if (e.which === 40){
          that.$selectableUl.focus();
          return false;
        }
      });

      that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
      .on('keydown', function(e) {
        if (e.which == 40){
          that.$selectionUl.focus();
          return false;
        }
      });
    },
    afterSelect: function(values) {
      this.qs1.cache();
      this.qs2.cache();
      for(var studentid in values) {
        _kodama_students.studentID[values[studentid]] = true;        
      }
      $('#ID').val(JSON.stringify(_kodama_students.studentID));
    },
    afterDeselect: function(values) {
      this.qs1.cache();
      this.qs2.cache();
      for(var studentid in values) {
        _kodama_students.studentID[values[studentid]] = false;
      }
      $('#ID').val(JSON.stringify(_kodama_students.studentID));
    }
  });

  //timechange
  $('#time_011,#time_012,#time_021,#time_022,#time_031,#time_032,#time_041,#time_042').change(function() {
    var idstring = this.id;
    if(idstring.search(/time_0/) == 0) {
      let id = string.substr(6, 1);
      if (parseInt(id).toString() != 'NaN') {
        timechanged($("#time" + id + '1'), $("#time" + id + '2'), $('#classindex' + id), $('#property' + id));
      }
    }
  });
  $('#time_011,#time_012,#time_021,#time_022,#time_031,#time_032,#time_041,#time_042').on('change.datetimepicker', function(e) {
    var idstring = this.id;
    if(idstring.search(/time_0/) == 0) {
      let id = idstring.substr(6, 1);
      if (parseInt(id).toString() != 'NaN') {
        timechanged($("#time" + id + '1'), $("#time" + id + '2'), $('#classindex' + id), $('#property' + id));
      }
    }
  });
  
  function timechanged($time1, $time2, $classindex, $property) {
    let strtime11 = $time1.val();
    let strtime12 = $time2.val();
    let property = [ 0 ];
    let classindex = getClassIndexProperty(strtime11, strtime12, property);
    $classindex.val(classindex);
    $property.val(property[0]);
    
    function getClassIndexProperty(strtime1, strtime2, property) {
      if(strtime1 && strtime1) {
        let time11 = new Date(strtime1); //毫秒级 / 1000
        let time12 = new Date(strtime2);
        let strdate1 = time11.toDateString();
        let strdate2 = time12.toDateString();
        if(strdate1 != strdate2 ) { //判断是否同一天
          return 0;
        }
      }

      property[0] = 0;
      let blate1 = [ false ];
      let blate2 = [ false ];
      let classindex1 = getClassIndex(strtime1, true, blate1);
      let classindex2 = getClassIndex(strtime2, false, blate2);
      if(classindex1 != 0 && classindex2 != 0 && classindex1 == classindex2) { //判断是否同一堂课
        property[0] = 1; //出席
        if(blate1[0] || blate2[0]) {
          property[0] = 6; //迟到早退
        }
      }
      return classindex1;

      function getClassIndex( time, bstart, blate ) {
        if(!time) {
          return 0;
        }
        
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
        let blate1 = [ false ];
        //判断第几课时段
        if ( classtimenum >= 4 ) {
          var betweenTime = isBetweenTime( classtime41, classtime42, time, bstart, blate1 );
          if ( betweenTime ) {
            classindex = 4;
            blate[0] = blate1[0];
          }
        }
        if ( classtimenum >= 3 ) {
          betweenTime = isBetweenTime( classtime31, classtime32, time, bstart, blate1 );
          if ( betweenTime ) {
            classindex = 3;
            blate[0] = blate1[0];
          }
        }
        if ( classtimenum >= 2 ) {
          betweenTime = isBetweenTime( classtime21, classtime22, time, bstart, blate1 );
          if ( betweenTime ) {
            classindex = 2;
            blate[0] = blate1[0];
          }
        }
        if ( classtimenum >= 1 ) {
          betweenTime = isBetweenTime( classtime11, classtime12, time, bstart, blate1 );
          if ( betweenTime ) {
            classindex = 1;
            blate[0] = blate1[0];
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
  }  
});
</script>