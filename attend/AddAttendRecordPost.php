<?php
require_once '../include/include_database.php';
require_once 'AttendClass.php';

use Attend\AttendClass;

$message = 'check in failed: not got student id';
if (isset ($_POST['studentID']))
{
	$studentID = $_POST['studentID'];
	$attend = new AttendClass($connection);
	$message = $attend->AddAttendRecord($studentID);
	
	if($message == '')
	{
		$message = "check in successfully!";
	}
}
echo $message;
return $message;
?>