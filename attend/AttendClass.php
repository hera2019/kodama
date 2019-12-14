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
				$hour = date("H", $time); //是否迟到
				if($hour < 8) {
					$property = 1;
				}
				else {
					$property = 6;
				}
				
				$sql = 'INSERT INTO attendance(studentID, ' . $whichtime . ', property) VALUES(:studentID, :currenttime, :property)';
				$statement =  $this->connection->prepare($sql);
				if ($statement->execute([':studentID' => $studentID, ':currenttime' => $currenttime, ':property' => $property])) {
					$message = '';
				}
				else {
					$message = 'check in failed';
					ShowErrorCode($statement);
				}
			}
			else
			{
				$update = true;
				$ID = $record->ID;
				if($record->time12 == NULL)
				{
					if($time - strtotime($record->time11) < 120)
					{
						$update = false;
					}
					$whichtime = 'time12';
				}
				else if($record->time21 == NULL)
				{
					if($time - strtotime($record->time12) < 120)
					{
						$update = false;
					}
					$whichtime = 'time21';
				}
				else if($record->time22 == NULL)
				{
					if($time - strtotime($record->time21) < 120)
					{
						$update = false;
					}
					$whichtime = 'time22';
				}
				else if($record->time31 == NULL)
				{
					if($time - strtotime($record->time22) < 120)
					{
						$update = false;
					}
					$whichtime = 'time31';
				}
				else if($record->time32 == NULL)
				{
					if($time - strtotime($record->time31) < 120)
					{
						$update = false;
					}
					$whichtime = 'time32';
				}
				else if($record->time41 == NULL)
				{
					if($time - strtotime($record->time32) < 120)
					{
						$update = false;
					}
					$whichtime = 'time41';
				}
				else if($record->time42 == NULL)
				{
					if($time - strtotime($record->time41) < 120)
					{
						$update = false;
					}
					$whichtime = 'time42';
				}
				else
				{
					$update = false;
				}
				
				if($update)
				{
					$sql = 'UPDATE attendance SET ' . $whichtime . '=:currenttime WHERE ID=:ID';
					$statement =  $this->connection->prepare($sql);
					if ($statement->execute([':currenttime' =>$currenttime, ':ID' => $ID])) {
						$message = '';
					}
					else {
						$message = 'check in failed';
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