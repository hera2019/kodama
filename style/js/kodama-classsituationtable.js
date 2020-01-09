var _kodama_students = {
  studentID: {},
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

  queryClasssituation('');
});

$(function () {  
  $('#checkbox_multiselect').on("click", function() {
    _kodama_students.multiselect = this.checked;
    document.getElementById('checkbox_hl1').style.visibility = this.checked ? "visible" : "hidden";
    document.getElementById('checkbox_hl2').style.visibility = this.checked ? "visible" : "hidden";
    cancelSelect();
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

function selectStudent(studentid, selected) {
  _kodama_students.studentID[studentid] = selected;
}

function queryClasssituation(queryParam) {
  //清空选中ID数组
  for(let key in _kodama_students.studentID) {
    _kodama_students.studentID[key] = false;
  }
  
  $('.dataTable').DataTable( {
    destroy: true, //销毁之前的DataTable，因为不可重复初始化
    responsive: true,
    select: true,
    order: [[ 5, 'desc' ]],
    pageLength: 100,
    "ajax": {
      "type": "POST",
      "url": '../dataproc/checkin_proc.php',
      "data": {
        'mod': 'queryclasssituation',
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
      { "data": "classname" },
      { "data": "classindex" },
      { "data": "checkinpercent",
        render: function (data, type, obj, meta) {
          return data > 50 ? '<span class="col-green">' + data + '%</span>' : '<span class="col-orange">' + data + '%</span>';
        }
      },
      { "data": "property",
        render: function (data, type, obj, meta) {
          let ret = '<span class="col-black">なし</span>';
          if(data == 0) {
            ret = '<span class="col-red">不明</span>';
          } else if(data == 1) {
            ret = '<span class="col-green">授業</span>';
          }
          return ret;
        }
      },
      { "data": "recordtime" },
      { "data": "manualmodified",
        render: function (data, type, obj, meta) {
          return data == 0 ? '自動生成' : '<span class="col-orange">手動変更</span>';
        }
      },
    ],
    deferRender:true, //延迟渲染(deferRender)
  });
}

function setClassProperty(property)
{
  //alert( table.rows('.selected').data().length +' row(s) selected' );
  if(_kodama_students.studentID && !isEmptyID(_kodama_students.studentID))
  {
    var text = "You will not be able to recover this record!";
    var btntext = "Yes, modify it!";
    showConfirmMessage(text, btntext, JSON.stringify(_kodama_students.studentID), property);
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

function showConfirmMessage(text, btntext, IDs, property) {
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
    postClassProperty(IDs, property);
    swal("Modified!", "Records are being modified now.", "success");
  });
}

function postClassProperty(IDs, property) {
  // 提交数据函数
  $.ajax({
    // 调用jquery的ajax方法
    type: "POST", // 设置ajax方法提交数据的形式
    url: "../dataproc/checkin_proc.php", // 把数据提交到php
    /* 提交的数据，必须使用key/value的形式，如"key=value"，
     * 如果多个键值对，就使用&分隔开，如"key1=value1&key2=value2" */
    data: "mod=editclasssituation&IDs=" + IDs + "&property=" + property,
    success: function (data) {
      // 提交成功后的回调，postdata变量是php输出的内容
      data = JSON.parse(data);
      if(data.result == 200) {
        queryClasssituation('');
        document.getElementById('message').innerHTML = data.message;
      } else {
        document.getElementById('message').innerHTML = data.message + ' error code: ' + data.result;
      }
    }
  });
}