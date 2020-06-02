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
            <h2>東京四木教育学院入学願書及び履歴書<small>THE APPLICATION FOR ADMISSION TO TOKYO YOTSUGI EDUCATION ACADEMY</small></h2>
            <ul class="header-button">
              <li><a href="javascript:void(0);" onclick="refreshRecord();">
                <div class="kodama-icon-circle bg-orange"> <i class="material-icons">query_builder</i> </div>
                <div class="kodama-menu-info">
                  <h4>Reload</h4>
                </div>
              </a></li>
              <li><a href="javascript:void(0);" onclick="saveData_001();">
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
                  <th class="col-xs-12 text-center" colspan="12"><h4>東京四木教育学院入学願書及び履歴書</h4><small>THE APPLICATION FOR ADMISSION TO TOKYO YOTSUGI EDUCATION ACADEMY</small></th>
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
                  <th class="text-left" colspan="12" id="export">Tip: <span style="background-color: #fffede;">黄色</span> のフォームに学生情報を入力してください。フォーム内で改行するには、[Ctrl]＋[Enter]キーを押します。<span style="background-color: #EDFCFF;">水色</span> のフォーム内のデータを変更すると、生徒の基本情報が変更されます。</th>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1">1</th>
                  <th class="col-xs-2" colspan="2">国籍<br>Nationality</th>
                  <td class="col-xs-2 kodama-dependcontrol" colspan="2" style="padding: 0px 10px;">
                      <select class="kodama-select" name="nationalityregion" id="select_student.nationalityregion">
                        <option class="kodama-select" value="-1">Select nationalityregion</option>
                        <?php
                        require_once('../include/include_function.php');
                        $sql = 'SELECT typename FROM idconfig WHERE type="nationalityregion" ORDER BY typeID ASC';
                        $statement = $connection->prepare($sql);
                        $statement->execute();
                        $recordnationalityregion = $statement->fetchAll( PDO::FETCH_OBJ );
                        foreach($recordnationalityregion as $recordnationalityregion): ?>
                        <option class="kodama-select" value="<?= $recordnationalityregion->typename; ?>"><?= $recordnationalityregion->typename; ?></option>
                        <?php endforeach; ?>
                      </select>
                  </td>
                  <th class="col-xs-2" colspan="2">出生地<br>Place of  birth</th>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_birthplace"></td>
                  <td class="col-xs-3 kodama-dependcontrol" colspan="3" rowspan="6">
                      <div class="form-line" id="photo_drag" style="width: 190px; margin: 0 auto;">
                        <form id="formphoto" name="formphoto" enctype="multipart/form-data" method="post" action="../plugin/upload/upload.php" />
                        <input hidden="true" type="file" size="32" id="photofile" name="photofile" value="" accept="image/*" />
                        <input hidden="true" type="text" name="photo" id="photo_student.photo" value="default/empty.jpg" />
                        <img class="photoimage" id="photoimage" autocomplete="off" alt="写真" height="190" src="../data/photo/default/empty.jpg" />
                      </div>
                  </td>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1">2</th>
                  <th class="col-xs-2" colspan="2">氏名<br>Name</th>
                  <td class="col-xs-1 kodama-depend" colspan="1" id="text_student.lastname" style="text-align: right"></td>
                  <td class="col-xs-1 kodama-depend" colspan="1" id="text_student.firstname" style="text-align: left"></td>
                  <th class="col-xs-2" colspan="2">英字<br>Alphabet letters</th>
                  <td class="col-xs-1 kodama-depend" colspan="1" id="text_student.lastnamealphabet" style="text-align: right"></td>
                  <td class="col-xs-1 kodama-depend" colspan="1" id="text_student.firstnamealphabet" style="text-align: left"></td>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1">3</th>
                  <th class="col-xs-2" colspan="2">生年月日<br>Date of  birth</th>
                  <td class="col-xs-2 kodama-dependcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0061" data-target-input="nearest">
                      <input type="text" id="time_student.birthday" autocomplete="off" class="form-control datetimepicker-input" data-target="#time_0061" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <th class="col-xs-2" colspan="2">性別<br>Sex</th>
                  <td class="col-xs-1 kodama-dependcontrol" colspan="2" id="radio_student.genderfemale"> 
                    <input name="group_0071" type="radio" id="radio_0072" class="with-gap radio-col-blue" />
                    <label for="radio_0072">男 Male</label>
                    <input name="group_0071" type="radio" id="radio_0073" class="with-gap radio-col-pink" />
                    <label for="radio_0073">女 Female</label>
                  </td>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1" rowspan="2">4</th>
                  <th class="col-xs-2" colspan="2">現住所<br>Address</th>
                  <td class="col-xs-6 kodama-fill text-left" colspan="6" id="text_curaddress"></td>
                </tr>
                <tr>
                  <th class="col-xs-2" colspan="2">户籍所在地<br>Address</th>
                  <td class="col-xs-6 kodama-fill text-left" colspan="6" id="text_householdaddress"></td>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1">5</th>
                  <th class="col-xs-2" colspan="2">配偶者の有無<br>Married status</th>
                  <td class="col-xs-1 kodama-fillcontrol" colspan="2" id="radio_married">
                    <input name="group_0101" type="radio" id="radio_0102" class="with-gap radio-col-blue-grey" />
                    <label for="radio_0102">無 Single</label>
                    <input name="group_0101" type="radio" id="radio_0103" class="with-gap radio-col-green" />
                    <label for="radio_0103">有 Married</label>
                  </td>
                  <th class="col-xs-2" colspan="2">配偶者氏名<br>Spouses' name</th>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_spousesname"></td>
                </tr>
                <tr>
                  <th class="col-xs-12" colspan="12"></th>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1" rowspan="7">6</th>
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
                  <th class="col-xs-2" colspan="2">父親<br>Father</th>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_fathername"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0131" data-target-input="nearest">
                      <input type="text" id="time_fatherbirthday" class="form-control datetimepicker-input" data-target="#time_0131" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_fatheroccupation"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_fatheraddress"></td>
                </tr>
                <tr>
                  <th class="col-xs-2" colspan="2">母親<br>Mother</th>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_mothername"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0171" data-target-input="nearest">
                      <input type="text" id="time_motherbirthday" class="form-control datetimepicker-input" data-target="#time_0171" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_motheroccupation"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_motheraddress"></td>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationship1"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipname1"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0221" data-target-input="nearest">
                      <input type="text" id="time_relationshipbirthday1" class="form-control datetimepicker-input" data-target="#time_0221" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipoccupation1"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_relationshipaddress1"></td>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationship2"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipname2"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0271" data-target-input="nearest">
                      <input type="text" id="time_relationshipbirthday2" class="form-control datetimepicker-input" data-target="#time_0271" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipoccupation2"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_relationshipaddress2"></td>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationship3"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipname3"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0321" data-target-input="nearest">
                      <input type="text" id="time_relationshipbirthday3" class="form-control datetimepicker-input" data-target="#time_0321" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_relationshipoccupation3"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_relationshipaddress3"></td>
                </tr>
                <tr>
                  <th class="col-xs-12" colspan="12"></th>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1" rowspan="7">7</th>
                  <th class="col-xs-11" colspan="11">学歴 Educational background</th>
                </tr>
                <tr>
                  <th class="col-xs-3" colspan="3">学校名<br>Name of  school</th>
                  <th class="col-xs-4" colspan="4">入学年月～卒業年月<br>Admission　date～Graduation date</th>
                  <th class="col-xs-3" colspan="3">所在地<br>Location</th>
                  <th class="col-xs-1" colspan="1">年数<br>Years</th>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_eduschoolname1"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-monthpicker" id="time_0361" data-target-input="nearest">
                      <input type="text" id="time_eduadmission1" class="form-control datetimepicker-input" data-target="#time_0361" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-monthpicker" id="time_0371" data-target-input="nearest">
                      <input type="text" id="time_edugraduation1" class="form-control datetimepicker-input" data-target="#time_0371" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_eduschoollocation1"></td>
                  <td class="col-xs-1 kodama-fill" colspan="1" id="text_eduschoolyear1"></td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_eduschoolname2"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-monthpicker" id="time_0411" data-target-input="nearest">
                      <input type="text" id="time_eduadmission2" class="form-control datetimepicker-input" data-target="#time_0411" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-monthpicker" id="time_0421" data-target-input="nearest">
                      <input type="text" id="time_edugraduation2" class="form-control datetimepicker-input" data-target="#time_0421" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_eduschoollocation2"></td>
                  <td class="col-xs-1 kodama-fill" colspan="1" id="text_eduschoolyear2"></td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_eduschoolname3"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-monthpicker" id="time_0461" data-target-input="nearest">
                      <input type="text" id="time_eduadmission3" class="form-control datetimepicker-input" data-target="#time_0461" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-monthpicker" id="time_0471" data-target-input="nearest">
                      <input type="text" id="time_edugraduation3" class="form-control datetimepicker-input" data-target="#time_0471" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_eduschoollocation3"></td>
                  <td class="col-xs-1 kodama-fill" colspan="1" id="text_eduschoolyear3"></td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_eduschoolname4"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-monthpicker" id="time_0511" data-target-input="nearest">
                      <input type="text" id="time_eduadmission4" class="form-control datetimepicker-input" data-target="#time_0511" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-monthpicker" id="time_0521" data-target-input="nearest">
                      <input type="text" id="time_edugraduation4" class="form-control datetimepicker-input" data-target="#time_0521" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_eduschoollocation4"></td>
                  <td class="col-xs-1 kodama-fill" colspan="1" id="text_eduschoolyear4"></td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_eduschoolname5"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-monthpicker" id="time_0561" data-target-input="nearest">
                      <input type="text" id="time_eduadmission5" class="form-control datetimepicker-input" data-target="#time_0561" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-monthpicker" id="time_0571" data-target-input="nearest">
                      <input type="text" id="time_edugraduation5" class="form-control datetimepicker-input" data-target="#time_0571" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_eduschoollocation5"></td>
                  <td class="col-xs-1 kodama-fill" colspan="1" id="text_eduschoolyear5"></td>
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
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_jpschoolname1"></td>
                  <td class="col-xs-4 kodama-fill text-left" colspan="4" id="text_jpschoollocation1"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0621" data-target-input="nearest">
                      <input type="text" id="time_jpadmission1" class="form-control datetimepicker-input" data-target="#time_0621" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0631" data-target-input="nearest">
                      <input type="text" id="time_jpgraduation1" class="form-control datetimepicker-input" data-target="#time_0631" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_jpschoolname2"></td>
                  <td class="col-xs-4 kodama-fill text-left" colspan="4" id="text_jpschoollocation2"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0661" data-target-input="nearest">
                      <input type="text" id="time_jpadmission2" class="form-control datetimepicker-input" data-target="#time_0661" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0671" data-target-input="nearest">
                      <input type="text" id="time_jpgraduation2" class="form-control datetimepicker-input" data-target="#time_0671" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class="col-xs-12" colspan="12"></th>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1" rowspan="4">9</th>
                  <th class="col-xs-5 text-right" colspan="5">職歴　Occupation career</th>                  
                  <td class="col-xs-2 kodama-fillcontrol text-left" colspan="2" id="radio_occupation">
                    <input name="group_0681" type="radio" id="radio_0682" class="with-gap radio-col-blue-grey" />
                    <label for="radio_0682">無 No</label>
                    <input name="group_0681" type="radio" id="radio_0683" class="with-gap radio-col-green" />
                    <label for="radio_0683">有 Yes</label>
                  </td>
                </tr>
                <tr>
                  <th class="col-xs-2" colspan="2">勤務先<br>Employer's name</th>
                  <th class="col-xs-2" colspan="2">職種<br>Occupation	</th>
                  <th class="col-xs-3" colspan="3">所在地<br>Location</th>
                  <th class="col-xs-4" colspan="4">就職年月日～退職年月日<br>Period of  employment</th>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_employername1"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_occupation1"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_occupationlocation1"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0721" data-target-input="nearest">
                      <input type="text" id="time_employstart1" class="form-control datetimepicker-input" data-target="#time_0721" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0731" data-target-input="nearest">
                      <input type="text" id="time_employend1" class="form-control datetimepicker-input" data-target="#time_0731" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_employername2"></td>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_occupation2"></td>
                  <td class="col-xs-3 kodama-fill text-left" colspan="3" id="text_occupationlocation2"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0771" data-target-input="nearest">
                      <input type="text" id="time_employstart2" class="form-control datetimepicker-input" data-target="#time_0771" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0781" data-target-input="nearest">
                      <input type="text" id="time_employend2" class="form-control datetimepicker-input" data-target="#time_0781" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class="col-xs-12" colspan="12"></th>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1" rowspan="4">10</th>
                  <th class="col-xs-5 text-right" colspan="5">出入国歴 Previous stay in japan</th>               
                  <td class="col-xs-2 kodama-fillcontrol text-left" colspan="2" id="radio_prevjp">
                    <input name="group_0791" type="radio" id="radio_0792" class="with-gap radio-col-blue-grey" />
                    <label for="radio_0792">無 No</label>
                    <input name="group_0791" type="radio" id="radio_0793" class="with-gap radio-col-green" />
                    <label for="radio_0793">有 Yes</label>
                  </td>
                  <th class="col-xs-2" colspan="2">（ある場合の回数：</th>
                  <td class="col-xs-1 kodama-fill" colspan="1" id="text_prevjptimes"></td>
                  <th class="col-xs-1" colspan="1">回）</th>
                </tr>
                <tr>
                  <th class="col-xs-3" colspan="3">入国目的<br>Purpose of entry</th>
                  <th class="col-xs-4" colspan="4">在留資格<br>Visa status</th>
                  <th class="col-xs-4" colspan="4">入国年月日　～　出国年月日<br>Date of entry　　　～　　Date of departure</th>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_entrypurpose1"></td>
                  <td class="col-xs-4 kodama-fill" colspan="4" id="text_visastatus1"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0831" data-target-input="nearest">
                      <input type="text" id="time_prevjpentry1" class="form-control datetimepicker-input" data-target="#time_0831" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0841" data-target-input="nearest">
                      <input type="text" id="time_prevjpdeparture1" class="form-control datetimepicker-input" data-target="#time_0841" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-xs-3 kodama-fill" colspan="3" id="text_entrypurpose2"></td>
                  <td class="col-xs-4 kodama-fill" colspan="4" id="text_visastatus2"></td>
                  <td class="col-xs-2 kodama-fillcontrol" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0871" data-target-input="nearest">
                      <input type="text" id="time_prevjpentry2" class="form-control datetimepicker-input" data-target="#time_0871" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-xs-2" colspan="2">
                    <div class="form-group kodama-datepicker" id="time_0881" data-target-input="nearest">
                      <input type="text" id="time_prevjpdeparture2" class="form-control datetimepicker-input" data-target="#time_0881" data-toggle="datetimepicker"/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class="col-xs-12" colspan="12"></th>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1" rowspan="2">11</th>
                  <th class="col-xs-11" colspan="11">就学理由　Reasons to study in Japan</th>
                </tr>
                <tr>
                  <td class="col-xs-11 kodama-fill text-left" colspan="11" rowspan="1" id="text_reasonstojapan" style="vertical-align: top; height: 400px;"></td>
                </tr>
                <tr>
                  <th class="col-xs-12" colspan="12"></th>
                </tr>
                <tr>
                  <th class="col-xs-1" colspan="1" rowspan="6">12</th>
                  <th class="col-xs-11" colspan="11">修了後の予定　Plans after graduation</th>
                </tr>
                <tr>
                  <td class="col-xs-1 kodama-fillcontrol" colspan="1" rowspan="2">
                    <input type="checkbox" id="checkbox_furtherjpstudy" class="filled-in chk-col-pink"/>
                    <label for="checkbox_furtherjpstudy"></label>
                  </td>
                  <th class="col-xs-2" colspan="2" rowspan="2">日本での進学<br>Further studies in Japan</th>
                  <th class="col-xs-4" colspan="4">進学志望校名<br>Name of  school</th>
                  <th class="col-xs-4" colspan="4">志望学科<br>Subject</th>
                </tr>
                <tr>
                  <td class="col-xs-4 kodama-fill" colspan="4" id="text_furtherschoolname"></td>
                  <td class="col-xs-4 kodama-fill" colspan="4" id="text_furthersubject"></td>
                </tr>
                <tr>
                  <td class="col-xs-1 kodama-fillcontrol" colspan="1">
                    <input type="checkbox" id="checkbox_getjpjob" class="filled-in chk-col-pink"/>
                    <label for="checkbox_getjpjob"></label>
                  </td>
                  <th class="col-xs-2" colspan="2">日本での就職<br>Get a job in Japan</th>
                </tr>
                <tr>
                  <td class="col-xs-1 kodama-fillcontrol" colspan="1">
                    <input type="checkbox" id="checkbox_returncountry" class="filled-in chk-col-pink"/>
                    <label for="checkbox_returncountry"></label>
                  </td>
                  <th class="col-xs-2" colspan="2">帰国<br>Return to home country</th>
                </tr>
                <tr>
                  <td class="col-xs-1 kodama-fillcontrol" colspan="1">
                    <input type="checkbox" id="checkbox_otherplan" class="filled-in chk-col-pink"/>
                    <label for="checkbox_otherplan"></label>f
                  </td>
                  <th class="col-xs-2" colspan="2">その他<br>Others</th>
                  <th class="col-xs-1" colspan="1">説明：</th>
                  <td class="col-xs-7 kodama-fill text-left" colspan="7" id="text_otherplan"></td>
                </tr>
                <tr>
                  <th class="col-xs-12" colspan="12"></th>
                </tr>
                <tr>
                  <th class="col-xs-2" colspan="2" style="border: none;">作成年月日<br>Application date</th>
                  <td class="col-xs-1 kodama-fill" colspan="1" id="text_applicationyear" style="border: none;"></td>
                  <th class="col-xs-1" colspan="1" style="border: none;">年<br>Y</th>
                  <td class="col-xs-1 kodama-fill" colspan="1" id="text_applicationmonth" style="border: none;"></td>
                  <th class="col-xs-1" colspan="1" style="border: none;">月<br>M</th>
                  <td class="col-xs-1 kodama-fill" colspan="1" id="text_applicationdate" style="border: none;"></td>
                  <th class="col-xs-1" colspan="1" style="border: none;">日<br>D</th>
                  <th class="col-xs-2" colspan="2" style="border: none;">本人署名<br>Signature of applicant</th>
                  <td class="col-xs-2 kodama-fill" colspan="2" id="text_signatureapplicant" style="border: none;"></td>
                </tr>

                <!-- style="height: 40px;" -->
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
<script src="../style/js/kodama-photoupload.js"></script>
<script src="../style/js/kodama-table-iba.js"></script>