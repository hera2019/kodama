<!-- code by zmq -->
<?php
require_once('../include/include_database.php' );
require_once('../include/include_function.php' );

if ( isset( $_GET[ 'ID' ] ) ) {
  $ID = $_GET[ 'ID' ];
} else {
  echo "<script>history.back();</script>";
  return;
}

$sql = 'SELECT * FROM usermanage WHERE ID=:ID';
$statement = $connection->prepare( $sql );
$statement->execute( [ ':ID' => $ID ] );
$record = $statement->fetch( PDO::FETCH_OBJ );
if ( $record != NULL ) {
  $name = $record->name;
  $nickname = $record->nickname;
  $email = $record->email;
  $password = '';
  $description = $record->description;
} else {
    $info = '<strong>User information modification failed! Database select user id error! </strong><br><a href="javascript:history.back();">Please click here edit user again!</a><br><strong>or</strong><br><a href="../page/usermanage.php" class="alert-link">click here back to user manage page!</a>';
    $info = base64_encode( $info );
    $info = rawurlencode( $info );
    GotoURL( '../page/info2.php?bg=red&title=Edit User&info=' . $info );
    return;
}

$nameNeedEdit = false;
$nicknameNeedEdit = false;
$emailNeedEdit = false;
$passwordNeedEdit = false;
$descriptionNeedEdit = false;
if ( isset( $_POST[ 'namesurname' ] ) ) {
  $name = $_POST[ 'namesurname' ];
  $name = trim($name);
  if ( $name != '' && $name != $record->name ) {
    $nameNeedEdit = true;
  }
}
if ( isset( $_POST[ 'nickname' ] ) ) {
  $nickname = $_POST[ 'nickname' ];
  $nickname = trim($nickname);
  if ( $nickname != $record->nickname ) {
    $nicknameNeedEdit = true;
  }
}
if ( isset( $_POST[ 'email' ] ) ) {
  $email = $_POST[ 'email' ];
  $email = trim($email);
  if ( $email != '' && $email != $record->email ) {
    $emailNeedEdit = true;
  }
}
if ( isset( $_POST[ 'password' ] ) ) {
  $password = $_POST[ 'password' ];
  if ( $password != '' ) {
    $passwordNeedEdit = true;
  }
}
if ( isset( $_POST[ 'description' ] ) ) {
  $description = $_POST[ 'description' ];
  $description = trim($description);
  if ( $description != $record->description ) {
    $descriptionNeedEdit = true;
  }
}
if ( $nameNeedEdit || $nicknameNeedEdit || $emailNeedEdit || $passwordNeedEdit || $descriptionNeedEdit ) {
  $bFirst = true;
  $sql = "UPDATE usermanage SET ";
  if ( $nameNeedEdit ) {
    if ( $bFirst ) {
      $bFirst = false;
    } else {
      $sql .= ", ";
    }
    $sql .= "name=" . "'" . $name . "'";
  }
  if ( $nicknameNeedEdit ) {
    if ( $bFirst ) {
      $bFirst = false;
    } else {
      $sql .= ", ";
    }
    $sql .= "nickname=" . "'" . $nickname . "'";
  }
  if ( $emailNeedEdit ) {
    if ( $bFirst ) {
      $bFirst = false;
    } else {
      $sql .= ", ";
    }
    $sql .= "email=" . "'" . $email . "'";
  }
  if ( $passwordNeedEdit ) {
    if ( $bFirst ) {
      $bFirst = false;
    } else {
      $sql .= ", ";
    }
    $sql .= "password=" . "SHA('" . $password . "')";
  }
  if ( $descriptionNeedEdit ) {
    if ( $bFirst ) {
      $bFirst = false;
    } else {
      $sql .= ", ";
    }
    $sql .= "description=" . "'" . $description . "'";
  }
  $sql .= " WHERE ID=" . $ID;
  $statement = $connection->prepare( $sql );
  if ( $statement->execute() ) {
    GotoURL( '../page/info.php?bg=green&title=Edit User Info&info=<strong>User information modified successfully! <a href="../page/usermanage.php" class="alert-link">Please click here back to user manage page!</a>' );
  } else {
    $info = '<strong>User information modification failed! Database update user error, maybe name or email have been exist already! </strong><br><a href="javascript:history.back();">Please click here edit user again!</a><br><strong>or</strong><br><a href="../page/usermanage.php" class="alert-link">click here back to user manage page!</a>';
    $info = base64_encode( $info );
    $info = rawurlencode( $info );
    GotoURL( '../page/info2.php?bg=red&title=Edit User&info=' . $info );
    return;
  }
} else {
    $info = '<strong>User information have not been modified! Because no items need to be modified! </strong><br><a href="javascript:history.back();">Please click here edit user again!</a><br><strong>or</strong><br><a href="../page/usermanage.php" class="alert-link">click here back to user manage page!</a>';
    $info = base64_encode( $info );
    $info = rawurlencode( $info );
    GotoURL( '../page/info2.php?bg=light-blue&title=Edit User&info=' . $info );
    return;
}
?>