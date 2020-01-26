<head>
<!-- code by zmq -->
<?php $INCLUDE_STUDENT_INFO = true; ?>
<?php
require_once( '../include/include_database.php' );
require_once( '../include/include_function.php' );
require_once( 'frame.php' );

$message = '';
$ID = '';
//学生信息
if ( isset( $_COOKIE[ 'KODAMA_STUDENT_INFO' ] ) && !empty( $_COOKIE[ 'KODAMA_STUDENT_INFO' ] ) ) {
  $StudentInfoString = $_COOKIE[ 'KODAMA_STUDENT_INFO' ];
  $StudentInfo = json_decode($StudentInfoString);
  if (!empty( $StudentInfo ) ) {
    $ID = $StudentInfo->studentid;
    $sql = 'SELECT description FROM student WHERE ID=:ID';
    $statement = $connection->prepare( $sql );
    $statement->execute( [ ':ID' => $ID ] );
    $recordstudent = $statement->fetch( PDO::FETCH_OBJ );
    if ( $recordstudent != NULL ) {
    } else {
      $message = "No student info.";
    }
  }
}
?>
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
          <form id="infoform" method="POST" action="../dataproc/student_proc.php">
            <div class="msg" style="padding-bottom: 2rem;"><font class="col-<?= $KODAMA_THEME_COLOR; ?>">
              <?php
              if(empty($ID)) {
                echo 'Please choose a student first. <span class=\'bg-white\'><a href = "../page/studenttable.php">Click here choose a student.</a></span>';
              } else {
                echo 'Edit Student Description:';
              }
              ?></font></div>            
            <div  style="padding-left: 2rem;">
              <div id="message" class="alert-warning align-left col-white" style="line-height: 23px; width: 100%;"><?= $message; ?></div>
            </div>
            <hr>
            <div class="kodama-texthorli">
              <div class="form-group" style="word-wrap: break-word; word-break: break-all;">
                <div class="form-line">
                  <textarea cols="12" rows="8" value="" type="text" class="form-control" name="description" id="text_description" placeholder="Description" autofocus><?= empty($recordstudent) ? '' : $recordstudent->description; ?></textarea>
                </div>
              </div>
            </div>
            
            <button class="btn btn-block btn-lg bg-<?= $KODAMA_THEME_COLOR; ?> waves-effect" type="submit">Submit</button>
            <input type="hidden" name="mod" id="mod" value="updatedescription" />
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
<script src="../style/js/kodama-formajaxsubmit.js"></script>
<!-- Input Mask Plugin Js -->
<script src="../style/js/jquery.inputmask.bundle.js"></script>
<script src="../style/js/kodama-table-student-infoedit.js"></script>
<script type="text/javascript">
  var _studentrecord = { // 默认值需要与html中同样，重置时使用
    'text_description': '',
    'text_ID': '',
  };
  g_records.mod = 'getdescription';
  
  $(document).ready(function() {  
    var $maskedInput = $('.kodama-texthorli');
    $maskedInput.find('.residenceperiod-mask').inputmask('9年mヶ月');
  });
</script>