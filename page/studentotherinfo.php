<!-- code by zmq -->
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
$ID = '';
  //学生信息
if ( isset( $_COOKIE[ 'KODAMA_STUDENT_INFO' ] ) && !empty( $_COOKIE[ 'KODAMA_STUDENT_INFO' ] ) ) {
  $StudentInfoString = $_COOKIE[ 'KODAMA_STUDENT_INFO' ];
  $StudentInfo = json_decode($StudentInfoString);
  if (!empty( $StudentInfo ) ) {
    $ID = $StudentInfo->studentid;
    $sql = 'SELECT * FROM student2 WHERE ID=:ID';
    $statement = $connection->prepare( $sql );
    $statement->execute( [ ':ID' => $ID ] );
    $recordstudent = $statement->fetch( PDO::FETCH_OBJ );
    if ( $recordstudent != NULL ) {
      $students = get_object_vars($recordstudent);
      console_log($students);
    } else {
      $message = "No student info.";
    }
  }
}

//遍历表列
$sql = 'Select COLUMN_NAME, DATA_TYPE, COLUMN_COMMENT, ORDINAL_POSITION from INFORMATION_SCHEMA.COLUMNS Where table_name = "student2" ORDER BY ORDINAL_POSITION ASC';
$statement = $connection->prepare( $sql );
$statement->execute();
$recordcolumn = $statement->fetchAll( PDO::FETCH_OBJ );
if ( $recordcolumn == NULL ) {
  $message = "table student2 column info not found.";
}
?><head>
<!-- tempusdominus-bootstrap Datetime Picker Css -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="../style/css/tempusdominus-bootstrap-4.css" rel="stylesheet" />
</head>

<section class="content">
  <div class="container-fluid">
    <div class="signup-box">
      <div class="card">
        <div class="body">
          <form id="infoform" method="POST" action="../dataproc/student_proc.php">
            <div class="msg" style="padding-bottom: 2rem;"><font class="col-<?= $KODAMA_THEME_COLOR; ?>">
              <?php
              if(empty($ID)) {
                echo 'Please choose a student first. <span class=\'bg-white\'><a href = "../page/studenttable.php">Click here choose a student.</a></span>';
              } else {
                echo 'Edit Student other Info:';
              }
              ?></font></div>            
            <div  style="padding-left: 2rem;">
              <div id="message" class="alert-warning align-left col-white" style="line-height: 23px; width: 100%;"><?= $message; ?></div>
            </div>
            <hr>
            <div class="kodama-texthorli">
              <!-- 循环自动添加控件 -->
              <?php
              foreach($recordcolumn as $column) {
                if($column->DATA_TYPE == 'text') {
                  echo '<li class="input-group">';
                    echo '<span class="input-group-addon">' . $column->COLUMN_COMMENT . ': </span>';
                    echo '<div class="form-line">';
                    echo '<input value="';
                      $studentvalue = '';
                      if(!empty($students)) {
                        $studentvalue = $students[$column->COLUMN_NAME];
                      }
                      echo $studentvalue;
                      echo '" type="text" class="form-control" name="' . $column->COLUMN_NAME . '">';
                    echo '</div>';
                  echo '</li>';
                } else if($column->DATA_TYPE == 'timestamp') {
                  echo '<li class="input-group">';
                    echo '<span class="input-group-addon">' . $column->COLUMN_COMMENT . ': </span>';
                    echo '<div class="form-line form-group kodama-datepicker" id="time_' . $column->ORDINAL_POSITION . '" data-target-input="nearest" style="margin-bottom: 0;">';
                      echo '<input type="text" autocomplete="nes" class="form-control datetimepicker-input" data-target="#time_' . $column->ORDINAL_POSITION . '" data-toggle="datetimepicker" name="' . $column->COLUMN_NAME . '" style="text-align: left; width: 100%;" value="';                     
                      $studentvalue = '';
                      if(!empty($students)) {
                        $studentvalue = $students[$column->COLUMN_NAME];
                      }
                      echo $studentvalue;
                      echo '">';
                    echo '</div>';
                  echo '</li>';
                }
              }
              ?>
              
              <li class="input-group-select clearfix">
                <div class="form-line">
                  <select class="kodama-icon-select" name="nationalityregion">
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
                  <select class="kodama-icon-select" name="classID" id="classID">
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
                  <select class="kodama-icon-select" name="classteacherID" id="classteacherID">
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
                  <select class="kodama-icon-select" name="status">
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
            <input type="hidden" name="ID" id="ID" value="<?= $ID; ?>" />
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
<script type="text/javascript">
$(document).ready(function(){
  g_classesinfo = <?php echo $php_classesinfo; ?>;
});
</script>
<script src="../style/js/kodama-studentedit.js"></script>