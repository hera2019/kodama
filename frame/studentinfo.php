<?php
//主题色彩
if ( isset( $_COOKIE[ 'KODAMA_STUDENT_INFO' ] ) && !empty( $_COOKIE[ 'KODAMA_STUDENT_INFO' ] ) ) {
  $StudentInfoString = $_COOKIE[ 'KODAMA_STUDENT_INFO' ];
  $StudentInfo = json_decode($StudentInfoString);
}
?>
<div class="card m-b-10">
  <div class="kodama-header col-<?= $KODAMA_THEME_COLOR; ?>" style="padding-top: 10px; padding-bottom: 10px;">
    <h2><small class="col-<?= $KODAMA_THEME_COLOR; ?>">Student Info: You will operate the data by this student<?= (isset($strinfo) && !empty($strinfo) ? $strinfo : '') ?>.</small></h2>
  </div>
  <div class="body" style="padding-top: 10px; padding-bottom: 10px;">
    <div id="studentid" hidden="true"><?= isset($StudentInfo) ? $StudentInfo->studentid : ''; ?></div>
    <table class="kodama-studentinfo table table-bordered m-b-10">
      <tbody>
        <tr>
          <th class="col-xs-1" colspan="1">学籍番号</th>
          <td class="col-xs-1" colspan="1" id="studentnumber"><?= isset($StudentInfo) ? $StudentInfo->studentnumber : ''; ?></td>
          <th class="col-xs-1" colspan="1">申請番号</th>
          <td class="col-xs-1" colspan="1" id="applicationnumber"><?= isset($StudentInfo) ? $StudentInfo->applicationnumber : ''; ?></td>
          <th class="col-xs-1" colspan="1">クラス名</th>
          <td class="col-xs-1" colspan="1" id="classname"><?= isset($StudentInfo) ? $StudentInfo->classname : ''; ?></td>
          <td class="col-xs-1 photo" colspan="1" rowspan="5">
            <img class="photo" id="info_photo" alt="写真" height="100" src="
              <?php
              $photo = $PHOTO_PATH . 'default/empty.jpg';
              if(isset($StudentInfo) && !empty($StudentInfo)) {
                if(isset($StudentInfo->photo) && !empty($StudentInfo->photo)) {
                  $photo = $PHOTO_PATH . $StudentInfo->photo;
                } else {
                  if(isset($StudentInfo->genderfemale)) {
                    if($StudentInfo->genderfemale == '女 Female') {
                      $photo = $PHOTO_PATH . 'default/female.jpg';
                    } else if($StudentInfo->genderfemale == '男 Male') {
                      $photo = $PHOTO_PATH . 'default/male.jpg';
                    }
                  }
                }
              }
              echo $photo;
              ?>" />
          </td>
        </tr>
        <tr>
          <th class="col-xs-1" colspan="1">氏名(姓・名)</th>
          <td class="col-xs-1" colspan="1"><span id="lastname"><?= isset($StudentInfo) ? $StudentInfo->lastname : ''; ?></span> <span id="firstname"><?= isset($StudentInfo) ? $StudentInfo->firstname : ''; ?></span></td>
          <th class="col-xs-1" colspan="1">国籍・地域</th>
          <td class="col-xs-1" colspan="1" id="nationalityregion"><?= isset($StudentInfo) ? $StudentInfo->nationalityregion : ''; ?></td>
          <th class="col-xs-1" colspan="1">担任</th>
          <td class="col-xs-1" colspan="1" id="classteachername"><?= isset($StudentInfo) ? $StudentInfo->classteachername : ''; ?></td>
        </tr>
        <tr>
          <th class="col-xs-1" colspan="1">フリガナ(姓・名)</th>
          <td class="col-xs-1" colspan="1"><span id="lastnamefurigana"><?= isset($StudentInfo) ? $StudentInfo->lastnamefurigana : ''; ?></span> <span id="firstnamefurigana"><?= isset($StudentInfo) ? $StudentInfo->firstnamefurigana : ''; ?></span></td>
          <th class="col-xs-1" colspan="1">生年月日</th>
          <td class="col-xs-1" colspan="1" id="birthday"><?= isset($StudentInfo) ? $StudentInfo->birthday : ''; ?></td>
          <th class="col-xs-1" colspan="1">在籍狀態</th>
          <td class="col-xs-1" colspan="1" id="statusname"><?= isset($StudentInfo) ? $StudentInfo->statusname : ''; ?></td>
        </tr>
        <tr>
          <th class="col-xs-1" colspan="1">アルファベット(姓・名)</th>
          <td class="col-xs-1" colspan="1"><span id="lastnamealphabet"><?= isset($StudentInfo) ? $StudentInfo->lastnamealphabet : ''; ?></span> <span id="firstnamealphabet"><?= isset($StudentInfo) ? $StudentInfo->firstnamealphabet : ''; ?></span></td>
          <th class="col-xs-1" colspan="1">性别</th>
          <td class="col-xs-1" colspan="1" id="genderfemale"><?= isset($StudentInfo) ? $StudentInfo->genderfemale : ''; ?></td>
          <th class="col-xs-1" colspan="1">累计出席率(昨日まで)</th>
          <td class="col-xs-1" colspan="1" id="attendancebeforeday"></td>
        </tr>
        <tr>
          <th class="col-xs-1" colspan="1">母国语(姓・名)</th>
          <td class="col-xs-1" colspan="1"><span id="lastnamemotherland"><?= isset($StudentInfo) ? $StudentInfo->lastnamemotherland : ''; ?></span> <span id="firstnamemotherland"><?= isset($StudentInfo) ? $StudentInfo->firstnamemotherland : ''; ?></span></td>
          <th class="col-xs-1" colspan="1">携帶電話番号</th>
          <td class="col-xs-1" colspan="1" id="phonenumber"><?= isset($StudentInfo) ? $StudentInfo->phonenumber : ''; ?></td>
          <th class="col-xs-1" colspan="1">累计出席率(前月まで)</th>
          <td class="col-xs-1" colspan="1" id="attendancebeforemonth"></td>
        </tr>
        <tr>
          <th class="col-xs-1" colspan="1">要注意事項</th>
          <td class="col-xs-6" colspan="6" id="description"><?= isset($StudentInfo) ? $StudentInfo->description : ''; ?></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
