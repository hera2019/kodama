var _kodama_students = {
  studentID: {},
  studentkey: [
    'studentid',
    'studentnumber',
    'applicationnumber',
    'classname',
    'lastname',
    'firstname',
    'nationalityregion',
    'classteachername',
    'lastnamefurigana',
    'firstnamefurigana',
    'birthday',
    'statusname',
    'lastnamealphabet',
    'firstnamealphabet',
    'genderfemale',
    //'attendancebeforeday',
    'lastnamemotherland',
    'firstnamemotherland',
    'phonenumber',
    //'attendancebeforemonth',
    'description',
    'photo',
  ],
  currentstudentid: '',
  multiselect: false,
};

$(document).ready(function () {
  $('.dataTable').DataTable({ //初始化DataTable
    destroy: true, //销毁之前的DataTable，因为不可重复初始化
        responsive: true,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
  });
  
  $('#queryform').bind('submit', function() {    
    var formParam = $("#queryform").serializeArray();
    var queryParam = " WHERE 1=1";
    formParam.forEach(function(item) {
      if(item.value) {
        if((item.name == 's.classID' && item.value <= 0) ||
          (item.name == 's.nationalityregion' && item.value < 0) ||
          (item.name == 's.status' && item.value < 0) ||
          (item.name == 's.genderfemale' && item.value < 0)) {
          return;
        }
        queryParam += " AND " + item.name + "='" + item.value + "'";
      }
    });

    queryStudent(queryParam);
    return false;
  });
  
  queryStudent('');
});

$(function () {  
  $('#checkbox_multiselect').on("click", function() {
    _kodama_students.multiselect = this.checked;
    document.getElementById('checkbox_hl1').style.visibility = this.checked ? "visible" : "hidden";
    document.getElementById('checkbox_hl2').style.visibility = this.checked ? "visible" : "hidden";
    cancelSelect();
    cancelSelectData();
  });   

  $('.dataTable').on("click", "input:checkbox", function() {
    let studentid = '';
    var check = this.checked;
    if(this.id == 'checkbox_hc1' || this.id == 'checkbox_hc2') {
      let els = $('.dataTable').find('input[type="checkbox"]');
      if (els && els.length) { //Checkbox找到          
        for (let i = 0; i < els.length; i++) {
          els[i].checked = check;
          let id = els[i].id;
          if(id != 'checkbox_hc1' && id != 'checkbox_hc2') {
            selectStudent(id.replace(/checkboxi_/, ""), this.checked);
          }
          let eltr = els[i].parentNode.parentNode.parentNode;
          if(eltr) {
            if (check) {
              $(eltr).addClass('selected');
            } else {
              $(eltr).removeClass('selected');
            }
          }
        }
      }
    } else {
      let eltr = this.parentNode.parentNode.parentNode;
      if(eltr) {
        check = !$(eltr).hasClass('selected');
        if(!_kodama_students.multiselect) { //单选
          cancelSelect();
        }
        this.checked = check;
        if (!check) {
          $(eltr).removeClass('selected');
          document.getElementById('checkbox_hc1').checked = false;
          document.getElementById('checkbox_hc2').checked = false;
        } else {
          $(eltr).addClass('selected');
        }
        studentid = this.id.replace(/checkboxi_/, "");
        selectStudent(studentid, check);
      }
    }
    showStudent(studentid, check);
  });

  $('.dataTable tbody').on( 'click', 'tr', function () { //此代码之前必须有DataTable初始化代码
    var check = !$(this).hasClass('selected');
    if(!_kodama_students.multiselect) { //单选
      cancelSelect();
    }
    var studentid = '';
    if ( !check ) {
      $(this).removeClass('selected');
      document.getElementById('checkbox_hc1').checked = false;
      document.getElementById('checkbox_hc2').checked = false;
    }
    else {
      $(this).addClass('selected');
    }
    let els = $(this).find('input[type="checkbox"]');
    if (els && els.length) { //Checkbox找到  
      els[0].checked = check;
      let id = els[0].id;
      if(id != 'checkbox_hc1' && id != 'checkbox_hc2') {
        studentid = id.replace(/checkboxi_/, "");
        selectStudent(studentid, check);
      }
    }
    showStudent(studentid, check);
  });
});

function cancelSelect() {
  let table = $('.dataTable').DataTable();
  if(table) {
    let rows = table.rows(['.selected']).nodes();
    $(rows).removeClass('selected');
    let els = $(rows).find('input[type="checkbox"]');
    for(let i=0; i<els.length; i++) { //Checkbox找到  
      els[i].checked = false;
      let id = els[i].id;
      if(id != 'checkbox_hc1' && id != 'checkbox_hc2') {
        let studentid = id.replace(/checkboxi_/, "");
        selectStudent(studentid, false);
      }
    }
  }
}

function cancelSelectData() {
  _kodama_students.currentstudentid = '';
  for(let key of _kodama_students.studentkey) {
    if(key == 'photo') {
      document.getElementById("info_photo").src = kodamafunc.PHOTO_PATH + "default/empty.jpg";
    } else {
      document.getElementById(key).innerHTML = '';
    }
  }
  //Set Cookie
  document.cookie = 'KODAMA_STUDENT_INFO' + "=" + "" + ";" + ";path=/";
}

function selectStudent(studentid, selected) {
  _kodama_students.studentID[studentid] = selected;
}

function showStudent(studentid, selected) {
  let table = $('.dataTable').DataTable();
  if(table) {
    let datas = table.rows(['.selected']).data();
    let data = datas[0];
    let id = _kodama_students.currentstudentid;
    if(selected) {
      if(studentid != '') {
        id = studentid;
      }
    }
    if(id != '' && datas.length) {
      for(let index of Object.keys(datas)) {
        if(datas[index]["ID"] == id) {
          data = datas[index];
          break;
        }
      }
    }
    let photo = '';
    if(data) { //把学生信息显示到Student Info
      let info = {};
      _kodama_students.currentstudentid = data["ID"];
      for(let key of _kodama_students.studentkey) {
        let value = data[key];
        if(key == 'genderfemale') {
          if(value == 1) {
            value = '女 Female';
            if(photo.length == 0) {
              photo = kodamafunc.PHOTO_PATH + "default/female.jpg";
            }
          } else if(value == 0) {
            value = '男 Male';
            if(photo.length == 0) {
              photo = kodamafunc.PHOTO_PATH + "default/male.jpg";
            }
          } else {
            value = '';
            if(photo.length == 0) {
              photo = kodamafunc.PHOTO_PATH + "default/empty.jpg";
            }
          }
        } else if(key == 'photo') {
          if(data[key]) {
            photo = kodamafunc.PHOTO_PATH + data[key];
            info[key] = data[key];
          }         
          continue;
        } else if(key == 'studentid') { //两个ID名称不一样，用studentid防止元素ID冲突
          value = data['ID'];
        } else if(key == 'description' && data[key]) { //修改回车换行符\r\n:<br>
          value = data[key];
          value = value.replace(/\\r\\n/g, '<br>');
          value = value.replace(/\\n/g, '<br>');
          value = value.replace(/\r\n/g, '<br>');
          value = value.replace(/\n/g, '<br>');
        }
        document.getElementById(key).innerHTML = value;
        info[key] = value;
      }
      if(photo.length == 0) {
        photo = kodamafunc.PHOTO_PATH + "default/empty.jpg"
      }
      document.getElementById("info_photo").src = photo;
      
      //console.log(JSON.stringify(info));
      
      //Set Cookie
      document.cookie = 'KODAMA_STUDENT_INFO' + "=" + JSON.stringify(info) + ";" + ";path=/";
    } else {
      _kodama_students.currentstudentid = '';
      for(let key of _kodama_students.studentkey) {
        if(key == 'photo') {
          document.getElementById("info_photo").src = kodamafunc.PHOTO_PATH + "default/empty.jpg";
        } else {
          document.getElementById(key).innerHTML = '';
        }
      }
      //Set Cookie
      document.cookie = 'KODAMA_STUDENT_INFO' + "=" + "" + ";" + ";path=/";
    }
  }  
  //console.log(_kodama_students);
}

function queryStudent(queryParam) {
  //清空选中ID数组
  for(let key in _kodama_students.studentID) {
    _kodama_students.studentID[key] = false;
  }
  
  $('.dataTable').DataTable( {
    destroy: true, //销毁之前的DataTable，因为不可重复初始化
    responsive: true,
    select: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
    "ajax": {
      "type": "POST",
      "url": '../dataproc/student_proc.php',
      "data": {
        'mod': 'query',
        'param': queryParam,
      },
      "dataSrc": "",
    },
    "columns": [
      { //這裡的data變數值為sysid，相等於obj.sysid
        "data": "ID",
        orderable: false,
        render: function (data, type, obj, meta) {
          var str = '<div class="custom-control custom-checkbox text-center">';
          str += '<input type="checkbox" id="checkboxi_' + data + '" class="custom-control-input filled-in chk-col-amber"/>';
          str += '<label class="custom-control-label" for="checkboxi_' + data + '"></label>';
          str += '</div>';
          return str;
        }
      },
      { "data": "studentnumber" },
      { "data": "name" },
      { "data": "classname" },
      { "data": "statusname" },
      { "data": "genderfemale",
        render: function (data, type, obj, meta) {
          return data == 1 ? '女 Female' : data == 0 ? '男 Male' : '';
        }
      },
      { "data": "nationalityregion" },
    ],
    deferRender:true, //延迟渲染(deferRender)
  });
}

function addStudent()
{
  window.location.href = "studentedit.php?mod=add";
}

function editStudent()
{
  window.location.href = "studentedit.php";
}

function deleteStudent()
{
  //alert( table.rows('.selected').data().length +' row(s) selected' );
  if(_kodama_students.studentID && !isEmptyID(_kodama_students.studentID))
  {
    var text = "You will not be able to recover this record!";
    var btntext = "Yes, delete it!";
    showConfirmMessage(text, btntext, JSON.stringify(_kodama_students.studentID));
  }
  
  function isEmptyID(IDs) {
    for(let key in IDs) {
      if(IDs[key]) {
        return false;
      }
    }
    return true;
  }
}

function showConfirmMessage(text, btntext, param) {
  swal({
    title: "Are you sure?",
    text: text,
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: btntext,
    closeOnConfirm: false
  }, function () {
    //console.log(param);
    postDeleteStudent(param);
    swal("Deleted!", "Records are being deleted now.", "success");
  });
}

function postDeleteStudent(param) {
  // 提交数据函数
  $.ajax({
    // 调用jquery的ajax方法
    type: "POST", // 设置ajax方法提交数据的形式
    url: "../dataproc/student_proc.php", // 把数据提交到php
    /* 提交的数据，必须使用key/value的形式，如"key=value"，
     * 如果多个键值对，就使用&分隔开，如"key1=value1&key2=value2" */
    data: "mod=delete&param=" + param,
    success: function (data) {
      // 提交成功后的回调，postdata变量是php输出的内容
      data = JSON.parse(data);
      if(data.result == 200) {
        document.getElementById('message').innerHTML = data.message;
        let table = $('.dataTable').DataTable();
        if(table) {
          table.rows(['.selected']).remove().draw();
          _kodama_students.currentstudentid = '';
          for(let key in _kodama_students.studentID) {
            _kodama_students.studentID[key] = false;
          }
        }
      } else {
        document.getElementById('message').innerHTML = data.message + ' error code: ' + data.result;
      }
    }
  });
}