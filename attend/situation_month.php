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
  
  $classindexnew = [];
  $classindexnew[1] = $recordattendance->classindex1;
  $classindexnew[2] = $recordattendance->classindex2;
  $classindexnew[3] = $recordattendance->classindex3;
  $classindexnew[4] = $recordattendance->classindex4;
  $propertynew = [];
  $propertynew[1] = $recordattendance->property1;
  $propertynew[2] = $recordattendance->property2;
  $propertynew[3] = $recordattendance->property3;
  $propertynew[4] = $recordattendance->property4;
  $timerecord = [];
  $timerecord[1] = strtotime($recordattendance->time11);
  $timerecord[2] = strtotime($recordattendance->time21);
  $timerecord[3] = strtotime($recordattendance->time31);
  $timerecord[4] = strtotime($recordattendance->time41);
  
  for($i=1; $i<=4; $i++) {
    $date = date( 'Y-m-01', $timerecord[$i] );
    $dateNo = date( 'd', $timerecord[$i] ) + 0;

    $property = $propertynew[$i];
    $classindex = $classindexnew[$i];
    if(empty($classindex) || empty($property)) {
      continue;
    }

    $monthclasslesson = 0;
    $lessons = $LessonClass->GetClassLessons($classindex);
    $classID = $recordattendance->classID;
    if(isset($classlesson) && isset($classlesson[$date]) && isset($classlesson[$date][$classID])) {
      $monthclasslesson = $classlesson[$date][$classID];
    } else {
      continue;
    }
    $propertykey = 'd' . $dateNo . 'c' . $classindex;

    //统计签到课时数
    $attendlesson = 0;
    $checkday = date( 'Y-m-d', $timerecord[$i] );
    if(isset($classproperty) && isset($classproperty[$checkday]) && isset($classproperty[$checkday][$classID]) && isset($classproperty[$checkday][$classID][$classindex]) && $classproperty[$checkday][$classID][$classindex] == 1) {      
      if($property == 1) {
        $attendlesson = $lessons;
      } elseif($property == 6 && $lessons > 0) {
        $attendlesson = $lessons - 1;
      }
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

      echo 'date: ' . $date . ' studentID: ' . $studentID . ' attendlesson: ' . $attendlesson . ' classlesson: ' . $monthclasslesson . ' property: ' . $propertytext . '<br>';

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

      echo 'date: ' . $date . ' studentID: ' . $studentID . ' attendlesson: ' . $attendlesson . ' classlesson: ' . $monthclasslesson . ' property: ' . $propertytext . ' : new record<br>';

      $statement = $connection->prepare( $sql );
      if ( $statement->execute( [ ':studentID' => $studentID, ':property' => $propertytext, ':attendlesson' => $attendlesson, ':classlesson' => $monthclasslesson, ':date' => $date ] ) ) {      
      } else {
        echo ShowErrorCode( $statement );
      }
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