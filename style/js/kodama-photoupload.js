//照片上传，点击照片可选择图片文件上传，拖拽图片文件到照片取域也可以上传，支持中日文名
$(function () {
  function uploadPhoto(file) {
    // 提交数据函数
    document.getElementById('message').innerHTML = 'Image is uploading...';
    document.getElementById('xhr_progressgrd').style.width = '100%';
    let curDate = new Date();
    let path = curDate.getFullYear() + "/" + (curDate.getMonth()+1);
    var formData = new FormData($("#formphoto")[0]);
    formData.append("action", 'ajax');
    formData.append("dir", path);
    formData.append("my_field", file);
    $.ajax({
      xhr: function() {
          var xhr = $.ajaxSettings.xhr();
          xhr.upload.addEventListener('progress', progressHandlingFunction, false);
          return xhr;
      },
      async: true,
      type: "POST",
      url: "../plugin/upload/upload.php",
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      success: function (data) {
        data = JSON.parse(data);
        if(data.result == 200) {
          let photoname = document.getElementById('photo'); //可能的ID值1，记录图片文件名
          if(photoname) {
            photoname.value = data.filename;
          } else {
            photoname = document.getElementById('photo_student.photo'); //可能的ID值2，记录图片文件名
            if(photoname) {
              photoname.value = data.filename;
            }
          }
          document.getElementById('photoimage').src = kodamafunc.PHOTO_PATH + data.filename;
          document.getElementById('message').innerHTML = data.message;
        } else {
          document.getElementById('message').innerHTML = data.message + ' error code: ' + data.result;
        }
      }
    });
  }
  
  function progressHandlingFunction(event) {
    if (event.lengthComputable) {
      self.progress = event.loaded / event.total;
    } else if (this.explicitTotal) {
      self.progress = Math.min(1, event.loaded / self.explicitTotal);
    } else {
      self.progress = 0;
    }
    document.getElementById('xhr_progress').innerHTML = ' ' + Math.floor(self.progress*1000)/10 + '%';
    document.getElementById('xhr_progress').style.width = self.progress*100 + '%';
  }
  
  function dnd_hover(e) {
    e.stopPropagation();
    e.preventDefault();
    e.target.className = (e.type == "dragover" ? "hover" : "");  
  }

  // xhr example
  var xhr_file = null;
  document.getElementById("photofile").onchange = function () {
    xhr_file = this.files[0];
    uploadPhoto(xhr_file);
  }
  document.getElementById("photoimage").onclick = function (e) {
    e.preventDefault();
    document.getElementById("photofile").click();
  }
  // drag and drop example
  document.getElementById("photo_drag").style.display = "block";
  document.getElementById("photofile").style.display = "none";
  document.getElementById("photo_drag").ondragover = function (e) {
    dnd_hover(e);
  }
  document.getElementById("photo_drag").ondragleave = function (e) {
    dnd_hover(e);
  }
  document.getElementById("photo_drag").ondrop = function (e) {
    dnd_hover(e);
    var files = e.target.files || e.dataTransfer.files;
    xhr_file = files[0];
    uploadPhoto(xhr_file);
  }
});