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
            <h2>東京四木教育学院履歴書（追加）<small></small></h2>
            <ul class="header-button">
              <li><a href="javascript:void(0);" onclick="refreshRecord();">
                <div class="kodama-icon-circle bg-orange"> <i class="material-icons">query_builder</i> </div>
                <div class="kodama-menu-info">
                  <h4>Reload</h4>
                </div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="saveData_003();">
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
                  <th></th> <th></th> <th></th> <th></th> <th></th> <th></th>
                  <th></th> <th></th> <th></th> <th></th> <th></th> <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th class="col-xs-12 text-center" colspan="12"><h4>東京コダマ教育学院履歴書（追加）</h4><small></small></th>
                </tr>
                <tr>
                  <th class="col-xs-12" colspan="12" style="padding-bottom: 0;"><div class="text-left alert-warning align-left col-white" id="message"></div></th>
                </tr>
                <tr>
                  <th class="col-xs-12" colspan="12" style="padding-top: 0;">
                    <div id="xhr_progressgrd" class="progress" style="width: 0%;">
                      <div id="xhr_progress" class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; display: inline;">
                      </div>
                    </div>
                  </th>
                </tr>
                <tr>
                  <th class="col-xs-12 text-left" colspan="12" id="export">Tip: Use CTRL+ENTER to wrap in the input box. Modifying the light blue table data will change the basic information.</th>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1" rowspan="10">6</th>
                  <th class="col-xs-11" colspan="11">家族 Family</th>
                </tr>
                <tr>
                  <th class="col-xs-2" colspan="2">続柄<br>Relationship</th>
                  <th class="col-xs-2" colspan="2">氏名<br>Full name</th>
                  <th class="col-xs-2" colspan="2">生年月日<br>Date of  birth</th>
                  <th class="col-xs-2" colspan="2">職業<br>Occupation</th>
                  <th class="col-xs-3" colspan="3">本国の現住所<br>Address in home country</th>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationship4"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipname4"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0011" data-target-input="nearest">
                      <input type="text" id="time_relationshipbirthday4" class="form-control datetimepicker-input" data-target="#time_0011" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipoccupation4"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_relationshipaddress4"></td>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationship5"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipname5"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0021" data-target-input="nearest">
                      <input type="text" id="time_relationshipbirthday5" class="form-control datetimepicker-input" data-target="#time_0021" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipoccupation5"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_relationshipaddress5"></td>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationship6"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipname6"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0031" data-target-input="nearest">
                      <input type="text" id="time_relationshipbirthday6" class="form-control datetimepicker-input" data-target="#time_0031" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipoccupation6"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_relationshipaddress6"></td>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationship7"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipname7"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0041" data-target-input="nearest">
                      <input type="text" id="time_relationshipbirthday7" class="form-control datetimepicker-input" data-target="#time_0041" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipoccupation7"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_relationshipaddress7"></td>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationship8"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipname8"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0051" data-target-input="nearest">
                      <input type="text" id="time_relationshipbirthday8" class="form-control datetimepicker-input" data-target="#time_0051" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipoccupation8"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_relationshipaddress8"></td>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationship9"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipname9"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0061" data-target-input="nearest">
                      <input type="text" id="time_relationshipbirthday9" class="form-control datetimepicker-input" data-target="#time_0061" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipoccupation9"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_relationshipaddress9"></td>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationship10"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipname10"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0071" data-target-input="nearest">
                      <input type="text" id="time_relationshipbirthday10" class="form-control datetimepicker-input" data-target="#time_0071" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipoccupation10"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_relationshipaddress10"></td>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationship11"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipname11"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0081" data-target-input="nearest">
                      <input type="text" id="time_relationshipbirthday11" class="form-control datetimepicker-input" data-target="#time_0081" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipoccupation11"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_relationshipaddress11"></td>
                </tr>
                <tr>
                  <th class="col-xs-12" colspan="12"></th>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1" rowspan="4">7</th>
                  <th class="col-xs-11" colspan="11">学歴 Educational background</th>
                </tr>
                <tr>
                  <th class="col-xs-3" colspan="3">学校名<br>Name of  school</th>
                  <th class="col-xs-4" colspan="4">入学年月～卒業年月<br>Admission　date～Graduation date</th>
                  <th class="col-xs-3" colspan="3">所在地<br>Location</th>
                  <th class="col-xs-1" colspan="1">年数<br>Years</th>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_eduschoolname6"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-monthpicker" id="time_0091" data-target-input="nearest">
                      <input type="text" id="time_eduadmission6" class="form-control datetimepicker-input" data-target="#time_0091" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-monthpicker" id="time_0101" data-target-input="nearest">
                      <input type="text" id="time_edugraduation6" class="form-control datetimepicker-input" data-target="#time_0101" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_eduschoollocation6"></td>
                  <td class="col-xs-1 kodama-fill" colspan="1" id="text_eduschoolyear6"></td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_eduschoolname7"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-monthpicker" id="time_0111" data-target-input="nearest">
                      <input type="text" id="time_eduadmission7" class="form-control datetimepicker-input" data-target="#time_0111" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-monthpicker" id="time_0121" data-target-input="nearest">
                      <input type="text" id="time_edugraduation7" class="form-control datetimepicker-input" data-target="#time_0121" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_eduschoollocation7"></td>
                  <td class="col-xs-1 kodama-fill" colspan="1" id="text_eduschoolyear7"></td>
                </tr>
                <tr>
                  <th class="col-xs-12" colspan="12"></th>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1" rowspan="4">8</th>
                  <th class="col-xs-11" colspan="11">日本語学習歴　Experience of Japanese studying</th>
                </tr>
                <tr>
                  <th class="col-xs-3" colspan="3">学校名<br>Name of  school</th>
                  <th class="col-xs-4" colspan="4">所在地<br>Location</th>
                  <th class="col-xs-4" colspan="4">入学年月日～卒業年月日<br>Admission　date～Graduation date</th>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_jpschoolname3"></td>
                  <td class="col-xs-4 kodama-fill text-left" colspan="4" id="text_jpschoollocation3"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0131" data-target-input="nearest">
                      <input type="text" id="time_jpadmission3" class="form-control datetimepicker-input" data-target="#time_0131" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0141" data-target-input="nearest">
                      <input type="text" id="time_jpgraduation3" class="form-control datetimepicker-input" data-target="#time_0141" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_jpschoolname4"></td>
                  <td class="col-xs-4 kodama-fill text-left" colspan="4" id="text_jpschoollocation4"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0151" data-target-input="nearest">
                      <input type="text" id="time_jpadmission4" class="form-control datetimepicker-input" data-target="#time_0151" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0161" data-target-input="nearest">
                      <input type="text" id="time_jpgraduation4" class="form-control datetimepicker-input" data-target="#time_0161" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class="col-xs-12" colspan="12"></th>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1" rowspan="4">9</th>
                  <th class="col-xs-5 text-right" colspan="5">職歴　Occupation career</th>
                </tr>
                <tr>
                  <th class="col-xs-2" colspan="2">勤務先<br>Employer's name</th>
                  <th class="col-xs-2" colspan="2">職種<br>Occupation	</th>
                  <th class="col-xs-3" colspan="3">所在地<br>Location</th>
                  <th class="col-xs-4" colspan="4">就職年月日～退職年月日<br>Period of  employment</th>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_employername3"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_occupation3"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_occupationlocation3"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0171" data-target-input="nearest">
                      <input type="text" id="time_employstart3" class="form-control datetimepicker-input" data-target="#time_0171" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0181" data-target-input="nearest">
                      <input type="text" id="time_employend3" class="form-control datetimepicker-input" data-target="#time_0181" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_employername4"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_occupation4"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_occupationlocation4"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0191" data-target-input="nearest">
                      <input type="text" id="time_employstart4" class="form-control datetimepicker-input" data-target="#time_0191" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0201" data-target-input="nearest">
                      <input type="text" id="time_employend4" class="form-control datetimepicker-input" data-target="#time_0201" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class="col-xs-12" colspan="12"></th>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1" rowspan="10">10</th>
                  <th class="col-xs-5 text-right" colspan="5">出入国歴 Previous stay in japan</th>  
                </tr>
                <tr>
                  <th class="col-xs-3" colspan="3">入国目的<br>Purpose of entry</th>
                  <th class="col-xs-4" colspan="4">在留資格<br>Visa status</th>
                  <th class="col-xs-4" colspan="4">入国年月日　～　出国年月日<br>Date of entry　　　～　　Date of departure</th>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_entrypurpose3"></td>
                  <td class="col-xs-4 kodama-fill" colspan="4" id="text_visastatus3"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0211" data-target-input="nearest">
                      <input type="text" id="time_prevjpentry3" class="form-control datetimepicker-input" data-target="#time_0211" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0221" data-target-input="nearest">
                      <input type="text" id="time_prevjpdeparture3" class="form-control datetimepicker-input" data-target="#time_0221" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_entrypurpose4"></td>
                  <td class="col-xs-4 kodama-fill" colspan="4" id="text_visastatus4"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0231" data-target-input="nearest">
                      <input type="text" id="time_prevjpentry4" class="form-control datetimepicker-input" data-target="#time_0231" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0241" data-target-input="nearest">
                      <input type="text" id="time_prevjpdeparture4" class="form-control datetimepicker-input" data-target="#time_0241" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_entrypurpose5"></td>
                  <td class="col-xs-4 kodama-fill" colspan="4" id="text_visastatus5"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0251" data-target-input="nearest">
                      <input type="text" id="time_prevjpentry5" class="form-control datetimepicker-input" data-target="#time_0251" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0261" data-target-input="nearest">
                      <input type="text" id="time_prevjpdeparture5" class="form-control datetimepicker-input" data-target="#time_0261" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_entrypurpose6"></td>
                  <td class="col-xs-4 kodama-fill" colspan="4" id="text_visastatus6"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0271" data-target-input="nearest">
                      <input type="text" id="time_prevjpentry6" class="form-control datetimepicker-input" data-target="#time_0271" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0281" data-target-input="nearest">
                      <input type="text" id="time_prevjpdeparture6" class="form-control datetimepicker-input" data-target="#time_0281" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_entrypurpose7"></td>
                  <td class="col-xs-4 kodama-fill" colspan="4" id="text_visastatus7"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0291" data-target-input="nearest">
                      <input type="text" id="time_prevjpentry7" class="form-control datetimepicker-input" data-target="#time_0291" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0301" data-target-input="nearest">
                      <input type="text" id="time_prevjpdeparture7" class="form-control datetimepicker-input" data-target="#time_0301" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_entrypurpose8"></td>
                  <td class="col-xs-4 kodama-fill" colspan="4" id="text_visastatus8"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0311" data-target-input="nearest">
                      <input type="text" id="time_prevjpentry8" class="form-control datetimepicker-input" data-target="#time_0311" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0321" data-target-input="nearest">
                      <input type="text" id="time_prevjpdeparture8" class="form-control datetimepicker-input" data-target="#time_0321" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_entrypurpose9"></td>
                  <td class="col-xs-4 kodama-fill" colspan="4" id="text_visastatus9"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0331" data-target-input="nearest">
                      <input type="text" id="time_prevjpentry9" class="form-control datetimepicker-input" data-target="#time_0331" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0341" data-target-input="nearest">
                      <input type="text" id="time_prevjpdeparture9" class="form-control datetimepicker-input" data-target="#time_0341" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_entrypurpose10"></td>
                  <td class="col-xs-4 kodama-fill" colspan="4" id="text_visastatus10"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0351" data-target-input="nearest">
                      <input type="text" id="time_prevjpentry10" class="form-control datetimepicker-input" data-target="#time_0351" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0361" data-target-input="nearest">
                      <input type="text" id="time_prevjpdeparture10" class="form-control datetimepicker-input" data-target="#time_0361" data-toggle="datetimepicker"/>
                    </div>
                  </td>
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
<script src="../style/js/kodama-table-ibara.js"></script>