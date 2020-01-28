<?php
require_once (dirname(__FILE__).'/../include/include_database.php');

/////////////////////////////////////////////////////////////////////////////////////
class ClassLesson{
  public $property = 0;
  public $lessons = 4;
  function __construct($property, $lessons) {
    $this->property = $property;
    $this->lessons = $lessons;
  }
}
class AttendMonth {
  public $lessonall = 0;
  public $lessonattend = 0;
  public $lessonabsent = 0;
  public $lessonlate = 0;
  public $lessonattendpercent = 0;
  public $dayall = 0;
  public $dayattend = 0;
  public $dayabsent = 0;
  public $dayattendpercent = 0;
  public $days = array();
  
  public function ResetValue() {
    $this->lessonall = 0;
    $this->lessonattend = 0;
    $this->lessonabsent = 0;
    $this->lessonlate = 0;
    $this->lessonattendpercent = 0;
    $this->dayall = 0;
    $this->dayattend = 0;
    $this->dayabsent = 0;
    $this->dayattendpercent = 0;
    $this->days = array();
  }
  public function CheckValue() {
    if($this->dayall == 0) {
      $this->lessonall = $this->lessonall > 0 ? $this->lessonall : '';
      $this->lessonattend = $this->lessonattend > 0 ? $this->lessonattend : '';
      $this->lessonabsent = $this->lessonabsent > 0 ? $this->lessonabsent : '';
      $this->lessonlate = $this->lessonlate > 0 ? $this->lessonlate : '';
      $this->lessonattendpercent = $this->lessonattendpercent > 0 ? $this->lessonattendpercent : '';
      $this->dayall = $this->dayall > 0 ? $this->dayall : '';
      $this->dayattend = $this->dayattend > 0 ? $this->dayattend : '';
      $this->dayabsent = $this->dayabsent > 0 ? $this->dayabsent : '';
      $this->dayattendpercent = $this->dayattendpercent > 0 ? $this->dayattendpercent : '';
    }
  }
}

//$month1 = new AttendMonth();
//$month1->days['dayname'] = 'dayproperty';
//$StudentAttendance['yearname']['monthname'] = $month1;
//$StudentAttendance['yearname']['monthname']->days[dayname]
/////////////////////////////////////////////////////////////////////////////////////////

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
$day1 = date("d", $time1) + 0;   // 时间1的月份

$year2  = date("Y", $time) + 0;   // 时间2的年份
$month2 = date("m", $time) + 0;   // 时间2的月份
$day2 = date("d", $time) + 0;   // 时间2的月份
$echo = "";
//$echo .= $month2 . " ";
//$echo .= $month1 . " ";
if($year2 - $year1 > 2) {
  $year1 = $year2 - 2;
}

$sql = 'SELECT situationclass.classID AS classID, situationclass.classindex AS classindex, situationclass.property AS property, situationclass.lessons AS lessons, situationclass.recordtime AS recordtime FROM situationclass LEFT JOIN student ON student.classID=situationclass.classID WHERE student.ID=:studentID';
$statement = $connection->prepare($sql);
$statement->execute( [ ':studentID' => $studentID ] );
$recordsituationclass = $statement->fetchAll(PDO::FETCH_OBJ);
$classrec = array();
foreach($recordsituationclass as $recordsituationclass) {
  $timerec = strtotime($recordsituationclass->recordtime);
  $yearrec  = date("Y", $timerec) + 0;   // 年
  $monthrec = date("m", $timerec) + 0;   // 月
  $dayrec = date("d", $timerec) + 0;   // 日
  $propertykey = 'd' . $dayrec . 'c' . $recordsituationclass->classindex;
  $classrec[$yearrec][$monthrec][$propertykey] = new ClassLesson($recordsituationclass->property, $recordsituationclass->lessons);
  //$echo .= $yearrec . ' ' . $monthrec . ' ' . $propertykey . ' ' . $recordsituationclass->property . " class <br>";
}
echo json_encode($classrec);
echo '<br>';
echo '<br>';
$sql = 'SELECT property, date FROM situationmonth WHERE studentID=:studentID';
$statement = $connection->prepare($sql);
$statement->execute( [ ':studentID' => $studentID ] );
$recordsituationmonth = $statement->fetchAll(PDO::FETCH_OBJ);
$attendrec = array();
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

$StudentAttendance = array();
$monthattend = new AttendMonth();
for($year=$year1; $year<=$year2; $year++) {                  
  $m1 = 1;
  $m2 = 12;
  if($year == $year1) {
    $m1 = $month1;
  } else if($year == $year2) {
    $m2 = $month2;
  }
  for($m=$m1; $m<=$m2; $m++) {
    $monthattend->ResetValue();

    //计算全部课时
    $allday = 0;
    //计算每日签到
    $attendday = 0;
    $absentday = 0;
    $lateday = 0;
    
    $d1 = 1;
    $d2 = 31;
    if($year == $year1 && $m == $m1) {
      $d1 = $day1;
    } else if($year == $year2 && $m == $m2) {
      $d2 = $day2;
    }
    for($d=$d1; $d<=$d2; $d++) {
      $property = 0;
      $lessons = array();
      $lessons[0] = 4;
      $selectedindex = 0;
      for($k=0; $k<=4; $k++) {
        $propertykey = 'd' . $d . 'c' . $k;

        //每日上课课时
        if(isset($classrec[$year]) && isset($classrec[$year][$m]) && isset($classrec[$year][$m][$propertykey])) {
          //$echo .= $year . ' ' . $m . ' ' . $propertykey . ' ' . $classrec[$year][$m][$propertykey] . " property 2 <br>";
          $classlesson = $classrec[$year][$m][$propertykey];
          if($classlesson->property == 1) {
            $monthattend->days[$d] = $arrayproperty[2]; //欠
            if(!isset($attendrec[$year]) || !isset($attendrec[$year][$m]) || !isset($attendrec[$year][$m][$propertykey])) {
              $attendrec[$year][$m][$propertykey] = 2;
            }

            //每日出勤情况
            if(isset($attendrec[$year]) && isset($attendrec[$year][$m]) && isset($attendrec[$year][$m][$propertykey])) {
              $property1 = $attendrec[$year][$m][$propertykey];
              $arraypriority = array(0, 2, 5, 4, 3, 7, 6, 1);
              $priority = array_search($property, $arraypriority);
              $priority1 = array_search($property1, $arraypriority);
              if($priority < $priority1) {
                $selectedindex = $k; // selected index
                $property = $property1;
                $lessons[$k] = $classlesson->lessons;
              }
            }
            $lessons[$k] = $classlesson->lessons;
            $monthattend->dayall += 1;
            $monthattend->lessonall += $lessons[$k];
          }
        }
      }
      if($property == 1) { //'出'
        $monthattend->dayattend += 1;
        $monthattend->lessonattend += $lessons[$selectedindex];
      } else if($property == 2) { //'欠'
        $monthattend->dayabsent += 1;
        $monthattend->lessonabsent += $lessons[$selectedindex];
      } else if($property == 3) { //'公'
      } else if($property == 4) { //'休'
      } else if($property == 5) { //'帰'
      } else if($property == 6) { //'遅'
        $monthattend->lessonlate += 1;
        $monthattend->dayattend += 1;
        $monthattend->lessonattend += $lessons[$selectedindex] - 1;
      } else if($property == 7) { //'-'-:休校日
      } else if($property == 0) { //'-'-:休校日
        $property = 7;
      }
      $monthattend->days[$d] = $arrayproperty[$property];
    }

    if($monthattend->dayall > 0) {
      $monthattend->dayattendpercent = round($monthattend->dayattend / $monthattend->dayall * 100) . "%";
    }
    if($monthattend->lessonall > 0) {
      $monthattend->lessonattendpercent = round($monthattend->lessonattend / $monthattend->lessonall * 100) . "%";
    }
    $monthattend->CheckValue();
    $StudentAttendance[$year][$m] = clone $monthattend;
  }
}
echo json_encode($StudentAttendance);
echo '<br>';
?>