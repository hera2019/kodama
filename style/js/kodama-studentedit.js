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
        //history.back();
        document.getElementById('message').innerHTML = data.message;
      } else {
        document.getElementById('message').innerHTML = data.message + ' error code: ' + data.result;
      }
    });
    return false;
  });
});