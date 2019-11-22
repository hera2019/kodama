<!-- code by zmq -->
<?php
require_once('../include/include_database.php');
require_once('../include/include_function.php');

//主题色彩
$KODAMA_THEME_COLOR = 'rose-red';
if ( isset( $_COOKIE[ 'KODAMA_THEME_COLOR' ] ) ) {
  $KODAMA_THEME_COLOR = $_COOKIE[ 'KODAMA_THEME_COLOR' ];
  if(empty($KODAMA_THEME_COLOR)) {
    $KODAMA_THEME_COLOR = 'rose-red';
  }
}

$message = '';
if ( isset( $_GET[ 'token' ] ) && trim( $_GET[ 'token' ] ) != '' ) {
  // trim移除字符串两侧的空白字符或其他预定义字符

  $tokenvalue = $_GET[ 'token' ];
  $tokenvalue = trim( $_GET[ 'token' ] );
  $tokencheck = checktoken( $connection, $tokenvalue );
  if ( $tokencheck == '9990002' ) {
    $message = 'Token error verification failed.';
  } else if ( $tokencheck == '9990003' ) {
    $message = 'Token has expired.';
  } else {
    $address = $tokencheck;

    //修改密码
    $password = get_password();
    $sql = 'UPDATE usermanage SET password=SHA(:password) WHERE email=:email';
    $statement = $connection->prepare( $sql );
    if ( $statement->execute( [ ':password' => $password, ':email' => $address ] ) ) {
      $message = "Please use this password sign-in, and modify it later.<br><br>The new password is:<br><br>" . $password . "<br><br><a href='signin.php'>Sign In</a><br>";
    } else {
      $message = "The email address " . $address . " is not found!";
      ShowErrorCode( $statement );
    }
  }
}

function get_password( $length = 8 ) {
  $str = substr( md5( time() ), 0, $length );
  return $str;
}

//token验证方法，当前时间超过deadtime则过期，使用后删除token
function checktoken( $connection, $tokenvalue ) {
  $ret = '9990002'; //token错误验证失败
  $sql = 'SELECT ID, context, deadtime FROM token WHERE tokenvalue=:tokenvalue';
  $statement = $connection->prepare( $sql );
  $statement->execute( [ ':tokenvalue' => $tokenvalue ] );
  $record = $statement->fetch( PDO::FETCH_OBJ );
  if ( !empty( $record ) ) {
    $ID = $record->ID;
    if ( strtotime( $record->deadtime ) >= time() ) {
      $ret = $record->context; //token验证成功，time_out刷新成功，可以获取接口信息
    } else {
      $ret = '9990003'; //过期
    }

    // 删除token
    $sql = 'DELETE FROM token WHERE ID=:ID';
    $statement = $connection->prepare( $sql );
    $statement->execute( [ ':ID' => $record->ID ] );
  }

  return $ret;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<title>KODAMA</title>
<!-- Favicon-->
<link rel="icon" href="../favicon.ico" type="image/x-icon">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Noto+Sans+SC&display=swap" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

<link href="../style/css/bootstrap.css" rel="stylesheet">
<link href="../style/css/style.css" rel="stylesheet">
</head>

<body>
  <section class="content" style="margin: 0px;">
    <div class="container-fluid">
      <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="width: 100%; padding: 2rem;">
        <div class="card">
          <div class="header bg-<?= $KODAMA_THEME_COLOR; ?>">
            <h2>
              Password Reset
              <small></small> </h2>
          </div>
          <div class="body">
            <?php if(!empty($message)) echo $message; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>