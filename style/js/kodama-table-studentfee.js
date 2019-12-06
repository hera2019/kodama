//信息ID
var _studentfee = {
  'select_feetype': '',
  'time_paymentdate': '',
  'text_period': '',
  'text_moneyamount': '',
  'time_expirationdate': '',
  'select_teacherID': '',
  'text_description': '',
  'text_ID': '',
};

$(function () {
  $('#mainTable').editableTableWidget();
});

$(document).ready(function () {
  refreshFee();
});

function saveFee() {
  document.getElementById('message').innerHTML = "Saving data...";

  var id = document.getElementById('studentid').innerHTML;
  saveData(id, _studentfee);
}

function refreshFee() {
  document.getElementById('message').innerHTML = "Loading data...";

  var id = document.getElementById('studentid').innerHTML;
  postGetData(id, _studentfee);
}

function saveData(id, studentfee) {

  document.getElementById('message').innerHTML = "Saving data...";
  
  let data = {};
  let record = {};
  
  for(let i=1; i<=12; i++) {
    pushRecord(record, studentfee, i);
    data["record" + i] = {};
    $.extend(data["record" + i], record);
  }
  console.log(data);
  postSaveData(id, JSON.stringify(data));  

  function pushRecord(data, studentfee, recordindex) {
    for(var key in studentfee) {
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
    url: "../dataproc/studentfee_proc.php", // 把数据提交到php
    /* 提交的数据，必须使用key/value的形式，如"key=value"，
     * 如果多个键值对，就使用&分隔开，如"key1=value1&key2=value2" */
    data: "mod=update&studentID=" + id + "&data=" + postData,
    success: function (message) {
      // 提交成功后的回调，msg变量是php输出的内容
      document.getElementById('message').innerHTML = message;
  
      postGetData(id, _studentfee);
    }
  });
}

function postGetData(id, studentfee) {  
  for(let i=1; i<=12; i++) {
    resetRecord(studentfee, i);
  }
  // 提交数据函数  
  $.ajax({
    // 调用jquery的ajax方法  
    type: "POST", // 设置ajax方法提交数据的形式  
    url: "../dataproc/studentfee_proc.php", // 把数据提交到php  

    /* 提交的数据，必须使用key/value的形式，如"key=value"， 
     * 如果多个键值对，就使用&分隔开，如"key1=value1&key2=value2" */
    data: "mod=get&studentID=" + id,
    success: function (postdata) {
      // 提交成功后的回调，postdata变量是php输出的内容
      //setData2(postdata); //遍历数据推送到元素
      setData(postdata, studentfee); //遍历元素取数据
    }
  });

  function setData(postdata, studentfee) {
    //console.log(postdata);
    if(isJsonString(postdata)) {
      var jsonStr = JSON.parse(postdata);
      //jsonStr = postdata;//eval(postdata);
      if(jsonStr.message) {
        document.getElementById('message').innerHTML = jsonStr.message;
      }
      if(jsonStr.result == 200 && jsonStr.data) {        
        let jsondata = JSON.parse(jsonStr.data);
        for(let i=1; i<=12; i++) {
          setRecord(jsondata['record' + i], studentfee, i);
        }
      } else {
        document.getElementById('message').innerHTML = postdata;
      }
    }
  }

  function isJsonString(str) {
    try {
      if (typeof JSON.parse(str) == "object") {
        return true;
      }
    } catch(e) {
    }
    return false;
  }
  
  function setRecord(data, studentfee, recordindex) {
    if(data) {
      for(var key in studentfee) {
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
  
  function resetRecord(studentfee, recordindex) {
    for(var key in studentfee) {
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
            el.value = '-1';
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