//最大记录数
var g_records = {
  num: 1,
  itemname: '',
};

$(function () {
  $('#mainTable').editableTableWidget();
  $('.kodama-editable-select').editableSelect({
    effects: 'default',
    filter: false,
  });
});

$(document).ready(function () {
  refreshRecord();
});

function newRecord() {
  g_records.num += 1;
  $("#recordrow" + g_records.num).removeAttr('hidden');
}

function refreshRecord() {
  document.getElementById('message').innerHTML = "Loading data...";

  var id = document.getElementById('studentid').innerHTML;
  postGetData(id, _studentrecord);
}

function saveRecord() {
  document.getElementById('message').innerHTML = "Saving data...";

  var id = document.getElementById('studentid').innerHTML;
  saveData(id, _studentrecord);
}

function saveData(id, studentrecord) {

  document.getElementById('message').innerHTML = "Saving data...";
  
  let data = {};
  let record = {};
  
  for(let i=1; i<=g_records.num; i++) {
    pushRecord(record, studentrecord, i);
    data["record" + i] = {};
    $.extend(data["record" + i], record);
  }
  console.log(data);
  postSaveData(id, encodeURIComponent(JSON.stringify(data)));

  function pushRecord(data, studentrecord, recordindex) {
    for(var key in studentrecord) {
      if(key.search(/text_/) == 0) { //text
        let keyname = key.substring(5);
        if(keyname) {
          let el = document.getElementById('text_' + recordindex + '_' + keyname);
          if(el) {
            data[keyname] = el.innerHTML;
          }
        }
      } else if(key.search(/select_/) == 0) { //select
        let keyname = key.substring(7);
        if(keyname) {
          let el = document.getElementById('select_' + recordindex + '_' + keyname);
          if(el) {
            data[keyname] = el.value;
          }
        }
      } else if(key.search(/time_/) == 0) { //time
        let keyname = key.substring(5);
        if(keyname) {
          let el = document.getElementById('time_' + recordindex + '_' + keyname);
          if(el) {
            data[keyname] = el.value;
          }
        }
      } else if(key.search(/photo_/) == 0) { //photo
        let keyname = key.substring(6);
        if(keyname) {
          let el = document.getElementById('photo_' + recordindex + '_' + keyname);
          if(el) {
            data[keyname] = el.value;
          }
        }
      } else if(key.search(/radio_/) == 0) { //radio
        let keyname = key.substring(6);
        if(keyname) {
          let el = document.getElementById('radio_' + recordindex + '_' + keyname);
          if(el) {
            let els = el.getElementsByTagName("input");
            for (let i = 0; i < els.length; i++) {
              if (els[i].checked) {
                data[keyname] = i;
              }
            }
          }
        }
      } else if(key.search(/checkbox_/) == 0) { //checkbox
        let keyname = key.substring(9);
        if(keyname) {
          let el = document.getElementById('checkbox_' + recordindex + '_' + keyname);
          if(el) {
            data[keyname] = el.checked;
          }
        }
      }
    }  
  }
}
  
function postSaveData(id, postData) {
  // 提交数据函数  
  $.ajax({
    // 调用jquery的ajax方法  
    type: "POST", // 设置ajax方法提交数据的形式  
    url: "../dataproc/studentitemdata_proc.php", // 把数据提交到php
    /* 提交的数据，必须使用key/value的形式，如"key=value"，
     * 如果多个键值对，就使用&分隔开，如"key1=value1&key2=value2" */
    data: "mod=update" + g_records.itemname + "&studentID=" + id + "&data=" + postData,
    success: function (postdata) {
      // 提交成功后的回调，postdata变量是php输出的内容
      if(kodamafunc.isJsonString(postdata)) {
        var jsonStr = JSON.parse(postdata);
        if(jsonStr.message) {
          document.getElementById('message').innerHTML = jsonStr.message;
        }
      } else {
        document.getElementById('message').innerHTML = postdata;
      }
      
      // reload
      postGetData(id, _studentrecord, true);
    }
  });
}

function postGetData(id, studentrecord, nomsg=false) {  
  for(let i=1; i<=g_records.num; i++) {
    resetRecord(studentrecord, i);
  }
  g_records.num = 1;
  // 提交数据函数  
  $.ajax({
    // 调用jquery的ajax方法  
    type: "POST", // 设置ajax方法提交数据的形式  
    url: "../dataproc/studentitemdata_proc.php", // 把数据提交到php  

    /* 提交的数据，必须使用key/value的形式，如"key=value"， 
     * 如果多个键值对，就使用&分隔开，如"key1=value1&key2=value2" */
    data: "mod=get" + g_records.itemname + "&studentID=" + id,
    success: function (postdata) {
      // 提交成功后的回调，postdata变量是php输出的内容
      setData(postdata, studentrecord, nomsg); //遍历元素取数据
    }
  });

  function setData(postdata, studentrecord, nomsg) {
    //console.log(postdata);
    if(kodamafunc.isJsonString(postdata)) {
      var jsonStr = JSON.parse(postdata);
      if(jsonStr.result == 200 && jsonStr.data) {
        let jsondata = JSON.parse(jsonStr.data);
        for(let i=1; i<=g_records.num; i++) {
          setRecord(jsondata['record' + i], studentrecord, i);
        }
      }
      if(jsonStr.message && (jsonStr.result != 200 || !nomsg)) { //save后reload，不显示get成功信息
        document.getElementById('message').innerHTML = jsonStr.message;
      }
    } else {
      document.getElementById('message').innerHTML = postdata;
    }
  }

  function setRecord(data, studentrecord, recordindex) {
    if(data) {
      newRecord();
      for(var key in studentrecord) {
        if(key.search(/text_/) == 0) { //text
          let keyname = key.substring(5);
          if(keyname && data[keyname]) {
            let el = document.getElementById('text_' + recordindex + '_' + keyname);
            if(el) {
              el.innerHTML = data[keyname];
            }
          }
        } else if(key.search(/text2_/) == 0) { //text2
          let keyname = key.substring(6);
          let n = keyname.search(/_/);
          if(n > 0) {
            let keyname1 = keyname.substring(0, n);
            let keyname2 = keyname.substring(n + 1);          
            if((keyname1 && data[keyname1]) || (keyname2 && data[keyname2])) {
              let el = document.getElementById('text2_' + recordindex + '_' + keyname);
              if(el) {
                el.innerHTML = (data[keyname1] ? data[keyname1] : '') + ' ' + (data[keyname2] ? data[keyname2] : '');
              }
            }
          }
        } else if(key.search(/select_/) == 0) { //select
          let keyname = key.substring(7);
          if(keyname && data[keyname]) {
            let el = document.getElementById('select_' + recordindex + '_' + keyname);
            if(el) {
              el.value = data[keyname];
            }
          }
        } else if(key.search(/time_/) == 0) { //time
          let keyname = key.substring(5);
          if(keyname && data[keyname]) {
            let el = document.getElementById('time_' + recordindex + '_' + keyname);
            if(el) {
              el.value = data[keyname];
            }
          }
        } else if(key.search(/radio_/) == 0) { //radio
          let keyname = key.substring(6);
          if(keyname && data[keyname]) {
            let el = document.getElementById('radio_' + recordindex + '_' + keyname);
            if(el) {
              let els = el.getElementsByTagName("input");
              for (let i = 0; i < els.length; i++) {
                if (i == data[keyname]) {
                  els[i].checked = true; //索引值=0,1,2....
                } else {
                  els[i].checked = false; //索引值=0,1,2....
                }
              }
            }
          }
        } else if(key.search(/photo_/) == 0) { //photo
          let keyname = key.substring(6);
          if(keyname) {
            let eltext = document.getElementById('photo_' + recordindex + '_' + keyname);
            let elimage = document.getElementById('photoimage');
            if(eltext && elimage) {
              if(data[keyname]) {
                eltext.value = data[keyname];
                elimage.src = kodamafunc.PHOTO_PATH + data[keyname];
              } else {
                if(data['genderfemale'] == 1) {
                  elimage.src = kodamafunc.PHOTO_PATH + 'default/female.jpg';
                } else if(data['genderfemale'] == 0) {
                  elimage.src = kodamafunc.PHOTO_PATH + 'default/male.jpg';
                }
              }
            }            
          }
        }
      }
    }    
  }
  
  function resetRecord(studentrecord, recordindex) {
    for(var key in studentrecord) {
      if(key.search(/text_/) == 0) { //text
        let keyname = key.substring(5);
        if(keyname) {
          let el = document.getElementById('text_' + recordindex + '_' + keyname);
          if(el) {
            el.innerHTML = '';
          }
        }
      } else if(key.search(/select_/) == 0) { //select
        let keyname = key.substring(7);
        if(keyname) {
          let el = document.getElementById('select_' + recordindex + '_' + keyname);
          if(el) {
            el.value = '';
          }
        }
      } else if(key.search(/time_/) == 0) { //time
        let keyname = key.substring(5);
        if(keyname) {
          let el = document.getElementById('time_' + recordindex + '_' + keyname);
          if(el) {
            el.value = '';
          } 
        }
      }
    }
  }
}