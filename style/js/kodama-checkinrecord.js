var _kodama_records = {
  recordID: {},
  currentrecordid: '',
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
        if(item.name == 's.classID' && item.value <= 0) {
          return;
        }
        queryParam += " AND " + item.name + " LIKE '%" + item.value + "%'";
      }
    });

    queryStudent(queryParam);
    return false;
  });
  
  let queryParam = " WHERE 1=1";
  queryStudent(queryParam);
});

$(function () {  
  $('#checkbox_multiselect').on("click", function() {
    _kodama_records.multiselect = this.checked;
    document.getElementById('checkbox_hl1').style.visibility = this.checked ? "visible" : "hidden";
    document.getElementById('checkbox_hl2').style.visibility = this.checked ? "visible" : "hidden";
    cancelSelect();
    cancelSelectData();
  });   

  $('.dataTable').on("click", "input:checkbox", function() {
    let recordid = '';
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
        if(!_kodama_records.multiselect) { //单选
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
        recordid = this.id.replace(/checkboxi_/, "");
        selectStudent(recordid, check);
      }
    }
    showStudent(recordid, check);
  });

  $('.dataTable tbody').on( 'click', 'tr td', function () { //此代码之前必须有DataTable初始化代码
    var tdindex = $(this).index();
    if(tdindex == 1) {
      return;
    }
    
    var $row = $(this).parent();
    var check = !$row.hasClass('selected');
    if(!_kodama_records.multiselect) { //单选
      cancelSelect();
    }
    var recordid = '';
    if ( !check ) {
      $row.removeClass('selected');
      document.getElementById('checkbox_hc1').checked = false;
      document.getElementById('checkbox_hc2').checked = false;
    }
    else {
      $row.addClass('selected');
    }
    let els = $row.find('input[type="checkbox"]');
    if (els && els.length) { //Checkbox找到  
      els[0].checked = check;
      let id = els[0].id;
      if(id != 'checkbox_hc1' && id != 'checkbox_hc2') {
        recordid = id.replace(/checkboxi_/, "");
        selectStudent(recordid, check);
      }
    }
    showStudent(recordid, check);
  });
  
  // Add event listener for opening and closing details
  $('.dataTable tbody').on('click', 'td.details-control', function() {
    var table = $('.dataTable').DataTable();    
    var tr = $(this).closest('tr');
    var row = table.row( tr );

    if(row.child.isShown()){
      // This row is already open - close it
      row.child.hide();
      tr.removeClass('shown');
    } else {
      // Open this row
      row.child(format(row.data())).show();
      tr.addClass('shown');
    }
  });
  
  // Add event listener for opening and closing details
  $('.dataTable thead, .dataTable tfoot').on('click', 'td.details-control', function() {
    var table = $('.dataTable').DataTable();
    //修改顶底图标
    var tr = $('.details-thead').closest('tr');
    if(tr.hasClass('shown')) {
      tr.removeClass('shown');
    } else {
      tr.addClass('shown');
    }
    tr = $('.details-tfoot').closest('tr');
    if(tr.hasClass('shown')) {
      tr.removeClass('shown');
    } else {
      tr.addClass('shown');
    }
    //修改每行图标
    table.rows().every(function(){
      // If row has details collapsed
      if(!this.child.isShown()){
        // Open this row
        this.child(format(this.data())).show();
        $(this.node()).addClass('shown');
      } else {
        // Collapse row details
        this.child.hide();
        $(this.node()).removeClass('shown');      
      }
    });
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
        let recordid = id.replace(/checkboxi_/, "");
        selectStudent(recordid, false);
      }
    }
  }
}

function cancelSelectData() {
  _kodama_records.currentrecordid = '';
}

function selectStudent(recordid, selected) {
  _kodama_records.recordID[recordid] = selected;
}

function showStudent(recordid, selected) {
  let table = $('.dataTable').DataTable();
  if(table) {
    let datas = table.rows(['.selected']).data();
    let data = datas[0];
    let id = _kodama_records.currentrecordid;
    if(selected) {
      if(recordid != '') {
        id = recordid;
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
    if(data) { //把学生信息显示到Student Info
      let info = {};
      _kodama_records.currentrecordid = data["ID"];
    } else {
      _kodama_records.currentrecordid = '';
    }
  }
}
function getPropertyName(property) {  
  let ret = '';//'<span class="col-black">なし</span>';
  if(property == 0) {
    ret = '<span class="col-pink">不明</span>';
  } else if(property == 1) {
    ret = '<span class="col-green">出席</span>';
  } else if(property == 6) {
    ret = '<span class="col-orange">遅刻早退</span>';
  } else if(property == 2) {
    ret = '<span class="col-deep-orange">欠席</span>';
  } else if(property == 3) { //3
    ret = '<span class="col-brown">公欠</span>';
  } else if(property == 4) { //4
    ret = '<span class="col-blue-grey">休学</span>';
  } else if(property == 5) { //5
    ret = '<span class="col-grey">一時帰国</span>';
  } else if(property == 7) { //7
    ret = '<span class="col-black">休校日</span>';
  }
  return ret;
}

function format(data) {
  // `data` is the original data object for the row
  return '<table class="row-expand col-blue-grey" cellpadding="11" cellspacing="0">'+
    '<tbody>'+
      '<tr>'+
        '<td class="col-xs-1"></td>'+
        '<td class="col-xs-1">日時1:</td>'+
        '<td class="col-xs-3">'+(data.time11==null?"":data.time11)+'</td>'+
        '<td class="col-xs-3">'+(data.time12==null?"":data.time12)+'</td>'+
        '<td class="col-xs-1">限目:</td>'+
        '<td class="col-xs-1">'+(data.classindex1==null?"":data.classindex1)+'</td>'+
        '<td class="col-xs-1">属性:</td>'+
        '<td class="col-xs-1">'+getPropertyName(data.property1)+'</td>'+
      '</tr>'+
      '<tr>'+
        '<td></td>'+
        '<td>日時2:</td>'+
        '<td>'+(data.time21==null?"":data.time21)+'</td>'+
        '<td>'+(data.time22==null?"":data.time22)+'</td>'+
        '<td class="col-xs-1">限目:</td>'+
        '<td>'+(data.classindex2==null?"":data.classindex2)+'</td>'+
        '<td class="col-xs-1">属性:</td>'+
        '<td>'+getPropertyName(data.property2)+'</td>'+
      '</tr>'+
      '<tr>'+
        '<td></td>'+
        '<td>日時3:</td>'+
        '<td>'+(data.time31==null?"":data.time31)+'</td>'+
        '<td>'+(data.time32==null?"":data.time32)+'</td>'+
        '<td class="col-xs-1">限目:</td>'+
        '<td>'+(data.classindex3==null?"":data.classindex3)+'</td>'+
        '<td class="col-xs-1">属性:</td>'+
        '<td>'+getPropertyName(data.property3)+'</td>'+
      '</tr>'+
      '<tr>'+
        '<td></td>'+
        '<td>日時4:</td>'+
        '<td>'+(data.time41==null?"":data.time41)+'</td>'+
        '<td>'+(data.time42==null?"":data.time42)+'</td>'+
        '<td class="col-xs-1">限目:</td>'+
        '<td>'+(data.classindex4==null?"":data.classindex4)+'</td>'+
        '<td class="col-xs-1">属性:</td>'+
        '<td>'+getPropertyName(data.property4)+'</td>'+
      '</tr>'+
    '</tbody>'+
  '</table>';
}

function queryStudent(queryParam) {
  //清空选中ID数组
  for(let key in _kodama_records.recordID) {
    _kodama_records.recordID[key] = false;
  }
  
  $('.dataTable').DataTable( {
    destroy: true, //销毁之前的DataTable，因为不可重复初始化
    responsive: true,
    select: true,
    order: [[ 8, 'desc' ]],
    pageLength: 50,
    "ajax": {
      "type": "POST",
      "url": '../dataproc/checkin_proc.php',
      "data": {
        'mod': 'querycheckin',
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
      {
        'className':      'details-control',
        'orderable':      false,
        'data':           null,
        'defaultContent': ''
      },
      { "data": "studentnumber" },
      { "data": "name" },
      { "data": "classname" },
      { "data": "time11" },
      { "data": "time12" },
      { "data": "property1",
        render: function (data, type, obj, meta) {
          return getPropertyName(data);
        }
      },
      { "data": "recordtime" },
      { "data": "deviceID" },
      { "data": "manualmodified",
        render: function (data, type, obj, meta) {
          return data == 0 ? '自動生成' : '<span class="col-orange">手動変更</span>';
        }
      },
    ],
    deferRender:true, //延迟渲染(deferRender)
  });
}

function addRecord()
{
  window.location.href = "checkinadd.php";
}

function editRecord()
{
  if(_kodama_records.currentrecordid)
  {
    window.location.href = "checkinedit.php?ID=" + _kodama_records.currentrecordid;
  }
}

function deleteRecord()
{
  //alert( table.rows('.selected').data().length +' row(s) selected' );
  if(_kodama_records.recordID && !isEmptyID(_kodama_records.recordID))
  {
    var text = "You will not be able to recover this record!";
    var btntext = "Yes, delete it!";
    showConfirmMessage(text, btntext, JSON.stringify(_kodama_records.recordID));
  }  
}

function isEmptyID(IDs) {
  for(let key in IDs) {
    if(IDs[key]) {
      return false;
    }
  }
  return true;
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
    data: "mod=deletecheckin&param=" + param,
    success: function (data) {
      // 提交成功后的回调，postdata变量是php输出的内容
      data = JSON.parse(data);
      if(data.result == 200) {
        document.getElementById('message').innerHTML = data.message;
        let table = $('.dataTable').DataTable();
        if(table) {
          table.rows(['.selected']).remove().draw();
          _kodama_records.currentrecordid = '';
          for(let key in _kodama_records.recordID) {
            _kodama_records.recordID[key] = false;
          }
        }
      } else {
        document.getElementById('message').innerHTML = data.message + ' error code: ' + data.result;
      }
    }
  });
}