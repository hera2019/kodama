<?php
require_once '../include/include_database.php';
require_once( '../include/include_function.php' ); // function and const
require_once( '../frame/head.php' );

$signinmod = 2;
if ( !require_once( '../user/checksign.php' ) ) {
  return;
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

// 生成班级单选按钮
function radioClassFun( $connection ) {
  // 定义保存html代码的变量
  $html = '';
  //get user ID nickname
  $sql = 'SELECT ID, name FROM class';
  $statement = $connection->prepare( $sql );
  $statement->execute();
  $record = $statement->fetchAll( PDO::FETCH_OBJ );
  $bFirst = TRUE;
  foreach ( $record as $record ) {
    if ( $bFirst ) {
      $html .= '
        <li class="RadioCheckboxBtn bg-rose-red btn waves-effect" name="groupClassBtn">
          <input name="groupClass" type="radio" id="groupClass' . $record->ID . '" value=' . $record->ID . ' class="with-gap radio-col-yellow" checked />
          <label style="font-size: 16px;" for="groupClass' . $record->ID . '">' . $record->name . '</label>
        </li>';
    } else {
      # 遍历数组,分别形成不同的单选框html代码
      $html .= '
        <li class="RadioCheckboxBtn bg-white col-rose-red btn waves-effect" name="groupClassBtn">
          <input name="groupClass" type="radio" id="groupClass' . $record->ID . '" value=' . $record->ID . ' class="with-gap radio-col-yellow" />
          <label class="RadioCheckboxTxt" style="font-size: 16px;" for="groupClass' . $record->ID . '">' . $record->name . '</label>
        </li>';
    }
    $bFirst = FALSE;
  }
  // 返回完整的html代码交由浏览器解析
  return $html;
}
?>

<!-- HTML  -->
<!doctype html>
<!-- code by zmq -->
<html lang="en">
<head>
<title>学生集團チェックインページ</title>
<meta charset="utf-8">
<!-- tempusdominus-bootstrap Datetime Picker Css -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="../style/css/tempusdominus-bootstrap-4.css" rel="stylesheet" />
<!-- Multi Select Css -->
<link href="../style/css/multi-select.css" rel="stylesheet">
<style>
/*@import url('https://fonts.googleapis.com/css?family=Noto+Sans+SC&display=swap');*/
ul.CheckUI {
  list-style-type: none;
  margin: 0 0;
  padding: 0 1.5rem;
}
/* WANG CHONGYANG需要140px  */
li.CheckUI {
  display: inline-block;
  width: 183px;
  margin: 0 0;
}
.RadioCheckboxBtn {
  /*padding: 5 25 5 10;*/
  margin: 10px 20px;
}
.RadioCheckboxTxt {
  margin: 2px 0;
}
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

<!-- Body  -->
<body class="bg-info theme-<?= $KODAMA_THEME_COLOR; ?>">
  <div class="container" style="width: auto; margin-left: 15px; margin-right: 15px;">
    <div class="card mt-15">
      <hr>
      <center><h4>学生集團チェックインページ</h4></center>
      <div class="body">
        <ul class="CheckUI">
          <li class="CheckUI"><h6>クラス：</h6></li>
        </ul>
        <ul class="classnamecls CheckUI">
          <div class="demo-radio-button" id="classarea">
            <?php echo radioClassFun($connection);?>
          </div>
        </ul>
        <hr>
        
        <ul class="CheckUI">
          <li class="CheckUI" style="width: 60px; text-align: left;"><h6>名前：</h6></li>
          <li class="CheckUI">
            <input name="AllStudent" type="checkbox" id="allstudent" value=0 class="filled-in chk-col-light-blue" />
            <label class="RadioCheckboxTxt" for="allstudent">すべてを選択</label>
          </li>
        </ul>
        
        <ul class="studentnamecls CheckUI">
          <div class="demo-checkbox" id="studentarea">
            please choose your class.
          </div>
        </ul>
        <hr>
        <form id="infoform" method="POST" action="../dataproc/checkin_proc.php">
          <div class="kodama-texthorli">
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

          <center>
            <h4><p class="col-rose-red" id="message" style="text-align: center;">please check in...</p></h4>
            <div class="form-group">
              <button type="submit" name="submit" id="submit" class="btn bg-<?= $KODAMA_THEME_COLOR; ?> waves-effect" style="padding: 5 40;"><h4>チェックイン</h4></button>
              <input type="hidden" name="mod" id="mod" value="addcheckin" />
              <input type="hidden" name="ID" id="ID" value="" />
            </div>
          </center>
        </form>
        <!--<div class="alert alert-success">
        </div>-->
      </div>
    </div>
  </div>

<!-- script  --> 
<script>
var strQuery = "";
function getProfile(str)
{
	strQuery = str;
	//显示第一个班级学生
	for(var key in strQuery)
	{
		var strHtml = strQuery[key];
		document.getElementById('studentarea').innerHTML = strHtml;
		break;
	}
}
</script> 
<!-- <script type="text/javascript" src="GetStudentRadio.php"></script>一定要放在接收函数之后  -->
<?php
  require_once('GetStudentCheckbox.php');
?>
<script>
  getProfile(<?= $json_string ?>);
</script>
  
<?php require_once('../frame/foot.php'); ?>
  
<script src="../style/js/jquery.validate.js"></script>
<!-- tempusdominus-bootstrap Datetime Picker Css -->
<script src="../style/js/moment-with-locales.js"></script>
<script src="../style/js/tempusdominus-bootstrap-4.js"></script>
<!-- Multi Select Plugin Js -->
<script src="../style/js/jquery.multi-select.js"></script>
<script src="../style/js/jquery.quicksearch.js"></script>
<script src="../style/js/kodama-datetimepicker.js"></script>
<script src="../style/js/kodama-formajaxsubmit.js"></script>
  
<script>
//DOM的onload事件处理函数
$(document).ready(function()
{
  var _kodama_students = {
    studentID: {},
  };

  //获取单选按钮边框外的事件
  $("li[name='groupClassBtn']").on('click', function(e) //name='groupBtn'
  {
    if(!$(e.target).is($('label'))) {
      $(this).children('input').trigger('click');
    }
  });

  //获取单选按钮的数据 //id不可重复
  $("input:radio[name='groupClass']").on('click', function(e)
  {
    for(var key in strQuery)
    {
      if(key == e.currentTarget.id)
      {
        $("input[name='groupClass']").parent().removeClass("bg-rose-red");
        $("input[name='groupClass']").parent().addClass("bg-white col-rose-red");
        e.currentTarget.parentNode.className = "RadioCheckboxBtn bg-rose-red btn waves-effect";

        var strHtml = strQuery[key];
        document.getElementById('studentarea').innerHTML = strHtml;
        document.getElementById('message').innerHTML = "Please check in...";
        break;
      }
    }
    e.stopPropagation(); //禁止触发父控件事件
  });
  // student全选按钮点击事件
  $("#allstudent").on('click', function(e)
  {
    var strHtml = "すべてを選択 ：";
    var el = $("input[name='groupStudent']");
    if(e.currentTarget.checked) {
      el.parent().removeClass("bg-white col-blue-grey");
      el.parent().addClass("bg-light-blue");
      el.prop("checked", true);
    } else {
      el.parent().removeClass("bg-light-blue");
      el.parent().addClass("bg-white col-blue-grey");
      el.prop("checked", false);
      strHtml = "";
    }

    $("input[name='groupStudent']").each(function() {
      var studentid = $(this).val();
      _kodama_students.studentID[studentid] = e.currentTarget.checked;
    });
    $('#ID').val(JSON.stringify(_kodama_students.studentID));

    document.getElementById('message').innerHTML = strHtml;
  });

  //获取复选按钮边框外的事件
  $("#studentarea").on('click', "li[name='groupStudentBtn']", function(e) //name='groupBtn'
  {
    if(!$(e.target).is($('label'))) {
      $(this).children('input').trigger('click');
    }
  });
  
  // student复选按钮点击事件，因为是动态创建，只能这样获得parent事件
  $("#studentarea").on('click', "input[name='groupStudent']", function(e)
  //$("input:checkbox[name='groupStudent']").on('click', function(e)
  {
    var strHtml = "";
    //$("input[name='groupStudent']").parent().removeClass("bg-light-blue");
    //$("input[name='groupStudent']").parent().addClass("bg-white col-blue-grey");
    if(e.currentTarget.checked) {
      strHtml = e.currentTarget.id;
      strHtml += " ：";
      strHtml += e.currentTarget.nextSibling.nextSibling.innerHTML;
      e.currentTarget.parentNode.className = "RadioCheckboxBtn bg-light-blue btn waves-effect";
    } else {
      e.currentTarget.parentNode.className = "RadioCheckboxBtn bg-white col-blue-grey btn waves-effect";
    }

    var studentid = e.currentTarget.value;
    _kodama_students.studentID[studentid] = e.currentTarget.checked;
    $('#ID').val(JSON.stringify(_kodama_students.studentID));

    document.getElementById('message').innerHTML = strHtml;
    e.stopPropagation(); //禁止触发父控件事件
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
</body>
</html>