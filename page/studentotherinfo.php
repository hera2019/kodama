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
        <?php
        if(isset($INCLUDE_STUDENT_INFO) && $INCLUDE_STUDENT_INFO) {
          require_once( '../frame/studentinfo.php' );
        }
        ?>
      <div class="card">
        <div class="body">
          <form id="infoform" method="POST" action="../dataproc/student2_proc.php">
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
                } else if($column->DATA_TYPE == 'date') {
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
                } else if(($column->COLUMN_NAME == 'course') || 
                          ($column->COLUMN_NAME == 'curriculum') || 
                          ($column->COLUMN_NAME == 'residence') || 
                          ($column->COLUMN_NAME == 'career')) {
                  echo '<li class="input-group-select clearfix">';
                    echo '<span class="input-group-addon">' . $column->COLUMN_COMMENT . ': </span>';
                    echo '<div class="form-line">';
                      echo '<select class="kodama-icon-select" name="' . $column->COLUMN_NAME . '">';
                        echo '<option value="-1">-- Please select --</option>';
                        $sql = 'SELECT typeID, typename FROM idconfig WHERE type="' . $column->COLUMN_NAME . '" ORDER BY typeID ASC';
                        $statement = $connection->prepare($sql);
                        $statement->execute();
                        $recordstatus = $statement->fetchAll( PDO::FETCH_OBJ );
                        foreach($recordstatus as $recordstatus) {
                          $selected = empty($students) ? '' : ($students[$column->COLUMN_NAME] == $recordstatus->typeID ? ' selected="selected"' : '');
                          echo '<option value="' . $recordstatus->typeID . '"' . $selected . '>' . $recordstatus->typename . '</option>';
                        }
                      echo '</select>';
                    echo '</div>';
                  echo '</li>';
                }
              }
              ?>
            </div>
            
            <button class="btn btn-block btn-lg bg-<?= $KODAMA_THEME_COLOR; ?> waves-effect" type="submit">Submit</button>
            <input type="hidden" name="mod" id="mod" value="update" />
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
<script src="../style/js/kodama-studentedit.js"></script>