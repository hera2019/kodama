<?php
$signinmod = 1;
if ( !require_once( '../user/checksign.php' ) ) {
  return;
}
require_once '../include/include_database.php';
require_once( '../include/include_function.php' ); // function and const
require_once( '../frame/head.php' );

//define ('ATTEND_ADD_ATTEND_RECORD_PARENT_URL', 'queryrecord.php');

// 生成班级单选按钮
function radioClassFun( $connection ) {
  // 定义保存html代码的变量
  $html = '';
  //get user ID nickname
  $sql = 'SELECT ID, name FROM class';
  $statement = $connection->prepare( $sql );
  $statement->execute();
  $record = $statement->fetchAll( PDO::FETCH_OBJ );
  $bFirst = TRUE;
  foreach ( $record as $record ) {
    if ( $bFirst ) {
      $html .= '
        <li class="RadioCheckboxBtn bg-rose-red btn waves-effect" name="groupClassBtn">
          <input name="groupClass" type="radio" id="groupClass' . $record->ID . '" value=' . $record->ID . ' class="with-gap radio-col-yellow" checked />
          <label class="RadioCheckboxTxt" style="font-size: 16;" for="groupClass' . $record->ID . '">' . $record->name . '</label>
        </li>';
      //<li style=\"text-align:center\">
      //	<input type=\"radio\"  id = \"class" . $record->ID . "\"  name=\"classname\" value=\"" . $record->ID . "\" checked data-labelauty=\"" . $record->name . "\">
      //</li>'';
    } else {
      # 遍历数组,分别形成不同的单选框html代码
      $html .= '
        <li class="RadioCheckboxBtn bg-white col-rose-red btn waves-effect" name="groupClassBtn">
          <input name="groupClass" type="radio" id="groupClass' . $record->ID . '" value=' . $record->ID . ' class="with-gap radio-col-yellow" />
          <label class="RadioCheckboxTxt" style="font-size: 16;" for="groupClass' . $record->ID . '">' . $record->name . '</label>
        </li>';
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
<title>学生個人チェックインページ</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- <link rel="stylesheet" href="../style/css/CheckInStyle.css"> -->
<style>
/*@import url('https://fonts.googleapis.com/css?family=Noto+Sans+SC&display=swap');*/
ul.CheckUI {
  list-style-type: none;
  margin: 0 0;
  padding: 0 1.5rem;
}
/* WANG CHONGYANG需要140px  */
li.CheckUI {
  display: inline-block;
  width: 183px;
  margin: 0 0;
}
.RadioCheckboxBtn {
  /*padding: 5 25 5 10;*/
  margin: 10px 20px;
}
.RadioCheckboxTxt {
  margin: 2px 0;
}
</style>
</head>

<!-- Body  -->
<body class="bg-info theme-<?= $KODAMA_THEME_COLOR; ?>">
  <div class="container" style="width: auto; margin-left: 15px; margin-right: 15px;">
    <div class="card mt-15">
      <hr>
      <center><h4>学生個人チェックインページ</h4></center>
      <div class="body">
        <ul class="CheckUI">
          <li class="CheckUI"><h6>クラス：</h6></li>
        </ul>
        <ul class="classnamecls CheckUI">
          <div class="demo-radio-button" id="classarea">
            <?php echo radioClassFun($connection);?>
          </div>
        </ul>
        <hr>
        <ul class="CheckUI">
          <li class="CheckUI" style="width: 60px; text-align: left;"><h6>名前：</h6></li>
        </ul>
        
        <ul class="studentnamecls CheckUI">
          <div class="demo-radio-button" id="studentarea">
            please choose your class.
          </div>
        </ul>
        <hr>
        <center>
          <h4><p class="col-rose-red" id="message">please check in...</p></h4>
          <div class="form-group">
            <button type="submit" name="submit" id="submit" class="btn bg-<?= $KODAMA_THEME_COLOR; ?> waves-effect" style="padding: 5 40;"><h4>チェックイン</h4></button>
          </div>
        </center>
        <!--<div class="alert alert-success">
        </div>-->
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
<!-- <script type="text/javascript" src="GetStudentRadio.php"></script>一定要放在接收函数之后  -->
<?php
  require_once('GetStudentRadio.php');
?>
<script>
  getProfile(<?= $json_string ?>);
</script> 
  
<?php require_once('../frame/foot.php'); ?>
  
<script>
$(document).ready(function()
{
  //获取单选按钮边框外的事件
  $("li[name='groupClassBtn']").on('click', function(e) //name='groupBtn'
  {
    if(!$(e.target).is($('label'))) {
      $(this).children('input').trigger('click');
    }
  });

  //获取单选按钮的数据 //id不可重复
  $("input:radio[name='groupClass']").on('click', function(e)
  {
    for(var key in strQuery)
    {
      if(key == e.currentTarget.id)
      {
        $("input[name='groupClass']").parent().removeClass("bg-rose-red");
        $("input[name='groupClass']").parent().addClass("bg-white col-rose-red");
        e.currentTarget.parentNode.className = "RadioCheckboxBtn bg-rose-red btn waves-effect";

        var strHtml = strQuery[key];
        document.getElementById('studentarea').innerHTML = strHtml;
        document.getElementById('message').innerHTML = "Please check in...";
        break;
      }
    }
    e.stopPropagation(); //禁止触发父控件事件
  });

  //获取单选按钮边框外的事件
  $("#studentarea").on('click', "li[name='groupStudentBtn']", function(e) //name='groupBtn'
  {
    if(!$(e.target).is($('label'))) {
      $(this).children('input').trigger('click');
    }
  });
  
  // student单选按钮点击事件，因为是动态创建，只能这样获得parent事件
  $("#studentarea").on('click', "input[name='groupStudent']", function(e)
  {
    $("input[name='groupStudent']").parent().removeClass("bg-light-blue");
    $("input[name='groupStudent']").parent().addClass("bg-white col-blue-grey");
    e.currentTarget.parentNode.className = "RadioCheckboxBtn bg-light-blue btn waves-effect";

    var strHtml = e.currentTarget.id;
    strHtml += " ：";
    strHtml += e.currentTarget.nextSibling.nextSibling.innerHTML;
    document.getElementById('message').innerHTML = strHtml;
    e.stopPropagation(); //禁止触发父控件事件
  });

	$("#submit").click(function ()
	{
		//$("#message").text("checking in...");
		document.getElementById('message').innerHTML = "Checking in...";
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
        data: "studentID="+$("input[name='groupStudent']:checked").val(), 
        success:function(message)
        {
            // 提交成功后的回调，msg变量是php输出的内容
        	//alert("2");
        	//$("#message").text(message);
        	document.getElementById('message').innerHTML = message;
		}
    });
}
</script>
</body>
</html>