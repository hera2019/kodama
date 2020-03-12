function saveData(id, fileID, _student_file) {

  document.getElementById('message').innerHTML = "Saving data...";

  var data = {
    'student': {},
    'student2': {},
    'studentdata': {},
  };
  pushStudentData(data.studentdata, _student_file.studentdata);
  console.log(data.studentdata);
  
  pushStudent(data.student, _student_file.student, 'student');
  console.log(data.student);
  
  pushStudent(data.student2, _student_file.student2, 'student2');
  console.log(data.student2);

  postSaveData(id, fileID, JSON.stringify(data));

  function pushStudentData(data, filestudentdata) {
    for(var key in filestudentdata) {
      if(key.search(/text_/) == 0) { //text
        let el = document.getElementById(key);
        if(el) {
          data[key] = el.innerHTML;
        }
      } else if(key.search(/select_/) == 0) { //select
        let el = document.getElementById(key);
        if(el) {
          data[key] = el.value;
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

  function pushStudent(data, filestudent, dbtable) {
    for(var key in filestudent) {
      if(key.search(/text_/) == 0) { //text
        let keyname = key.substring(5);
        if(keyname) {
          let el = document.getElementById('text_' + dbtable + '.' + keyname);
          if(el) {
            data[keyname] = el.innerHTML;
          }
        }
      } else if(key.search(/select_/) == 0) { //select
        let keyname = key.substring(7);
        if(keyname) {
          let el = document.getElementById('select_' + dbtable + '.' + keyname);
          if(el) {
            data[keyname] = el.value;
          }
        }
      } else if(key.search(/time_/) == 0) { //time
        let keyname = key.substring(5);
        if(keyname) {
          let el = document.getElementById('time_' + dbtable + '.' + keyname);
          if(el) {
            data[keyname] = el.value;
          }
        }
      } else if(key.search(/photo_/) == 0) { //photo
        let keyname = key.substring(6);
        if(keyname) {
          let el = document.getElementById('photo_' + dbtable + '.' + keyname);
          if(el) {
            data[keyname] = el.value;
          }
        }
      } else if(key.search(/radio_/) == 0) { //radio
        let keyname = key.substring(6);
        if(keyname) {
          let el = document.getElementById('radio_' + dbtable + '.' + keyname);
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
          let el = document.getElementById('checkbox_' + dbtable + '.' + keyname);
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
    url: "../dataproc/adminssiondata_proc.php", // 把数据提交到php
    /* 提交的数据，必须使用key/value的形式，如"key=value"，
     * 如果多个键值对，就使用&分隔开，如"key1=value1&key2=value2" */
    data: "mod=update&studentID=" + id + "&fileID=" + fileID + "&data=" + postData,
    success: function (message) {
      // 提交成功后的回调，msg变量是php输出的内容
      document.getElementById('message').innerHTML = message;
    }
  });
}

function postGetData(id, fileID, _student_file) {
  resetData(_student_file);
  // 提交数据函数  
  $.ajax({
    // 调用jquery的ajax方法  
    type: "POST", // 设置ajax方法提交数据的形式  
    url: "../dataproc/adminssiondata_proc.php", // 把数据提交到php  

    /* 提交的数据，必须使用key/value的形式，如"key=value"， 
     * 如果多个键值对，就使用&分隔开，如"key1=value1&key2=value2" */
    data: "mod=get&studentID=" + id + "&fileID=" + fileID,
    success: function (postdata) {
      // 提交成功后的回调，postdata变量是php输出的内容
      setData(postdata, _student_file); //遍历元素取数据
    }
  });
  
  function resetData(_student_file) {
    resetStudent(_student_file.student, 'student');
    resetStudent(_student_file.student2, 'student2');
    resetStudentData(_student_file.studentdata);
  }
  
  function setData(postdata, _student_file) {
    //console.log(postdata);
    if(isJsonString(postdata)) {
      var jsonStr = JSON.parse(postdata);
      //jsonStr = postdata;//eval(postdata);
      if(jsonStr.message) {
        document.getElementById('message').innerHTML = jsonStr.message;
      }

      setStudent(jsonStr.student, _student_file.student, 'student');
      setStudent(jsonStr.student2, _student_file.student2, 'student2');

      if(jsonStr.studentdata) {
        setStudentData(JSON.parse(jsonStr.studentdata), _student_file.studentdata);
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
  
  function setStudent(student, filestudent, dbtable) {
    if(student) {
      for(var key in filestudent) {
        if(key.search(/text_/) == 0) { //text
          let keyname = key.substring(5);
          if(keyname && student[keyname]) {
            let el = document.getElementById('text_' + dbtable + '.' + keyname);
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
              let el = document.getElementById('text2_' + dbtable + '.' + keyname);
              if(el) {
                el.innerHTML = (student[keyname1] ? student[keyname1] : '') + ' ' + (student[keyname2] ? student[keyname2] : '');
              }
            }
          }
        } else if(key.search(/select_/) == 0) { //select
          let keyname = key.substring(7);
          if(keyname && student[keyname]) {
            let el = document.getElementById('select_' + dbtable + '.' + keyname);
            if(el) {
              el.value = student[keyname];
            }
          }
        } else if(key.search(/time_/) == 0) { //time
          let keyname = key.substring(5);
          if(keyname && student[keyname]) {
            let el = document.getElementById('time_' + dbtable + '.' + keyname);
            if(el) {
              el.value = student[keyname];
            }
          }
        } else if(key.search(/radio_/) == 0) { //radio
          let keyname = key.substring(6);
          if(keyname && student[keyname]) {
            let el = document.getElementById('radio_' + dbtable + '.' + keyname);
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
            let eltext = document.getElementById('photo_' + dbtable + '.' + keyname);
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
                } else {
                  elimage.src = kodamafunc.PHOTO_PATH + 'default/empty.jpg';
                }
              }
            }            
          }
        }
      }
    }    
  }
  
  function setStudentData(studentdata, filestudentdata) {
    if(studentdata) {
      for(var key in filestudentdata) {
        if(key.search(/text_/) == 0) { //text
          if(studentdata[key]) {
            let el = document.getElementById(key);
            if(el) {
              el.innerHTML = studentdata[key];
            }
          }
        } else if(key.search(/select_/) == 0) { //select
          if(studentdata[key]) {
            let el = document.getElementById(key);
            if(el) {
              el.value = studentdata[key];
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
  
  function resetStudent(filestudent, dbtable) {
    if(filestudent) {
      for(var key in filestudent) {
        if(key.search(/text_/) == 0) { //text
          let keyname = key.substring(5);
          if(keyname) {
            let el = document.getElementById('text_' + dbtable + '.' + keyname);
            if(el) {
              el.innerHTML = filestudent[key];
            }
          }
        } else if(key.search(/text2_/) == 0) { //text2
          let keyname = key.substring(6);
          let n = keyname.search(/_/);
          if(n > 0) {
            let keyname1 = keyname.substring(0, n);
            let keyname2 = keyname.substring(n + 1);          
            if(keyname1 || keyname2) {
              let el = document.getElementById('text2_' + dbtable + '.' + keyname);
              if(el) {
                el.innerHTML = (filestudent['text_' + keyname1] ? filestudent['text_' + keyname1] : '') + ' ' + (filestudent['text_' + keyname2] ? filestudent['text_' + keyname2] : '');
              }
            }
          }
        } else if(key.search(/select_/) == 0) { //select
          let keyname = key.substring(7);
          if(keyname) {
            let el = document.getElementById('select_' + dbtable + '.' + keyname);
            if(el) {
              el.value = filestudent[key];
            }
          }
        } else if(key.search(/time_/) == 0) { //time
          let keyname = key.substring(5);
          if(keyname) {
            let el = document.getElementById('time_' + dbtable + '.' + keyname);
            if(el) {
              el.value = filestudent[key];
            }
          }
        } else if(key.search(/radio_/) == 0) { //radio
          let keyname = key.substring(6);
          if(keyname) {
            let el = document.getElementById('radio_' + dbtable + '.' + keyname);
            if(el) {
              let els = el.getElementsByTagName("input");
              for (let i = 0; i < els.length; i++) {
                if (i == filestudent[key]) {
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
            let eltext = document.getElementById('photo_' + dbtable + '.' + keyname);
            let elimage = document.getElementById('photoimage');
            if(eltext && elimage) {
              eltext.value = filestudent[key];
              elimage.src = kodamafunc.PHOTO_PATH + 'default/empty.jpg';
            }
          }
        }
      }
    }    
  }
  
  function resetStudentData(filestudentdata) {
    if(filestudentdata) {
      for(var key in filestudentdata) {
        if(key.search(/text_/) == 0) { //text
          let el = document.getElementById(key);
          if(el) {
            el.innerHTML = filestudentdata[key];
          }
        } else if(key.search(/select_/) == 0) { //select
          let el = document.getElementById(key);
          if(el) {
            el.value = filestudentdata[key];
          }
        } else if(key.search(/time_/) == 0) { //time
          let el = document.getElementById(key);
          if(el) {
            el.value = filestudentdata[key];
          }
        } else if(key.search(/checkbox_/) == 0) { //checkbox
          let el = document.getElementById(key);
          if(el) {
            el.checked = filestudentdata[key];
          }
        } else if(key.search(/radio_/) == 0) { //radio
          let el = document.getElementById(key);
          if(el) {
            let els = el.getElementsByTagName("input");
            for (let i = 0; i < els.length; i++) {
              if (i == filestudentdata[key]) {
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