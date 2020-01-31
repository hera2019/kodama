<?php
require_once '../include/include_database.php';

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//situation_class.php中已定义
require_once 'attend_class.php';
use Attend\LessonClass;
$LessonClass = new LessonClass($connection);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$time = time();
$currenttime = date( 'Y-m-d H:i:s', $time );
$sql = 'SELECT * from attendance';
$statement = $connection->prepare( $sql );
$statement->execute();
$recordattendance = $statement->fetchAll(PDO::FETCH_OBJ);
//echo $sql . '<br>';
foreach($recordattendance as $recordattendance) {
  $property1 = '';
  $classindex1 = $LessonClass->GetClassIndexProperty($recordattendance->time11, $recordattendance->time12, $property1);
  $property2 = '';
  $classindex2 = $LessonClass->GetClassIndexProperty($recordattendance->time21, $recordattendance->time22, $property2);
  $property3 = '';
  $classindex3 = $LessonClass->GetClassIndexProperty($recordattendance->time31, $recordattendance->time32, $property3);
  $property4 = '';
  $classindex4 = $LessonClass->GetClassIndexProperty($recordattendance->time41, $recordattendance->time42, $property4);
  if($property1 === '') $property1 = NULL;
  if($property2 === '') $property2 = NULL;
  if($property3 === '') $property3 = NULL;
  if($property4 === '') $property4 = NULL;
  echo $property1.' '.$property2.' '.$property3.' '.$property4.' '.'<br>';
  
  $sql = 'UPDATE attendance SET classindex1=:classindex1, property1=:property1, classindex2=:classindex2, property2=:property2, classindex3=:classindex3, property3=:property3, classindex4=:classindex4, property4=:property4 WHERE ID=:ID';
  $statement = $connection->prepare( $sql );
  if ( $statement->execute( [ ':classindex1' => $classindex1, ':property1' => $property1, ':classindex2' => $classindex2, ':property2' => $property2, ':classindex3' => $classindex3, ':property3' => $property3, ':classindex4' => $classindex4, ':property4' => $property4, ':ID' => $recordattendance->ID ] ) ) {
  } else {
    echo ShowErrorCode( $statement );
  }
}
?>