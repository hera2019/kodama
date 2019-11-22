<?php
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