<?php
require_once '../include/include_database.php';
//查询每日上课时间
$classtimenum = 1;
$classtime11 = '08:00:00';
$classtime12 = '12:00:00';
$aheadperiod = 60;
$delayperiod = 60;
$sql = 'SELECT * FROM  classtime';
$statement = $connection->prepare( $sql );
$statement->execute();
$record3 = $statement->fetch( PDO::FETCH_OBJ ); //只有一条记录不用fetchAll
if ( $record3 != NULL && $record3->num != 0 ) {
  $classtimenum = $record3->num;
  $classtime11 = $record3->time11;
  $classtime12 = $record3->time12;
  $classtime21 = $record3->time21;
  $classtime22 = $record3->time22;
  $classtime31 = $record3->time31;
  $classtime32 = $record3->time32;
  $classtime41 = $record3->time41;
  $classtime42 = $record3->time42;
  $aheadperiod = $record3->aheadperiod;
  $delayperiod = $record3->delayperiod;
}

//判断当前时间是否上课时间
/**
 * 判断当前的时分是否在指定的时间段内
 * @param $start 开始时分  eg:10:30:00
 * @param $end  结束时分   eg:15:30:00
 * @author:zmq
 * @date:2019/8/12 14:34
 * @return: bool  1：在范围内，0:没在范围内
 */
function checkIsBetweenTime( $start, $end, $cur = NULL ) {
  if ( $cur == NULL ) {
    $date = date( 'H:i:s' );
  } else {
    $date = date( 'H:i:s', $cur );
  }
  $curTime = strtotime( $date ); //当前时分秒
  $assignTime1 = strtotime( $start ); //获得指定秒钟时间戳，00:00:00
  $assignTime2 = strtotime( $end ); //获得指定秒钟时间戳，01:00:00
  $result = 0;
  if ( $curTime > $assignTime1 && $curTime < $assignTime2 ) {
    $result = 1;
  }
  return $result;
}

$time = time();
$classindex = 0;
$classstart = $classtime11;
$classend = $classtime12;
//判断第几课时段
if ( $classtimenum >= 4 ) {
  $isBetweenTime = checkIsBetweenTime( $classtime41, $classtime42, $time );
  if ( $isBetweenTime ) {
    $classindex = 4;
    $classstart = $classtime41;
    $classend = $classtime42;
  }
}
if ( $classtimenum >= 3 ) {
  $isBetweenTime = checkIsBetweenTime( $classtime31, $classtime32, $time );
  if ( $isBetweenTime ) {
    $classindex = 3;
    $classstart = $classtime31;
    $classend = $classtime32;
  }
}
if ( $classtimenum >= 2 ) {
  $isBetweenTime = checkIsBetweenTime( $classtime21, $classtime22, $time );
  if ( $isBetweenTime ) {
    $classindex = 2;
    $classstart = $classtime21;
    $classend = $classtime22;
  }
}
if ( $classtimenum >= 1 ) {
  $isBetweenTime = checkIsBetweenTime( $classtime11, $classtime12, $time );
  if ( $isBetweenTime ) {
    $classindex = 1;
    $classstart = $classtime11;
    $classend = $classtime12;
  }
}
if ( $classindex == 0 ) {
  echo "非上课时间";
  return; //非上课时间
}

$currenttime = date( 'Y-m-d H:i:s', $time );
//签到时间段：$classstart-$aheadperiod到$classend之间
$currentday = date( 'Y-m-d', $time );
$starttime = $currentday . ' ' . date( 'H:i:s', strtotime( $classstart ) );
$endtime = $currentday . ' ' . date( 'H:i:s', strtotime( $classend ) );

//判断当前上课时间classschedule记录是否已生成，已生成则退出
$sql = "SELECT * FROM  classschedule WHERE recordtime between :starttime and :endtime";
$statement = $connection->prepare( $sql );
$statement->execute( [ ':starttime' => $starttime, ':endtime' => $endtime ] );
$record4 = $statement->fetchAll( PDO::FETCH_OBJ );

$starttime = $currentday . ' ' . date( 'H:i:s', strtotime( $classstart ) - $aheadperiod * 60 ); //提前aheadperiod分钟签到
//echo $classstart . ' ' . $aheadperiod . ' ' . $classend . '<br>';
//echo $starttime . ' ' . $endtime . '<br>';
//查询班级ID、学生人数
$sql = 'SELECT ID FROM class';
$statement = $connection->prepare( $sql );
$statement->execute();
$record1 = $statement->fetchAll( PDO::FETCH_OBJ );
foreach ( $record1 as $record2 ) {
  $classID = $record2->ID;
  $sql = "SELECT COUNT(*) from student WHERE classID=:classID";
  $statement = $connection->prepare( $sql );
  $statement->execute( [ ':classID' => $classID ] );
  $studentnum = $statement->fetchColumn();
  echo $classID . '  ' . $studentnum . '<br>';
  if ( $studentnum > 0 && $classID > 0 ) {
    $bFind = FALSE;
    $record5 = $record4;
    foreach ( $record5 as $record6 ) {
      if ( $classindex == $record6->classindex && $classID == $record6->classID ) {
        $bFind = TRUE;
        break; //已生成则继续循环
      }
    }

    //查询当课段签到人数，提前60分钟签到（可设定），签到时间段：$classstart-$aheadperiod到$classend之间        
    //$sql = "SELECT COUNT(*) from attendance LEFT JOIN user on attendance.userID = user.ID 
    //    WHERE date_format(time1,'%Y-%m-%d') = date_format(now(),'%Y-%m-%d') 
    //    AND user.classID = :classID";
    //time1 >= between :starttime AND time1 <= :endtime
    $sql = "SELECT COUNT(*) from attendance LEFT JOIN student on attendance.studentID=student.ID
            WHERE time11 between :starttime and :endtime
            AND student.classID = :classID";
    $statement = $connection->prepare( $sql );
    $statement->execute( [ ':classID' => $classID, ':starttime' => $starttime, ':endtime' => $endtime ] );
    //$record = $statement->fetch(PDO::FETCH_OBJ);
    $checkinnum = $statement->fetchColumn(); //取得欄位1 的值  (也就是count(*))
    echo $checkinnum . '<br>';
    //echo $studentnum . '<br>';
    if ( $checkinnum == 0 ) {
      $property = 7; //休
    } elseif ( $checkinnum / $studentnum > 0.5 ) //>50%自动判断为有课
    {
      $property = 1; //出
    }
    else //<50%提醒负责人确认
    {
      $property = 0;
      //提醒负责人确认

    }
    //echo $checkinnum . '  ' . $property . '<br>';
    if ( !$bFind ) //生成
    {
      $sql = 'INSERT INTO classschedule(classID, classindex, studentnum, checkinnum, property) VALUES(:classID, :classindex, :studentnum, :checkinnum, :property)';
      $statement = $connection->prepare( $sql );
      if ( $statement->execute( [ ':classID' => $classID, ':classindex' => $classindex, ':studentnum' => $studentnum, ':checkinnum' => $checkinnum, ':property' => $property ] ) ) {} else {
        ShowErrorCode( $statement );
      }
    } elseif ( $property != $record6->property ) //修改
    {
      $sql = 'UPDATE classschedule SET studentnum=:studentnum, checkinnum=:checkinnum, property=:property, recordtime=:recordtime  WHERE ID=:ID';
      $statement = $connection->prepare( $sql );
      if ( $statement->execute( [ ':studentnum' => $studentnum, ':checkinnum' => $checkinnum, ':property' => $property, ':recordtime' => $currenttime, ':ID' => $record6->ID ] ) ) {} else {
        ShowErrorCode( $statement );
      }
    }
  }
}
?>
