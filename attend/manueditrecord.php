<!-- code by zmq -->
<?php
require 'db.php';
require '../include/include_function.php';$ID = $_GET['ID'];
//$sql = 'SELECT * FROM attendance WHERE ID=:ID';
$sql = 'SELECT *,attendance.ID AS ID,student.ID AS userID2 FROM attendance  INNER JOIN student ON attendance.userID = student.ID AND  attendance.ID=:ID';
$statement = $connection->prepare($sql);
$statement->execute([':ID' => $ID ]);
$record = $statement->fetch(PDO::FETCH_OBJ);
$record->time1 = str_replace(' ', 'T', $record->time1);  //转换为datetime-local控件格式，控件中step="01"可以设置到秒；
$record->time2 = str_replace(' ', 'T', $record->time2);  //转换为datetime-local控件格式，控件中step="01"可以设置到秒；
$record->time3 = str_replace(' ', 'T', $record->time3);  //转换为datetime-local控件格式，控件中step="01"可以设置到秒；
$record->time4 = str_replace(' ', 'T', $record->time4);  //转换为datetime-local控件格式，控件中step="01"可以设置到秒；
$record->time5 = str_replace(' ', 'T', $record->time5);  //转换为datetime-local控件格式，控件中step="01"可以设置到秒；
$record->time6 = str_replace(' ', 'T', $record->time6);  //转换为datetime-local控件格式，控件中step="01"可以设置到秒；
$record->time7 = str_replace(' ', 'T', $record->time7);  //转换为datetime-local控件格式，控件中step="01"可以设置到秒；
$record->time8 = str_replace(' ', 'T', $record->time8);  //转换为datetime-local控件格式，控件中step="01"可以设置到秒；

if (isset($_POST['time1'])) { // && isset($_POST['time2'])
  $time1 = $_POST['time1'];
  $time2 = $_POST['time2'];
  $time3 = $_POST['time3'];
  $time4 = $_POST['time4'];
  $time5 = $_POST['time5'];
  $time6 = $_POST['time6'];
  $time7 = $_POST['time7'];
  $time8 = $_POST['time8'];
  $time1 = ($time1 == '' ? NULL : $time1); //空值要用NULL；
  $time2 = ($time2 == '' ? NULL : $time2); //空值要用NULL；
  $time3 = ($time3 == '' ? NULL : $time3); //空值要用NULL；
  $time4 = ($time4 == '' ? NULL : $time4); //空值要用NULL；
  $time5 = ($time5 == '' ? NULL : $time5); //空值要用NULL；
  $time6 = ($time6 == '' ? NULL : $time6); //空值要用NULL；
  $time7 = ($time7 == '' ? NULL : $time7); //空值要用NULL；
  $time8 = ($time8 == '' ? NULL : $time8); //空值要用NULL；
  
  if(isset($_POST['propertyID']))
  {
      $property = $_POST['propertyID'];
  }
  if($property == 0)
  {
      if($record->property == NULL || $record->property == '' || $record->property == 0)
      {
          $weekday = date("w", strtotime($time1)); //是否假日
          if($weekday == 0 || $weekday == 6)
          {
              $property = 7;
          }
          else 
          {
              $hour = date("H", strtotime($time1)); //是否迟到
              if($hour < 8) {
                  $property = 1;
              }
              else {
                  $property = 6;
              }
          }
      }
      else
      {
          $property =  $record->property;
      }
  }
  
  $sql = 'UPDATE attendance SET time1=:time1, time2=:time2, time3=:time3, time4=:time4, time5=:time5, time6=:time6, time7=:time7, time8=:time8, property=:property, manualmodified=1  WHERE ID=:ID';
  $statement = $connection->prepare($sql);
  if ($statement->execute([':time1' =>$time1, ':time2' =>$time2, ':time3' =>$time3, ':time4' =>$time4, ':time5' =>$time5, ':time6' =>$time6, ':time7' =>$time7, ':time8' =>$time8, ':property'=>$property, ':ID' => $ID])) {
  	GotoURL("queryrecord.php");
  }
  else {
      ShowErrorCode($statement);
  }
}

 ?>
<?php require 'queryrecordheader.php'; ?>
<div class="container">
  <div class="card mt-5">
    <div class="card-header">
      <h2>Update Record</h2>
    </div>
    <div class="card-body">
      <?php if(!empty($message)): ?>
        <div class="alert alert-success">
          <?= $message; ?>
        </div>
      <?php endif; ?>
      <form method="post">
        <div class="form-group">
          <label for="name">name</label>
          <input value="<?= $record->name; ?>" type="text" name="name" ID="name" disabled>
        </div>
        <div class="form-group">
          <label for="time1">time1</label>
          <input type="datetime-local"  step="01"  value="<?= $record->time1 ?>" name="time1"  ID="time1">
        </div>
        <div class="form-group">
          <label for="time2">time2</label>
          <input type="datetime-local"  step="01"  value="<?= $record->time2 ?>" name="time2"  ID="time2">
        </div>
       <div class="form-group">
          <label for="time3">time3</label>
          <input type="datetime-local"  step="01"  value="<?= $record->time3 ?>" name="time3"  ID="time3">
        </div>
        <div class="form-group">
          <label for="time4">time4</label>
          <input type="datetime-local"  step="01"  value="<?= $record->time4 ?>" name="time4"  ID="time4">
        </div>
       <div class="form-group">
          <label for="time5">time5</label>
          <input type="datetime-local"  step="01"  value="<?= $record->time5 ?>" name="time5"  ID="time5">
        </div>
        <div class="form-group">
          <label for="time6">time6</label>
          <input type="datetime-local"  step="01"  value="<?= $record->time6 ?>" name="time6"  ID="time6">
        </div>
       <div class="form-group">
          <label for="time7">time7</label>
          <input type="datetime-local"  step="01"  value="<?= $record->time7 ?>" name="time7"  ID="time7">
        </div>
        <div class="form-group">
          <label for="time8">time8</label>
          <input type="datetime-local"  step="01"  value="<?= $record->time8 ?>" name="time8"  ID="time8">
        </div>
        <div class="form-group">
          <label for="propertyID">property</label>
          <select  id="propertyID" name="propertyID" οnchange="selectcity()">
   			<option value="0">---Select---</option>
   			<?php
   			    //get property
       			$sql = 'SELECT ID, property FROM  property';
       			$statement = $connection->prepare($sql);
       			$statement->execute();
       			$record1 = $statement->fetchAll(PDO::FETCH_OBJ);
       			
       			foreach($record1 as $record2)
                {
                    if($record->property == $record2->ID)
                    {
                        echo "<option value='$record2->ID' selected = 'selected'>$record2->property</option>";//选中，循环，拼凑下拉框选项
                    }
                    else 
                    {
                        echo "<option value='$record2->ID'>$record2->property</option>";//循环，拼凑下拉框选项
                    }
                } 
              ?>
   		  </select>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-info">Update Record</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php require 'footer.php'; ?>
