<!-- code by zmq -->
<?php $INCLUDE_STUDENT_INFO = true; ?>
<?php
require_once( '../include/include_database.php' );
require_once( '../include/include_function.php' );
require_once( 'frame.php' );

$baseitem = [
  //'nickname' => array('Nickname', 'face'),
  'lastname' => array('Lastname', 'contacts'),
  'firstname' => array('Firstname', 'contacts'),
  'lastnamefurigana' => array('Lastname Furigana', 'contacts'),
  'firstnamefurigana' => array('Firstname Furigana', 'contacts'),
  'lastnamealphabet' => array('Lastname Alphabet', 'contacts'),
  'firstnamealphabet' => array('Firstname Alphabet', 'contacts'),
  'lastnamemotherland' => array('Lastname Motherland', 'contacts'),
  'firstnamemotherland' => array('Firstname Motherland', 'contacts'),
  'studentnumber' => array('Student Number', 'format_list_numbered'),
  'applicationnumber' => array('Application Number', 'format_list_numbered'),
  'phonenumber' => array('Phone Number', 'local_phone'),
];

$message = '';
$mod = 'update';
$ID = '';
if ( isset( $_GET[ 'mod' ] ) && !empty( $_GET[ 'mod' ] ) ) {
  $mod = $_GET[ 'mod' ];
}
if($mod != 'add') {
  //学生信息
  if ( isset( $_COOKIE[ 'KODAMA_STUDENT_INFO' ] ) && !empty( $_COOKIE[ 'KODAMA_STUDENT_INFO' ] ) ) {
    $StudentInfoString = $_COOKIE[ 'KODAMA_STUDENT_INFO' ];
    $StudentInfo = json_decode($StudentInfoString);
    if (!empty( $StudentInfo ) ) {
      $ID = $StudentInfo->studentid;
      $mod = 'update';
      $sql = 'SELECT * FROM student WHERE ID=:ID';
      $statement = $connection->prepare( $sql );
      $statement->execute( [ ':ID' => $ID ] );
      $recordstudent = $statement->fetch( PDO::FETCH_OBJ );
      if ( $recordstudent != NULL ) {
        $students = get_object_vars($recordstudent);
        console_log($students);
      } else {
        $message = "This student not found.";
      }
    }
  }
}

?><head>
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
</style>
</head>

<section class="content">
  <div class="container-fluid">
    <div class="signup-box">
      <?php
      if(isset($INCLUDE_STUDENT_INFO) && $INCLUDE_STUDENT_INFO) {
        require_once( '../frame/studentinfo.php' );
      }
      ?>
      <div class="card">
        <div class="body">
          <form id="infoform" method="POST" action="../dataproc/student_proc.php">
            <div class="msg" style="padding-bottom: 2rem;"><font class="col-<?= $KODAMA_THEME_COLOR; ?>">
              <?php
              if($mod == 'update') {
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
            <div class="kodama-horli">
              <div class='kodama-divtable'>
                <div class='row'>
                  <div class='intable'>
                    <div class='row'>
                      <div class='cell'>
                        <div class="form-group form-float">
                          <span class="input-group-addon"> <i class="material-icons col-rose-red">person</i> </span>
                          <div class="form-line">
                            <input value="<?= empty($recordstudent) ? '' : $recordstudent->name; ?>" type="text" class="form-control" name="name" id="text_name" required autofocus>
                            <label class="form-label">Name</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='cell'>
                        <div class="form-group">
                          <span class="input-group-addon"> <i class="material-icons col-green">pregnant_woman</i> </span>
                          <div class="form-line" id="radio_genderfemale>
                            <input name="genderfemale" type="radio" id="radio_001" class="with-gap radio-col-blue" value="0" <?= empty($recordstudent) ? '' : ($recordstudent->genderfemale ? '' : 'checked="checked"'); ?> />
                            <label for="radio_001">男 Male</label>
                            <input name="genderfemale" type="radio" id="radio_002" class="with-gap radio-col-pink" value="1" <?= empty($recordstudent) ? '' : ($recordstudent->genderfemale ? 'checked="checked"' : ''); ?> />
                            <label for="radio_002">女 Female</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='cell'>
                        <div class="form-group input-group form-float"><!-- input-group确保时间控件弹出来位置正确，否则会按card高度计算 -->
                          <span class="input-group-addon"> <i class="material-icons col-green">today</i> </span>
                          <div class="form-line form-group kodama-datepicker" id="time_001" data-target-input="nearest" style="margin-bottom: 0;">
                            <input type="text" autocomplete="off" class="form-control datetimepicker-input" data-target="#time_001" data-toggle="datetimepicker" name="birthday" id="time_birthday" style="text-align: left; width: 100%;" value="<?= empty($recordstudent) ? '' : $recordstudent->birthday; ?>" /><!-- autocomplete="off":禁用Chrome自动提示填充,使用随机值，填充但不出现下拉框 -->
                            <label class="form-label">Birthday</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class='row'>
                      <div class='cell'>
                        <div class="form-group form-float" style="word-wrap: break-word; word-break: break-all;">
                          <span class="input-group-addon"> <i class="material-icons col-green">description</i> </span>
                          <div class="form-line">
                            <textarea cols="12" rows="4" value="" type="text" class="form-control" name="description" id="text_description"><?= empty($recordstudent) ? '' : $recordstudent->description; ?></textarea>
                            <label class="form-label">Description</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class='cell merged'>
                    <div align="left">
                      <div class="form-line" id="photo_drag" style="width: 190px;">
                        <form id="formphoto" name="formphoto" enctype="multipart/form-data" method="post" action="../plugin/upload/upload.php" />
                        <input hidden="true" type="text" name="photo" id="photo_photo" value="<?= !empty($recordstudent) && !empty($recordstudent->photo) ? $photo = $recordstudent->photo : ''; ?>" />
                        <img class="photoimage" id="photoimage" autocomplete="off" alt="写真" height="190" src="
                         <?php
                         $photo = $PHOTO_PATH . 'default/empty.jpg';
                         if(!empty($recordstudent)) {
                           if(!empty($recordstudent->photo)) {
                             $photo = $PHOTO_PATH . $recordstudent->photo;
                           } else {
                             if($recordstudent->genderfemale) {
                               $photo = $PHOTO_PATH . 'default/female.jpg';
                             } else {
                               $photo = $PHOTO_PATH . 'default/male.jpg';
                             }
                           }
                         }
                         echo $photo;
                         ?>" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
                            
              <!-- 循环自动添加控件 -->
              <?php foreach($baseitem as $key => $value): ?>
              <li class="form-group form-float">
                <span class="input-group-addon"> <i class="material-icons col-green"><?= $value[1]; ?></i> </span>
                <div class="form-line">
                  <input value="<?php
                    $studentvalue = '';
                    if(!empty($students)) {
                      $studentvalue = $students[$key];
                    } else if($mod == 'add' && $key == 'studentnumber') {
                      //查询学号最大值+1
                      $sql = 'SELECT MAX(studentnumber + 1) AS maxstudentnumber FROM student';
                      $statement = $connection->prepare($sql);
                      $statement->execute();
                      $recordmaxstudentnumber = $statement->fetch( PDO::FETCH_OBJ );
                      if($recordmaxstudentnumber != NULL) {
                        $studentvalue = $recordmaxstudentnumber->maxstudentnumber;
                      }
                    }
                    echo $studentvalue;
                    ?>" type="text" class="form-control" name="<?= $key; ?>" id="text_<?= $key; ?>">
                  <label class="form-label"><?= $value[0]; ?></label>
                </div>
              </li>
              <?php endforeach; ?>
              
              <li class="input-group-select clearfix">
                <span class="input-group-addon"> <i class="material-icons col-green">account_balance</i> </span>
                <div class="form-line">
                  <select class="kodama-icon-select" name="nationalityregion" id="select_nationalityregion">
                    <option value="-1">-- Please select nationalityregion --</option>
                    <?php
                    $sql = 'SELECT typeID, typename FROM idconfig WHERE type="nationalityregion" ORDER BY typeID ASC';
                    $statement = $connection->prepare($sql);
                    $statement->execute();
                    $recordnationalityregion = $statement->fetchAll( PDO::FETCH_OBJ );
                    foreach($recordnationalityregion as $recordnationalityregion): ?>
                    <option value="<?= $recordnationalityregion->typename ?>" <?= empty($recordstudent) ? '' : ($recordstudent->nationalityregion == $recordnationalityregion->typename ? 'selected="selected"' : ''); ?>><?= $recordnationalityregion->typename ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </li>
              <li class="input-group-select clearfix">
                <span class="input-group-addon"> <i class="material-icons col-green">class</i> </span>
                <div class="form-line">
                  <select class="kodama-icon-select" name="classID" id="select_classID">
                    <option value="0">-- Please select class --</option>
                    <?php
                    $sql = 'SELECT ID, name, classteacherID FROM class';
                    $statement = $connection->prepare($sql);
                    $statement->execute();
                    $recordclasses = $statement->fetchAll( PDO::FETCH_OBJ );
                    $php_classesinfo = json_encode($recordclasses);
                    foreach($recordclasses as $recordclass): ?>
                    <option value="<?= $recordclass->ID ?>" <?= empty($recordstudent) ? '' : ($recordstudent->classID == $recordclass->ID ? 'selected="selected"' : ''); ?>><?= $recordclass->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </li>
              <li class="input-group-select clearfix">
                <span class="input-group-addon"> <i class="material-icons col-green">perm_identity</i> </span>
                <div class="form-line">
                  <select class="kodama-icon-select" name="classteacherID" id="select_classteacherID">
                    <option value="0">-- Please select teacher --</option>
                    <?php
                    $sql = 'SELECT ID, name FROM teacher';
                    $statement = $connection->prepare($sql);
                    $statement->execute();
                    $recordteachers = $statement->fetchAll( PDO::FETCH_OBJ );
                    foreach($recordteachers as $recordteacher): ?>
                    <option value="<?= $recordteacher->ID ?>" <?= empty($recordstudent) ? '' : ($recordstudent->classteacherID == $recordteacher->ID ? 'selected="selected"' : ''); ?>><?= $recordteacher->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </li>
              <li class="input-group-select clearfix">
                <span class="input-group-addon"> <i class="material-icons col-green">beenhere</i> </span>
                <div class="form-line">
                  <select class="kodama-icon-select" name="status" id="select_status">
                    <option value="-1">-- Please select status --</option>
                    <?php
                    $sql = 'SELECT typeID, typename FROM idconfig WHERE type="status" ORDER BY typeID ASC';
                    $statement = $connection->prepare($sql);
                    $statement->execute();
                    $recordstatus = $statement->fetchAll( PDO::FETCH_OBJ );
                    foreach($recordstatus as $recordstatus): ?>
                    <option value="<?= $recordstatus->typeID ?>" <?= empty($recordstudent) ? '' : ($recordstudent->status == $recordstatus->typeID ? 'selected="selected"' : ''); ?>><?= $recordstatus->typename ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </li>
            </div>
            
            <button class="btn btn-block btn-lg bg-<?= $KODAMA_THEME_COLOR; ?> waves-effect" type="submit">Submit</button>
            <input type="hidden" name="mod" id="mod" value="<?= $mod; ?>" />
            <input type="hidden" name="ID" id="text_ID" value="<?= $ID; ?>" />
          </form>
          <input hidden="true" type="file" size="32" id="photofile" name="photofile" value="" accept="image/*" />
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
<script src="../style/js/kodama-table-student-infoedit.js"></script>
<script type="text/javascript">
  var _studentrecord = { // 默认值需要与html中同样，重置时使用
    'text_name': '',
    'radio_	genderfemale': '',
    'time_birthday': '',
    'text_description': '',
    'text_lastname': '',
    'text_firstname': '',
    'text_lastnamefurigana': '',
    'text_firstnamefurigana': '',
    'text_lastnamealphabet': '',
    'text_firstnamealphabet': '',
    'text_lastnamemotherland': '',
    'text_firstnamemotherland': '',
    'text_studentnumber': '',
    'text_applicationnumber': '',
    'text_phonenumber': '',
    'photo_photo': '',
    'select_nationalityregion': '',
    'select_classID': '',
    'select_classteacherID': '',
    'select_status': '',
    'text_ID': '',
  };
  g_records.mod = 'get';
    
  $(document).ready(function() {
    var g_classesinfo = {};
    g_classesinfo = <?php echo $php_classesinfo; ?>;

    //班级select 联动 教师select，选中班级，自动选择班主任老师 
    //js或html调用php变量只能放在php文件里面，js文件不可以
    $('#select_classID').change(function(){ //选中班级，自动选择班主任老师
      //$(this).children('option:selected').val();//可以用
      //$("#classID option:selected").val();//也可以用
      let classid = $("#select_classID").val(); //可以用，这就是selected的值
      //console.log(classid);

      let classesinfo = g_classesinfo;
      //console.log(classesinfo);
      let teacherid = 0;
      for(let classinfo of classesinfo) {
        if(classinfo.ID == classid) {
          teacherid = classinfo.classteacherID;
          break;
        }
      }
      $('#select_classteacherID').val(teacherid); //设置select的值
      //$('#classteacherID').selectpicker('val', teacherid); //设置select的值
      //$('#classteacherID').selectpicker('refresh'); //必须刷新才能看到结果
    });
  });
</script>
<script src="../style/js/kodama-formajaxsubmit.js"></script>
<script src="../style/js/kodama-photoupload.js"></script>