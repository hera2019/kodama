<?php
require_once '../include/include_database.php';
//查询每日上课时间
$classtimenum = 1;
$classtime11 = '08:00:00';
$classtime12 = '12:00:00';
$minutes1 = 240;
$aheadperiod = 60;
$delayperiod = 60;
$allowlate = 0;
$allowearly = 0;
$sql = 'SELECT * FROM classtime';
$statement = $connection->prepare( $sql );
$statement->execute();
$record3 = $statement->fetch( PDO::FETCH_OBJ ); //只有一条记录不用fetchAll
if ( $record3 != NULL && $record3->num != 0 ) {
  $classtimenum = $record3->num;
  $classtime11 = $record3->time11;
  $classtime12 = $record3->time12;
  $minutes1 = $record3->minutes1;
  $classtime21 = $record3->time21;
  $classtime22 = $record3->time22;
  $minutes2 = $record3->minutes2;
  $classtime31 = $record3->time31;
  $classtime32 = $record3->time32;
  $minutes3 = $record3->minutes3;
  $classtime41 = $record3->time41;
  $classtime42 = $record3->time42;
  $minutes4 = $record3->minutes4;
  $aheadperiod = $record3->aheadperiod;
  $delayperiod = $record3->delayperiod;
  $allowlate = $record3->allowlate;
  $allowearly = $record3->allowearly;
}

$time = time();
$currenttime = date( 'Y-m-d H:i:s', $time );
$lastID = 0;
$lasttime = '2019-07-01 00:00:00';
$sql = 'SELECT recordID, recordtime FROM lastrecord WHERE tablename="attendance"';
$statement = $connection->prepare( $sql );
$statement->execute();
$recordlastrecord = $statement->fetch( PDO::FETCH_OBJ ); //只有一条记录不用fetchAll
if($recordlastrecord) {
  $lastID = $recordlastrecord->recordID;
  //echo $lastID . ': lastID<br>';
  if($recordlastrecord->recordtime) {
    $lasttime = $recordlastrecord->recordtime;
  }
}

if($lastID > 0) {
  $sql = 'SELECT * from attendance WHERE ID > ' . $lastID;
} else {
  $sql = 'SELECT * from attendance WHERE recordtime > "' . $lasttime . '"';
}
$statement = $connection->prepare( $sql );
$statement->execute();
$recordattendance = $statement->fetchAll(PDO::FETCH_OBJ);
foreach($recordattendance as $recordattendance) {
  $studentID = $recordattendance->studentID;
  $property = $recordattendance->property;
  $timerecord = strtotime($recordattendance->time11);
  $date = date( 'Y-m-01', $timerecord );
  $dateNo = date( 'd', $timerecord ) + 0;
  $classindex = GetClassIndex($recordattendance->time11, $classtimenum, $aheadperiod,
                              $classtime11, $classtime12,
                              $classtime21, $classtime22,
                              $classtime31, $classtime32,
                              $classtime41, $classtime42);
  if($classindex == 0) {
    continue;
  }
  $propertykey = 'd' . $dateNo . 'c' . $classindex;
  //echo $studentID.' '.$date.' '.$propertykey . ': studentID<br>';
  
  $sql = "SELECT ID, property from situationmonth WHERE studentID=:studentID AND date=:date";
  $statement = $connection->prepare( $sql );
  $statement->execute( [ ':studentID' => $studentID, ':date' => $date ] );
  $recordsituationmonth = $statement->fetch(PDO::FETCH_OBJ);
  if($recordsituationmonth) {
    //$sql = 'UPDATE situationmonth SET property=JSON_SET(property, "$.' . $propertykey . '", :property), recordtime=:recordtime WHERE ID=:ID'; //mysql5.7以上版本支持JSON，bluehost服务器mysql目前版本是5.6.41-84.1
    $propertyarray = array();
    if(!empty($recordsituationmonth->property)) {
      $propertyarray = json_decode($recordsituationmonth->property, true);
    }
    $propertyarray[$propertykey] = $property;
    $propertytext = json_encode($propertyarray);
    $sql = 'UPDATE situationmonth SET property=:property, recordtime=:recordtime WHERE ID=:ID';
    $statement = $connection->prepare( $sql );
    if ( $statement->execute( [ ':property' => $propertytext, ':recordtime' => $currenttime, ':ID' => $recordsituationmonth->ID ] ) ) {
    } else {
      echo ShowErrorCode( $statement );
    }
  } else {
    //$sql = 'INSERT INTO situationmonth(studentID, property, date) VALUES(:studentID, JSON_OBJECT("' . $propertykey . '", :property), :date)'; //mysql5.7以上版本支持JSON，bluehost服务器mysql目前版本是5.6.41-84.1
    $propertyarray = array();
    $propertyarray[$propertykey] = $property;
    $propertytext = json_encode($propertyarray);
    $sql = 'INSERT INTO situationmonth(studentID, property, date) VALUES(:studentID, :property, :date)';
    $statement = $connection->prepare( $sql );
    if ( $statement->execute( [ ':studentID' => $studentID, ':property' => $propertytext, ':date' => $date ] ) ) {      
    } else {
      echo ShowErrorCode( $statement );
    }
  }
}
if($recordattendance) {
  $sql = 'UPDATE lastrecord SET recordID=:recordID, recordtime=:recordtime WHERE tablename="attendance"';
  $statement = $connection->prepare( $sql );
  $statement->execute( [ ':recordID' => $recordattendance->ID, ':recordtime' => $recordattendance->recordtime ] );
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
function IsBetweenTime( $start, $end, $time, $aheadperiod ) {
  $date = date( 'H:i:s', strtotime( $time ) );
  $curTime = strtotime( $date ); //当前时分秒
  $assignTime1 = strtotime( $start ) - $aheadperiod * 60; //获得指定秒钟时间戳，00:00:00
  $assignTime2 = strtotime( $end ); //获得指定秒钟时间戳，01:00:00
  $result = false;
  if ( $curTime > $assignTime1 && $curTime < $assignTime2 ) {
    $result = true;
  }
  return $result;
}

function GetClassIndex( $time, $classtimenum, $aheadperiod,
                        $classtime11, $classtime12,
                        $classtime21, $classtime22,
                        $classtime31, $classtime32,
                        $classtime41, $classtime42 ) {
  $classindex = 0;
  $classstart = $classtime11;
  $classend = $classtime12;
  //判断第几课时段
  if ( $classtimenum >= 4 ) {
    $isBetweenTime = IsBetweenTime( $classtime41, $classtime42, $time, $aheadperiod );
    if ( $isBetweenTime ) {
      $classindex = 4;
      $classstart = $classtime41;
      $classend = $classtime42;
    }
  }
  if ( $classtimenum >= 3 ) {
    $isBetweenTime = IsBetweenTime( $classtime31, $classtime32, $time, $aheadperiod );
    if ( $isBetweenTime ) {
      $classindex = 3;
      $classstart = $classtime31;
      $classend = $classtime32;
    }
  }
  if ( $classtimenum >= 2 ) {
    $isBetweenTime = IsBetweenTime( $classtime21, $classtime22, $time, $aheadperiod );
    if ( $isBetweenTime ) {
      $classindex = 2;
      $classstart = $classtime21;
      $classend = $classtime22;
    }
  }
  if ( $classtimenum >= 1 ) {
    $isBetweenTime = IsBetweenTime( $classtime11, $classtime12, $time, $aheadperiod );
    if ( $isBetweenTime ) {
      $classindex = 1;
      $classstart = $classtime11;
      $classend = $classtime12;
    }
  }
  return $classindex;
}
?>
