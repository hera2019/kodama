<?php
require_once '../include/include_database.php';
require_once 'attend_class.php';

use Attend\AttendClass;

$message = 'Check in failed: not got student id';
if (isset ($_POST['studentID']))
{
	$studentID = $_POST['studentID'];
	$attend = new AttendClass($connection);
	$message = $attend->AddAttendRecord($studentID);
	
	if($message == '')
	{
		$message = "Check in successfully!";
	}
}
echo $message;
return $message;
?>