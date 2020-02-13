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
$sql = 'SELECT *, a.ID AS ID, s.classID AS classID from attendance AS a LEFT JOIN student AS s ON a.studentID=s.ID WHERE 1 ';
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
    $sql .= 'AND a.ID > ' . $lastID;
  } else {
    $sql .= 'AND a.time11 > "' . $lasttime . '"';
  }
}
$sql .= ' ORDER BY time11 ASC'; // AND studentID=87 
//echo $sql . '<br>';
$statement = $connection->prepare( $sql );
$statement->execute();
$recordattendance = $statement->fetchAll(PDO::FETCH_OBJ);
//echo $sql . '<br>';
$studentpropertie_s = array();
$studentfirsttime_s = array();
$lastattendancerecorID = 0;
$lastattendancerecortime = NULL;
foreach($recordattendance as $recordattendance) {
  //echo json_encode($recordattendance) . '<br>';
  $studentID = $recordattendance->studentID;
  $classID = $recordattendance->classID;
  $classindexs_4 = [];
  $classindexs_4[1] = $recordattendance->classindex1;
  $classindexs_4[2] = $recordattendance->classindex2;
  $classindexs_4[3] = $recordattendance->classindex3;
  $classindexs_4[4] = $recordattendance->classindex4;
  $propertys_4 = [];
  $propertys_4[1] = $recordattendance->property1;
  $propertys_4[2] = $recordattendance->property2;
  $propertys_4[3] = $recordattendance->property3;
  $propertys_4[4] = $recordattendance->property4;
  $timerecords_4 = [];
  $timerecords_4[1] = $recordattendance->time11;
  $timerecords_4[2] = $recordattendance->time21;
  $timerecords_4[3] = $recordattendance->time31;
  $timerecords_4[4] = $recordattendance->time41;
  
  for($i=1; $i<=4; $i++) {
    $classindex = $classindexs_4[$i];
    $property = $propertys_4[$i];
    $timerec = $timerecords_4[$i];
    if(empty($classindex) || empty($property) || empty($timerec)) {
      continue;
    }

    $year = date( 'Y', strtotime($timerec) ) + 0; //+0之后，01=>1
    $month = date( 'm', strtotime($timerec) ) + 0;
    $day = date( 'd', strtotime($timerec) ) + 0;
    $studentpropertie_s[$classID][$studentID][$year][$month][$day][$classindex] = $property;
    if(!isset($studentfirsttime_s[$classID]) || !isset($studentfirsttime_s[$classID][$studentID])) {
      $studentfirsttime_s[$classID][$studentID] = $timerec;
    }
  }
  
  $lastattendancerecorID = $recordattendance->ID;
  $lastattendancerecortime = $recordattendance->time11;
}
//echo json_encode($studentpropertie_s) . '<br><br>';
//echo json_encode($classlesson_s) . '<br><br>';
// END 统计每个签到记录
//////////////////////////////////////////////////////////////////////////////////////////////////////
//查询准确开课时间，如查询不到，从第一条记录时间开始
foreach($studentpropertie_s as $classID => $studentID_s) {
  foreach($studentID_s as $studentID => $property_year_s) {
    $timestart = NULL;
    $timeend = NULL;
    $sqlclassstartdate = 'SELECT classstartdate, schoolentrydate, completiondate, graduationdate, withdrawaldate, scheduledcompletiondate FROM student2 WHERE ID=:studentID';
    $statement = $connection->prepare($sqlclassstartdate);
    $statement->execute( [ ':studentID' => $studentID ] );
    $recordclassstartdate = $statement->fetch( PDO::FETCH_OBJ );
    if(!empty($recordclassstartdate)) {
      //开课时间
      if(!empty($recordclassstartdate->classstartdate)) {
        $timestart = $recordclassstartdate->classstartdate;
        //strtotime(date('Y-m-d', $time) . ' -2year+1month'); //2020-02-01=>2018-03-01
      } else if(!empty($recordclassstartdate->schoolentrydate)) {
        $timestart = $recordclassstartdate->schoolentrydate;
      }
      //修了时间
      if(!empty($recordclassstartdate->completiondate)) {
        $timeend = $recordclassstartdate->completiondate;
      } else if(!empty($recordclassstartdate->graduationdate)) {
        $timeend = $recordclassstartdate->graduationdate;
      } else if(!empty($recordclassstartdate->withdrawaldate)) {
        $timeend = $recordclassstartdate->withdrawaldate;
      } else if(!empty($recordclassstartdate->scheduledcompletiondate)) {
        $timeend = $recordclassstartdate->scheduledcompletiondate;
      }
    }
    //如果查询不到开课时间，从第一条签到记录开始
    if(empty($timestart)) {
      if(isset($studentfirsttime_s[$classID]) && isset($studentfirsttime_s[$classID][$studentID])) {
        $timestart = $studentfirsttime_s[$classID][$studentID];
      } else {
        continue; //既无开始时间，也无签到记录，忽略之
      }
    }
    if(empty($timeend)) {
      $timeend = date('Y-m-d', $time);
    }
    //echo 'classID: '.$classID . ' studentID: ' . $studentID . ' timestart: ' . $timestart . ' timeend: ' . $timeend . '<br>';
    
    //从开始时间到结束时间
    $yearstart_o = date( 'Y', strtotime($timestart) ) + 0;
    $monthstart_o = date( 'm', strtotime($timestart) ) + 0;
    $daystart_o = date( 'd', strtotime($timestart) ) + 0;
    $yearend_o = date( 'Y', strtotime($timeend) ) + 0;
    $monthend_o = date( 'm', strtotime($timeend) ) + 0;
    $dayend_o = date( 'd', strtotime($timeend) ) + 0;
    for($year=$yearstart_o; $year<=$yearend_o; $year++) {
      $monthstart = 1;
      $monthend = 12;
      if($year == $yearstart_o) {
        $monthstart = $monthstart_o;
      }
      if($year == $yearend_o) {
        $monthend = $monthend_o;
      }
      for($month=$monthstart; $month<=$monthend; $month++) {
        $monthproperty_s = array();
        $month_01 = date( 'Y-m-01', strtotime($year . '-' . $month . '-01') );
        $monthclasslesson = 0;
        $monthattendlesson = 0;
        $daystart = 1;
        $dayend = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        if($year == $yearstart_o && $month == $monthstart_o) {
          $daystart = $daystart_o;
        }
        if($year == $yearend_o && $month == $monthend_o) {
          $dayend = $dayend_o;
        }
        for($day=$daystart; $day<=$dayend; $day++) {
          $day_class = date( 'Y-m-d', strtotime($year . '-' . $month . '-' . $day) );  
          for($classindex=1; $classindex<=4; $classindex++) {
            //计算月应上课时间
            if(isset($classlesson_s) && isset($classlesson_s[$day_class]) && isset($classlesson_s[$day_class][$classID]) && isset($classlesson_s[$day_class][$classID][$classindex]) && $classlesson_s[$day_class][$classID][$classindex] > 0) {
              $monthproperty_s[$day][$classindex] = 2; //默认欠席
              $lessons = $classlesson_s[$day_class][$classID][$classindex];
              $monthclasslesson += $lessons;
              //echo 'day_class: ' . $day_class . ' monthclasslesson: ' . $monthclasslesson . ' monthclasslesson<br>';
              //计算月签到上课时间
              if(isset($property_year_s) && isset($property_year_s[$year]) && isset($property_year_s[$year][$month]) && isset($property_year_s[$year][$month][$day]) && isset($property_year_s[$year][$month][$day][$classindex])) {
                $property = $property_year_s[$year][$month][$day][$classindex];
                $monthproperty_s[$day][$classindex] = $property;
                if($property == 1) {
                  $monthattendlesson += $lessons;
                } elseif($property == 6 && $lessons > 0) { //此处将来修改，需计算迟到早退课时
                  $monthattendlesson += $lessons - 1; // 暂时算欠席1节课
                }
              }
            } else { //此节课不上课
              //echo 'day_class: ' . $day_class . ' no isset($classlesson_s<br>';
              continue;
            }            
          }
        } //day
        if($monthclasslesson == 0) {
          //echo 'month_01: ' . $month_01 . ' $monthclasslesson == 0<br>';
          continue; //此月不上课
        }
        
        // 每月数据记录数据库
        $sql = "SELECT ID, properties, classlesson, attendlesson from situationmonth WHERE studentID=:studentID AND date=:date";
        $statement = $connection->prepare( $sql );
        $statement->execute( [ ':studentID' => $studentID, ':date' => $month_01 ] );
        $recordsituationmonth = $statement->fetch(PDO::FETCH_OBJ);
        if($recordsituationmonth) {
          //$sql = 'UPDATE situationmonth SET property=JSON_SET(property, "$.' . $propertykey . '", :property), recordtime=:recordtime WHERE ID=:ID'; //mysql5.7以上版本支持JSON，bluehost服务器mysql目前版本是5.6.41-84.1
          //合并属性
          $propertytext = '';
          $propertyarray = array();
          if(!empty($recordsituationmonth->properties)) {
            $propertytext = $recordsituationmonth->properties;
            $propertyarray = json_decode($propertytext, true);
          }
          if(!empty($monthproperty_s)) {
            $propertytext = json_encode($propertyarray + $monthproperty_s);
          }
          //统计签到课时数
          if(!empty($recordsituationmonth->classlesson)) {
            $monthclasslesson += $recordsituationmonth->classlesson;
          }
          if(!empty($recordsituationmonth->attendlesson)) {
            $monthattendlesson += $recordsituationmonth->attendlesson;
          }
          $sql = 'UPDATE situationmonth SET properties=:properties, attendlesson=:attendlesson, classlesson=:classlesson, recordtime=:recordtime WHERE ID=:ID';

          echo 'date: ' . $month_01 . ' studentID: ' . $studentID . ' attendlesson: ' . $monthattendlesson . ' classlesson: ' . $monthclasslesson . ' properties: ' . $propertytext . '<br>';

          $statement = $connection->prepare( $sql );
          if ( $statement->execute( [ ':properties' => $propertytext, ':attendlesson' => $monthattendlesson, ':classlesson' => $monthclasslesson, ':recordtime' => $currenttime, ':ID' => $recordsituationmonth->ID ] ) ) {
          } else {
            echo ShowErrorCode( $statement );
          }
        } else {
          //$sql = 'INSERT INTO situationmonth(studentID, property, date) VALUES(:studentID, JSON_OBJECT("' . $propertykey . '", :property), :date)'; //mysql5.7以上版本支持JSON，bluehost服务器mysql目前版本是5.6.41-84.1
          $propertytext = '';
          if(!empty($monthproperty_s)) {
            $propertytext = json_encode($monthproperty_s);
          }
          $sql = 'INSERT INTO situationmonth(studentID, properties, attendlesson, classlesson, date) VALUES(:studentID, :properties, :attendlesson, :classlesson, :date)';

          echo 'date: ' . $month_01 . ' studentID: ' . $studentID . ' attendlesson: ' . $monthattendlesson . ' classlesson: ' . $monthclasslesson . ' properties: ' . $propertytext . ' : new record<br>';

          $statement = $connection->prepare( $sql );
          if ( $statement->execute( [ ':studentID' => $studentID, ':properties' => $propertytext, ':attendlesson' => $monthattendlesson, ':classlesson' => $monthclasslesson, ':date' => $month_01 ] ) ) {      
          } else {
            echo ShowErrorCode( $statement );
          }
        }
      } //month
    } //year
  } //studentID
} //classID
//结束
////////////////////////////////////////////////////////////////////////////////////////////////////////
//记录最后一条记录及时间
if($recordattendance) {
  $sql = 'UPDATE lastrecord SET recordID=:recordID, recordtime=:recordtime WHERE tablename="attendance"';
  $statement = $connection->prepare( $sql );
  $statement->execute( [ ':recordID' => $lastattendancerecorID, ':recordtime' => $lastattendancerecortime ] );
  echo 'The last record time is: ' . $lastattendancerecortime . '<br>';
}
?>