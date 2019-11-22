<?php
require_once '../include/include_database.php';
require_once 'AttendClass.php';

use Attend\AttendClass;

$message = 'check in failed: not got student id';
if (isset ($_GET['userID']))
{
	$userID = $_GET['userID'];
	$attend = new AttendClass($connection);
	$message = $attend->AddAttendRecord($userID);
}
echo $message;
return $message;
?>