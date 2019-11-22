<!-- code by zmq -->
<?php
require '../include/include_database.php';
require '../include/include_function.php';
$ID = $_GET['ID'];
$sql = 'SELECT * FROM user WHERE ID=:ID';
$statement = $connection->prepare($sql);
$statement->execute([':ID' => $ID ]);
$person = $statement->fetch(PDO::FETCH_OBJ);
if (isset ($_POST['name'])  && isset($_POST['nickname']) ) {
  $name = $_POST['name'];
  $nickname = $_POST['nickname'];
  $sql = 'UPDATE user SET name=:name, nickname=:nickname WHERE ID=:ID';
  $statement = $connection->prepare($sql);
  if ($statement->execute([':name' => $name, ':nickname' => $nickname, ':ID' => $ID])) {
  	GotoURL("index.php");
  }



}


 ?>
<?php require 'header.php'; ?>
<div class="container">
  <div class="card mt-5">
    <div class="card-header">
      <h2>Update User</h2>
    </div>
    <div class="card-body">
      <?php if(!empty($message)): ?>
        <div class="alert alert-success">
          <?= $message; ?>
        </div>
      <?php endif; ?>
      <form method="post">
        <div class="form-group">
          <label for="name">Name</label>
          <input value="<?= $person->name; ?>" type="text" name="name" ID="name" class="form-control">
        </div>
        <div class="form-group">
          <label for="nickname">nickname</label>
          <input type="nickname" value="<?= $person->nickname; ?>" name="nickname" ID="nickname" class="form-control">
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-info">Update person</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php require 'footer.php'; ?>
