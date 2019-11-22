//信息ID
var _student_file1 = {
  student: {
    'select_nationalityregion': '',
    'text_lastname': '',
    'text_firstname': '',
    'text_lastnamealphabet': '',
    'text_firstnamealphabet': '',
    'time_birthday': '',
    'radio_genderfemale': 0,
    'photo_photo': '',
  },
  studentdata: {
    'checkbox_furtherjpstudy': false,
    'checkbox_getjpjob': false,
    'checkbox_returncountry': false,
    'checkbox_otherplan': false,
    'radio_married': 0,
    'radio_occupation': 0,
    'radio_prevjp': 0,
    'text_birthplace': '',
    'text_curaddress': '',
    'text_householdaddress': '',
    'text_spousesname': '',
    'text_fathername': '',
    'text_fatheroccupation': '',
    'text_fatheraddress': '',
    'text_mothername': '',
    'text_motheroccupation': '',
    'text_motheraddress': '',
    'text_relationship1': '',
    'text_relationshipname1': '',
    'text_relationshipoccupation1': '',
    'text_relationshipaddress1': '',
    'text_relationship2': '',
    'text_relationshipname2': '',
    'text_relationshipoccupation2': '',
    'text_relationshipaddress2': '',
    'text_relationship3': '',
    'text_relationshipname3': '',
    'text_relationshipoccupation3': '',
    'text_relationshipaddress3': '',
    'text_eduschoolname1': '',
    'text_eduschoollocation1': '',
    'text_eduschoolyear1': '',
    'text_eduschoolname2': '',
    'text_eduschoollocation2': '',
    'text_eduschoolyear2': '',
    'text_eduschoolname3': '',
    'text_eduschoollocation3': '',
    'text_eduschoolyear3': '',
    'text_eduschoolname4': '',
    'text_eduschoollocation4': '',
    'text_eduschoolyear4': '',
    'text_eduschoolname5': '',
    'text_eduschoollocation5': '',
    'text_eduschoolyear5': '',
    'text_jpschoolname1': '',
    'text_jpschoollocation1': '',
    'text_jpschoolname2': '',
    'text_jpschoollocation2': '',
    'text_employername1': '',
    'text_occupation1': '',
    'text_occupationlocation1': '',
    'text_employername2': '',
    'text_occupation2': '',
    'text_occupationlocation2': '',
    'text_prevjptimes': '',
    'text_entrypurpose1': '',
    'text_visastatus1': '',
    'text_entrypurpose2': '',
    'text_visastatus2': '',
    'text_reasonstojapan': '',
    'text_furtherschoolname': '',
    'text_furthersubject': '',
    'text_otherplan': '',
    'time_fatherbirthday': '',
    'time_motherbirthday': '',
    'time_relationshipbirthday1': '',
    'time_relationshipbirthday2': '',
    'time_relationshipbirthday3': '',
    'time_eduadmission1': '',
    'time_edugraduation1': '',
    'time_eduadmission2': '',
    'time_edugraduation2': '',
    'time_eduadmission3': '',
    'time_edugraduation3': '',
    'time_eduadmission4': '',
    'time_edugraduation4': '',
    'time_eduadmission5': '',
    'time_edugraduation5': '',
    'time_jpadmission1': '',
    'time_jpgraduation1': '',
    'time_jpadmission2': '',
    'time_jpgraduation2': '',
    'time_employstart1': '',
    'time_employend1': '',
    'time_employstart2': '',
    'time_employend2': '',
    'time_prevjpentry1': '',
    'time_prevjpdeparture1': '',
    'time_prevjpentry2': '',
    'time_prevjpdeparture2': '',
  },
};

$(function () {
  $('#mainTable').editableTableWidget();
});

$(document).ready(function () {
  refreshData_001();
});

function saveData_001() {
  var id = document.getElementById('studentid').innerHTML;
  saveData(id , 1);
}

function saveData(id, fileID) {

  document.getElementById('message').innerHTML = "Saving data...";

  var data = {
    'student': {},
    'studentdata': {},
  };
  pushStudentData(data.studentdata);
  console.log(data.studentdata);
  
  pushStudent(data.student);
  console.log(data.student);

  postSaveData(id, fileID, JSON.stringify(data));

  function pushStudentData(data) {
    for(var key in _student_file1.studentdata) {
      if(key.search(/text_/) == 0) { //text
        let el = document.getElementById(key);
        if(el) {
          data[key] = el.innerHTML;
        }
      } else if(key.search(/time_/) == 0) { //time
        let el = document.getElementById(key);
        if(el) {
          data[key] = el.value;
        }
      } else if(key.search(/radio_/) == 0) { //radio
          let el = document.getElementById(key);
          if(el) {
            let els = el.getElementsByTagName("input");
            for (let i = 0; i < els.length; i++) {
              if (els[i].checked) {
                data[key] = i;
            }
          }
        }
      } else if(key.search(/checkbox_/) == 0) { //checkbox
        let el = document.getElementById(key);
        if(el) {
          data[key] = el.checked;
        }
      }
    }  
  }

  function pushStudent(data) {
    for(var key in _student_file1.student) {
      if(key.search(/text_/) == 0) { //text
        let keyname = key.substring(5);
        if(keyname) {
          let el = document.getElementById('text_student.' + keyname);
          if(el) {
            data[keyname] = el.innerHTML;
          }
        }
      } else if(key.search(/select_/) == 0) { //select
        let keyname = key.substring(7);
        if(keyname) {
          let el = document.getElementById('select_student.' + keyname);
          if(el) {
            data[keyname] = el.value;
          }
        }
      } else if(key.search(/time_/) == 0) { //time
        let keyname = key.substring(5);
        if(keyname) {
          let el = document.getElementById('time_student.' + keyname);
          if(el) {
            data[keyname] = el.value;
          }
        }
      } else if(key.search(/photo_/) == 0) { //photo
        let keyname = key.substring(6);
        if(keyname) {
          let el = document.getElementById('photo_student.' + keyname);
          if(el) {
            data[keyname] = el.value;
          }
        }
      } else if(key.search(/radio_/) == 0) { //radio
        let keyname = key.substring(6);
        if(keyname) {
          let el = document.getElementById('radio_student.' + keyname);
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
          let el = document.getElementById('checkbox_student.' + keyname);
          if(el) {
            data[keyname] = el.checked;
          }
        }
      }
    }  
  }
}

function postSaveData(id, fileID, postData) {
  // 提交数据函数  
  $.ajax({
    // 调用jquery的ajax方法  
    type: "POST", // 设置ajax方法提交数据的形式  
    url: "../dataproc/dataproc_post.php", // 把数据提交到php
    /* 提交的数据，必须使用key/value的形式，如"key=value"，
     * 如果多个键值对，就使用&分隔开，如"key1=value1&key2=value2" */
    data: "mod=update&studentID=" + id + "&fileID=" + fileID + "&data=" + postData,
    success: function (message) {
      // 提交成功后的回调，msg变量是php输出的内容
      document.getElementById('message').innerHTML = message;
    }
  });
}

function refreshData_001() {
  document.getElementById('message').innerHTML = "Loading data...";

  var id = document.getElementById('studentid').innerHTML;
  postGetData(id , 1);
}

function postGetData(id, fileID) {
  // 提交数据函数  
  $.ajax({
    // 调用jquery的ajax方法  
    type: "POST", // 设置ajax方法提交数据的形式  
    url: "../dataproc/dataproc_post.php", // 把数据提交到php  

    /* 提交的数据，必须使用key/value的形式，如"key=value"， 
     * 如果多个键值对，就使用&分隔开，如"key1=value1&key2=value2" */
    data: "mod=get&studentID=" + id + "&fileID=" + fileID,
    success: function (postdata) {
      // 提交成功后的回调，postdata变量是php输出的内容
      //setData2(postdata); //遍历数据推送到元素
      setData(postdata); //遍历元素取数据
    }
  });

  function setData(postdata) {
    //console.log(postdata);
    if(isJsonString(postdata)) {
      var jsonStr = JSON.parse(postdata);
      //jsonStr = postdata;//eval(postdata);
      if(jsonStr.message) {
        document.getElementById('message').innerHTML = jsonStr.message;
      }

      setStudent(jsonStr.student);

      if(jsonStr.studentdata) {
        setStudentData(JSON.parse(jsonStr.studentdata));
      }
    } else {
      document.getElementById('message').innerHTML = postdata;
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
  
  function setStudent(student) {
    if(student) {
      for(var key in _student_file1.student) {
        if(key.search(/text_/) == 0) { //text
          let keyname = key.substring(5);
          if(keyname && student[keyname]) {
            let el = document.getElementById('text_student.' + keyname);
            if(el) {
              el.innerHTML = student[keyname];
            }
          }
        } else if(key.search(/text2_/) == 0) { //text2
          let keyname = key.substring(6);
          let n = keyname.search(/_/);
          if(n > 0) {
            let keyname1 = keyname.substring(0, n);
            let keyname2 = keyname.substring(n + 1);          
            if((keyname1 && student[keyname1]) || (keyname2 && student[keyname2])) {
              let el = document.getElementById('text2_student.' + keyname);
              if(el) {
                el.innerHTML = (student[keyname1] ? student[keyname1] : '') + ' ' + (student[keyname2] ? student[keyname2] : '');
              }
            }
          }
        } else if(key.search(/select_/) == 0) { //select
          let keyname = key.substring(7);
          if(keyname && student[keyname]) {
            let el = document.getElementById('select_student.' + keyname);
            if(el) {
              el.value = student[keyname];
            }
          }
        } else if(key.search(/time_/) == 0) { //time
          let keyname = key.substring(5);
          if(keyname && student[keyname]) {
            let el = document.getElementById('time_student.' + keyname);
            if(el) {
              el.value = student[keyname];
            }
          }
        } else if(key.search(/radio_/) == 0) { //radio
          let keyname = key.substring(6);
          if(keyname && student[keyname]) {
            let el = document.getElementById('radio_student.' + keyname);
            if(el) {
              let els = el.getElementsByTagName("input");
              for (let i = 0; i < els.length; i++) {
                if (i == student[keyname]) {
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
            let eltext = document.getElementById('photo_student.' + keyname);
            let elimage = document.getElementById('photoimage');
            if(eltext && elimage) {
              if(student[keyname]) {
                eltext.value = student[keyname];
                elimage.src = kodamafunc.PHOTO_PATH + student[keyname];
              } else {
                if(student['genderfemale'] == 1) {
                  elimage.src = kodamafunc.PHOTO_PATH + 'default/female.jpg';
                } else if(student['genderfemale'] == 0) {
                  elimage.src = kodamafunc.PHOTO_PATH + 'default/male.jpg';
                }
              }
            }            
          }
        }
      }
    }    
  }
  
  function setStudentData(studentdata) {
    if(studentdata) {
      for(var key in _student_file1.studentdata) {
        if(key.search(/text_/) == 0) { //text
          if(studentdata[key]) {
            let el = document.getElementById(key);
            if(el) {
              el.innerHTML = studentdata[key];
            }
          }
        } else if(key.search(/time_/) == 0) { //time
          if(studentdata[key]) {
            let el = document.getElementById(key);
            if(el) {
              el.value = studentdata[key];
            }
          }
        } else if(key.search(/checkbox_/) == 0) { //checkbox
          if(studentdata[key] || studentdata[key] == false) {
            let el = document.getElementById(key);
            if(el) {
              el.checked = studentdata[key];
            }
          }
        } else if(key.search(/radio_/) == 0) { //radio
          if(studentdata[key] || studentdata[key] == 0) {
            let el = document.getElementById(key);
            if(el) {
              let els = el.getElementsByTagName("input");
              for (let i = 0; i < els.length; i++) {
                if (i == studentdata[key]) {
                  els[i].checked = true; //索引值=0,1,2....
                } else {
                  els[i].checked = false; //索引值=0,1,2....
                }
              }
            }
          }
        }
      }
    }   
  }
  
  function setData2(postdata) {
    //console.log(postdata);
    //document.getElementById('export').innerHTML = postdata;

    var jsonStr = JSON.parse(postdata);
    //jsonStr = postdata;//eval(postdata);
    if(jsonStr.message) {
      document.getElementById('message').innerHTML = jsonStr.message;
    }

    var student = jsonStr.student;
    if(student) {
      document.getElementById('student.name').innerHTML = student.name;
      document.getElementById('photoimage').src = kodamafunc.PHOTO_PATH + student.photo;
    }

    if(jsonStr.studentdata) {
      var studentdata = JSON.parse(jsonStr.studentdata);

      for (var key in studentdata) {
        var el = document.getElementById(key);
        if (el) {
          var value = studentdata[key];
          if (!setTime2(key, el, value)) {
            if (!setSelect2(key, el, value)) {
              if (!setRadio2(key, el, value)) {
                if (!setCheckbox2(key, el, value)) {
                  setText2(key, el, value);
                }
              }
            }
          }
        }
      }
    }
  }
  
  function setTime2(key, el, value) {
    if (key.indexOf("time") != -1) {
      var els = el.getElementsByTagName("input");
      if (els && els[0]) {
        els[0].value = value;
        return true;
      }
    }
    return false;
  }

  function setSelect2(key, el, value) {
    if (key.indexOf("select") != -1) {
      var els = el.getElementsByTagName("select");
      if (els && els[0]) {
        els[0].value = value;
        //$('#select_001 select').selectpicker('val', value); //设置select的值
        //$('#select_001 select').selectpicker('refresh'); //必须刷新才能看到结果
        return true;
      }
    }
    return false;
  }

  function setRadio2(key, el, value) {
    if (key.indexOf("radio") != -1) {
      var els = el.getElementsByTagName("input");
      for (var j = 0; j < els.length; j++) {
        if (j == value) {
          els[j].checked = true; //索引值=0,1,2....
        } else {
          els[j].checked = false; //索引值=0,1,2....
        }
      }
      return true;
    }
    return false;
  }

  function setCheckbox2(key, el, value) {
    if (key.indexOf("checkbox") != -1) {
      var els = el.getElementsByTagName("input");
      if (els && els[0]) {
        els[0].checked = value;
      }
      return true;
    }
    return false;
  }

  function setText2(key, el, value) {
    el.innerHTML = value;
    return true;
  }
}
