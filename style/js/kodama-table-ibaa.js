//信息ID
var _student_file2 = {
  student: {
  },
  student2: {
    'text_passportnumber': '',
    'time_passportexpiration': '',
  },
  studentdata: {
    'radio_passportyes': 0,
    'radio_passportno': 0,
    'radio_jpntest': 0,
    'radio_jpntestpass': 0,
    'radio_jptest1pass': 0,
    'radio_jptest2pass': 0,
    'radio_jpfamilylivetogether': 0,
    'checkbox_passportapplying': false,
    'text_cellphonenumber': '',
    'text_internetnumber': '',
    'text_visaapplyplace': '',
    'text_supportername': '',
    'text_supportercuraddress': '',
    'text_supporterhouseholdaddress': '',
    'text_supporterphonenumber': '',    
    'text_supporterrelationship': '',    
    'text_supporterworkplacename': '',
    'text_supportercompanyindustry': '',
    'text_supportercompanyaddress': '',
    'text_supporterworkphonenumber': '',
    'text_supporterannualincome': '',
    'text_jpntestpoint': '',
    'text_jptest1': '',
    'text_jptest1point': '',
    'text_jptest2': '',
    'text_jptest2point': '',
    'text_jpschoolname': '',
    'text_jpschoolmaterial': '',
    'text_jptesttime': '',
    'text_jpfamilyname': '',
    'text_jpfamilyoccupation': '',
    'text_jpfamilyrelation': '',
    'text_jpfamilyresidencequalification': '',
    'text_jpfamilynationality': '',
    'text_jpfamilyresidencenumber': '',
    'text_jpfamilycontactnumber': '',
    'text_jpfamilyworkplacename': '',
    'text_jpfamilyhomeaddress': '',
    'time_jpntest': '',
    'time_jptest1': '',
    'time_jptest2': '',
    'time_jpteststart': '',
    'time_jptestend': '',
    'time_jpfamilybirthday': '',
  },
};

$(function () {
  $('#mainTable').editableTableWidget();
});

$(document).ready(function () {
  refreshData_002();
});

function saveData_002() {
  document.getElementById('message').innerHTML = "Saving data...";

  var id = document.getElementById('studentid').innerHTML;
  saveData(id , 2, _student_file2);
}

function refreshData_002() {
  document.getElementById('message').innerHTML = "Loading data...";

  var id = document.getElementById('studentid').innerHTML;
  postGetData(id , 2, _student_file2);
}