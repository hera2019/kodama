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
	public function AddAttendRecord($userID = NULL)
	{
		$message = 'check in failed';
		if($userID != NULL && $userID != '' && $userID != 0)
		{
			$time = time();
			$currenttime = date('Y-m-d H:i:s', $time);
			$curday = date('Y-m-d', $time);
			$whichtime = 'time1';
			$sql = "SELECT * from attendance WHERE userID = :userID AND date_format(time1,'%Y-%m-%d') = :curday";
			$statement = $this->connection->prepare($sql);
			$statement->execute([':userID' => $userID, ':curday' => $curday]);
			$record = $statement->fetch(PDO::FETCH_OBJ);
			if($record == NULL || $record->time1 == NULL ||  $record->time1 == '') //first time of this day
			{
				$hour = date("H", $time); //是否迟到
				if($hour < 8) {
					$property = 1;
				}
				else {
					$property = 6;
				}
				
				$sql = 'INSERT INTO attendance(userID, ' . $whichtime . ', property) VALUES(:userID, :currenttime, :property)';
				$statement =  $this->connection->prepare($sql);
				if ($statement->execute([':userID' => $userID, ':currenttime' => $currenttime, ':property' => $property])) {
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
				if($record->time2 == NULL)
				{
					if($time - strtotime($record->time1) < 120)
					{
						$update = false;
					}
					$whichtime = 'time2';
				}
				else if($record->time3 == NULL)
				{
					if($time - strtotime($record->time2) < 120)
					{
						$update = false;
					}
					$whichtime = 'time3';
				}
				else if($record->time4 == NULL)
				{
					if($time - strtotime($record->time3) < 120)
					{
						$update = false;
					}
					$whichtime = 'time4';
				}
				else if($record->time5 == NULL)
				{
					if($time - strtotime($record->time4) < 120)
					{
						$update = false;
					}
					$whichtime = 'time5';
				}
				else if($record->time6 == NULL)
				{
					if($time - strtotime($record->time5) < 120)
					{
						$update = false;
					}
					$whichtime = 'time6';
				}
				else if($record->time7 == NULL)
				{
					if($time - strtotime($record->time6) < 120)
					{
						$update = false;
					}
					$whichtime = 'time7';
				}
				else if($record->time8 == NULL)
				{
					if($time - strtotime($record->time7) < 120)
					{
						$update = false;
					}
					$whichtime = 'time8';
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