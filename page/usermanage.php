<!-- code by zmq -->
<?php
require_once( '../include/include_database.php' );
require_once( 'frame.php' );

$message = '';

$sql = 'SELECT * FROM usermanage';
$statement = $connection->prepare( $sql );
$statement->execute();
$record = $statement->fetchAll(PDO::FETCH_OBJ);
if ( $record == NULL ) {
  $message = "User not found.";
}
?>
<section class="content">
  <div class="container-fluid"> 
    <div class="row m-t--20">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="kodama-header col-<?= $KODAMA_THEME_COLOR; ?>">
            <h2>User Manage<small>Choose a user first to edit or delete.</small></h2>            
            <ul class="header-button">
              <li> <a href="useradd.php">
                <div class="kodama-icon-circle bg-green"> <i class="material-icons">person_add</i> </div>
                <div class="kodama-menu-info">
                  <h4>Add</h4>
                </div>
              </a> </li>
              <li><a href="javascript:void(0);" onclick="editUser();">
                <div class="kodama-icon-circle bg-light-blue"> <i class="material-icons">person</i> </div>
                <div class="kodama-menu-info">
                  <h4>Edit</h4>
                </div>
              </a></li>
              <li> <a href="javascript:void(0);" onclick="deleteUser();">
                <div class="kodama-icon-circle bg-red"> <i class="material-icons">delete</i> </div>
                <div class="kodama-menu-info">
                  <h4>Delete</h4>
                </div>
              </a> </li>
            </ul>
          </div>
          <div class="body">
            <div class="list-group">
              <li class="list-group-item">
                <ul class="kodama-listh">
                  <li class="col-xs-2">Name</li>
                  <li class="col-xs-3">Nickname</li>
                  <li class="col-xs-4">Email</li>
                  <li class="col-xs-3">Description</li>
                </ul>
              </li>
              <?php foreach($record as $record): ?>
              <a class="list-group-item" data-toggle="list" data-itemid="<?= $record->ID; ?>" href="javascript:void(0);">
                <ul class="kodama-lista">
                  <li class="col-xs-2"><?= $record->name; ?></li>
                  <li class="col-xs-3"><?= ($record->nickname == '' ? '-' : $record->nickname); ?></li>
                  <li class="col-xs-4"><?= $record->email; ?></li>
                  <li class="col-xs-3"><?= ($record->description == '' ? '-' : $record->description); ?></li>
                </ul>
              </a>
              <?php endforeach; ?>
            </div>
          </div>
          <?php if(!empty($message)): ?>
          <div class="alert alert-info align-left">
            <p>
              <?= $message; ?>
            </p>
          </div>
          <?php endif; ?>
        </div>
      </div>       
    </div>
  </div>
</section>
<script src="../style/js/kodama-list.js"></script>