<?php
namespace NS_Kodama_DB;
require_once '../include/include_database.php';
require_once '../include/include_function.php';

use PDO;

/////////////////////////////////////////////////////////////////////////////////////////
// GetAttendance Struct
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

class Checkin_Class
{	
	protected $connection;
	
	public function __construct(PDO $db_connection)//, 
	{
		$this->connection = $db_connection;//& $GLOBALS['connection'];
	}
	
	public function __destruct()
	{
		// cleanup
	}
  	
	//添加一条记录
	public function AddCheckin($sqlarray)
	{
		$message = 'Add record failed!';
		if(empty($sqlarray))
		{
      $message = 'Param error!';
      return $message;
    }
    
    $title = '';
    $context = '';
    $studentIDs = [];
    foreach($sqlarray as $key => $value) {
      if(strstr($key, 'time') && empty($value)) {
        $title .= $key . ',';
        $context .= 'null,';
      } elseif($key == 'ID') { //studentID
        if(!empty($value)) {
          $studentIDs = json_decode($value);
        }
      } else {
        $title .= $key . ',';
        $context .= '"' . $value . '"' . ',';
      }
    }
    $title1 = '';
    $context1 = '';
    if(!empty($title) && !empty($context)) {
      $title .= 'manualmodified';
      $context .= '"1"';
      //$title = substr($title, 0, -1); //去掉最后的逗号
      //$context = substr($context, 0, -1); //去掉最后的逗号
      foreach($studentIDs as $key1 => $value1) {
        if($value1 == true) {
          $title1 = $title . ',studentID';
          $context2 = $context . ',"' . $key1 . '"';

          if(empty($context1)) {
            $context1 .= $context2;
          } else {
            $context1 .= '),(' . $context2;
          }
        }
      }
    } else {
      $message = '';//'Param error 2!';
      return $message;
    }
    
    $sql = 'INSERT INTO attendance('. $title1 . ') VALUES('. $context1 . ')';
    //console_log($sql);
    $message .= $sql;
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute()) {
      $message = '';
    }
    else {
      $message = 'Add record failed!';
      $message .= ShowErrorCode($statement);
    }
    
		return $message;
	}

	//更新一条记录
	public function UpdateCheckin($ID, $sqlarray)
	{
		$message = 'Update record failed!';
		if(empty($ID) || empty($sqlarray))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //数据表 Checkin
    //查询ID是否存在，不存在则返回错误
    $sql = "SELECT ID from attendance WHERE ID = :ID";
    $statement = $this->connection->prepare($sql);
    $statement->execute([':ID' => $ID]);
    $record = $statement->fetch( PDO::FETCH_OBJ );
    if ( $record == NULL )
    {
      $message = 'This record is not exist!';
      return $message;
    }
    
    $context = '';
    foreach($sqlarray as $key => $value) {
      if(strstr($key, 'time') && empty($value)) {
        $context .= $key . '=null,';
      } else {
        $context .= $key . '="' . $value . '"' . ',';
      }
    }
    if(!empty($context)) {
      $context .= 'recordtime="' . date('Y-m-d H:i:s') . '",';
      $context .= 'manualmodified="1"';
      //$context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message = '';//'Param error 2!';
      return $message;
    }
    
    $sql = 'UPDATE attendance SET ' . $context .' WHERE ID=:ID';
    //echo $sql;
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute([':ID' => $ID])) {
      $message = '';
    }
    else {
      $message = 'Update record failed!';
      $message .= ShowErrorCode($statement);
    }
		
		return $message;
	}
  
	//获取学生签到记录
	public function GetCheckin($checkinID, &$checkins)
	{
		if(!empty($checkinID))
		{
      $sql = 'SELECT * FROM attendance WHERE ID=:ID';
      $statement = $this->connection->prepare( $sql );
      $statement->execute( [ ':ID' => $checkinID ] );
      $recordcheckin = $statement->fetch( PDO::FETCH_OBJ );
      if ( $recordcheckin != NULL ) {
        $checkins = get_object_vars($recordcheckin);
        return '';
      }
      return 'Checkin record not found. ';
    }
		return 'Checkin ID not found. ';
	}
  
	//查询学生签到信息
	public function QueryCheckin($Param, &$data)
	{
		$message = 'Query record failed!';
    
    $sql = 'SELECT *, a.ID AS ID, s.studentnumber AS studentnumber, s.name AS name, c.name AS classname FROM attendance AS a LEFT JOIN student AS s ON a.studentID=s.ID LEFT JOIN class AS c ON s.classID=c.ID';
    $sql .= $Param;
    $statement = $this->connection->prepare($sql);
    $statement->execute();
    $record = $statement->fetchAll( PDO::FETCH_OBJ );
    if ( $record != NULL )
    {
      $message = '';
      $all = array();
      foreach($record as $record)
      {
        $all[] = $record;
      }
      $data = json_encode($all);
		  return $message;
    }
    else
    {
      //ShowErrorCode($statement);
      $message = 'Checkin record not found!';
      return $message;
    }
    
		return $message;
	}
  
	//删除记录
	public function DeleteCheckin($recordIDs)
	{
		$message = 'Delete record failed!';
		if(empty($recordIDs))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //批量删除DELETE FROM student WHERE ID IN (640,634,633)；
    $sql = 'DELETE FROM attendance WHERE ID IN ' . $recordIDs;
    //console_log($sql);
    $statement = $this->connection->prepare($sql);
    $statement->execute();
    $count = $statement->rowCount();
    if($count > 0) {
      $message = '';
    }
    else {
      $message = 'No record has been deleted!';
      ShowErrorCode($statement);
    }
    
		return $message;
	}
  
	//查询上课时间信息
	public function QueryClassSituation($Param, &$data)
	{
		$message = 'Query record failed!';
    
    $sql = 'SELECT *, s.ID AS ID, s.classindex AS classindex, LEFT(s.checkinnum * 100 / s.studentnum, 5) AS checkinpercent, s.recordtime AS recordtime, s.manualmodified AS manualmodified, c.name AS classname, s.property AS property FROM situationclass AS s LEFT JOIN class AS c ON s.classID = c.ID'; //CONCAT(left (s.checkinnum * 100 / s.studentnum, 5),"%") AS checkinpercent, 
    $sql .= $Param;
    $statement = $this->connection->prepare($sql);
    $statement->execute();
    $record = $statement->fetchAll( PDO::FETCH_OBJ );
    if ( $record != NULL )
    {
      $message = '';
      $all = array();
      foreach($record as $record)
      {
        $all[] = $record;
      }
      $data = json_encode($all);
		  return $message;
    }
    else
    {
      //ShowErrorCode($statement);
      $message = 'Record not found!';
      return $message;
    }
    
		return $message;
	}
  
	//修改上课时间记录
	public function EditClassSituation($checkinIDs, $property)
	{
		$message = 'Update record failed!';
		if(empty($checkinIDs))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //批量删除DELETE FROM checkin WHERE ID IN (640,634,633);
    $sql = 'UPDATE situationclass SET property="' . $property . '", manualmodified="1" WHERE ID IN ' . $checkinIDs;
    //console_log($sql);
    $statement = $this->connection->prepare($sql);
    if($statement->execute()) {
      $message = '';
    }
    else {
      $message = 'No record has been updated!';
      ShowErrorCode($statement);
    }
		
		return $message;
	}
  
	//获取学生签到信息
	public function GetAttendance($ID, &$data, &$info)
	{
		if(!empty($ID))
		{
      $message = '';
      $time = time();
      $sql = 'SELECT classstartdate FROM student2 WHERE ID=:ID';
      $statement = $this->connection->prepare($sql);
      $statement->execute( [ ':ID' => $ID ] );
      $recordclassstartdate = $statement->fetch( PDO::FETCH_OBJ );
      if(!empty($recordclassstartdate)) {
        $classstartdate = $recordclassstartdate->classstartdate;
        if($time < strtotime($classstartdate)) {
          //$message = "入学時間未到";
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
      $statement = $this->connection->prepare($sql);
      $statement->execute( [ ':studentID' => $ID ] );
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
      //$info .= json_encode($classrec) . '<br><br>';
      
      $sql = 'SELECT property, date FROM situationmonth WHERE studentID=:studentID';
      $statement = $this->connection->prepare($sql);
      $statement->execute( [ ':studentID' => $ID ] );
      $recordsituationmonth = $statement->fetchAll(PDO::FETCH_OBJ);
      $attendrec = array();
      foreach($recordsituationmonth as $recordsituationmonth) {
        $info .= $recordsituationmonth->date . $recordsituationmonth->property . "<br>";
        $timerec = strtotime($recordsituationmonth->date);
        $yearrec  = date("Y", $timerec) + 0;   // 时间1的年份
        $monthrec = date("m", $timerec) + 0;   // 时间1的月份
        $propertyobj = json_decode($recordsituationmonth->property);
        foreach($propertyobj as $key => $value) {
          $attendrec[$yearrec][$monthrec][$key] = $value;
        }
      }

      $sql = 'SELECT ID, property FROM attendproperty';
      $statement = $this->connection->prepare($sql);
      $statement->execute();
      $recordattendproperty = $statement->fetchAll(PDO::FETCH_OBJ);
      $arrayproperty = array();
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
          $d2 = $this->DaysInMonth($year, $m);
          if($year == $year1 && $m == $m1) {
            $d1 = $day1;
          } else if($year == $year2 && $m == $m2) {
            $d2 = $day2;
          }
          for($d=$d1; $d<=$d2; $d++) {
            $property = -1;
            $lessons = array();
            $lessons[0] = 4;
            $selectedindex = 0;
            for($k=0; $k<=4; $k++) {
              $propertykey = 'd' . $d . 'c' . $k;

              //每日上课课时
              if(isset($classrec[$year]) && isset($classrec[$year][$m]) && isset($classrec[$year][$m][$propertykey])) {
                //$info .= $year . ' ' . $m . ' ' . $propertykey . ' ' . $classrec[$year][$m][$propertykey]->property . " property 2 <br>";
                $classlesson = $classrec[$year][$m][$propertykey];
                if($classlesson->property == 1) {
                  $monthattend->days[$d] = $arrayproperty[2]; //欠
                  if(!isset($attendrec[$year]) || !isset($attendrec[$year][$m]) || !isset($attendrec[$year][$m][$propertykey])) {
                    $attendrec[$year][$m][$propertykey] = 2;
                  }

                  //每日出勤情况
                  if(isset($attendrec[$year]) && isset($attendrec[$year][$m]) && isset($attendrec[$year][$m][$propertykey])) {
                    $property1 = $attendrec[$year][$m][$propertykey];
                    $arraypriority = array(-1, 0, 2, 5, 4, 3, 7, 6, 1);
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
            } else if($property == 0) { //'不'
            } else if($property == 3) { //'公'
            } else if($property == 4) { //'休'
            } else if($property == 5) { //'帰'
            } else if($property == 6) { //'遅'
              $monthattend->lessonlate += 1;
              $monthattend->dayattend += 1;
              $monthattend->lessonattend += $lessons[$selectedindex] - 1;
            } else if($property == 7) { //'-'-:休校日
            } else if($property == -1) { //'-'-:休校日
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
      $data = json_encode($StudentAttendance);
    } else {
		  $message = 'Student ID not found. ';
    }
    return $message;
	}
  
  private function DaysInMonth($year='', $month='') {
    if(empty($year)) $year = date('Y');  
      if(empty($month)) $month = date('m');
    $day = '01';

    //检测日期是否合法
    if(!checkdate($month, $day, $year))
      return '输入的时间有误';

    //获取当年当月第一天的时间戳(时,分,秒,月,日,年)
    $timestamp = mktime(0, 0, 0, $month, $day, $year);
    $result = date('t', $timestamp);
    return $result;
  }
// end of class  
}