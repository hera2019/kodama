//bootstrap-treeview 可参考 http://www.jq22.com/jquery-info10461
$(function () {
  
    var _kodama_students2 = {
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
      'attendancebeforeday',
      'lastnamemotherland',
      'firstnamemotherland',
      'phonenumber',
      'attendancebeforemonth',
      'description',
      'photo',
    ],
    currentstudentid: '',
    multiselect: false,
  };

  var defaultData = [{
      text: 'Parent 1',
      href: '#parent1',
      tags: ['4'],
      nodes: [{
          text: 'Child 1',
          href: '#child1',
          tags: ['2'],
          nodes: [{
              text: 'Grandchild 1',
              href: '#grandchild1',
              tags: ['0']
            },
            {
              text: 'Grandchild 2',
              href: '#grandchild2',
              tags: ['0']
            }
          ]
        },
        {
          text: 'Child 2',
          href: '#child2',
          tags: ['0']
        }
      ]
    },
    {
      text: 'Parent 2',
      href: '#parent2',
      tags: ['0']
    },
    {
      text: 'Parent 3',
      href: '#parent3',
      tags: ['0']
    },
    {
      text: 'Parent 4',
      href: '#parent4',
      tags: ['0']
    },
    {
      text: 'Parent 5',
      href: '#parent5',
      tags: ['0']
    }
  ];

  var alternateData = [{
      text: 'Parent 1',
      tags: ['2'],
      nodes: [{
          text: 'Child 1',
          tags: ['3'],
          nodes: [{
              text: 'Grandchild 1',
              tags: ['6']
            },
            {
              text: 'Grandchild 2',
              tags: ['3']
            }
          ]
        },
        {
          text: 'Child 2',
          tags: ['3']
        }
      ]
    },
    {
      text: 'Parent 2',
      tags: ['7']
    },
    {
      text: 'Parent 3',
      icon: 'glyphicon glyphicon-earphone',
      href: '#demo',
      tags: ['11']
    },
    {
      text: 'Parent 4',
      icon: 'glyphicon glyphicon-cloud-download',
      href: '/demo.html',
      tags: ['19'],
      selected: true
    },
    {
      text: 'Parent 5',
      icon: 'glyphicon glyphicon-certificate',
      color: 'green',
      backColor: 'pink',
      selectedColor: 'yellow',
      selectedBackColor: "#00ff00",
      href: 'http://www.tesco.com',
      tags: ['available', '0']
    }
  ];

  var json = '[' +
    '{' +
      '"text": "Parent 1",' +
      '"tags": ["3"],'+
      '"nodes": [' +
        '{' +
          '"text": "Child 1",' +
          '"nodes": [' +
            '{' +
              '"text": "Grandchild 1"' +
            '},' +
            '{' +
              '"text": "Grandchild 2"' +
            '}' +
          ']' +
        '},' +
        '{' +
          '"text": "Child 2"' +
        '}' +
      ']' +
    '},' +
    '{' +
      '"text": "Parent 2"' +
    '},' +
    '{' +
      '"text": "Parent 3"' +
    '},' +
    '{' +
      '"text": "Parent 4"' +
    '},' +
    '{' +
      '"text": "Parent 5"' +
    '}' +
  ']';

  var studentclassData = [];

  var initSelectableTree = function (studentclassinfo) {
    return $('#treeview-selectable').treeview({
      data: studentclassData,

      color: "black",
      backColor: "white",
      onhoverColor: "#FFC107",
      borderColor: "#E91E63",
      showBorder: false,
      showTags: true,
      highlightSelected: true,
      selectedColor: "white",
      selectedBackColor: "#03A9F4",
      searchResultColor: "#E91E63",

      multiSelect: $('#chk-select-multi').is(':checked'),
      onNodeSelected: onTreeNodeSelected,
      onNodeUnselected: onTreeNodeUnselected,
    });
  };
  var $selectableTree = initSelectableTree();
  refreshStudent();

  var findSelectableNodes = function () {
    return $selectableTree.treeview('search', [$('#input-select-node').val(), {
      ignoreCase: false,
      exactMatch: false
    }]);
  };
  var selectableNodes = findSelectableNodes();

  $('#chk-select-multi:checkbox').on('change', function () {
    console.log('multi-select change');
    $selectableTree = initSelectableTree();
    showStudent(null, false);
    //refreshStudent();
  });

  $('#tree-refresh').on('click', function (e) {
    refreshStudent();
  });

  // Select/unselect/toggle nodes
  $('#input-select-node').on('keyup', function (e) {
    selectableNodes = findSelectableNodes();
    $('.select-node').prop('disabled', !(selectableNodes.length >= 1));
  });

  $('#btn-select-node.select-node').on('click', function (e) {
    $selectableTree.treeview('selectNode', [selectableNodes, {
      silent: $('#chk-select-silent').is(':checked')
    }]);
  });

  $('#btn-unselect-node.select-node').on('click', function (e) {
    $selectableTree.treeview('unselectNode', [selectableNodes, {
      silent: $('#chk-select-silent').is(':checked')
    }]);
  });

  $('#btn-toggle-selected.select-node').on('click', function (e) {
    $selectableTree.treeview('toggleNodeSelected', [selectableNodes, {
      silent: $('#chk-select-silent').is(':checked')
    }]);
  });

  function refreshStudent() {
    studentclassData.length = 0;
    // 提交数据函数  
    $.ajax({
      // 调用jquery的ajax方法  
      type: "POST", // 设置ajax方法提交数据的形式  
      url: "../dataproc/student_proc.php", // 把数据提交到php  

      /* 提交的数据，必须使用key/value的形式，如"key=value"， 
       * 如果多个键值对，就使用&分隔开，如"key1=value1&key2=value2" */
      data: "mod=querytree",
      success: function (postdata) {
        // 提交成功后的回调，postdata变量是php输出的内容
        let jsonrecord = JSON.parse(postdata);
        let studentclassinfo = [];

        for(var key in jsonrecord) {
          var student = jsonrecord[key];
          if(!studentclassinfo[student.classname] || studentclassinfo[student.classname].length <= 0) {
            studentclassinfo[student.classname] = new Array();
          }
          studentclassinfo[student.classname][student.ID] = {
            studentname: student.studentnumber + ': ' + student.name,
            student: student,
          };
        }
        
        for (var classname in studentclassinfo) {
          let studentinfo = studentclassinfo[classname];
          studentclass = {
            text: classname,
            nodes: [],
            tags: [],
          };
          var i=0;
          for (var studentid in studentinfo) {
            var student1 = studentinfo[studentid];
            student = {
              text: student1.studentname,
              id: studentid,
              student: student1.student,
            };
            studentclass.nodes.push(student);
            i++;
          }
          studentclass.tags.push(i);
          studentclassData.push(studentclass);
        }
        $selectableTree = initSelectableTree(studentclassinfo); //遍历元素取数据
      }
    });
  }

  var onTreeNodeSelected = function (event, node) {
    //$('#selectable-output').prepend('<p>' + node.text + ' was selected</p>');
    if(node.nodes && node.nodes.length > 0) {
      if($('#chk-select-multi:checkbox').is(':checked')) {
        for(var node1 in node.nodes) {
          $selectableTree.treeview('selectNode', [node.nodes[node1].nodeId, { silent: true }]);
        }
        showStudent(node.nodes[0].student, event.type == "nodeSelected");
      } else {
        $selectableTree.treeview('selectNode', [node.nodes[0].nodeId, { silent: true }]);
        showStudent(node.nodes[0].student, event.type == "nodeSelected");
      }
    } else {
      showStudent(node.student, event.type == "nodeSelected");
    }
  }

  var onTreeNodeUnselected = function (event, node) {
    //$('#selectable-output').prepend('<p>' + node.text + ' was unselected</p>');
    if(node.nodes && node.nodes.length > 0) {
      if($('#chk-select-multi:checkbox').is(':checked')) {
        for(var node1 in node.nodes) {
          $selectableTree.treeview('unselectNode', [node.nodes[node1].nodeId, { silent: true }]);
        }
        showStudent(node.nodes[0].student, event.type == "nodeSelected");
      } else {
        $selectableTree.treeview('unselectNode', [node.nodes[0].nodeId, { silent: true }]);
        showStudent(node.nodes[0].student, event.type == "nodeSelected");
      }
    } else {
      showStudent(node.student, event.type == "nodeSelected");
    }
  }
  
  function showStudent(data, selected) {
    if(!selected || !data) {
      data = null;
      let viewnodes = $('#treeview-selectable').treeview('getSelected', null);
      for(let nodeid in viewnodes) {
        let node1 = viewnodes[nodeid];
        if(node1 && node1.student) {
          data = node1.student;
          break;
        }
      }
    }

    let photo = '';
    if(data) { //把学生信息显示到Student Info
      let info = {};
      _kodama_students2.currentstudentid = data["ID"];
      for(let key of _kodama_students2.studentkey) {
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
        el = document.getElementById(key);
        if(el) {
          el.innerHTML = value;
        }
        info[key] = value;
      }
      if(photo.length == 0) {
        photo = kodamafunc.PHOTO_PATH + "default/empty.jpg"
      }
      el = document.getElementById("info_photo");
      if(el) {
        el.src = photo;
      }

      //console.log(JSON.stringify(info));

      //Set Cookie
      document.cookie = 'KODAMA_STUDENT_INFO' + "=" + JSON.stringify(info) + ";" + ";path=/";
    } else {
      _kodama_students2.currentstudentid = '';
      for(let key of _kodama_students2.studentkey) {
        if(key == 'photo') {
          el = document.getElementById("info_photo");
          if(el) {
            el.src = kodamafunc.PHOTO_PATH + "default/empty.jpg";
          }
        } else {
          el = document.getElementById(key);
          if(el) {
            el.innerHTML = '';
          }
        }
      }
      //Set Cookie
      document.cookie = 'KODAMA_STUDENT_INFO' + "=" + "" + ";" + ";path=/";
    }
    //console.log(_kodama_students2);

    if(typeof refreshRecord != 'undefined' && refreshRecord instanceof Function) {
      refreshRecord();
    }
  }
});
