<?php
require_once '../include/include_database.php';

// 生成学生单选按钮
function radioStudentFun($connection)
{
	//$classID = str_replace("class", "", $classID);
	// 定义保存html代码的变量
	$sql = 'SELECT ID FROM class';
	$statement = $connection->prepare($sql);
	$statement->execute();
	$record1 = $statement->fetchAll(PDO::FETCH_OBJ);
	$arr = array();
	foreach ($record1 as $record1)
	{
		$html = '';
		$classID = $record1->ID;
		$strClassID = "groupClass" . $classID;
		//get user ID name
		$sql = 'SELECT ID, lastname, firstname, studentnumber FROM student WHERE classID=:classID';
		$statement = $connection->prepare($sql);
		$statement->execute([':classID' => $classID]);
		$record = $statement->fetchAll(PDO::FETCH_OBJ);
		foreach($record as $record)
		{
			$name = $record->lastname .  "　" . $record->firstname; //$record->studentnumber . ": " . 
			# 遍历数组,分别形成不同的单选框html代码
      $html .= '
        <li class="RadioCheckboxBtn bg-white col-blue-grey btn waves-effect" name="groupStudentBtn">
          <input name="groupStudent" type="checkbox" id="' . $record->studentnumber . '" value=' . $record->ID . ' class="chk-col-yellow" />
          <label class="RadioCheckboxTxt" for="' . $record->studentnumber . '">' . $name . '</label>
        </li>';
		}
		$arr[$strClassID] = $html;
	}
	// 返回完整的html代码交由浏览器解析
	return $arr;
}

$ret = radioStudentFun($connection);
$json_string = json_encode($ret);
return $json_string;
?>