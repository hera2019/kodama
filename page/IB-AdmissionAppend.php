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
            <h2>東京四木教育学院入学追加調書<small></small></h2>
            <ul class="header-button">
              <li><a href="javascript:void(0);" onclick="refreshData_002();">
                <div class="kodama-icon-circle bg-orange"> <i class="material-icons">query_builder</i> </div>
                <div class="kodama-menu-info">
                  <h4>Reload</h4>
                </div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="saveData_002();">
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
                  <th colspan="12" class="text-center"><h4>東京四木教育学院入学追加調書</h4><small></small></th>
                </tr>
                <tr>
                  <th colspan="12" style="padding-bottom: 0;"><div class="text-left alert-warning align-left col-white" id="message"></div></th>
                </tr>
                <tr>
                  <th colspan="12" style="padding-top: 0;">
                    <div id="xhr_progressgrd" class="progress" style="width: 0%;">
                      <div id="xhr_progress" class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; display: inline;">
                      </div>
                    </div>
                  </th>
                </tr>
                <tr>
                  <th class="text-left" colspan="12" id="export">Tip: Use CTRL+ENTER to wrap in the input box. Modifying the light blue table data will change the basic information.</th>
                </tr>
                <tr>
                  <th colspan="1">13</th>
                  <th colspan="2">申請者の電話番号<br>Cellphone Number</th>
                  <td colspan="3" class="kodama-fill" id="text_cellphonenumber"></td>
                  <th colspan="3">QQ/WECHAT/SKYPE/FACEBOOK</th>
                  <td colspan="3" class="kodama-fill" id="text_internetnumber"></td>
                </tr>
                <tr>
                  <th colspan="12"></th>
                </tr>
                <tr>
                  <th colspan="1" rowspan="3">14</th>
                  <th colspan="11">旅券<br>Do you have a passport?</th>
                </tr>
                <tr>
                  <td colspan="2" class="kodama-fillcontrol" id="radio_passportyes">
                    <input name="group_0011" type="radio" id="radio_0012" value="1" class="with-gap radio-col-green"/>
                    <label for="radio_0012">有 Yes</label>
                  </td>
                  <th colspan="2">パスポート番号<br>Passport Number</th>
                  <td colspan="3" class="kodama-fill" id="text_passportnumber"></td>
                  <th colspan="2">有効期限<br>Date of expiration</th>
                  <td colspan="2" class="kodama-fillcontrol">
                    <div class="form-group kodama-datepicker" id="time_0011" data-target-input="nearest">
                      <input type="text" id="time_passportexpiration" autocomplete="off" class="form-control datetimepicker-input" data-target="#time_0011" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" class="kodama-fillcontrol" id="radio_passportno">
                    <input name="group_0011" type="radio" id="radio_0013" value="0" class="with-gap radio-col-blue-grey"/>
                    <label for="radio_0013">無 No</label>
                  </td>
                  <td colspan="9" class="kodama-fillcontrol text-left">
                    <input type="checkbox" id="checkbox_passportapplying" class="filled-in chk-col-green"/>
                    <label for="checkbox_passportapplying">取得手続中 Currently applying</label>
                  </td>
                </tr>
                <tr>
                  <th colspan="12"></th>
                </tr>
                <tr>
                  <th colspan="1">15</th>
                  <th colspan="4">査証申請予定地<br>Place to apply for visa</th>
                  <td colspan="7" class="kodama-fill" id="text_visaapplyplace"></td>
                </tr>
                <tr>
                  <th colspan="12"></th>
                </tr>
                <tr>
                  <th colspan="1" rowspan="12">16</th>
                  <th colspan="11">経費支弁者<br>Financial supporter</th>
                </tr>
                <tr>
                  <th colspan="4">氏名<br>The name of financial supporter</th>
                  <td colspan="7" class="kodama-fill" id="text_supportername"></td>
                </tr>
                <tr>
                  <th colspan="4">現住所<br>Address</th>
                  <td colspan="7" class="kodama-fill" id="text_supportercuraddress"></td>
                </tr>
                <tr>
                  <th colspan="4">户籍住所<br>Household registered address</th>
                  <td colspan="7" class="kodama-fill" id="text_supporterhouseholdaddress"></td>
                </tr>
                <tr>
                  <th colspan="4">電話番号<br>Phone</th>
                  <td colspan="7" class="kodama-fill" id="text_supporterphonenumber"></td>
                </tr>
                <tr>
                  <th colspan="4">申請人との関係<br>Relationship with the applicant</th>
                  <td colspan="7" class="kodama-fill" id="text_supporterrelationship"></td>
                </tr>
                <tr>
                  <th colspan="4">勤務先名<br>Name of work place</th>
                  <td colspan="7" class="kodama-fill" id="text_supporterworkplacename"></td>
                </tr>
                <tr>
                  <th colspan="4">会社の業務内容<br>Industry of the Company</th>
                  <td colspan="7" class="kodama-fill" id="text_supportercompanyindustry"></td>
                </tr>
                <tr>
                  <th colspan="4">勤務先住所<br>Company Address</th>
                  <td colspan="7" class="kodama-fill" id="text_supportercompanyaddress"></td>
                </tr>
                <tr>
                  <th colspan="4">勤務先電話番号<br>Phone of work place</th>
                  <td colspan="7" class="kodama-fill" id="text_supporterworkphonenumber"></td>
                </tr>
                <tr>
                  <th colspan="4">職位<br>Position</th>
                  <td colspan="7" class="kodama-fill" id="text_supporterworkposition"></td>
                </tr>
                <tr>
                  <th colspan="4">年収<br>Annual income of year</th>
                  <td colspan="7" class="kodama-fill" id="text_supporterannualincome"></td>
                </tr>
                <tr>
                  <th colspan="12"></th>
                </tr>
                <tr>
                  <th colspan="1" rowspan="6">17</th>
                  <th colspan="11">日本語学習歴<br>Experience of Japanese studying</th>
                </tr>
                <tr>
                  <th colspan="1" rowspan="3">現在の日本語能力及び学習状況</th>
                  <td colspan="4" class="kodama-fillcontrol" id="radio_jpntest">
                    <input name="group_0021" type="radio" id="radio_0022" class="with-gap radio-col-blue"/>
                    <label for="radio_0022">N1</label>
                    <input name="group_0021" type="radio" id="radio_0023" class="with-gap radio-col-blue"/>
                    <label for="radio_0023">N2</label>
                    <input name="group_0021" type="radio" id="radio_0024" class="with-gap radio-col-blue"/>
                    <label for="radio_0024">N3</label>
                    <input name="group_0021" type="radio" id="radio_0025" class="with-gap radio-col-blue"/>
                    <label for="radio_0025">N4</label>
                    <input name="group_0021" type="radio" id="radio_0026" class="with-gap radio-col-blue"/>
                    <label for="radio_0026">N5</label>
                  </td>
                  <td colspan="2" class="kodama-fillcontrol">
                    <div class="form-group kodama-monthpicker" id="time_0021" data-target-input="nearest">
                      <input type="text" id="time_jpntest" autocomplete="off" class="form-control datetimepicker-input" data-target="#time_0021" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td colspan="2" class="kodama-fillcontrol" id="radio_jpntestpass">
                    <input name="group_0031" type="radio" id="radio_0033" class="with-gap radio-col-blue-grey"/>
                    <label for="radio_0033">不合格</label>
                    <input name="group_0031" type="radio" id="radio_0032" class="with-gap radio-col-green"/>
                    <label for="radio_0032">合格</label>
                  </td>
                  <th colspan="1">点数</th>
                  <td colspan="1" class="kodama-fill" id="text_jpntestpoint"></td>
                </tr>
                <tr>
                  <th colspan="2">その他の試験名1</th>                  
                  <td colspan="2" class="kodama-fill" id="text_jptest1"></td>
                  <td colspan="2" class="kodama-fillcontrol">
                    <div class="form-group kodama-monthpicker" id="time_0031" data-target-input="nearest">
                      <input type="text" id="time_jptest1" autocomplete="off" class="form-control datetimepicker-input" data-target="#time_0031" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td colspan="2" class="kodama-fillcontrol" id="radio_jptest1pass">
                    <input name="group_0041" type="radio" id="radio_0043" class="with-gap radio-col-blue-grey"/>
                    <label for="radio_0043">不合格</label>
                    <input name="group_0041" type="radio" id="radio_0042" class="with-gap radio-col-green"/>
                    <label for="radio_0042">合格</label>
                  </td>
                  <th colspan="1">点数</th>
                  <td colspan="1" class="kodama-fill" id="text_jptest1point"></td>
                </tr>
                <tr>
                  <th colspan="2">その他の試験名2</th>                  
                  <td colspan="2" class="kodama-fill" id="text_jptest2"></td>
                  <td colspan="2" class="kodama-fillcontrol">
                    <div class="form-group kodama-monthpicker" id="time_0041" data-target-input="nearest">
                      <input type="text" id="time_jptest2" autocomplete="off" class="form-control datetimepicker-input" data-target="#time_0041" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td colspan="2" class="kodama-fillcontrol" id="radio_jptest2pass">
                    <input name="group_0051" type="radio" id="radio_0053" class="with-gap radio-col-blue-grey"/>
                    <label for="radio_0053">不合格</label>
                    <input name="group_0051" type="radio" id="radio_0052" class="with-gap radio-col-green"/>
                    <label for="radio_0052">合格</label>
                  </td>
                  <th colspan="1">点数</th>
                  <td colspan="1" class="kodama-fill" id="text_jptest2point"></td>
                </tr>
                <tr>
                  <th colspan="3">現在勉強中の日本語教育機関名</th>                  
                  <td colspan="2" class="kodama-fill" id="text_jpschoolname"></td>
                  <th colspan="2">使用教材</th>
                  <td colspan="4" class="kodama-fill" id="text_jpschoolmaterial"></td>
                </tr>
                <tr>
                  <th colspan="1">学習時間</th>
                  <td colspan="3" class="kodama-fillcontrol">
                    <div class="form-group kodama-datepicker" id="time_0051" data-target-input="nearest">
                      <input type="text" id="time_jpteststart" autocomplete="off" class="form-control datetimepicker-input" data-target="#time_0051" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <th colspan="1">～</th>
                  <td colspan="3" class="kodama-fillcontrol">
                    <div class="form-group kodama-datepicker" id="time_0061" data-target-input="nearest">
                      <input type="text" id="time_jptestend" autocomplete="off" class="form-control datetimepicker-input" data-target="#time_0061" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <th colspan="1">学習時間</th>
                  <td colspan="2" class="kodama-fill" id="text_jptesttime"></td>
                </tr>
                <tr>
                  <th colspan="12"></th>
                </tr>
                <tr>
                  <th colspan="1" rowspan="12">18</th>
                  <th colspan="11">在日親族(父・母・配偶者・子・兄弟姉妹等)及び同居者<br>Family in Japan (Father.Mother.Spouse.Son.Daughter.Borther.Sister or others) or co-residents</th>
                </tr>
                <tr>
                  <th colspan="1">氏名<br>Name</th>
                  <td colspan="2" class="kodama-fill" id="text_jpfamilyname"></td>
                  <th colspan="2">生年月日<br>Date of Birth</th>
                  <td colspan="2" class="kodama-fillcontrol">
                    <div class="form-group kodama-datepicker" id="time_0071" data-target-input="nearest">
                      <input type="text" id="time_jpfamilybirthday" class="form-control datetimepicker-input" data-target="#time_0071" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <th colspan="2">職業<br>Occupation</th>
                  <td colspan="2" class="kodama-fill" id="text_jpfamilyoccupation"></td>
                </tr>
                <tr>
                  <th colspan="1">続柄<br>Relation</th>
                  <td colspan="2" class="kodama-fill" id="text_jpfamilyrelation"></td>
                  <th colspan="2">在留資格<br>Residence Qualification</th>
                  <td colspan="2" class="kodama-fill" id="text_jpfamilyresidencequalification"></td>
                  <th colspan="2">同居予定<br>Live in Same Place</th>
                  <td colspan="2" class="kodama-fillcontrol" id="radio_jpfamilylivetogether">
                    <input name="group_0061" type="radio" id="radio_0063" class="with-gap radio-col-blue-grey"/>
                    <label for="radio_0063">無 No</label>
                    <input name="group_0061" type="radio" id="radio_0062" class="with-gap radio-col-green"/>
                    <label for="radio_0062">有 Yes</label>
                  </td>
                </tr>
                <tr>
                  <th colspan="1">国籍<br>Nationality</th>
                  <td colspan="2" class="kodama-fill" id="text_jpfamilynationality"></td>
                  <th colspan="2">在留カード番号<br>Residence Number</th>
                  <td colspan="2" class="kodama-fill" id="text_jpfamilyresidencenumber"></td>
                  <th colspan="2">電話番号<br>Contact Number</th>
                  <td colspan="2" class="kodama-fill" id="text_jpfamilycontactnumber"></td>
                </tr>
                <tr>
                  <th colspan="3">勤務先/通学先<br>Company Name/School Name</th>
                  <td colspan="8" class="kodama-fill" id="text_jpfamilyworkplacename"></td>
                </tr>
                <tr>
                  <th colspan="3">住所<br>Home Address</th>
                  <td colspan="8" class="kodama-fill" id="text_jpfamilyhomeaddress"></td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <th class="col-xs-12" colspan="12"></th>
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
<script src="../style/js/kodama-table-ibaa.js"></script>