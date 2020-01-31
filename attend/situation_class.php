<?php
require_once '../include/include_database.php';
//查询每日上课时间

require_once 'attend_class.php';

use Attend\LessonClass;
$LessonClass = new LessonClass($connection);

$time = time();
//签到时间段：$classstart-$aheadperiod到$classend之间
$lasttime = '2019-07-01 00:00:00';

if($REBUILD_ALL) { //重新生成
  $sqltruncate = 'DELETE * FROM situationclass WHERE manualmodified<1';
  echo $sqltruncate . '<br>';
  $statement = $connection->prepare( $sqltruncate );
  $statement->execute();
  $sql = 'SELECT recordtime FROM attendance ORDER BY ID ASC LIMIT 1';
} else {
  $lastID = 0;
  $sql2 = 'SELECT recordID, recordtime FROM lastrecord WHERE tablename="attendance"';
  $statement = $connection->prepare( $sql2 );
  $statement->execute();
  $recordlastrecord = $statement->fetch( PDO::FETCH_OBJ ); //只有一条记录不用fetchAll
  if($recordlastrecord) {
    $lastID = $recordlastrecord->recordID;
    if($recordlastrecord->recordtime) {
      $lasttime = $recordlastrecord->recordtime;
    }
  }
  
  if($lastID > 0) {
    $sql = 'SELECT recordtime FROM attendance WHERE ID > ' . $lastID . ' ORDER BY ID ASC LIMIT 1';
  }
}
$statement = $connection->prepare( $sql );
$statement->execute();
$recordattendance = $statement->fetch(PDO::FETCH_OBJ);
if($recordattendance) {
  $lasttime = $recordattendance->recordtime;
}
//echo $lastID . ' ' . $lasttime . ': lastID lasttime<br>';

//查询班级ID、学生人数
$classlesson = array(); //classID // 日期，年月01日，'Y-m-01'
$sql = 'SELECT ID FROM class';
$statement = $connection->prepare( $sql );
$statement->execute();
$recordclass = $statement->fetchAll( PDO::FETCH_OBJ );
foreach ( $recordclass as $recordclass ) {
  $classID = $recordclass->ID;
  $sql = "SELECT COUNT(*) from student WHERE classID=:classID";
  $statement = $connection->prepare( $sql );
  $statement->execute( [ ':classID' => $classID ] );
  $studentnum = $statement->fetchColumn();
  if ( $studentnum > 0 && $classID > 0 ) {
    
    $lasttime = date( 'Y-m-d', strtotime($lasttime) );
    $nexttime = strtotime($lasttime);
    $daynum = ($time - $nexttime) / 3600 / 24;
    for($i=0; $i<$daynum; $i++) {
      $thatday = date( 'Y-m-d', $nexttime );
      //echo $thatday . ' : thatday<br>';
      
      for($classindex=1; $classindex<=$LessonClass->GetClassTimeNum(); $classindex++) {
        $bFind = FALSE;
        $classstart = $LessonClass->GetClassTime1($classindex);
        $classend = $LessonClass->GetClassTime2($classindex);
        $lessons = $LessonClass->GetClassLessons($classindex);
        
        $recordtime = $thatday . ' ' . date( 'H:i:s', strtotime( $classstart ) );
        $endtime = $thatday . ' ' . date( 'H:i:s', strtotime( $classend ) );

        //判断当前上课时间classschedule记录是否已生成，已生成则退出
        $sql = "SELECT * FROM situationclass WHERE classID=:classID AND (recordtime between :starttime and :endtime)";
        $statement = $connection->prepare( $sql );
        $statement->execute( [ ':classID' => $classID, ':starttime' => $recordtime, ':endtime' => $endtime ] );
        $recordsituationclass = $statement->fetchAll( PDO::FETCH_OBJ );

        $starttime = $thatday . ' ' . date( 'H:i:s', strtotime( $classstart ) - $LessonClass->GetClassAheadPeriod() * 60 ); //提前aheadperiod分钟签到

        foreach ( $recordsituationclass as $recordsituationclass ) {
          if ( $classindex == $recordsituationclass->classindex && $classID == $recordsituationclass->classID ) {
            $bFind = TRUE;
            break; //已生成则继续循环
          }
        }
        
        $lessonday = date( 'Y-m-01', $nexttime );
        
        if($bFind && $recordsituationclass && $recordsituationclass->manualmodified) {
          if($recordsituationclass->property == 1) {
            if(isset($classlesson[$classID]) && isset($classlesson[$classID]) && isset($classlesson[$classID][$lessonday])) {
              $classlesson[$classID][$lessonday] += $lessons;
            }
            else {
              $classlesson[$classID][$lessonday] = $lessons;
            }
          }
          //echo $classID.' '.$classindex.' '.$lessonday.' '.$lessons . ' ' . $classlesson[$classID][$lessonday] . ': lesson1<br>';
          continue;
        }

        //查询当课段签到人数，提前60分钟签到（可设定），签到时间段：$classstart-$aheadperiod到$classend之间   
        $sql = "SELECT COUNT(*) from attendance LEFT JOIN student on attendance.studentID=student.ID
                WHERE time11 between :starttime and :endtime
                AND student.classID = :classID";
        $statement = $connection->prepare( $sql );
        $statement->execute( [ ':classID' => $classID, ':starttime' => $starttime, ':endtime' => $endtime ] );
        //$record = $statement->fetch(PDO::FETCH_OBJ);
        $checkinnum = $statement->fetchColumn(); //取得欄位1 的值  (也就是count(*))
        if ( $checkinnum == 0 ) {
          $property = 7; //休
          continue;
        } elseif ( $checkinnum / $studentnum > 0.5 ) {//>50%自动判断为有课
          $property = 1; //出
          if(isset($classlesson[$classID]) && isset($classlesson[$classID]) && isset($classlesson[$classID][$lessonday])) {
            $classlesson[$classID][$lessonday] += $lessons;
          }
          else {
            $classlesson[$classID][$lessonday] = $lessons;
          }
          //echo $classID.' '.$classindex.' '.$lessonday.' '.$lessons . ' ' . $classlesson[$classID][$lessonday] . ': lesson2<br>';
        }
        else { //<50%提醒负责人确认
          $property = 0;
          //提醒负责人确认

        }
        //echo $classID.' '.$classindex.' '.$lessonday.' '.$lessons . ': lesson2<br>';
        echo 'time classID index studentnum checkinnum lessons : ' . $recordtime . ' ' . $classID . ' ' . $classindex . ' ' . $studentnum . ' ' . $checkinnum . ' ' . $lessons . ' : new record<br>';
        if ( !$bFind ) { //生成
          //echo $classID . '  ' . $studentnum . '  ' . $checkinnum . ': INSERT<br>';
          $sql = 'INSERT INTO situationclass(classID, classindex, studentnum, checkinnum, property, lessons, recordtime) VALUES(:classID, :classindex, :studentnum, :checkinnum, :property, :lessons, :recordtime)';
          $statement = $connection->prepare( $sql );
          if ( $statement->execute( [ ':classID' => $classID, ':classindex' => $classindex, ':studentnum' => $studentnum, ':checkinnum' => $checkinnum, ':property' => $property, ':lessons' => $lessons, ':recordtime' => $recordtime ] ) ) {} else {
            ShowErrorCode( $statement );
          }
        } else { //修改if ( $property != $recordsituationclass->property )
          //echo $classID . '  ' . $studentnum . '  ' . $checkinnum . '  ' . $lessons . ': UPDATE<br>';
          $sql = 'UPDATE situationclass SET studentnum=:studentnum, checkinnum=:checkinnum, property=:property, lessons=:lessons, recordtime=:recordtime WHERE ID=:ID';
          $statement = $connection->prepare( $sql );
          if ( $statement->execute( [ ':studentnum' => $studentnum, ':checkinnum' => $checkinnum, ':property' => $property, ':lessons' => $lessons, ':recordtime' => $recordtime, ':ID' => $recordsituationclass->ID ] ) ) {} else {
            ShowErrorCode( $statement );
          }
        }
      }
      $nexttime = $nexttime + 3600 * 24;
    }
  }
}
?>