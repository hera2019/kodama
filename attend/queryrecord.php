<!-- code by zmq -->
<?php
require ("db.php");
$sql = 'SELECT *,attendance.ID AS ID,student.ID AS userID2,property.ID AS propertyID,property.property AS propertyname FROM (attendance  INNER JOIN student ON attendance.userID = student.ID)  LEFT JOIN property ON attendance.property = property.ID ORDER BY attendance.ID DESC';
$statement = $connection->prepare($sql);
$statement->execute();
$record = $statement->fetchAll(PDO::FETCH_OBJ);
 ?>
<?php
require 'queryrecordheader.php';
?>
<div class="container">
  <div class="card mt-5">
    <div class="card-header">
      <h2>Attendance Record</h2>
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <tr>
          <th>ID</th> <!-- <th style="width:20px">  </th> -->
          <th>name</th>
          <th>time1</th>
          <th>time2</th>
          <th>time3</th>
          <th>time4</th>
          <th>time5</th>
          <th>time6</th>
          <th>time7</th>
          <th>time8</th>
          <th>prop</th>
          <th>rectime</th>
          <th>m</th>
          <th>manage</th>
        </tr>
        <?php foreach($record as $record): ?>
          <tr>
            <td><?= $record->ID; ?></td>
            <td><?= $record->name; ?></td>
            <td><?= $record->time1; ?></td>
            <td><?= $record->time2; ?></td>
            <td><?= $record->time3; ?></td>
            <td><?= $record->time4; ?></td>
            <td><?= $record->time5; ?></td>
            <td><?= $record->time6; ?></td>
            <td><?= $record->time7; ?></td>
            <td><?= $record->time8; ?></td>
            <td><?= $record->propertyname; ?></td>
            <td><?= $record->recordtime; ?></td>
            <td><?= $record->manualmodified; ?></td>
            <td>
              <a href="manueditrecord.php?ID=<?= $record->ID ?>" class="btn btn-info">Edit</a>
              <a onclick="return confirm('Are you sure you want to delete this record?')" href="deleterecord.php?ID=<?= $record->ID ?>" class='btn btn-danger'>Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</div>
<?php
require 'footer.php'; 
?>