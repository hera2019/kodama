<?php
require_once('../include/include_function.php');

//使用一个会话变量检查登录状态
session_name( 'KODAMA_SESSID' );
session_start();
$usersignin = false;
if ( isset( $_SESSION[ 'user_id' ] ) ) { //通过$_SESSION['user_id']进行判断，如果用户未登录，则显示登录表单，让用户输入用户名和密码
  $userid = $_SESSION[ 'user_id' ];
  $username = $_SESSION[ 'nickname' ];
  $useremail = $_SESSION[ 'useremail' ];
  $usersignin = true;
} else {
  $home_url = '../user/signin.php';
  GotoURL( $home_url );
}
return $usersignin;
?>