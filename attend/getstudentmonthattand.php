<?php
require_once (dirname(__FILE__).'/../include/include_database.php');

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

$sql = 'SELECT situationclass.classID AS classID, situationclass.classindex AS classindex, situationclass.property AS property, situationclass.recordtime AS recordtime FROM situationclass LEFT JOIN student ON student.classID=situationclass.classID WHERE student.ID=:studentID';
$statement = $connection->prepare($sql);
$statement->execute( [ ':studentID' => $studentID ] );
$recordsituationclass = $statement->fetchAll(PDO::FETCH_OBJ);
$classrec = array
(
  array(
    array(),
  ),
);
foreach($recordsituationclass as $recordsituationclass) {
  $timerec = strtotime($recordsituationclass->recordtime);
  $yearrec  = date("Y", $timerec) + 0;   // 年
  $monthrec = date("m", $timerec) + 0;   // 月
  $dayrec = date("d", $timerec) + 0;   // 日
  $propertykey = 'd' . $dayrec . 'c' . $recordsituationclass->classindex;
  $classrec[$yearrec][$monthrec][$propertykey] = $recordsituationclass->property;
  //$echo .= $yearrec . ' ' . $monthrec . ' ' . $propertykey . ' ' . $recordsituationclass->property . " class <br>";
}

$sql = 'SELECT property, date FROM situationmonth WHERE studentID=:studentID';
$statement = $connection->prepare($sql);
$statement->execute( [ ':studentID' => $studentID ] );
$recordsituationmonth = $statement->fetchAll(PDO::FETCH_OBJ);
$attendrec = array
(
  array(
    array(),
  ),
);
foreach($recordsituationmonth as $recordsituationmonth) {
  $echo .= $recordsituationmonth->date . $recordsituationmonth->property . "<br>";
  $timerec = strtotime($recordsituationmonth->date);
  $yearrec  = date("Y", $timerec) + 0;   // 时间1的年份
  $monthrec = date("m", $timerec) + 0;   // 时间1的月份
  $propertyobj = json_decode($recordsituationmonth->property);
  foreach($propertyobj as $key => $value) {
    $attendrec[$yearrec][$monthrec][$key] = $value;
  }
}

$sql = 'SELECT ID, property FROM attendproperty';
$statement = $connection->prepare($sql);
$statement->execute();
$recordattendproperty = $statement->fetchAll(PDO::FETCH_OBJ);
$arrayproperty = array();
$arrayproperty[0] = '';
foreach($recordattendproperty as $recordattendproperty) {
  $arrayproperty[$recordattendproperty->ID] = $recordattendproperty->property;
}
?>
