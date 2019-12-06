<head>
<!-- code by zmq -->
<?php $INCLUDE_STUDENT_INFO = true; ?>
<?php require_once( 'frame.php' ); ?>
<?php require_once('../include/include_database.php'); ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="../style/css/tempusdominus-bootstrap-4.css" rel="stylesheet" />
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
            <h2>入学前その他の情報<small></small></h2>
            <ul class="header-button">
              <li><a href="javascript:void(0);" onclick="refreshData_004();">
                <div class="kodama-icon-circle bg-orange"> <i class="material-icons">query_builder</i> </div>
                <div class="kodama-menu-info">
                  <h4>Reload</h4>
                </div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="saveData_004();">
                <div class="kodama-icon-circle bg-light-blue"> <i class="material-icons">save</i> </div>
                <div class="kodama-menu-info">
                  <h4>Save</h4>
                </div>
              </a></li>
            </ul>
          </div>
          <div class="body">
            <table id="mainTable" class="kodama-formtable table table-bordered kodama-formtable-bordered text-center">
              <!-- <caption></caption> -->
              <thead>
                <tr>
                  <th class="col-xs-1"></th> <th class="col-xs-1"></th> <th class="col-xs-1"></th>
                  <th class="col-xs-1"></th> <th class="col-xs-1"></th> <th class="col-xs-1"></th>
                  <th class="col-xs-1"></th> <th class="col-xs-1"></th> <th class="col-xs-1"></th>
                  <th class="col-xs-1"></th> <th class="col-xs-1"></th> <th class="col-xs-1"></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th colspan="12" class="text-center"><h4>入学前その他の情報</h4><small></small></th>
                </tr>
                <tr>
                  <th colspan="12" style="padding-bottom: 0;"><div class="text-left alert-warning align-left col-white" id="message"></div></th>
                </tr>
                <tr>
                  <th class="text-left" colspan="12" id="export">Tip: <span style="background-color: #fffede;">Light yellow</span> table need to be filled in. Use CTRL+ENTER to wrap in the input box. Modifying the <span style="background-color: #EDFCFF;">light blue</span> table data will change the basic information.</th>
                </tr>
                <tr>
                  <th colspan="1" rowspan="4">1</th>
                  <th colspan="11">入学情報</th>
                </tr>
                <tr>
                  <th colspan="2">入学预计年月</th>
                  <td colspan="3" class="kodama-fillcontrol">
                    <div class="form-group kodama-monthpicker" id="time_0011" data-target-input="nearest">
                      <input type="text" id="time_scheduledschoolentrydate" autocomplete="off" class="form-control datetimepicker-input" data-target="#time_0011" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <th colspan="3">入学受付缔切日期</th>
                  <td colspan="3" class="kodama-fillcontrol">
                    <div class="form-group kodama-datepicker" id="time_0021" data-target-input="nearest">
                      <input type="text" id="time_schoolentrydeadlinedate" autocomplete="off" class="form-control datetimepicker-input" data-target="#time_0021" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th colspan="1">紹介者</th>
                  <td colspan="2" class="kodama-fillcontrol" style="padding: 0px 10px;">
                      <select class="kodama-select" name="referral" id="select_referral">
                        <option class="kodama-select" value="-1">Select referral</option>
                        <?php
                        $sql = 'SELECT typeID, typename FROM idconfig WHERE type="referral" ORDER BY typeID ASC';
                        $statement = $connection->prepare($sql);
                        $statement->execute();
                        $recordreferral = $statement->fetchAll( PDO::FETCH_OBJ );
                        foreach($recordreferral as $recordreferral): ?>
                        <option class="kodama-select" value="<?= $recordreferral->typeID; ?>"><?= $recordreferral->typename; ?></option>
                        <?php endforeach; ?>
                      </select>
                  </td>
                  <th colspan="1">紹介先</th>
                  <td colspan="3" class="kodama-fill" id="text_referralname"></td>
                  <th colspan="1">紹介料</th>
                  <td colspan="3" class="kodama-fill" id="text_referralfee"></td>
                </tr>
                <tr>
                  <th colspan="2">最終学歴</th>
                  <td colspan="3" class="kodama-fill" id="text_finaleducation"></td>
                  <th colspan="2">出身校</th>
                  <td colspan="4" class="kodama-fill" id="text_graduatedschool"></td>
                </tr>
                <tr>
                  <th colspan="12"></th>
                </tr>
                <tr>
                  <th colspan="1" rowspan="2">2</th>
                  <th colspan="11">面試摘要</th>
                </tr>
                <tr>
                  <td colspan="11" class="kodama-fill text-left" id="text_interviewsummary" style="vertical-align: top; height: 100px;"></td>
                </tr>
                <tr>
                  <th colspan="12"></th>
                </tr>
                <tr>
                  <th colspan="1" rowspan="3">3</th>
                  <th colspan="11">現住所</th>
                </tr>
                <tr>
                  <th colspan="2">聯繫電話</th>
                  <td colspan="4" class="kodama-fill" id="text_phone"></td>
                  <th colspan="2">郵編</th>
                  <td colspan="3" class="kodama-fill" id="text_postcode"></td>
                </tr>
                <tr>
                  <th colspan="2">地址</th>
                  <td colspan="9" class="kodama-fill text-left" id="text_address"></td>
                </tr>
                <tr>
                  <th colspan="12"></th>
                </tr>
                <tr>
                  <th colspan="1" rowspan="3">4</th>
                  <th colspan="11">緊急聯絡先</th>
                </tr>
                  <th colspan="2">緊急聯絡先名</th>
                  <td colspan="3" class="kodama-fill" id="text_emergencycontactname"></td>
                  <th colspan="2">電話</th>
                  <td colspan="4" class="kodama-fill" id="text_emergencycontactphone"></td>
                </tr>
                <tr>
                  <th colspan="2">地址</th>
                  <td colspan="9" class="kodama-fill text-left" id="text_emergencycontactaddress"></td>
                </tr>
                <tr>
                  <th colspan="12"></th>
                </tr>
                <tr>
                  <th colspan="1" rowspan="5">5</th>
                  <th colspan="11">保護者</th>
                </tr>
                <tr>
                  <th colspan="2">保護者名字</th>
                  <td colspan="3" class="kodama-fill" id="text_protectorname"></td>
                  <th colspan="2">聯繫電話</th>
                  <td colspan="4" class="kodama-fill" id="text_protectorphone"></td>
                </tr>
                <tr>
                  <th colspan="2">地址</th>
                  <td colspan="9" class="kodama-fill text-left" id="text_protectoraddress"></td>
                </tr>
                <tr>
                  <th colspan="2">勤務先名稱</th>
                  <td colspan="3" class="kodama-fill" id="text_protectorcompanyname"></td>
                  <th colspan="2">勤務先電話</th>
                  <td colspan="4" class="kodama-fill" id="text_protectorcompanyphone"></td>
                </tr>
                <tr>
                  <th colspan="2">勤務先地址</th>
                  <td colspan="9" class="kodama-fill text-left" id="text_protectorcompanyaddress"></td>
                </tr>
                <tr>
                  <th colspan="12"></th>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="12"></th>
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
<script src="../style/js/kodama-table.js"></script>
<script src="../style/js/kodama-table-ibaother.js"></script>