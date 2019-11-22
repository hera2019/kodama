<?php
require_once '../include/include_database.php';
require_once 'AttendClass.php';

use Attend\ AttendClass;

//define ('ATTEND_ADD_ATTEND_RECORD_PARENT_URL', 'queryrecord.php');

// 生成班级单选按钮
function radioClassFun( $connection ) {
  // 定义保存html代码的变量
  $html = '';
  //get user ID nickname
  $sql = 'SELECT ID, name FROM  class';
  $statement = $connection->prepare( $sql );
  $statement->execute();
  $record = $statement->fetchAll( PDO::FETCH_OBJ );
  $bFirst = TRUE;
  foreach ( $record as $record ) {
    if ( $bFirst ) {
      $html .= "
				<li style=\"text-align:center\">
					<input type=\"radio\"  id = \"class" . $record->ID . "\"  name=\"classname\" value=\"" . $record->ID . "\" checked data-labelauty=\"" . $record->name . "\">
				</li>";
    } else {
      # 遍历数组,分别形成不同的单选框html代码
      $html .= "
				<li style=\"text-align:center\">
					<input type=\"radio\"  id = \"class" . $record->ID . "\"  name=\"classname\" value=\"" . $record->ID . "\" data-labelauty=\"" . $record->name . "\">
				</li>";
    }
    $bFirst = FALSE;
  }
  // 返回完整的html代码交由浏览器解析
  return $html;
}
?>

<!-- HTML  -->
<!doctype html>
<!-- code by zmq -->
<html lang="en">
<head>
<title>Check In</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="../style/css/CheckInStyle.css">
<style>
@import url('https://fonts.googleapis.com/css?family=Noto+Sans+SC&display=swap');
ul {
    list-style-type: none;
    margin: 0 0;
    padding: 0 1.5rem;
}
li {
    display: inline-block;
    width: 183px;
    margin: 0 0;
}/* radio文字左右空白43，图片16  */
input.labelauty + label {
    font-size: 14px;
    font-family: "Noto Sans SC", sans-serif;
    padding-top: 5px;
}
.card-header {
    background-color: #d4edda;
    padding: .25rem 1.25rem;
    margin: 0 0;
}
.card-body {
    padding: 0 0;
    margin-top: 10px;
    margin-bottom: 0px;
}
h6 {
    margin-bottom: 0;
}
span {
    display: inline-block;
    width: 140px;
    margin: 0 0;
    padding: 0 0;
}/* WANG CHONGYANG需要140px  */
</style>
</head>

<!-- Body  -->
<body class="bg-info">
<div class="container">
<div class="card mt-5">
<div class="card-header">
  <h6>Class Name</h6>
</div>
<div class="card-body">
<ul class="classnamecls" id="classarea">
<?php echo radioClassFun($connection);?>
</div>
<div class="card-header">
  <h6>Student Name</h6>
</div>
<div class="card-body">
  <ul class="studentnamecls" id="studentarea">
    please choose your class.
  </ul>
</div>
</div>
</div>
<div class="container">
  <div class="card mt-5">
    <div class="card-body">
      <center>
        <div class="form-group">
          <button type="submit" name="submit" id="submit" class="btn btn-info">Check in</button>
        </div>
      </center>
      <div class="alert alert-success">
        <p id="message">please check in...</p>
      </div>
    </div>
  </div>
</div>

<!-- script  --> 
<script>
var strQuery = "";
function getProfile(str)
{
	strQuery = str;
	//显示第一个班级学生
	for(var key in strQuery)
	{
		var strHtml = strQuery[key];
		document.getElementById('studentarea').innerHTML = strHtml;
		break;
	}
}
</script> 
<script type="text/javascript" src="GetStudentRadio.php"></script><!-- 一定要放在接收函数之后  --> 
<script src="../style/js/jquery-1.8.3.min.js"></script> 
<script src="../style/js/jquery-labelauty.js"></script> 
<script>
//获取单选按钮的数据 //id不可重复
$('input:radio').on('click', function(e)
{
	if(e.currentTarget.name == "classname")
	{
		for(var key in strQuery)
		{
		   if(key == e.currentTarget.id)
		   {
				var strHtml = strQuery[key];
				//$("input[name='studentname']").remove();
				document.getElementById('studentarea').innerHTML = strHtml;
				$("input[name='studentname']").labelauty();
			   document.getElementById('message').innerHTML = "please check in...";
				break;
		   }
		}
	}
});

// student单选按钮点击事件，因为是动态创建，只能这样获得parent事件
$("#studentarea").on('click', "input[name='studentname']", function(e)
{ 
	   var strHtml = e.currentTarget.id;
	   strHtml += ": ";
	   strHtml += e.currentTarget.alt;
	   document.getElementById('message').innerHTML = strHtml;
});

//DOM的onload事件处理函数
$(document).ready(function()
{
	$("#submit").click(function ()
	{
		//$("#message").text("checking in...");
		document.getElementById('message').innerHTML = "checking in...";
		postsubmitdata();
	});
});

function postsubmitdata()
{
	// 提交数据函数  
    $.ajax(
	{
		// 调用jquery的ajax方法  
        type:"POST",// 设置ajax方法提交数据的形式  
        url:"AddAttendRecordPost.php",// 把数据提交到php  
          
        /* 提交的数据，必须使用key/value的形式，如"key=value"， 
         * 如果多个键值对，就使用&分隔开，如"key1=value1&key2=value2" */  
        data: "studentID="+$("input[name='studentname']:checked").val(), 
        success:function(message)
        {
            // 提交成功后的回调，msg变量是php输出的内容
        	//alert("2");
        	//$("#message").text(message);
        	document.getElementById('message').innerHTML = message;
		}
    });
}

//页面初始化
$(function()
{
	$(':input').labelauty();
});
</script>
</body>
</html>