<?php
header("content-Type: text/html; charset=utf-8");
require_once( '../config/config.php' );
$options = [];
//try {
$connection = new PDO($dsn, $username, $password, $options);
//} catch(PDOException $e) {
//}
//设置读取数据库的数据编码格式
//mysqli_set_charset($connection, "utf8");
$connection->query("set character set 'utf8'");//读库
$connection->query("set names 'utf8'");//写库
date_default_timezone_set('Asia/Tokyo');//设置php时区
$connection->query("SET time_zone = '+9:00'"); //设置数据库时区
$connection->query("flush privileges");//立即生效

//显示数据库错误
function ShowErrorCode( PDOStatement $statement ) {
  $code = $statement->errorCode();
  if ( $code == 00000 ) {
    //如果没有任何错误, errorCode() 返回的是: 00000 ，否则就会返回一些错误代码
    //echo "data operated successfully";
    return '<br>error code: ' . $code . '<br>';
  } else {
    $message = '<br>error code: ' . $code . '<br>';
    $message .= 'database error:<br>';
    $message .= 'SQL Query: ' . $statement->queryString; //$sql;
    $message .= '<pre>';
    $errorInfo = $statement->errorInfo();
    $message .= json_encode($errorInfo) . '<br>';
    $message .= '<pre><br>';
    //echo $message;
    return $message;
  }
}
//写数据库日志记录
function WriteLog( PDO $connection, string $strOperateProperty, string $strContent ) //$statement->queryString;
{
  $userID = 0;
  if ( isset( $_SESSION[ 'user_id' ] ) ) {
    $userID = $_SESSION[ 'user_id' ];
  }
  $sql = 'INSERT INTO operatelog(userID, operateproperty, content) VALUES(:userID, :operateproperty, :content)';
  $statement = $connection->prepare( $sql );
  if ( !$statement->execute( [ ':userID' => $userID, ':operateproperty' => $strOperateProperty, ':content' => $strContent ] ) ) {
    return ShowErrorCode( $statement );
  }
  return true;
}
?>