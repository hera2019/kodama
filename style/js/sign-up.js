$(function () {
    $('#infoform').validate({
        rules: {
            'terms': {
                required: true
            },
            'confirm': {
                equalTo: '[name="password"]'
            }
        },
        highlight: function (input) {
            console.log(input);
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
            $(element).parents('.form-group').append(error);
        }
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
    if(!checkFormValue()) {
      return false;
    }
    ajaxSubmit(this, function(data) {
      data = JSON.parse(data);
      if(data.result == 200) {
        //history.back();
        var el = document.getElementById('message');
        if(el) {
          el.innerHTML = data.message;
        }
      } else {
        el = document.getElementById('message');
        if(el) {
          el.innerHTML = data.message + ' error code: ' + data.result;
        }
      }
    });
    return false;
    
    function checkFormValue() {
      var username = $("#username").val().trim();
      var name = $("#name").val().trim();
      var email = $("#email").val().trim();
      var password = $("#password").val().trim();
      var confirm = $("#confirm").val().trim();
      var mod = $("#mod").val().trim();
      
      if(username.length == 0) {        
        el = document.getElementById('message');
        if(el) {
          el.innerHTML = 'Username not empty!';
        }
        return false;
      }
      
      if(mod =='updateuser') {
        if(password.length != 0 && password != confirm) {
          el = document.getElementById('message');
          if(el) {
            el.innerHTML = 'Twice password not same!';
          }
          return false;
        }
      } else {
        if(password.length == 0) {
          el = document.getElementById('message');
          if(el) {
            el.innerHTML = 'Password not empty!';
          }
          return false;
        } else if(password != confirm) {
          el = document.getElementById('message');
          if(el) {
            el.innerHTML = 'Twice password not same!';
          }
          return false;
        }
      }
      
      if(name.length == 0) {        
        el = document.getElementById('message');
        if(el) {
          el.innerHTML = 'Name not empty!';
        }
        return false;
      }
      
      if(email.length == 0) {
        el = document.getElementById('message');
        if(el) {
          el.innerHTML = 'Email not empty!';
        }
        return false;
      }
      
      return true;
    }
  });
});