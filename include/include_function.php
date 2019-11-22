<?php
//主题色彩
$KODAMA_THEME_COLOR = 'rose-red';
if ( isset( $_COOKIE[ 'KODAMA_THEME_COLOR' ] ) ) {
  $KODAMA_THEME_COLOR = $_COOKIE[ 'KODAMA_THEME_COLOR' ];
  if(empty($KODAMA_THEME_COLOR)) {
    $KODAMA_THEME_COLOR = 'rose-red';
  }
}

//照片默认目录
$PHOTO_PATH = '../data/photo/';

//跳转URL
function GotoURL( string $strURL ) {
  echo "<script language='javascript' type='text/javascript'>";
  echo "window.location.href='$strURL'";
  echo "</script>";
}

function GetParam($param) {
  $result = '';
  if(isset ($_GET[$param])) {
    $result = $_GET[$param];
  }
  if(empty($result)) {
    if(isset ($_POST[$param])) {
      $result = $_POST[$param];
    }
  }
  return $result;
}

function console_log($data)
{
	if (is_array($data) || is_object($data))
	{
		echo("<script>console.log('" . json_encode($data) . "');</script>");
	}
	else
	{
		echo("<script>console.log('" . $data . "');</script>");
	}
}
?>