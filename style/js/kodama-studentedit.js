var g_classesinfo = {};

//班级select 联动 教师select，选中班级，自动选择班主任老师 
//js或html调用php变量只能放在php文件里面，js文件不可以
$(document).ready(function(){
//$(function () {
  $('#classID').change(function(){ //选中班级，自动选择班主任老师
    //$(this).children('option:selected').val();//可以用
    //$("#classID option:selected").val();//也可以用
    let classid = $("#classID").val(); //可以用，这就是selected的值
    //console.log(classid);
    
    let classesinfo = g_classesinfo;
    //console.log(classesinfo);
    let teacherid = 0;
    for(let classinfo of classesinfo) {
      if(classinfo.ID == classid) {
        teacherid = classinfo.classteacherID;
        break;
      }
    }
    $('#classteacherID').val(teacherid); //设置select的值
    //$('#classteacherID').selectpicker('val', teacherid); //设置select的值
    //$('#classteacherID').selectpicker('refresh'); //必须刷新才能看到结果
  });
});

//将form转为AJAX提交
function ajaxSubmit(frm, fn) {
  var dataParam = getFormJson(frm);
  $.ajax({
    url: frm.action,
    type: frm.method,
    data: dataParam,
    success: fn
  });
}
//将form中的值转换为键值对。
function getFormJson(frm) {
  var o = {};
  var a = $(frm).serializeArray();
  $.each(a, function () {
    if (o[this.name] !== undefined) {
      if (!o[this.name].push) {
        o[this.name] = [o[this.name]];
      }
      o[this.name].push(this.value || '');
    } else {
      o[this.name] = this.value || '';
    }
  });
  return o;
}
//调用
$(document).ready(function(){
  $('#infoform').bind('submit', function() {
    ajaxSubmit(this, function(data) {
      data = JSON.parse(data);
      if(data.result == 200) {
        history.back();
      } else {
        document.getElementById('message').innerHTML = data.message + ' error code: ' + data.result;
      }
    });
    return false;
  });
});