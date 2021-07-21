<?php
session_start();
require_once('../include/include_function.php');

class KODAMA_USERINFO {
  public $userid = 0;
  public $username = '';
  public $userrights = '';
  public $useremail = '';
}

//使用一个会话变量检查登录状态
$usersignin = false;
if ( isset( $_SESSION[ 'user_id' ] ) ) { //通过$_SESSION['user_id']进行判断，如果用户未登录，则显示登录表单，让用户输入用户名和密码
  $KODAMA_USERINFO = new KODAMA_USERINFO();
  $KODAMA_USERINFO->userid = $_SESSION[ 'user_id' ];
  $KODAMA_USERINFO->username = $_SESSION[ 'username' ];
  $KODAMA_USERINFO->userrights = $_SESSION[ 'userrights' ];
  $KODAMA_USERINFO->useremail = $_SESSION[ 'useremail' ];
  $usersignin = true;
} else {
  $paramstring = '';
  if(!empty($signinmod)) {
    $paramstring = '?mod=' . $signinmod;
  }
  $home_url = '../user/signin.php' . $paramstring;
  GotoURL( $home_url );
}
return $usersignin;
?>