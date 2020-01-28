<head>
<!-- code by zmq -->
<?php $INCLUDE_STUDENT_INFO = true; ?>
<?php require_once( 'frame.php' ); ?>
<?php
$message = '';
$ID = '';
//学生信息
if ( isset( $_COOKIE[ 'KODAMA_STUDENT_INFO' ] ) && !empty( $_COOKIE[ 'KODAMA_STUDENT_INFO' ] ) ) {
  $StudentInfoString = $_COOKIE[ 'KODAMA_STUDENT_INFO' ];
  $StudentInfo = json_decode($StudentInfoString);
  if (!empty( $StudentInfo ) ) {
    $ID = $StudentInfo->studentid;
  }
}
?>
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
            <h2>出席状況<small></small></h2>
            <ul class="header-button">
              <li><a href="javascript:void(0);" onclick="refreshRecord();">
                <div class="kodama-icon-circle bg-orange"> <i class="material-icons">query_builder</i> </div>
                <div class="kodama-menu-info"><h4>Reload</h4></div>
              </a></li>
              <li><a href="../attend/situation_build.php">
                <div class="kodama-icon-circle bg-cyan"> <i class="material-icons">person_add</i> </div>
                <div class="kodama-menu-info"><h4>Build</h4></div>
              </a></li>
            </ul>
          </div>
          <div class="body">
            <div  style="padding-left: 0rem;">
              <div id="message" class="alert-warning align-left col-white" style="line-height: 23px; width: 100%;"><?= $message; ?></div>
            </div>
            <table class="kodama-table table-striped table-hover" style="width: 100%;">
              <caption><div class="text-left alert-warning align-left col-white" id="message"></div></caption>
              <thead class="bg-<?= $KODAMA_THEME_COLOR; ?>">
                <tr>
                  <th colspan="2">对象</th>
                  <th colspan="31">日（ <span class="bg-green">出:出席</span> <span class="bg-red">欠:欠席</span> <span class="bg-orange">遅:遅刻，早退</span> <span class="bg-brown">公:公欠</span> <span class="bg-blue-grey">休:休学</span> <span class="bg-grey">帰:一時帰国</span> <span class="bg-black">-:休校日</span> ）</th>
                  <th colspan="5">コマ数</th>
                  <th colspan="4">日数</th>
                </tr>
                <tr>
                  <th style="min-width: 60px;">年</th>
                  <th style="min-width: 40px;">月</th>
                  <?php for($i=1; $i<=31; $i++): ?>
                  <th style="min-width: 20px;"><?= $i ?></th>
                  <?php endfor; ?>
                  <th style="min-width: 30px;">全</th>
                  <th style="min-width: 30px;" class="bg-green">出席</th>
                  <th style="min-width: 30px;" class="bg-red">欠席</th>
                  <th style="min-width: 30px;" class="bg-orange">遅早</th>
                  <th style="min-width: 50px;">出席率</th>
                  <th style="min-width: 30px;">全</th>
                  <th style="min-width: 30px;" class="bg-green">出席</th>
                  <th style="min-width: 30px;" class="bg-red">欠席</th>
                  <th style="min-width: 50px;">出席率</th>
                </tr>
              </thead>
              <tbody id='tbody'>
              </tbody>
              <tfoot class="bg-<?= $KODAMA_THEME_COLOR; ?>">
                <tr>
                  <th>年</th>
                  <th>月</th>
                  <?php for($i=1; $i<=31; $i++): ?>
                  <th><?= $i ?></th>
                  <?php endfor; ?>
                  <th>全</th>
                  <th class="bg-green">出席</th>
                  <th class="bg-red">欠席</th>
                  <th class="bg-orange">遅早</th>
                  <th>出席率</th>
                  <th>全</th>
                  <th class="bg-green">出席</th>
                  <th class="bg-red">欠席</th>
                  <th>出席率</th>
                </tr>
              </tfoot>
            </table>
            <input type="hidden" name="mod" id="mod" value="updatedescription" />
            <input type="hidden" name="ID" id="text_ID" value="<?= $ID; ?>" />
            <div id='echo'><?= isset($echo) ? $echo : ''; ?></div>
          </div>
        </div>
      </div>
    </div>      
  </div>
</section>
<script src="../style/js/kodama-table-student-attendance.js"></script>