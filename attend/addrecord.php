<?php
require 'db.php';
if (isset ($_GET['userID'])) {
  $userID = $_GET['userID'];
  if($userID != '' && $userID != NULL)
  {
      $time1 = date('Y-m-d H:i:s');
      $sql = 'INSERT INTO attendance(userID, time1) VALUES(:userID, :time1)';
      $statement = $connection->prepare($sql);
      if ($statement->execute([':userID' => $userID, ':time1' =>$time1])) {
      }
      else {
          $query = $_SERVER['QUERY_STRING'];
          echo $query.'<br>';
          ShowErrorCode($statement);
      }
  }
}
?>
