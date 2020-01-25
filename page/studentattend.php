<head>
<!-- code by zmq -->
<?php $INCLUDE_STUDENT_INFO = true; ?>
<?php require_once( 'frame.php' ); ?>
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
              <li><a href="refreshRecord()">
                <div class="kodama-icon-circle bg-orange"> <i class="material-icons">query_builder</i> </div>
                <div class="kodama-menu-info">
                  <h4>Reload</h4>
                </div>
              </a></li>
              <li><a href="../attend/situation_build.php">
                <div class="kodama-icon-circle bg-cyan"> <i class="material-icons">person_add</i> </div>
                <div class="kodama-menu-info"><h4>Build</h4></div>
              </a></li>
              <li><a href="../page/checkinrecord.php?ID=<?= $studentID = isset($StudentInfo) ? $StudentInfo->studentid : ''; ?>">
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
                  <th colspan="31">日（ <span class="bg-green">出:出席</span> <span class="bg-red">欠:欠席</span> <span class="bg-orange">遅:遅刻，早退</span> <span class="bg-brown">公:公欠</span> <span class="bg-blue-grey">休:休学</span> <span class="bg-grey">帰:一時帰国</span> <span class="bg-black">-:休校日</span> ）</th>
                  <th colspan="5">コマ数</th>
                  <th colspan="4">日数</th>
                </tr>
                <tr>
                  <th style="width: 60px;">年</th>
                  <th style="width: 40px;">月</th>
                  <?php for($i=1; $i<=31; $i++): ?>
                  <th style="width: 20px;"><?= $i ?></th>
                  <?php endfor; ?>
                  <th style="width: 30px;">全</th>
                  <th style="width: 30px;" class="bg-green">出席</th>
                  <th style="width: 30px;" class="bg-red">欠席</th>
                  <th style="width: 30px;" class="bg-orange">遅早</th>
                  <th style="width: 50px;">出席率</th>
                  <th style="width: 30px;">全</th>
                  <th style="width: 30px;" class="bg-green">出席</th>
                  <th style="width: 30px;" class="bg-red">欠席</th>
                  <th style="width: 50px;">出席率</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $studentID = isset($StudentInfo) ? $StudentInfo->studentid : '';
                if(empty($studentID)) {
                  $message = "Student not choose";
                  //return;
                }
                
                require_once( '../attend/getstudentmonthattand.php' );
                
                for($year=$year1; $year<=$year2; $year++) {
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
                    
                    //计算全部课时
                    $allday = 0;
                    //计算每日签到
                    $attendday = 0;
                    $absentday = 0;
                    $lateday = 0;
                    for($j=1; $j<=31; $j++) {
                      echo '<td';
                      $property = 0;
                      $propertyname = "";
                      for($k=0; $k<=4; $k++) {
                        $propertykey = 'd' . $j . 'c' . $k;

                        //每日上课课时
                        if(isset($classrec[$year]) && isset($classrec[$year][$i]) && isset($classrec[$year][$i][$propertykey])) {
                          //$echo .= $year . ' ' . $i . ' ' . $propertykey . ' ' . $classrec[$year][$i][$propertykey] . " property 2 <br>";
                          if($classrec[$year][$i][$propertykey] == 1) {
                            $allday = $allday + 1;
                            if(!isset($attendrec[$year]) || !isset($attendrec[$year][$i]) || !isset($attendrec[$year][$i][$propertykey])) {
                              $attendrec[$year][$i][$propertykey] = 2;
                            }

                            //每日出勤情况
                            if(isset($attendrec[$year]) && isset($attendrec[$year][$i]) && isset($attendrec[$year][$i][$propertykey])) {
                              $property1 = $attendrec[$year][$i][$propertykey];
                              $arraypriority = array(1=>2, 5, 4, 3, 7, 6, 1);
                              $priority = array_search($property, $arraypriority);
                              $priority1 = array_search($property1, $arraypriority);
                              if($priority < $priority1) {
                                $property = $property1;
                              }
                            }
                          }
                        }
                      }
                      $propertyname = $arrayproperty[$property];
                      if($property == 1) { //'出'
                        $attendday = $attendday + 1;
                        echo ' class="col-green"';
                      } else if($property == 2) { //'欠'
                        $absentday = $absentday + 1;
                        echo ' class="col-red"';
                      } else if($property == 3) { //'公'
                        echo ' class="col-brown"';
                      } else if($property == 4) { //'休'
                        echo ' class="col-blue-grey"';
                      } else if($property == 5) { //'帰'
                        echo ' class="col-grey"';
                      } else if($property == 6) { //'遅'
                        $lateday = $lateday + 1;
                        echo ' class="col-orange"';
                      } else if($property == 7) { //'-'
                        echo ' class="col-black"';
                      }
                      echo '>' . $propertyname . '</td>';
                    }

                    $attendpercent = "";
                    if($allday > 0) {
                      $attendpercent = round(($attendday + $lateday) / $allday * 100) . "%";
                    }
                    echo '<td>' . $allday * 4 . '</td>';
                    echo '<td class="col-green">' . $attendday * 4 . '</td>';
                    echo '<td class="col-red">' . $absentday * 4 . '</td>';
                    echo '<td class="col-orange">' . $lateday * 4 . '</td>';
                    echo '<td>' . $attendpercent . '</td>';
                    echo '<td>' . $allday . '</td>';
                    echo '<td class="col-green">' . $attendday . '</td>';
                    echo '<td class="col-red">' . $absentday . '</td>';
                    echo '<td>' . $attendpercent . '</td>';

                    echo '</tr>';
                  }
                }
                ?>
                <tr>
                  <td class="bg-pink"></td>
                  <td id="ID" hidden="hidden"><?= $studentID; ?></td>
                </tr>
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
            <div><?= isset($echo) ? $echo : ''; ?></div>
          </div>
        </div>
      </div>
    </div>      
  </div>
</section>
<script type="text/javascript">
  function refreshRecord() {
    location.reload();
  }
</script>