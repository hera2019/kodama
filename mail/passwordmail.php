<?php
require_once('../include/include_database.php');
require_once('../include/include_function.php');
require_once('../plugin/mail/class.phpmailer.php');

//主题色彩
$KODAMA_THEME_COLOR = 'rose-red';
if ( isset( $_COOKIE[ 'KODAMA_THEME_COLOR' ] ) ) {
  $KODAMA_THEME_COLOR = $_COOKIE[ 'KODAMA_THEME_COLOR' ];
  if(empty($KODAMA_THEME_COLOR)) {
    $KODAMA_THEME_COLOR = 'rose-red';
  }
}

$message = '';
if ( isset( $_GET[ 'email' ] ) && trim( $_GET[ 'email' ] ) != '' ) {
  //trim移除字符串两侧的空白字符或其他预定义字符

  $address = $_GET[ 'email' ];
  $address = trim( $_GET[ 'email' ] );

  $tokenvalue = settoken();
  $property = 'resetpassword';
  $context = $address;
  $deadtime = date( 'Y-m-d H:i:s', time() + 86400 * 2 ); //过期时间：2天86400秒*2

  $sql = 'INSERT INTO token(tokenvalue, property, context, deadtime) VALUES(:tokenvalue, :property,:context, :deadtime)';
  $statement = $connection->prepare( $sql );
  if ( $statement->execute( [ ':tokenvalue' => $tokenvalue, ':property' => $property, ':context' => $context, ':deadtime' => $deadtime ] ) ) {
    $message = '';
    
    $url = GetCurUrl();
    $url = str_replace( "mail/passwordmail", "user/resetpassword", $url );
    $pos = strpos( $url, '?' );
    if ( $pos && $pos > 0 ) {
      $url = substr( $url, 0, $pos );
    }
    $url .= '?token=' . $tokenvalue;
    $hyperlink = "<a href=" . $url . ">Please click here to reset your password.</a>";

    $text = "Attention: This email is sent automatically, please do not reply.<br><br>The link will expire after use or 2 days later. <br><br>";
    
    $body = file_get_contents('mailtemplate.html');
    $body = preg_replace("[/\/i]", '', $body);
    $body = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $body); //清除字符：ï»¿ 
    $body = str_replace("<%message%>", $text . $hyperlink, $body);

    $mail = new PHPMailer();
    $mail->IsSMTP(); //使用SMTP方式发送
    $mail->SMTPAuth = true; // enable SMTP authentication
    $mail->SMTPSecure = "ssl"; // sets the prefix to the servier
    $mail->Host = "mail.example.com"; //您的企业邮局域名
    $mail->Port = 465; //ssl：465; no ssl：587;
    //$mail->SMTPDebug = 2; // enables SMTP debug information (for testing)
    $mail->Username = "no-reply@example.com"; //邮局用户名(请填写完整的email地址)
    $mail->Password = "REDACTED"; //邮局密码
    $mail->AddReplyTo( 'no-reply@example.com', 'KODAMA' );
    $mail->AddAddress( $address, "User" );
    $mail->SetFrom( 'no-reply@example.com', 'KODAMA' );
    $mail->Subject = 'Reset password';
    $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
    $mail->MsgHTML( $body );
    //$mail->AddAttachment( 'images/phpmailer.gif' ); // attachment
    //$mail->AddAttachment( 'images/phpmailer_mini.gif' ); // attachment
    if ( !$mail->Send() ) {
      $message =  "mail send failed. <br>";
      $message .= "error：" . $mail->ErrorInfo;
    } else {
      $message = "Mail send succeded.<br><br>Because the email is sent automatically, if it is not received, please confirm whether it is in the spam.<br><br><a href='../user/signin.php'>Sign In</a><br>";
    }
  } else {
    $message = 'write token record failed<br>';
    $message .= ShowErrorCode( $statement );
  }
}

function GetCurUrl() {
  $url = '';
  if ( isset( $_SERVER[ "REQUEST_SCHEME" ] ) ) {
    $url .= $_SERVER[ "REQUEST_SCHEME" ];
  } else {
    $url .= 'http';
    if ( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] == 'on' ) {
      $url .= 'https';
    }
  }
  $url .= '://';
  $url .= $_SERVER[ 'SERVER_NAME' ];
  if ( $_SERVER[ 'SERVER_PORT' ] != '80' ) {
    $url .= $_SERVER[ 'SERVER_PORT' ];
  } else {

  }
  $url .= $_SERVER[ 'REQUEST_URI' ];
  return $url;
}

//下面是生成token方法代码
function settoken() {
  $str = md5( uniqid( md5( microtime( true ) ), true ) ); //生成一个不会重复的字符串
  $str = sha1( $str ); //加密
  return $str;
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
              Password Mail
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