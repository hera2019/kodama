<!-- code by zmq -->
<?php
require_once('../include/include_database.php' );
require_once('../include/include_function.php' );

if ( isset( $_GET[ 'ID' ] ) && !empty( $_GET[ 'ID' ] ) ) {
  $ID = $_GET[ 'ID' ];
} else {
  GotoURL( '../page/info.php?bg=orange&title=Delete User&info=<strong>User deletion failed! User ID get error! </strong><a href="../page/usermanage.php" class="alert-link">Please click here back to user manage page!</a>' );
  return;
}

$sql = 'DELETE FROM usermanage WHERE ID=:ID';
$statement = $connection->prepare( $sql );
$statement->execute( [ ':ID' => $ID ] );
$count = $statement->rowCount();
if($count > 0) {
  GotoURL( '../page/info.php?bg=green&title=Delete User&info=<strong>User deleted successfully! </strong><a href="../page/usermanage.php" class="alert-link">Please click here back to user manage page!</a>' );
  return;
} else {
  GotoURL( '../page/info.php?bg=red&title=Delete User&info=<strong>User deletion failed! Database delete error! </strong><a href="../page/usermanage.php" class="alert-link">Please click here back to user manage page!</a>' );
  return;
}
?>