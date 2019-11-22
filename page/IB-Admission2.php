<?php require_once( 'frame.php' ); ?><head>
<!-- Bootstrap Material Datetime Picker Css -->
<!-- <link href="../style/css/bootstrap-material-datetimepicker.css" rel="stylesheet" /> -->
<link href="../style/css/bootstrap-datetimepicker.css" rel="stylesheet" />
<link href="../style/css/kodama.css" rel="stylesheet">
</head>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="kodama-header col-<?= $KODAMA_THEME_COLOR; ?>">
            <h2>東京四木教育学院入学願書及び履歴書<small>THE APPLICATION FOR ADMISSION TO TOKYO YOTSUGI EDUCATION ACADEMY</small></h2>
            <ul class="header-dropdown m-r--5">
              <li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                <ul class="dropdown-menu pull-right">
                  <ul class="menu">
                    <li> <a href="javascript:void(0);" onclick="refreshData_001();">
                        <div class="kodama-icon-circle bg-green"> <i class="material-icons">save</i> </div>
                        <div class="kodama-menu-info">
                          <h4>Refresh Data</h4>
                        </div>
                      </a> </li>
                    <li> <a href="javascript:void(0);" onclick="saveData_001();">
                        <div class="kodama-icon-circle bg-green"> <i class="material-icons">save</i> </div>
                        <div class="kodama-menu-info">
                          <h4>Save Data</h4>
                        </div>
                      </a> </li>
                  </ul>
                </ul>
              </li>
            </ul>
          </div>
          <div class="body">
            <table id="mainTable" class="table table-bordered text-center">
              <caption></caption>
              <thead>
                <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="col-md-12 text-center" colspan="12"><h4>東京四木教育学院入学願書及び履歴書</h4><small>THE APPLICATION FOR ADMISSION TO TOKYO YOTSUGI EDUCATION ACADEMY</small></th>
                </tr>
                <tr>
                  <td class="col-md-12 text-left" colspan="12" id="export"></td>
                </tr>
                <tr>
                  <td class="col-md-12 text-left" colspan="12" id="message">Use the ctrl+enter to wrap in the input box.</td>
                </tr>
                <tr>
                  <td class="col-md-1" colspan="1">1</td>
                  <td class="col-md-2" colspan="2">国籍<br>Nationality</td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_001"></td>
                  <td class="col-md-2" colspan="2">出生地<br>Place of  birth</td>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_002"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" rowspan="6" id="data_003"></td>
                </tr>
                <tr>
                  <td class="col-md-1" colspan="1">2</td>
                  <td class="col-md-2" colspan="2">氏名<br>Name</td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_004"></td>
                  <td class="col-md-2" colspan="2">英字<br>Alphabet letters</td>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_005"></td>
                </tr>
                <tr>
                  <td class="col-md-1" colspan="1">3</td>
                  <td class="col-md-2" colspan="2">生年月日<br>Date of  birth</td>
                  <td class="col-md-3 kodama-fillcontrol" colspan="3" id="time_006">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                  <td class="col-md-2" colspan="2">性別<br>Sex</td>
                  <td class="col-md-1 kodama-fillcontrol" colspan="2" id="radio_007"> 
                    <input name="group_0071" type="radio" id="radio_0072" class="with-gap radio-col-blue" />
                    <label for="radio_0072">男 Male</label>
                    <input name="group_0071" type="radio" id="radio_0073" class="with-gap radio-col-pink" />
                    <label for="radio_0073">女 Female</label>
                  </td>
                </tr>
                <tr>
                  <td class="col-md-1" colspan="1" rowspan="2">4</td>
                  <td class="col-md-2" colspan="2">現住所<br>Address</td>
                  <td class="col-md-7 kodama-fill" colspan="7" id="data_008"></td>
                </tr>
                <tr>
                  <td class="col-md-2" colspan="2">户籍所在地<br>Address</td>
                  <td class="col-md-7 kodama-fill" colspan="7" id="data_009"></td>
                </tr>
                <tr>
                  <td class="col-md-1" colspan="1">5</td>
                  <td class="col-md-2" colspan="2">配偶者の有無<br>Married status</td>
                  <td class="col-md-1 kodama-fillcontrol" colspan="2" id="radio_010">
                    <input name="group_0101" type="radio" id="radio_0102" class="with-gap radio-col-grey" />
                    <label for="radio_0102">無 Single</label>
                    <input name="group_0101" type="radio" id="radio_0103" class="with-gap radio-col-green" />
                    <label for="radio_0103">有 Married</label>
                  </td>
                  <td class="col-md-2" colspan="2">配偶者氏名<br>Spouses' name</td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_011"></td>
                </tr>
                <tr>
                  <td class="col-md-12" colspan="12"></td>
                </tr>
                <tr>
                  <td class="col-md-1" colspan="1" rowspan="7">6</td>
                  <td class="col-md-11" colspan="11">家族 Family</td>
                </tr>
                <tr>
                  <td class="col-md-2" colspan="2">続柄<br>Relationship</td>
                  <td class="col-md-2" colspan="2">氏名<br>Full name</td>
                  <td class="col-md-2" colspan="2">生年月日<br>Date of  birth</td>
                  <td class="col-md-2" colspan="2">職業<br>Occupation</td>
                  <td class="col-md-3" colspan="3">本国の現住所<br>Address in home country</td>
                </tr>
                <tr>
                  <td class="col-md-2" colspan="2">父親<br>Father</td>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_012"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_013">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_014"></td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_015"></td>
                </tr>
                <tr>
                  <td class="col-md-2" colspan="2">母親<br>Mother</td>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_016"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_017">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_018"></td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_019"></td>
                </tr>
                <tr>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_020"></td>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_021"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_022">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_023"></td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_024"></td>
                </tr>
                <tr>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_025"></td>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_026"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_027">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_028"></td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_029"></td>
                </tr>
                <tr>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_030"></td>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_031"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_032">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_033"></td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_034"></td>
                </tr>
                <tr>
                  <td class="col-md-12" colspan="12"></td>
                </tr>
                <tr>
                  <td class="col-md-1" colspan="1" rowspan="7">7</td>
                  <td class="col-md-11" colspan="11">学歴 Educational background</td>
                </tr>
                <tr>
                  <td class="col-md-3" colspan="3">学校名<br>Name of  school</td>
                  <td class="col-md-4" colspan="4">入学年月～卒業年月<br>Admission　date～Graduation date</td>
                  <td class="col-md-3" colspan="3">所在地<br>Location</td>
                  <td class="col-md-1" colspan="1">年数<br>Years</td>
                </tr>
                <tr>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_035"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_036">
                    <div class="form-group">
                      <input type='text' class="kodama-monthpicker form-control text-center" placeholder="month..."/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-md-2" colspan="2" id="time_037">
                    <div class="form-group">
                      <input type='text' class="kodama-monthpicker form-control text-center" placeholder="month..."/>
                    </div>
                  </td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_038"></td>
                  <td class="col-md-1 kodama-fill" colspan="1" id="data_039"></td>
                </tr>
                <tr>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_040"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_041">
                    <div class="form-group">
                      <input type='text' class="kodama-monthpicker form-control text-center" placeholder="month..."/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-md-2" colspan="2" id="time_042">
                    <div class="form-group">
                      <input type='text' class="kodama-monthpicker form-control text-center" placeholder="month..."/>
                    </div>
                  </td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_043"></td>
                  <td class="col-md-1 kodama-fill" colspan="1" id="data_044"></td>
                </tr>
                <tr>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_045"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_046">
                    <div class="form-group">
                      <input type='text' class="kodama-monthpicker form-control text-center" placeholder="month..."/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-md-2" colspan="2" id="time_047">
                    <div class="form-group">
                      <input type='text' class="kodama-monthpicker form-control text-center" placeholder="month..."/>
                    </div>
                  </td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_048"></td>
                  <td class="col-md-1 kodama-fill" colspan="1" id="data_049"></td>
                </tr>
                <tr>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_050"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_051">
                    <div class="form-group">
                      <input type='text' class="kodama-monthpicker form-control text-center" placeholder="month..."/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-md-2" colspan="2" id="time_052">
                    <div class="form-group">
                      <input type='text' class="kodama-monthpicker form-control text-center" placeholder="month..."/>
                    </div>
                  </td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_053"></td>
                  <td class="col-md-1 kodama-fill" colspan="1" id="data_054"></td>
                </tr>
                <tr>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_055"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_056">
                    <div class="form-group">
                      <input type='text' class="kodama-monthpicker form-control text-center" placeholder="month..."/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-md-2" colspan="2" id="time_057">
                    <div class="form-group">
                      <input type='text' class="kodama-monthpicker form-control text-center" placeholder="month..."/>
                    </div>
                  </td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_058"></td>
                  <td class="col-md-1 kodama-fill" colspan="1" id="data_059"></td>
                </tr>
                <tr>
                  <td class="col-md-12" colspan="12"></td>
                </tr>
                <tr>
                  <td class="col-md-1" colspan="1" rowspan="4">8</td>
                  <td class="col-md-11" colspan="11">日本語学習歴　Experience of Japanese studying</td>
                </tr>
                <tr>
                  <td class="col-md-3" colspan="3">学校名<br>Name of  school</td>
                  <td class="col-md-4" colspan="4">所在地<br>Location</td>
                  <td class="col-md-4" colspan="4">入学年月日～卒業年月日<br>Admission　date～Graduation date</td>
                </tr>
                <tr>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_060"></td>
                  <td class="col-md-4 kodama-fill" colspan="4" id="data_061"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_062">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-md-2" colspan="2" id="time_063">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_064"></td>
                  <td class="col-md-4 kodama-fill" colspan="4" id="data_065"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_066">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-md-2" colspan="2" id="time_067">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-md-12" colspan="12"></td>
                </tr>
                <tr>
                  <td class="col-md-1" colspan="1" rowspan="4">9</td>
                  <td class="col-md-5 text-right" colspan="5">職歴　Occupation career</td>                  
                  <td class="col-md-2 kodama-fillcontrol text-left" colspan="2" id="radio_068">
                    <input name="group_0681" type="radio" id="radio_0682" class="with-gap radio-col-grey" />
                    <label for="radio_0682">無 No</label>
                    <input name="group_0681" type="radio" id="radio_0683" class="with-gap radio-col-green" />
                    <label for="radio_0683">有 Yes</label>
                  </td>
                  <td class="col-md-4" colspan="4"></td>
                </tr>
                <tr>
                  <td class="col-md-2" colspan="2">勤務先<br>Employer's name</td>
                  <td class="col-md-2" colspan="2">職種<br>Occupation	</td>
                  <td class="col-md-3" colspan="3">所在地<br>Location</td>
                  <td class="col-md-4" colspan="4">就職年月日～退職年月日<br>Period of  employment</td>
                </tr>
                <tr>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_069"></td>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_070"></td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_071"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_072">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-md-2" colspan="2" id="time_073">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_074"></td>
                  <td class="col-md-2 kodama-fill" colspan="2" id="data_075"></td>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_076"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_077">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-md-2" colspan="2" id="time_078">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-md-12" colspan="12"></td>
                </tr>
                <tr>
                  <td class="col-md-1" colspan="1" rowspan="4">10</td>
                  <td class="col-md-5 text-right" colspan="5">出入国歴 Previous stay in japan</td>               
                  <td class="col-md-2 kodama-fillcontrol text-left" colspan="2" id="radio_079">
                    <input name="group_0791" type="radio" id="radio_0792" class="with-gap radio-col-grey" />
                    <label for="radio_0792">無 No</label>
                    <input name="group_0791" type="radio" id="radio_0793" class="with-gap radio-col-green" />
                    <label for="radio_0793">有 Yes</label>
                  </td>
                  <td class="col-md-2" colspan="2">（ある場合の回数：</td>
                  <td class="col-md-1 kodama-fill" colspan="1" id="data_080"></td>
                  <td class="col-md-1" colspan="1">回）</td>
                </tr>
                <tr>
                  <td class="col-md-3" colspan="3">入国目的<br>Purpose of entry</td>
                  <td class="col-md-4" colspan="4">在留資格<br>Visa status</td>
                  <td class="col-md-4" colspan="4">入国年月日　～　出国年月日<br>Date of entry　　　～　　Date of departure</td>
                </tr>
                <tr>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_081"></td>
                  <td class="col-md-4 kodama-fill" colspan="4" id="data_082"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_083">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-md-2" colspan="2" id="time_084">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-md-3 kodama-fill" colspan="3" id="data_085"></td>
                  <td class="col-md-4 kodama-fill" colspan="4" id="data_086"></td>
                  <td class="col-md-2 kodama-fillcontrol" colspan="2" id="time_087">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                  <td class="kodama-fillcontrol col-md-2" colspan="2" id="time_088">
                    <div class="form-group">
                      <input type='text' class="kodama-datepicker form-control text-center" placeholder="date..."/>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="col-md-12" colspan="12"></td>
                </tr>
                <tr>
                  <td class="col-md-1" colspan="1" rowspan="3">11</td>
                  <td class="col-md-11" colspan="11">就学理由　Reasons to study in Japan</td>
                </tr>
                <tr>
                  <td class="col-md-11 kodama-fill text-left" colspan="11" rowspan="2" id="data_089" style="vertical-align: top; height: 400px;"></td>
                </tr>
                <tr>
                  <td class="col-md-12" colspan="12"></td>
                </tr>
                <tr>
                  <td class="col-md-1" colspan="1" rowspan="5">12</td>
                  <td class="col-md-11" colspan="11">修了後の予定　Plans after graduation</td>
                </tr>
                <tr>
                  <td class="col-md-1 kodama-fillcontrol" colspan="1" id="checkbox_090">
                    <input type="checkbox" id="checkbox_0901" class="filled-in chk-col-pink"/>
                    <label for="checkbox_0901"></label>
                  </td>
                  <td class="col-md-2" colspan="2">日本での進学<br>Further studies in Japan</td>
                  <td class="col-md-4" colspan="4">進学志望校名<br>Name of  school</td>
                  <td class="col-md-4" colspan="4">志望学科<br>Subject</td>
                </tr>
                <tr>
                  <td class="col-md-1 kodama-fillcontrol" colspan="1" id="checkbox_091">
                    <input type="checkbox" id="checkbox_0911" class="filled-in chk-col-pink"/>
                    <label for="checkbox_0911"></label>
                  </td>
                  <td class="col-md-2" colspan="2">日本での就職<br>Get a job in Japan</td>
                  <td class="col-md-4 kodama-fill" colspan="4" rowspan="2" id="data_092"></td>
                  <td class="col-md-4 kodama-fill" colspan="4" rowspan="2" id="data_093"></td>
                </tr>
                <tr>
                  <td class="col-md-1 kodama-fillcontrol" colspan="1" id="checkbox_094">
                    <input type="checkbox" id="checkbox_0941" class="filled-in chk-col-pink"/>
                    <label for="checkbox_0941"></label>
                  </td>
                  <td class="col-md-2" colspan="2">帰国<br>Return to home country</td>
                </tr>
                <tr>
                  <td class="col-md-1 kodama-fillcontrol" colspan="1" id="checkbox_095">
                    <input type="checkbox" id="checkbox_0951" class="filled-in chk-col-pink"/>
                    <label for="checkbox_0951"></label>
                  </td>
                  <td class="col-md-2" colspan="2">その他<br>Others</td>
                  <td class="col-md-1" colspan="1">説明：</td>
                  <td class="col-md-7 kodama-fill" colspan="7" id="data_096"></td>
                </tr>
                <tr>
                  <td class="col-md-12" colspan="12"></td>
                </tr>
                                
                <!-- style="height: 40px;" -->
              </tbody>
              <tfoot>
                <tr>
                  <th class="col-md-12" colspan="12"></th>
                </tr>
              </tfoot>
            </table>
        </div>
      </div>
    </div>      
  </div>
</section>

<!-- Editable Table Js -->
<script src="../style/js/mindmup-editabletable.js"></script>
<script src="../style/js/editable-table.js"></script>
<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="../style/js/autosize.js"></script>
<script src="../style/js/moment-with-locales.js"></script>
<!-- <script src="../style/js/bootstrap-material-datetimepicker.js"></script> -->
<script src="../style/js/bootstrap-datetimepicker.js"></script>
<!-- <script src="../style/js/basic-form-elements.js"></script> -->
<!-- <script src="../style/js/locale/ja.js"></script> -->
<script src="../style/js/kodama-table.js"></script>