<?php
require_once( 'frame.php' );
require_once( '../include/include_database.php' );
?>
<head>
<!-- code by zmq -->
<?php $INCLUDE_STUDENT_INFO = true;

$message = '';

//遍历表列
$sql = 'Select COLUMN_NAME, DATA_TYPE, COLUMN_COMMENT, ORDINAL_POSITION from INFORMATION_SCHEMA.COLUMNS Where table_name = "studentscore" ORDER BY ORDINAL_POSITION ASC';
$statement = $connection->prepare( $sql );
$statement->execute();
$recordcolumn = $statement->fetchAll( PDO::FETCH_OBJ );
if ( $recordcolumn == NULL ) {
  $message = "table studentscore column info not found.";
}
?>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="../style/css/tempusdominus-bootstrap-4.css" rel="stylesheet" />
<link href="../style/css/jquery-editable-select.css" rel="stylesheet" />
</head>

<section class="content">
  <div class="container-fluid">
    <div class="row m-t--60">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php
        if(isset($INCLUDE_STUDENT_INFO) && $INCLUDE_STUDENT_INFO) {
          require_once( '../frame/studentinfo.php' );
        }
        ?>
        <div class="card">
          <div class="kodama-header col-<?= $KODAMA_THEME_COLOR; ?>">
            <h2>成績情報<small></small></h2>
            <ul class="header-button">
              <li><a href="javascript:void(0);" onclick="newRecord();">
                <div class="kodama-icon-circle bg-green"> <i class="material-icons">create_new_folder</i> </div>
                <div class="kodama-menu-info">
                  <h4>追加</h4>
                </div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="refreshRecord();">
                <div class="kodama-icon-circle bg-orange"> <i class="material-icons">query_builder</i> </div>
                <div class="kodama-menu-info">
                  <h4>リロード</h4>
                </div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="saveRecord();">
                <div class="kodama-icon-circle bg-light-blue"> <i class="material-icons">save</i> </div>
                <div class="kodama-menu-info">
                  <h4>保存</h4>
                </div>
              </a></li>
            </ul>
          </div>
          <div class="body">
            <table id="mainTable" class="kodama-formtable table table-bordered kodama-formtable-bordered text-center">
              <caption><div class="text-left alert-warning align-left col-white" id="message"><?= $message; ?></div></caption>
              <thead>
                <tr class="bg-<?= $KODAMA_THEME_COLOR; ?>">
                <?php
                foreach($recordcolumn as $column) {
                  if(($column->COLUMN_NAME == 'ID') || 
                    ($column->COLUMN_NAME == 'studentID')) {
                    continue;
                  }
                  echo '<th class="col-xs-1 text-center">' . $column->COLUMN_COMMENT . '</th>';
                }
                ?>
                </tr>
              </thead>
              <tbody id='tbody'>
                <?php
                
                for($i=1; $i<=24; $i++) {
                  if($i == 1) {
                    echo '<tr id=recordrow' . $i . '>';
                  } else {
                    echo '<tr id=recordrow' . $i . ' hidden="hidden">';
                  }
                  foreach($recordcolumn as $column) {
                    if(($column->COLUMN_NAME == 'ID') || 
                      ($column->COLUMN_NAME == 'studentID')) {
                      continue;
                    } elseif(($column->COLUMN_NAME == 'examname')) {
                      echo '<td class="kodama-fill" id="text_' . $i . '_examname"></td>';
                    } elseif(($column->COLUMN_NAME == 'examdate')) {
                      echo '<td class="kodama-fillcontrol">';
                        echo '<div class="form-group kodama-datepicker" id="time_' . $i . '_0011" data-target-input="nearest">';
                          echo '<input type="text" id="time_' . $i . '_examdate" class="form-control datetimepicker-input" data-target="#time_' . $i . '_0011" data-toggle="datetimepicker"/>';
                        echo '</div>';
                      echo '</td>';
                    } else {
                      echo '<td class="kodama-fillcontrol" style="padding: 0px 10px;">';
                        echo '<select class="kodama-select kodama-editable-select" name="score" id="selecttext_' . $i . '_' . $column->COLUMN_NAME . '">';
                          echo '<option class="kodama-select" value="A+">A+</option>';
                          echo '<option class="kodama-select" value="A">A</option>';
                          echo '<option class="kodama-select" value="A-">A-</option>';
                          echo '<option class="kodama-select" value="B+">B+</option>';
                          echo '<option class="kodama-select" value="B">B</option>';
                          echo '<option class="kodama-select" value="B-">B-</option>';
                          echo '<option class="kodama-select" value="C+">C+</option>';
                          echo '<option class="kodama-select" value="C">C</option>';
                          echo '<option class="kodama-select" value="C-">C-</option>';
                          echo '<option class="kodama-select" value="D+">D+</option>';
                          echo '<option class="kodama-select" value="D">D</option>';
                          echo '<option class="kodama-select" value="D-">D-</option>';
                        echo '</select>';
                      echo '</td>';
                    } 
                  }
                  echo '<td class="kodama-fill" id="text_' . $i . '_ID" hidden="hidden"></td>';
                echo '</tr>';
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>      
  </div>
</section>

<!-- Editable Table Js -->
<script src="../style/js/mindmup-editabletable.js"></script>
<script src="../style/js/moment-with-locales.js"></script>
<script src="../style/js/tempusdominus-bootstrap-4.js"></script>
<script src="../style/js/kodama-datetimepicker.js"></script>
<script src="../style/js/jquery-editable-select.js"></script>
<script src="../style/js/kodama-table-student-datasave.js"></script>
<script type="text/javascript">
//信息ID
var _studentrecord = {
  'text_examname': '',
  'selecttext_scoretalk': '',
  'selecttext_scoreword': '',
  'selecttext_scoregrammar': '',
  'selecttext_scoreread': '',
  'selecttext_scorewrite': '',
  'selecttext_scorelisten': '',
  'selecttext_scoresynthesis': '',
  'time_examdate': '',
  'text_ID': '',
};
g_records.itemname = 'score';
</script>