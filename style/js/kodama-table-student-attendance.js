$(document).ready(function () {
  refreshRecord();
});

function refreshRecord() {
  document.getElementById('message').innerHTML = "Loading data...";

  var id = document.getElementById('studentid').innerHTML;
  postGetData(id);
}

function postGetData(id, nomsg=false) {
  resetRecord();
  if(id == '' || id <= 0) {
    return;
  }
  // 提交数据函数  
  $.ajax({
    // 调用jquery的ajax方法  
    type: "POST", // 设置ajax方法提交数据的形式  
    url: "../dataproc/checkin_proc.php", // 把数据提交到php  

    /* 提交的数据，必须使用key/value的形式，如"key=value"， 
     * 如果多个键值对，就使用&分隔开，如"key1=value1&key2=value2" */
    data: "mod=getattendance&ID=" + id,
    success: function (postdata) {
      // 提交成功后的回调，postdata变量是php输出的内容
      setData(postdata, nomsg); //遍历元素取数据
    }
  });

  function setData(postdata, nomsg) {
    //console.log(postdata);
    if(kodamafunc.isJsonString(postdata)) {
      var jsonStr = JSON.parse(postdata);
      if(jsonStr.result == 200 && jsonStr.data) {
        let jsondata = JSON.parse(jsonStr.data);
        setRecord(jsondata);
      }
      if(jsonStr.message && (jsonStr.result != 200 || !nomsg)) { //save后reload，不显示get成功信息
        document.getElementById('message').innerHTML = jsonStr.message;
      }
      if(jsonStr.info && !nomsg) { //save后reload，不显示get成功信息
        let elecho = document.getElementById('echo');
        if(elecho) {
          elecho.innerHTML = jsonStr.info;
        }
      }
    } else {
      document.getElementById('message').innerHTML = postdata;
    }
  }

  function setRecord(data) {
    if(data) {
      var tbContent = '';
      for(let year in data.months) {
        let months = data.months[year];
        let monthsarray = Object.entries(months);
        let firstmonth = true;
        for(let m in months) {
          let monthinfo = months[m];
          tbContent += '<tr>';
          if(firstmonth) {
            tbContent += '<td rowspan=' + monthsarray.length + '>' + year + '年</td>';
            firstmonth = false;
          }
          tbContent += '<td>' + m + '月</td>';

          for(let d=1; d<=31; d++) {
            let propertyname = '';
            if(monthinfo.days[d]) {
              propertyname = monthinfo.days[d];
            }
            tbContent += '<td>';
            while(propertyname) {              
              let word1 = propertyname.substr(0, 1);
              propertyname = propertyname.replace(word1, '');
              if(word1 == '出') { //1
                tbContent += '<span class="col-green">出</span>';
              } else if(word1 == '欠') { //2
                tbContent += '<span class="col-deep-orange">欠</span>';
              } else if(word1 == '不') { //0
                tbContent += '<span class="col-pink">不</span>';
              } else if(word1 == '公') { //3
                tbContent += '<span class="col-brown">公</span>';
              } else if(word1 == '休') { //4
                tbContent += '<span class="col-blue-grey">休</span>';
              } else if(word1 == '帰') { //5
                tbContent += '<span class="col-grey">帰</span>';
              } else if(word1 == '遅') { //6
                tbContent += '<span class="col-orange">遅</span>';
              } else if(word1 == '-') { //7
                tbContent += '<span class="col-black">-</span>';
              }
            }
            tbContent += '</td>';
          }

          tbContent += '<td>' + monthinfo.lessonall + '</td>';
          tbContent += '<td class="col-green">' + monthinfo.lessonattend + '</td>';
          tbContent += '<td class="col-red">' + monthinfo.lessonabsent + '</td>';
          tbContent += '<td class="col-orange">' + monthinfo.lessonlate + '</td>';
          tbContent += '<td>' + monthinfo.lessonattendpercent + '</td>';
          tbContent += '<td>' + monthinfo.dayall + '</td>';
          tbContent += '<td class="col-green">' + monthinfo.dayattend + '</td>';
          tbContent += '<td class="col-red">' + monthinfo.dayabsent + '</td>';
          tbContent += '<td>' + monthinfo.dayattendpercent + '</td>';

          tbContent += '</tr>';
        }
      }
      $('#tbody').append(tbContent);
    }    
  }
  
  function resetRecord() {
    $('#tbody').empty();
  }
}