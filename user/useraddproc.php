<!-- code by zmq -->
<?php
require_once( '../include/include_database.php' );
require_once( '../include/include_function.php' );

$name = '';
$nickname = '';
$email = '';
$password = '';
$description = '';

if ( isset( $_POST[ 'namesurname' ] ) ) {
  $name = $_POST[ 'namesurname' ];
  $name = trim( $name );
  if ( $name == '' ) {
    echo "<script>history.back();</script>";
    return;
  }
}
if ( isset( $_POST[ 'nickname' ] ) ) {
  $nickname = $_POST[ 'nickname' ];
  $nickname = trim( $nickname );
}
if ( isset( $_POST[ 'email' ] ) ) {
  $email = $_POST[ 'email' ];
  $email = trim( $email );
  if ( $email == '' ) {
    echo "<script>history.back();</script>";
    return;
  }
}
if ( isset( $_POST[ 'password' ] ) ) {
  $password = $_POST[ 'password' ];
  if ( $password == '' ) {
    echo "<script>history.back();</script>";
    return;
  }
}
if ( isset( $_POST[ 'description' ] ) ) {
  $description = $_POST[ 'description' ];
  $description = trim( $description );
}

$sql = 'SELECT COUNT(*) FROM usermanage WHERE name=:name OR email=:email';
$statement = $connection->prepare( $sql );
$statement->execute( [ ':name' => $name, ':email' => $email ] );
$num = $statement->fetchColumn();
if ( $num == 0 ) {
  $sql = 'INSERT INTO usermanage(name, nickname, email, password, description) VALUES(:name, :nickname, :email, SHA(:password), :description)';
  $statement = $connection->prepare( $sql );
  if ( $statement->execute( [ ':name' => $name, ':nickname' => $nickname, ':email' => $email, ':password' => $password, ':description' => $description ] ) ) {
    GotoURL( '../page/info.php?bg=green&title=Add User&info=<strong>User information added successfully! </strong><br><a href="../page/useradd.php" class="alert-link">Please click here add another user!</a><br><strong>or</strong><br><a href="../page/usermanage.php" class="alert-link">click here back to user manage page!</a>' );
  } else {
    $info = '<strong>User information added failed! Database insert error! </strong><br><a href="javascript:history.back();">Please click here add user again!</a><br><strong>or</strong><br><a href="../page/usermanage.php" class="alert-link">click here back to user manage page!</a>';
    $info = base64_encode( $info );
    $info = rawurlencode( $info );
    GotoURL( '../page/info2.php?bg=red&title=Add User&info=' . $info );
    return;
  }
} else {
  $info = '<strong>User information added failed! Name or email have been exist already! </strong><br><a href="javascript:history.back();">Please click here add user again!</a><br><strong>or</strong><br><a href="../page/usermanage.php" class="alert-link">click here back to user manage page!</a>';
  $info = base64_encode( $info );
  $info = rawurlencode( $info );
  GotoURL( '../page/info2.php?bg=orange&title=Add User&info=' . $info );
  return;
}
?>