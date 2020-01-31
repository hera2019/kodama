<?php
require_once '../include/include_database.php';

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//situation_class.php中已定义
//require_once 'attend_class.php';
//use Attend\LessonClass;
//$LessonClass = new LessonClass($connection);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$time = time();
$currenttime = date( 'Y-m-d H:i:s', $time );
$sql = 'SELECT *, a.ID AS ID, s.classID AS classID from attendance AS a LEFT JOIN student AS s ON a.studentID=s.ID';
if($REBUILD_ALL) { //重新生成
  $sqltruncate = 'truncate table situationmonth'; //清空表
  echo $sqltruncate . '<br>';
  $statement = $connection->prepare( $sqltruncate );
  $statement->execute();
} else {
  $lastID = 0;
  $lasttime = '2019-07-01 00:00:00';
  $sql3 = 'SELECT recordID, recordtime FROM lastrecord WHERE tablename="attendance"';
  $statement = $connection->prepare( $sql3 );
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
    $sql .= ' WHERE a.ID > ' . $lastID;
  } else {
    $sql .= ' WHERE a.recordtime > "' . $lasttime . '"';
  }
}
$statement = $connection->prepare( $sql );
$statement->execute();
$recordattendance = $statement->fetchAll(PDO::FETCH_OBJ);
//echo $sql . '<br>';
foreach($recordattendance as $recordattendance) {
  $studentID = $recordattendance->studentID;
  $property = $recordattendance->property;
  $timerecord = strtotime($recordattendance->time11);
  $date = date( 'Y-m-01', $timerecord );
  $dateNo = date( 'd', $timerecord ) + 0;
  
  $propertynew = 0;
  $classindex = $LessonClass->GetClassIndexProperty($recordattendance->time11, $recordattendance->time12, $propertynew);
  if($classindex == 0) {
    continue;
  }
  if(!$recordattendance->manualmodified) { //没有手动更改过，属性按新计算结果
    $property = $propertynew;
  }
  
  $lessons = $LessonClass->GetClassLessons($classindex);
  $monthclasslesson = 0;
  $classID = $recordattendance->classID;
  if(isset($classlesson) && isset($classlesson[$classID]) && isset($classlesson[$classID][$date])) {
    $monthclasslesson = $classlesson[$classID][$date];
  } else {
    continue;
  }
  $propertykey = 'd' . $dateNo . 'c' . $classindex;

  //统计签到课时数
  $attendlesson = 0;
  if($property == 1) {
    $attendlesson = $lessons;
  } elseif($property == 6 && $lessons > 0) {
    $attendlesson = $lessons - 1;
  }
  
  $sql = "SELECT ID, property, attendlesson from situationmonth WHERE studentID=:studentID AND date=:date";
  $statement = $connection->prepare( $sql );
  $statement->execute( [ ':studentID' => $studentID, ':date' => $date ] );
  $recordsituationmonth = $statement->fetch(PDO::FETCH_OBJ);
  if($recordsituationmonth) {
    //$sql = 'UPDATE situationmonth SET property=JSON_SET(property, "$.' . $propertykey . '", :property), recordtime=:recordtime WHERE ID=:ID'; //mysql5.7以上版本支持JSON，bluehost服务器mysql目前版本是5.6.41-84.1
    $propertyarray = array();
    if(!empty($recordsituationmonth->property)) {
      $propertyarray = json_decode($recordsituationmonth->property, true);
    }
    //统计签到课时数
    if(!empty($recordsituationmonth->attendlesson)) {
      $attendlesson += $recordsituationmonth->attendlesson;
    }

    $propertyarray[$propertykey] = $property;
    $propertytext = json_encode($propertyarray);
    $sql = 'UPDATE situationmonth SET property=:property, attendlesson=:attendlesson, classlesson=:classlesson, recordtime=:recordtime WHERE ID=:ID';
    
    echo 'date studentID attendlesson classlesson property : ' . $date . ' ' . $studentID . ' ' . $attendlesson . ' ' . $monthclasslesson . ' ' . $propertytext . '<br>';
    
    $statement = $connection->prepare( $sql );
    if ( $statement->execute( [ ':property' => $propertytext, ':attendlesson' => $attendlesson, ':classlesson' => $monthclasslesson, ':recordtime' => $currenttime, ':ID' => $recordsituationmonth->ID ] ) ) {
    } else {
      echo ShowErrorCode( $statement );
    }
  } else {
    //$sql = 'INSERT INTO situationmonth(studentID, property, date) VALUES(:studentID, JSON_OBJECT("' . $propertykey . '", :property), :date)'; //mysql5.7以上版本支持JSON，bluehost服务器mysql目前版本是5.6.41-84.1
    $propertyarray = array();
    $propertyarray[$propertykey] = $property;
    $propertytext = json_encode($propertyarray);
    $sql = 'INSERT INTO situationmonth(studentID, property, attendlesson, classlesson, date) VALUES(:studentID, :property, :attendlesson, :classlesson, :date)';
    
    echo 'date studentID attendlesson classlesson property : ' . $date . ' ' . $studentID . ' ' . $attendlesson . ' ' . $monthclasslesson . ' ' . $propertytext . ' : new record<br>';
    
    $statement = $connection->prepare( $sql );
    if ( $statement->execute( [ ':studentID' => $studentID, ':property' => $propertytext, ':attendlesson' => $attendlesson, ':classlesson' => $monthclasslesson, ':date' => $date ] ) ) {      
    } else {
      echo ShowErrorCode( $statement );
    }
  }
}
if($recordattendance) {
  $sql = 'UPDATE lastrecord SET recordID=:recordID, recordtime=:recordtime WHERE tablename="attendance"';
  $statement = $connection->prepare( $sql );
  $statement->execute( [ ':recordID' => $recordattendance->ID, ':recordtime' => $recordattendance->recordtime ] );
  echo 'The last record time is: ' . $recordattendance->recordtime . '<br>';
}
?>