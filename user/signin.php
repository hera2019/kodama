<?php
//插入连接数据库的相关信息
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

//使用会话内存储的变量值之前必须先开启会话
if ( isset( $_COOKIE[ 'KODAMA_SESSID' ] ) ) {
  $KODAMA_SESSID = $_COOKIE[ 'KODAMA_SESSID' ];
}
isset( $KODAMA_SESSID ) ? session_id( $KODAMA_SESSID ) : $KODAMA_SESSID = session_id();
// 如果设置了$SESSID，就将SessionID赋值为$SESSID，否则生成SessionID
setcookie( 'KODAMA_SESSID', $KODAMA_SESSID, time() + 60, '/' ); // 储存SessionID到Cookie中，时间31天2678400秒 //第4个参数路径一定要有
session_name( 'KODAMA_SESSID' );
session_start();

$message = "";
$user_username = '';
$user_password = '';
$user_rememberme = '';
if ( isset( $_POST[ 'username' ] ) ) //用户提交登录表单时执行如下代码
{
  $user_username = $_POST[ 'username' ];
  $user_password = $_POST[ 'password' ];
  $user_rememberme = "";
  if ( isset( $_POST[ 'rememberme' ] ) ) {
    $user_rememberme = $_POST[ 'rememberme' ];
  }
  if ( !empty( $user_username ) && !empty( $user_password ) ) {
    //MySql中的SHA()函数用于对字符串进行单向加密
    //用用户名和密码进行查询
    $sql = "SELECT ID, username, name, email FROM usermanage WHERE username = :username AND password = SHA(:password)";
    $statement = $connection->prepare( $sql );
    $statement->execute( [ ':username' => $user_username, ':password' => $user_password ] );
    $record = $statement->fetch( PDO::FETCH_OBJ );
    //若查到的记录正好为一条，则设置SESSION，同时进行页面重定向
    if ( $record != NULL ) {
      $_SESSION[ 'user_id' ] = $record->ID;
      if( empty( $record->name ) ) //防止昵称为空
      {
        $record->name = $record->username;
      }
      $_SESSION[ 'username' ] = $record->name;        
      $_SESSION[ 'useremail' ] = $record->email;
      $_SESSION[ 'rememberme' ] = $user_rememberme;
      if ( $user_rememberme == "on" ) {
        $_SESSION[ 'password' ] = base64_encode( $user_password );
      }
      WriteLog( $connection, 'Login', $_SESSION[ 'username' ] );
      $home_url = '../index.php';
      GotoURL( $home_url );
    } else //若查到的记录不对，则设置错误信息
    {
      $message = 'ERROR: The username or password you entered is incorrect.';
    }
  } else {
    $message = 'ERROR: The username or password you entered is incorrect.';
  }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<title>Sign In | KODAMA</title>
<!-- Favicon-->
<link rel="icon" href="../favicon.ico" type="image/x-icon">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Noto+Sans+SC&display=swap" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

<!-- Bootstrap Core Css -->
<link href="../style/css/bootstrap.css" rel="stylesheet">

<!-- Waves Effect Css -->
<link href="../style/css/waves.css" rel="stylesheet" />

<!-- Custom Css -->
<link href="../style/css/style.css" rel="stylesheet">
<link href="../style/css/kodama.css" rel="stylesheet">
<style>
.login-back {
  background-color: #00BCD4;
  padding-left: 0;
  margin: 5% auto;
  overflow-x: hidden;
  height: 100%;
}
.login-backpic {
  text-align: center;
  background-image: url('../style/images/login-back.jpg');
  width: 1005px;
  height: 484px;
  background-position: center;
  background-repeat: no-repeat;
  margin: 10% auto;
  margin-bottom: 5%;
}
.login-page {
  background-color: #fff;/* #00BCD4; */
  width: 360px;
  height: 484px;
  margin: 0 0 0 645px;
  overflow-x: hidden;
  padding-top: 50px;
}
.logo {
  margin: 0;
  margin-left: 20px;
  margin-right: 20px;
  margin-top: 0;
  margin-bottom: 0 !important;
  padding-top: 8px;
  padding-bottom: 10px;
}
.horidiv {
  min-width: 720px;
  padding-left:30%;
  padding-right:20%;
}
.horiul {
  text-align: left;
  float: center;
  list-style: none;/*display: inline-block;*/
}
.horili {
  display: inline;
}
</style>
</head>

<body class="login-back">
  <div class="login-backpic">
    <div class="login-page">
      <div class="login-box">
        <div class="logo bg-<?= $KODAMA_THEME_COLOR; ?>"> <a href="https://www.example.com/"><b>KODAMA</b></a> <small></small> </div>
        <?php if(!empty($message)): ?>
        <div class="card">
          <div class="body">
            <div class="row m-t--15 m-b--35">
              <div class="alert alert-warning align-left">
                <p>
                  <?= $message; ?>
                </p>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <div class="card1">
          <div class="body" style="padding: 20px;">
            <!--通过$_SESSION['user_id']进行判断，如果用户未登录，则显示登录表单，让用户输入用户名和密码-->
            <?php
              if ( isset( $_SESSION[ 'rememberme' ] ) ) {
                if ( $_SESSION[ 'rememberme' ] == "on" ) {
                  if ( empty( $user_username ) ) {
                    $user_rememberme = $_SESSION[ 'rememberme' ];
                    $user_username = $_SESSION[ 'username' ];
                    $user_password = base64_decode( $_SESSION[ 'password' ] );
                  }
                }
              }
              ?>
            <!-- $_SERVER['PHP_SELF']代表用户提交表单时，调用自身php文件 -->
            <form id="sign_in" method="POST">
              <div class="msg">Sign in to start your session</div>
              <div class="input-group"> <span class="input-group-addon"> <i class="material-icons">person</i> </span>
                <div class="form-line"> 
                  <!-- 如果用户已输过用户名，则回显用户名 -->
                  <input value="<?php if(!empty($user_username)) echo $user_username; ?>" type="text" class="form-control" name="username" placeholder="Username" required autofocus>
                </div>
              </div>
              <div class="input-group"> <span class="input-group-addon"> <i class="material-icons">lock</i> </span>
                <div class="form-line">
                  <input value="<?php if(!empty($user_password)) echo $user_password; ?>" type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
              </div>
              <div class="row">
                <!--
                <div class="col-xs-8 p-t-5">
                  <input <?php if($user_rememberme == "on") echo "checked"; ?> type="checkbox" name="rememberme" id="rememberme" class="filled-in <?php if(!empty($KODAMA_THEME_COLOR)) echo 'chk-col-' . $KODAMA_THEME_COLOR; else echo 'chk-col-pink'?>">
                  <label for="rememberme">Remember Me</label>
                </div>
                !-->
                <div class="col-xs-8 p-t-5"> <a href="forgot-password.php">Forgot Password?</a> </div>
                <div class="col-xs-4">
                  <button class="btn btn-block bg-<?= $KODAMA_THEME_COLOR; ?> waves-effect" type="submit">SIGN IN</button>
                </div>
              </div>
              <div class="row m-t-10 m-b-10"> 
                <!--<div class="col-xs-6">
                                  <a href="sign-up.html">Register Now!</a>
                              </div>-->
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="horidiv col-white" style="font-size: 12pt;">
    <ul class="horiul col-xs-2">
      <div style="font-size: 10pt;">株式会社</div>
      <div><a href="https://www.example.com/"><span class="col-white" style="font-size: 16pt;">KODAMA</span></a></div>
    </ul>
    <ul class="horiul col-xs-5">
      <div>support@example.com<br>00 0000-0000</div>
    </ul>
    <ul class="horiul col-xs-5">
      <div>日本国東京都千代田区<br>サンプル1-2-3</div>
    </ul>
  </div>
</body>
<!-- Jquery Core Js --> 
<script src="../style/js/jquery.min.js"></script> 

<!-- Bootstrap Core Js --> 
<script src="../style/js/bootstrap.js"></script> 

<!-- Waves Effect Plugin Js --> 
<script src="../style/js/waves.js"></script> 

<!-- Validation Plugin Js --> 
<script src="../style/js/jquery.validate.js"></script> 

<!-- Custom Js --> 
<script src="../style/js/sign-in.js"></script>
</html>