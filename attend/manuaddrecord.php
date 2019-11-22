<?php
require '../include/include_database.php';
require 'AttendClass.php';

use Attend\AttendClass;

define ('ATTEND_ADD_ATTEND_RECORD_PARENT_URL', 'queryrecord.php');

$message = '';
if (isset ($_POST['userID']))
{
  $userID = $_POST['userID'];
  $attend = new AttendClass($connection);
  $message = $attend->AddAttendRecord($userID);
  if($message == '')
  {  	
  	GotoURL(ATTEND_ADD_ATTEND_RECORD_PARENT_URL);
  }
}

 ?>
<?php require 'queryrecordheader.php'; ?>
<div class="container">
  <div class="card mt-5">
    <div class="card-header">
      <h2>Check in</h2>
    </div>
    <div class="card-body">
      <?php if(!empty($message)): ?>
        <div class="alert alert-success">
          <?= $message; ?>
        </div>
      <?php endif; ?>
      <form method="post">
        <div class="form-group">
          <label for="userID">user</label>
          <select  id="userID" name="userID" οnchange="selectcity()">
   			<option>---Select---</option>
   			<?php
       			//get user ID nickname
       			$sql = 'SELECT ID, nickname FROM  user';
       			$statement = $connection->prepare($sql);
       			$statement->execute();
       			$record = $statement->fetchAll(PDO::FETCH_OBJ);
       			
       			foreach($record as $record)
                {
                  echo "<option value='$record->ID'>$record->nickname</option>";//循环，拼凑下拉框选项
                } 
              ?>
   		  </select>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-info">Check in</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php require 'footer.php'; ?>
