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
class StudentAttendance {
  public $firsttime = NULL;
  public $lasttime = NULL;
  public $months = array();
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
    if($this->dayall == 0 && $this->lessonall == 0) {
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

//$studentattendance = new StudentAttendance();
//$month1 = new AttendMonth();
//$month1->days['dayname'] = 'dayproperty';
//$studentattendance->months['yearname']['monthname'] = $month1;
//$studentattendance->months['yearname']['monthname']->days[dayname]
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
      } elseif((strstr($key, 'classindex') && empty($value)) || (strstr($key, 'property') && empty($value))) {
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
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute()) {
      $message = '';
    }
    else {
      $message = 'Add record failed!';
      $message .= $sql;
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
      } elseif((strstr($key, 'classindex') && empty($value)) || (strstr($key, 'property') && empty($value))) {
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
  
	//添加一条班级记录
	public function AddClassManage($sqlarray)
	{
		$message = 'Add record failed!';
		if(empty($sqlarray))
		{
      $message = 'Param error!';
      return $message;
    }
    
    $title = '';
    $context = '';
    foreach($sqlarray as $key => $value) {
      if($key == 'ID') {
        continue;
      }
      $title .= $key . ',';
      $context .= '"' . $value . '"' . ',';
    }
    if(!empty($title) && !empty($context)) {
      $title = substr($title, 0, -1); //去掉最后的逗号
      $context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message = '';//'Param error 2!';
      return $message;
    }
    
    $sql = 'INSERT INTO class('. $title . ') VALUES('. $context . ')';
    //console_log($sql);
    $statement =  $this->connection->prepare($sql);
    if ($statement->execute()) {
      $message = '';
    }
    else {
      $message = 'Add record failed!';
      ShowErrorCode($statement);
    }
    
		return $message;
	}

	//查询班级信息
	public function QueryClassManage($Param, &$data)
	{
		$message = 'Query record failed!';
    
    $sql = 'SELECT c.ID AS ID, c.name AS name, c.description AS description, u.name AS classteachername FROM class AS c LEFT JOIN usermanage AS u ON c.classteacherID=u.ID'; //CONCAT(left (s.checkinnum * 100 / s.studentnum, 5),"%") AS checkinpercent, 
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
  
	//修改班级信息
	public function UpdateClassManage($ID, $sqlarray)
	{
		$message = 'Update record failed!';
		if(empty($ID) || empty($sqlarray))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //数据表 Checkin
    //查询ID是否存在，不存在则返回错误
    $sql = "SELECT ID from class WHERE ID = :ID";
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
      if($key == 'ID') {
        continue;
      }
      $context .= $key . '="' . $value . '"' . ',';
    }
    if(!empty($context)) {      
      $context = substr($context, 0, -1); //去掉最后的逗号
    } else {
      $message = '';
      return $message;
    }
    
    $sql = 'UPDATE class SET ' . $context .' WHERE ID=:ID';
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
  
	//删除记录
	public function DeleteClassManage($ID)
	{
		$message = 'Delete record failed!';
		if(empty($ID))
		{
      $message = 'Param error!';
      return $message;
    }
    
    //批量删除DELETE FROM student WHERE ID IN (640,634,633)；
    $sql = 'DELETE FROM class WHERE ID=:ID';
    //console_log($sql);
    $statement = $this->connection->prepare($sql);
    $statement->execute([':ID' => $ID]);
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
  
	//获取学生签到信息
	public function GetAttendance($ID, &$data, &$info)
	{
		if(!empty($ID))
		{
      $message = '';/*
      $sql = 'SELECT situationclass.classID AS classID, situationclass.classindex AS classindex, situationclass.property AS property, situationclass.lessons AS lessons, situationclass.recordtime AS recordtime FROM situationclass LEFT JOIN student ON student.classID=situationclass.classID WHERE student.ID=:studentID ORDER BY recordtime ASC';
      $statement = $this->connection->prepare($sql);
      $statement->execute( [ ':studentID' => $ID ] );
      $recordsituationclass = $statement->fetchAll(PDO::FETCH_OBJ);
      $classrec = array();
      foreach($recordsituationclass as $recordsituationclass) {
        if($recordsituationclass->property > 0) {
          $timerec = strtotime($recordsituationclass->recordtime);
          $yearrec  = date("Y", $timerec) + 0;   // 年
          $monthrec = date("m", $timerec) + 0;   // 月
          $dayrec = date("d", $timerec) + 0;   // 日
          $classrec[$yearrec][$monthrec][$dayrec][$recordsituationclass->classindex] = new ClassLesson($recordsituationclass->property, $recordsituationclass->lessons);
        }
      }
      ksort($classrec);
      foreach($classrec as $info_y => $info_month_s) {
        foreach($info_month_s as $info_m => $info_day_s) {
          $info .= $info_y . '-' . $info_m . '=' . json_encode($info_day_s) . '<br>';
        }
      }
      $info = str_replace('{"property":', '', $info); //去掉键值字符
      $info = str_replace(',"lessons":"4"}', '', $info); //无用信息
      $info .= '<br>';
      */
      $sql = 'SELECT ID, property FROM attendproperty';
      $statement = $this->connection->prepare($sql);
      $statement->execute();
      $recordattendproperty = $statement->fetchAll(PDO::FETCH_OBJ);
      $arrayproperty = array();
      foreach($recordattendproperty as $recordattendproperty) {
        $arrayproperty[$recordattendproperty->ID] = $recordattendproperty->property;
      }

      $studentattendance = new StudentAttendance();
      $monthattend = new AttendMonth();
      
      $sql = 'SELECT properties, date, classlesson, attendlesson FROM situationmonth WHERE studentID=:studentID ORDER BY date ASC';
      $statement = $this->connection->prepare($sql);
      $statement->execute( [ ':studentID' => $ID ] );
      $recordsituationmonth = $statement->fetchAll(PDO::FETCH_OBJ);
      $attendrec = array();
      $arraypriority = array(-1, 0, 2, 5, 4, 3, 7, 6, 1);//属性优先级比较用
      foreach($recordsituationmonth as $recordsituationmonth) { //每月
        if(empty($studentattendance->firsttime)) {
          $studentattendance->firsttime = $recordsituationmonth->date;
        }
        $studentattendance->lasttime = $recordsituationmonth->date;
        $timerec = strtotime($recordsituationmonth->date);
        $year = date("Y", $timerec) + 0;
        $month = date("m", $timerec) + 0;
        $monthrec = date("Y-m", $timerec);
        $info .= $monthrec . '=' . $recordsituationmonth->properties . "<br>";  
        $monthattend->ResetValue();

        //计算全部课时
        $allday = 0;
        //计算每日签到
        $attendday = 0;
        $absentday = 0;
        $lateday = 0;

        if(!empty($recordsituationmonth->properties)) {
          $propertyobj = json_decode($recordsituationmonth->properties);
          foreach($propertyobj as $day => $valueday) { //每天
            $property = -1;
            foreach($valueday as $classindex => $property1) {
              if($property1 == 6) { //'遅'
                $monthattend->lessonlate += 1;
              }

              //属性优先级比较
              $priority = array_search($property, $arraypriority);
              $priority1 = array_search($property1, $arraypriority);
              if($priority < $priority1) {
                $property = $property1;
              }
              if(isset($monthattend->days[$day])) {
                $monthattend->days[$day] .= $arrayproperty[$property1];
              } else {
                $monthattend->days[$day] = $arrayproperty[$property1];
              }
            } //每次课
            if($property == 1) { //'出'
              $monthattend->dayall += 1;
              $monthattend->dayattend += 1;
            } else if($property == 2) { //'欠'
              $monthattend->dayall += 1;
              $monthattend->dayabsent += 1;
            } else if($property == 0) { //'不'
            } else if($property == 3) { //'公'
            } else if($property == 4) { //'休'
            } else if($property == 5) { //'帰'
            } else if($property == 6) { //'遅'
              $monthattend->dayall += 1;
              $monthattend->dayattend += 1;
            } else if($property == 7) { //'-'-:休校日
            } else if($property == -1) { //'-'-:休校日
              $property = 7;
            }
          } //每日
        }
        $monthattend->lessonall = $recordsituationmonth->classlesson;
        $monthattend->lessonattend = $recordsituationmonth->attendlesson;
        $monthattend->lessonabsent = $monthattend->lessonall - $monthattend->lessonattend - $monthattend->lessonlate;
        if($monthattend->dayall > 0) {
          $monthattend->dayattendpercent = round($monthattend->dayattend / $monthattend->dayall * 100) . "%";
        }
        if($monthattend->lessonall > 0) {
          $monthattend->lessonattendpercent = round($monthattend->lessonattend / $monthattend->lessonall * 100) . "%";
        }
        $monthattend->CheckValue();
        $studentattendance->months[$year][$month] = clone $monthattend;
      } //每月       
      $info = str_replace('"', '', $info); //无用信息
      //$info = ''; //不显示调试信息
      $data = json_encode($studentattendance);
    } else {
		  $message = 'Student ID not found. ';
    } //每年
    return $message;
	}  
// end of class  
}