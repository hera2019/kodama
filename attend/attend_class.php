<?php
namespace Attend;

require_once '../include/include_function.php';

use PDO;

class AttendClass
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
	
	//添加一条刷卡记录
	public function AddAttendRecord($studentID = NULL)
	{
		$message = 'check in failed';
		if($studentID != NULL && $studentID != '' && $studentID != 0)
		{
			$time = time();
			$currenttime = date('Y-m-d H:i:s', $time);
			$curday = date('Y-m-d', $time);
			$whichtime = 'time11';
			$sql = "SELECT * from attendance WHERE studentID = :studentID AND date_format(time11,'%Y-%m-%d') = :curday";
			$statement = $this->connection->prepare($sql);
			$statement->execute([':studentID' => $studentID, ':curday' => $curday]);
			$record = $statement->fetch(PDO::FETCH_OBJ);
			if($record == NULL || $record->time11 == NULL ||  $record->time11 == '') //first time of this day
			{
        $classindex = 0;
				$property = 0;
        $LessonClass = new LessonClass($this->connection);
        $classindex = $LessonClass->GetClassIndexProperty($currenttime, null, $property);
				$sql = 'INSERT INTO attendance(studentID, ' . $whichtime . ', classindex1, property1) VALUES(:studentID, :currenttime, :classindex, :property)';
				$statement =  $this->connection->prepare($sql);
				if ($statement->execute([ ':studentID' => $studentID, ':currenttime' => $currenttime, ':classindex' => $classindex, ':property' => $property ])) {
					$message = '';
				}
				else {
					$message = 'Check in failed';
					ShowErrorCode($statement);
				}
			}
			else
			{
        $classindex = 0;
				$property = 0;
				$update = true;
				$ID = $record->ID;
				if($record->time12 == NULL)
				{
					if($time - strtotime($record->time11) < 120)
					{
						$update = false;
					} else {
            $LessonClass = new LessonClass($this->connection);
            $classindex = $LessonClass->GetClassIndexProperty($record->time11, $currenttime, $property);
            $whichtime = 'time12';
            $whichclassindex = 'classindex1';
            $whichproperty = 'property1';
          }
				}
				else if($record->time21 == NULL)
				{
					if($time - strtotime($record->time12) < 120)
					{
						$update = false;
					} else {
            $LessonClass = new LessonClass($this->connection);
            $classindex = $LessonClass->GetClassIndexProperty($currenttime, null, $property);
            $whichtime = 'time21';
            $whichclassindex = 'classindex2';
            $whichproperty = 'property2';
          }
				}
				else if($record->time22 == NULL)
				{
					if($time - strtotime($record->time21) < 120)
					{
						$update = false;
					} else {
            $LessonClass = new LessonClass($this->connection);
            $classindex = $LessonClass->GetClassIndexProperty($record->time21, $currenttime, $property);
            //$message = ' T: ' . $record->time21 . ' ' . $currenttime . ' ' . $classindex . ' ' . $property . ' E ';
            $whichtime = 'time22';
            $whichclassindex = 'classindex2';
            $whichproperty = 'property2';
          }
				}
				else if($record->time31 == NULL)
				{
					if($time - strtotime($record->time22) < 120)
					{
						$update = false;
					} else {
            $LessonClass = new LessonClass($this->connection);
            $classindex = $LessonClass->GetClassIndexProperty($currenttime, null, $property);
            $whichtime = 'time31';
            $whichclassindex = 'classindex3';
            $whichproperty = 'property3';
          }
				}
				else if($record->time32 == NULL)
				{
					if($time - strtotime($record->time31) < 120)
					{
						$update = false;
					} else {
            $LessonClass = new LessonClass($this->connection);
            $classindex = $LessonClass->GetClassIndexProperty($record->time31, $currenttime, $property);
            $whichtime = 'time32';
            $whichclassindex = 'classindex3';
            $whichproperty = 'property3';
          }
				}
				else if($record->time41 == NULL)
				{
					if($time - strtotime($record->time32) < 120)
					{
						$update = false;
					} else {
            $LessonClass = new LessonClass($this->connection);
            $classindex = $LessonClass->GetClassIndexProperty($currenttime, null, $property);
            $whichtime = 'time41';
            $whichclassindex = 'classindex4';
            $whichproperty = 'property4';
          }
				}
				else if($record->time42 == NULL)
				{
					if($time - strtotime($record->time41) < 120)
					{
						$update = false;
					} else {
            $LessonClass = new LessonClass($this->connection);
            $classindex = $LessonClass->GetClassIndexProperty($record->time41, $currenttime, $property);
            $whichtime = 'time42';
            $whichclassindex = 'classindex4';
            $whichproperty = 'property4';
          }
				}
				else
				{
					$update = false;
				}
				
				if($update)
				{
					$sql = 'UPDATE attendance SET ' . $whichtime . '=:currenttime, ' . $whichclassindex . '=:classindex, ' . $whichproperty . '=:property, recordtime=:currenttime WHERE ID=:ID';
					$statement =  $this->connection->prepare($sql);
					if ($statement->execute([':currenttime' =>$currenttime, ':classindex' => $classindex, ':property' => $property, ':ID' => $ID])) {
						$message = '';
					}
					else {
						$message = 'Check in failed';
						ShowErrorCode($statement);
					}
				}
				else
				{
					$message = 'Too many check-ins today, or two check-in intervals of less than 2 minutes.';
				}
			}
		}
		
		return $message;
	}	
}

class LessonClass
{	
	protected $connection;
  
  protected $classtimenum;
  protected $classtime1 = array();
  protected $classtime2 = array();
  protected $lessons = array();
  protected $aheadperiod;
  protected $delayperiod;
  protected $allowlate;
  protected $allowearly;
	
	public function __construct(PDO $db_connection)//, 
	{
		$this->connection = $db_connection;//& $GLOBALS['connection'];
    
    $this->classtimenum = 1;
    $this->classtime1[1] = '08:00:00';
    $this->classtime2[1] = '12:00:00';
    $this->lessons[1] = 4;
    $this->aheadperiod = 60;
    $this->delayperiod = 60;
    $this->allowlate = 5;
    $this->allowearly = 5;
    
    $sql = 'SELECT * FROM  classtime';
    $statement = $this->connection->prepare( $sql );
    $statement->execute();
    $recordclasstime = $statement->fetch( PDO::FETCH_OBJ ); //只有一条记录不用fetchAll
    if ( $recordclasstime != NULL && $recordclasstime->num != 0 ) {
      $this->classtimenum = $recordclasstime->num;
      $this->classtime1[1] = $recordclasstime->time11;
      $this->classtime2[1] = $recordclasstime->time12;
      $this->lessons[1] = $recordclasstime->lessons1;
      $this->classtime1[2] = $recordclasstime->time21;
      $this->classtime2[2] = $recordclasstime->time22;
      $this->lessons[2] = $recordclasstime->lessons2;
      $this->classtime1[3] = $recordclasstime->time31;
      $this->classtime2[3] = $recordclasstime->time32;
      $this->lessons[3] = $recordclasstime->lessons3;
      $this->classtime1[4] = $recordclasstime->time41;
      $this->classtime2[4] = $recordclasstime->time42;
      $this->lessons[4] = $recordclasstime->lessons4;
      $this->aheadperiod = $recordclasstime->aheadperiod;
      $this->delayperiod = $recordclasstime->delayperiod;
      $this->allowlate = $recordclasstime->allowlate;
      $this->allowearly = $recordclasstime->allowearly;
    }
	}
	
	public function __destruct()
	{
		// cleanup
	}
  
  function GetClassTimeNum() {
    return $this->classtimenum;
  }
  
  function GetClassTime1($classindex) {  
    return $this->classtime1[$classindex];
  }
  
  function GetClassTime2($classindex) {  
    return $this->classtime2[$classindex];
  }
  
  function GetClassLessons($classindex) {  
    return $this->lessons[$classindex];
  }
  
  function GetClassAheadPeriod() {  
    return $this->aheadperiod;
  }
  
	//判断当前时间是否上课时间
  function IsBetweenTime( $start, $end, $time, $bstart, &$blate ) { //$bstart上课开始时间，还是下课时间
    $date = date( 'H:i:s', strtotime( $time ) );
    $curTime = strtotime( $date ); //当前时分秒
    $assignstart = strtotime( $start ); //获得指定秒钟时间戳，00:00:00
    $assignend = strtotime( $end ); //获得指定秒钟时间戳，01:00:00
    $result = false;
    $blate = false;
    if($bstart) {
      if ( $curTime > $assignstart - $this->aheadperiod * 60 
          && $curTime < $assignend ) {
        $result = true;
        if ( $curTime - $assignstart > $this->allowlate * 60 ) {
          $blate = true;
        }
      }
    } else {
      if ( $curTime > $assignstart 
          && $curTime < $assignend + $this->delayperiod * 60 ) {
        $result = true;
        if ( $assignend - $curTime > $this->allowearly * 60 ) {
          $blate = true;
        }
      }
    }
    return $result;
  }

  function GetClassIndex( $time, $bstart, &$blate ) {
    if(empty($time)) {
      return 0;
    }

    $classindex = 0;
    $blate = false;
    $blate1 = false;
    //判断第几课时段
    if ( $this->classtimenum >= 4 ) {
      $isBetweenTime = $this->IsBetweenTime( $this->classtime1[4], $this->classtime2[4], $time, $bstart, $blate1 );
      if ( $isBetweenTime ) {
        $classindex = 4;
        $blate = $blate1;
      }
    }
    if ( $this->classtimenum >= 3 ) {
      $isBetweenTime = $this->IsBetweenTime( $this->classtime1[3], $this->classtime2[3], $time, $bstart, $blate1 );
      if ( $isBetweenTime ) {
        $classindex = 3;
        $blate = $blate1;
      }
    }
    if ( $this->classtimenum >= 2 ) {
      $isBetweenTime = $this->IsBetweenTime( $this->classtime1[2], $this->classtime2[2], $time, $bstart, $blate1 );
      if ( $isBetweenTime ) {
        $classindex = 2;
        $blate = $blate1;
      }
    }
    if ( $this->classtimenum >= 1 ) {
      $isBetweenTime = $this->IsBetweenTime( $this->classtime1[1], $this->classtime2[1], $time, $bstart, $blate1 );
      if ( $isBetweenTime ) {
        $classindex = 1;
        $blate = $blate1;
      }
    }
    return $classindex;
  }

  function GetClassIndexProperty( $time1, $time2, &$property ) {
    $property = 0;
    if(!empty($time1) && !empty($time2)) {
      $date1 = date( 'Y-m-d', strtotime( $time1 ) );
      $date2 = date( 'Y-m-d', strtotime( $time2 ) );
      if(strtotime( $date1 ) != strtotime( $date2 )) { //判断是否同一天
        return 0;
      }
    }
    if(empty($time1) && empty($time2)) {
      $property = '';
      return 0;
    }
    
    $blate1 = false;
    $blate2 = false;
    $classindex1 = $this->GetClassIndex($time1, true, $blate1);
    $classindex2 = $this->GetClassIndex($time2, false, $blate2);
    if($classindex1 != 0 && $classindex2 != 0 && $classindex1 == $classindex2) { //判断是否同一堂课
      $property = 1; //出席
      if($blate1 || $blate2) {
        $property = 6; //迟到早退
      }
    }

    return $classindex1;
  }

  function GetProperty( $time1, $time2 ) {
    $date1 = date( 'Y-m-d', strtotime( $time1 ) );
    $date2 = date( 'Y-m-d', strtotime( $time2 ) );
    if(strtotime( $date1 ) != strtotime( $date2 )) { //判断是否同一天
      return 0;
    }

    $property = 0;
    $blate1 = false;
    $blate2 = false;
    $classindex1 = $this->GetClassIndex($time1, true, $blate1);
    $classindex2 = $this->GetClassIndex($time2, false, $blate2);
    if($classindex1 != 0 && $classindex2 != 0 && $classindex1 == $classindex2) { //判断是否同一堂课
      $property = 1; //出席
      if($blate1 || $blate2) {
        $property = 6; //迟到早退
      }
    }

    return $property;
  }
}