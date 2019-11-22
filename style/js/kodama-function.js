var kodamafunc ={};

//照片默认目录
kodamafunc.PHOTO_PATH = '../data/photo/';

kodamafunc.getUrlParam = function(name) {
  var reg = new RegExp("[?&]" + name + "=([^&#]*)", "i");
  var res = window.location.href.match(reg);
  if (res && res.length > 1) {
    return decodeURIComponent(res[1]);
  }
  return '';
}

kodamafunc.setCookie = function(c_name, value, exseconds) {
  var exdate = new Date();
  exdate.setTime(exdate.getTime() + (exseconds * 1000));
  document.cookie = c_name + "=" + escape(value) + ((exseconds == null) ? "" : ";expires=" + exdate.toGMTString()) + ";path=/";
}

kodamafunc.getCookie = function(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i].trim();
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
