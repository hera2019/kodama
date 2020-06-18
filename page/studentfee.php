<head>
<!-- code by zmq -->
<?php $INCLUDE_STUDENT_INFO = true; ?>
<?php require_once( 'frame.php' ); ?>
<?php require_once('../include/include_database.php'); ?>
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
            <h2>入金情報<small></small></h2>
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
              <thead>
                <tr class="bg-<?= $KODAMA_THEME_COLOR; ?>">
                  <th class="col-xs-2 text-center">入金種類</th>
                  <th class="col-xs-2 text-center">入金日</th>
                  <th class="col-xs-2 text-center">期間</th>
                  <th class="col-xs-1 text-center">金額（円）</th>
                  <th class="col-xs-2 text-center">有効期限</th>
                  <th class="col-xs-2 text-center">担当</th>
                  <th class="col-xs-1 text-center">備考</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = 'SELECT typeID, typename FROM idconfig WHERE type="fee" ORDER BY typeID ASC';
                $statement = $connection->prepare($sql);
                $statement->execute();
                $recordfee = $statement->fetchAll( PDO::FETCH_OBJ );
                
                $sql = 'SELECT ID, name FROM usermanage WHERE isteacher=1 ORDER BY ID ASC';
                $statement = $connection->prepare($sql);
                $statement->execute();
                $recordteacher = $statement->fetchAll( PDO::FETCH_OBJ );
                
                for($i=1; $i<=24; $i++): ?>
                <tr id=recordrow<?= $i; ?><?= $i == 1 ? '' : " hidden=\"hidden\""; ?>>
                  <td class="kodama-fillcontrol" style="padding: 0px 10px;">
                      <select class="kodama-select" name="feetype" id="select_<?= $i; ?>_feetype">
                        <option class="kodama-select" value="-1">- - - -</option>
                        <?php foreach($recordfee as $recordfee1): ?>
                        <option class="kodama-select" value="<?= $recordfee1->typeID; ?>"><?= $recordfee1->typename; ?></option>
                        <?php endforeach; ?>
                      </select>
                  </td>
                  <td class="kodama-fillcontrol">
                    <div class="form-group kodama-datepicker" id="time_<?= $i; ?>_0011" data-target-input="nearest">
                      <input type="text" id="time_<?= $i; ?>_paymentdate" class="form-control datetimepicker-input" data-target="#time_<?= $i; ?>_0011" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol" style="padding: 0px 10px;">                    
                    <select class="kodama-select kodama-editable-select" name="period" id="selecttext_<?= $i; ?>_period">
                      <option class="kodama-select" value="二年">二年</option>
                      <option class="kodama-select" value="一年">一年</option>
                      <option class="kodama-select" value="6ヶ月">6ヶ月</option>
                      <option class="kodama-select" value="3ヶ月">3ヶ月</option>
                      <option class="kodama-select" value="1ヶ月">1ヶ月</option>
                    </select>
                  </td>
                  <td class="kodama-fill" id="text_<?= $i; ?>_moneyamount"></td>
                  <td class="kodama-fillcontrol">
                    <div class="form-group kodama-datepicker" id="time_<?= $i; ?>_0012" data-target-input="nearest">
                      <input type="text" id="time_<?= $i; ?>_expirationdate" class="form-control datetimepicker-input" data-target="#time_<?= $i; ?>_0012" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol" style="padding: 0px 10px;">
                      <select class="kodama-select" name="teacher" id="select_<?= $i; ?>_teacherID">
                        <option class="kodama-select" value="-1">- - - -</option>
                        <?php foreach($recordteacher as $recordteacher1): ?>
                        <option class="kodama-select" value="<?= $recordteacher1->ID; ?>"><?= $recordteacher1->name; ?></option>
                        <?php endforeach; ?>
                      </select>
                  </td>
                  <td class="kodama-fill" id="text_<?= $i; ?>_description"></td>
                  <td class="kodama-fill" id="text_<?= $i; ?>_ID" hidden="hidden"></td>
                </tr>
                <?php endfor; ?>
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
var _studentrecord = {
  'select_feetype': '',
  'time_paymentdate': '',
  'selecttext_period': '',
  'text_moneyamount': '',
  'time_expirationdate': '',
  'select_teacherID': '',
  'text_description': '',
  'text_ID': '',
};
g_records.itemname = 'fee';
</script>