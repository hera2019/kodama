$(document).ready(function () {
  $('.dataTable').DataTable({ //初始化DataTable
    destroy: true, //销毁之前的DataTable，因为不可重复初始化
        responsive: true,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
  });

  queryClassManage('');
});

$(function () {
  $('.dataTable tbody').on( 'click', 'tr', function () { //此代码之前必须有DataTable初始化代码
    var check = !$(this).hasClass('selected');    
    if ( !check ) {
      $(this).removeClass('selected');
    }
    else {
      cancelSelect();
      $(this).addClass('selected');
    }
  });
});

function cancelSelect() {
  let table = $('.dataTable').DataTable();
  if(table) {
    let rows = table.rows(['.selected']).nodes();
    $(rows).removeClass('selected');
  }
}

function queryClassManage(queryParam) {
  $('.dataTable').DataTable( {
    destroy: true, //销毁之前的DataTable，因为不可重复初始化
    responsive: true,
    select: true,
    order: [[ 0, 'asc' ]],
    paging: false,
    searching : false, //去掉搜索框方法一
    info: false,   //去掉底部文字
    "ajax": {
      "type": "POST",
      "url": '../dataproc/checkin_proc.php',
      "data": {
        'mod': 'queryclassmanage',
        'param': queryParam,
      },
      "dataSrc": "",
    },
    "columns": [
      { "data": "ID", "visible": false },
      { "data": "name" },
      { "data": "classteachername" },
      { "data": "description" },
    ],
    deferRender:true, //延迟渲染(deferRender)
  });
}

function addRecord()
{
  window.location.href = "classedit.php?mod=add";
}

function editRecord()
{
  var id = 0;
  let table = $('.dataTable').DataTable();
  if(table) {
    let data = table.rows(['.selected']).data()[0];
    if(data) {
      id = data.ID;
    }
  }
  if(id)
  {
    window.location.href = "classedit.php?ID=" + id;
  }
}

function deleteRecord()
{
  var id = 0;
  let table = $('.dataTable').DataTable();
  if(table) {
    let data = table.rows(['.selected']).data()[0];
    if(data) {
      id = data.ID;
    }
  }
  if(id)
  {
    var text = "You will not be able to recover this record!";
    var btntext = "Yes, delete it!";
    showConfirmMessage(text, btntext, id);
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
    postDeleteRecord(param);
    swal("Deleted!", "Records are being deleted now.", "success");
  });
}

function postDeleteRecord(param) {
  // 提交数据函数
  $.ajax({
    // 调用jquery的ajax方法
    type: "POST", // 设置ajax方法提交数据的形式
    url: "../dataproc/checkin_proc.php", // 把数据提交到php
    /* 提交的数据，必须使用key/value的形式，如"key=value"，
     * 如果多个键值对，就使用&分隔开，如"key1=value1&key2=value2" */
    data: "mod=deleteclassmanage&param=" + param,
    success: function (data) {
      // 提交成功后的回调，postdata变量是php输出的内容
      data = JSON.parse(data);
      if(data.result == 200) {
        document.getElementById('message').innerHTML = data.message;
        let table = $('.dataTable').DataTable();
        if(table) {
          table.rows(['.selected']).remove().draw();
        }
      } else {
        document.getElementById('message').innerHTML = data.message + ' error code: ' + data.result;
      }
    }
  });
}