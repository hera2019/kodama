<?php
require_once( 'frame.php' );
require_once( '../include/include_database.php' );
?>
<head>
<!-- code by zmq -->
<?php $INCLUDE_STUDENT_INFO = true; ?>
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
            <h2>賞罰<small></small></h2>
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
              <caption><div class="text-left alert-warning align-left col-white" id="message"></div></caption>
              <thead><tr></tr></thead>
              <?php
              $sql = 'SELECT ID, name FROM usermanage WHERE isteacher=1 ORDER BY ID ASC';
              $statement = $connection->prepare($sql);
              $statement->execute();
              $recordteacher = $statement->fetchAll( PDO::FETCH_OBJ );
              
              for($i=1; $i<=24; $i++): ?>
              <tbody id=recordrow<?= $i; ?><?= $i == 1 ? '' : " hidden=\"hidden\""; ?>>
                <tr>
                  <th class="col-xs-2">タイトル</th>
                  <td class="col-xs-2 kodama-fill text-left" colspan="3" id="text_<?= $i; ?>_title"></td>
                </tr>
                <tr>
                  <th class="col-xs-2">賞罰</th>
                  <td class="col-xs-2 kodama-fillcontrol" style="padding: 0px 10px;">
                      <select class="kodama-select" name="teacher" id="select_<?= $i; ?>_rewardstype">
                        <option class="kodama-select" value="-1">- - - -</option>
                        <option class="kodama-select" value="0">賞</option>
                        <option class="kodama-select" value="1">罰</option>
                      </select>
                  </td>
                  <th class="col-xs-2">クラス</th>
                  <th class="col-xs-2 text-left" id="text_<?= $i; ?>_class"><?= isset($StudentInfo) ? $StudentInfo->classname : ''; ?>
                </tr>
                <tr>
                  <th class="col-xs-2">実施者</th>
                  <td class="col-xs-2 kodama-fillcontrol" style="padding: 0px 10px;">
                      <select class="kodama-select" name="teacher" id="select_<?= $i; ?>_teacherID">
                        <option class="kodama-select" value="-1">- - - -</option>
                        <?php foreach($recordteacher as $recordteacher1): ?>
                        <option class="kodama-select" value="<?= $recordteacher1->ID; ?>"><?= $recordteacher1->name; ?></option>
                        <?php endforeach; ?>
                      </select>
                  </td>
                  <th class="col-xs-2">日時</th>
                  <td class="col-xs-2 kodama-fillcontrol">
                    <div class="form-group kodama-datetimepicker" id="time_<?= $i; ?>_0011" data-target-input="nearest">
                      <input type="text" id="time_<?= $i; ?>_executiontime" class="form-control datetimepicker-input" data-target="#time_<?= $i; ?>_0011" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class="col-xs-2">賞罰内容</th>
                  <td class="col-xs-2 kodama-fill text-left" colspan="3" id="text_<?= $i; ?>_rewardscontent"></td>
                </tr>
                <tr>
                  <th class="col-xs-2">賞罰原因</th>
                  <td class="col-xs-2 kodama-fill text-left" colspan="3" id="text_<?= $i; ?>_rewardsreason" style="vertical-align: top; height: 100px;"></td>
                </tr>
                <tr>
                  <td class="kodama-fill" id="text_<?= $i; ?>_ID" hidden="hidden"></td>
                  <th class="col-xs-12" colspan="4" style="height: 10px; background-color: #e9e9e9;"></th>
                </tr>
              </tbody>
              <?php endfor; ?>
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
var _studentrecord = { // 默认值需要与html中同样，重置时使用
  'text_title': '',
  'select_rewardstype': '',
  'text_class': '<?= isset($StudentInfo) ? $StudentInfo->classname : ''; ?>',
  'select_teacherID': '',
  'time_executiontime': '',
  'text_rewardscontent': '',
  'text_rewardsreason': '',
  'time_recordtime': '',
  'text_ID': '',
};
g_records.itemname = 'rewards';
</script>