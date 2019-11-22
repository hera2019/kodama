<!-- code by zmq -->
<?php
require '../include/include_database.php';
require '../include/include_function.php';
$ID = $_GET['ID'];
$sql = 'DELETE FROM attendance WHERE ID=:ID';
$statement = $connection->prepare($sql);
if ($statement->execute([':ID' => $ID])) {
	GotoURL("queryrecord.php");
}