<head>
<!-- code by zmq -->
<?php $INCLUDE_STUDENT_INFO = true; ?>
<?php require_once( 'frame.php' ); ?>
<?php require_once('../include/include_database.php'); ?>
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
              <li><a href="javascript:void(0);"Modify>
                <div class="kodama-icon-circle bg-orange"> <i class="material-icons">query_builder</i> </div>
                <div class="kodama-menu-info">
                  <h4>Reload</h4>
                </div>
              </a></li>
              <li><a href="javascript:void(0);"Modify>
                <div class="kodama-icon-circle bg-light-blue"> <i class="material-icons">mode_edit</i> </div>
                <div class="kodama-menu-info">
                  <h4>Modify</h4>
                </div>
              </a></li>
            </ul>
          </div>
          <div class="body">
            <table class="kodama-table table-striped table-hover">
              <caption><div class="text-left alert-warning align-left col-white" id="message"></div></caption>
              <thead class="bg-<?= $KODAMA_THEME_COLOR; ?>">
                <tr>
                  <th colspan="2">对象</th>
                  <th colspan="4">日数</th>
                  <th colspan="5">コマ数</th>
                  <th colspan="31">日（ <span class="bg-green">出:出席</span> <span class="bg-red">欠:欠席</span> <span class="bg-orange">遅:遅刻，早退</span> <span class="bg-brown">公:公欠</span> <span class="bg-blue-grey">休:休学</span> <span class="bg-grey">帰:一時帰国</span> <span class="bg-black">-:休校日</span> ）</th>
                </tr>
                <tr>
                  <th style="width: 60px;">年</th>
                  <th style="width: 40px;">月</th>
                  <th style="width: 30px;">全</th>
                  <th style="width: 30px;" class="bg-green">出席</th>
                  <th style="width: 30px;" class="bg-red">欠席</th>
                  <th style="width: 50px;">出席率</th>
                  <th style="width: 30px;">全</th>
                  <th style="width: 30px;" class="bg-green">出席</th>
                  <th style="width: 30px;" class="bg-red">欠席</th>
                  <th style="width: 30px;" class="bg-orange">遅早</th>
                  <th style="width: 50px;">出席率</th>
                  <?php for($i=1; $i<=31; $i++): ?>
                  <th style="width: 20px;"><?= $i ?></th>
                  <?php endfor; ?>
                </tr>
              </thead>
              <tbody>
                <?php
                $studentID = isset($StudentInfo) ? $StudentInfo->studentid : '';
                if(empty($studentID)) {
                  $message = "Student not choose";
                  //return;
                }
                $time = time();
                $sql = 'SELECT classstartdate FROM student2 WHERE ID=:ID';
                $statement = $connection->prepare($sql);
                $statement->execute( [ ':ID' => $studentID ] );
                $recordclassstartdate = $statement->fetch( PDO::FETCH_OBJ );
                if(!empty($recordclassstartdate)) {
                  $classstartdate = $recordclassstartdate->classstartdate;
                  if($time < strtotime($classstartdate)) {
                    $message = "入学時間未到";
                    //return;
                  }
                }
                if(!isset($classstartdate) || empty($classstartdate)) {
                  $classstartdate = date('Y-m-d', $time - 2*365*24*3600);
                }
                
                $time1 = strtotime($classstartdate);//开始时间 时间戳
                $year1  = date("Y", $time1) + 0;   // 时间1的年份
                $month1 = date("m", $time1) + 0;   // 时间1的月份

                $year2  = date("Y", $time) + 0;   // 时间2的年份
                $month2 = date("m", $time) + 0;   // 时间2的月份
                $echo = "";
                //$echo .= $month2 . " ";
                //$echo .= $month1 . " ";
                if($year2 - $year1 > 2) {
                  $year1 = $year2 - 2;
                }
                
                $sql = 'SELECT attendance.recordtime AS recordtime, attendance.ID AS ID, attendproperty.ID AS propertyID, attendproperty.property AS propertyname FROM attendance LEFT JOIN attendproperty ON attendance.property = attendproperty.ID WHERE studentID=:ID';
                $statement = $connection->prepare($sql);
                $statement->execute( [ ':ID' => $studentID ] );
                $recordattend = $statement->fetchAll(PDO::FETCH_OBJ);
                $attendrec = array
                (
                  array(
                    array(),
                  ),
                );
                foreach($recordattend as $recordattend1) {
                  $timerec = strtotime($recordattend1->recordtime);
                  $yearrec  = date("Y", $timerec) + 0;   // 时间1的年份
                  $monthrec = date("m", $timerec) + 0;   // 时间1的月份
                  $dayrec = date("d", $timerec) + 0;   // 时间1的日份
                  $attendrec[$yearrec][$monthrec][$dayrec] = $recordattend1->propertyname;
                }
                
                for($year = $year1; $year <= $year2; $year++) {
                  if($year == $year1) {
                    $m1 = $month1;
                    $m2 = 12;
                  } else if($year == $year2) {
                    $m1 = 1;
                    $m2 = $month2;
                  } else {                    
                    $m1 = 1;
                    $m2 = 12;
                  }
                  for($i=$m1; $i<=$m2; $i++) {
                    echo '<tr>';
                    if($i == $m1) {                      
                      echo '<td rowspan=' . ($m2 - $m1 + 1) . '>' . $year . '年</td>';
                    }
                    echo '<td>' . $i . '月</td>';
                    
                    echo '<td>20</td>';
                    echo '<td class="col-green">16</td>';
                    echo '<td class="col-red">2</td>';
                    echo '<td>90%</td>';
                    echo '<td>80</td>';
                    echo '<td class="col-green">64</td>';
                    echo '<td class="col-red">8</td>';
                    echo '<td class="col-orange">8</td>';
                    echo '<td>90%</td>';
                    
                    for($j=1; $j<=31; $j++) {
                      echo '<td';
                      if(isset($attendrec[$year]) && isset($attendrec[$year][$i]) && isset($attendrec[$year][$i][$j])) {
                        if($attendrec[$year][$i][$j] == '出')
                        {
                          echo ' class="col-green"';
                        } else if($attendrec[$year][$i][$j] == '欠') {
                          echo ' class="col-red"';
                        } else if($attendrec[$year][$i][$j] == '遅') {
                          echo ' class="col-orange"';
                        } else if($attendrec[$year][$i][$j] == '公') {
                          echo ' class="col-brown"';
                        } else if($attendrec[$year][$i][$j] == '休') {
                          echo ' class="col-blue-grey"';
                        } else if($attendrec[$year][$i][$j] == '帰') {
                          echo ' class="col-grey"';
                        } else if($attendrec[$year][$i][$j] == '-') {
                          echo ' class="col-black"';
                        }
                        echo '>' . $attendrec[$year][$i][$j];
                      }
                      else {
                        echo '>';
                      }
                      echo '</td>';
                    }
                    echo '</tr>';
                  }
                }
                
                //$recordteacher = $statement->fetchAll( PDO::FETCH_OBJ );
                ?>
                <tr>
                  <td class="bg-pink"></td>
                  <td id="ID" hidden="hidden"><?= $studentID; ?></td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                </tr>
              </tfoot>
            </table>
            <div class="text-left alert-warning align-left col-white"><?= isset($echo) ? $echo : ''; ?></div>
          </div>
        </div>
      </div>
    </div>      
  </div>
</section>

<script type="text/javascript">
$(document).ready(function(){
  //g_feerecordnum = <?php echo $php_feerecordnum; ?>;
});
</script>