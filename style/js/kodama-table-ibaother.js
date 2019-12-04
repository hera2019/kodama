//信息ID
var _student_file4 = {
  student: {
  },
  student2: {
  },
  studentdata: {
    'text_referralname': '',
    'text_referralfee': '',
    'text_finaleducation': '',
    'text_graduatedschool': '',
    'text_interviewsummary': '',
    'text_postcode': '',
    'text_address': '',
    'text_phone': '',
    'text_emergencycontactname': '',
    'text_emergencycontactaddress': '',
    'text_emergencycontactphone': '',
    'text_protectorname': '',
    'text_protectoraddress': '',
    'text_protectorphone': '',
    'text_protectorcompanyname': '',
    'text_protectorcompanyphone': '',
    'text_protectorcompanyaddress': '',
    'time_scheduledschoolentrydate': '',
    'time_schoolentrydeadlinedate': '',
    'select_referral': '',
  },
};

$(function () {
  $('#mainTable').editableTableWidget();
});

$(document).ready(function () {
  refreshData_004();
});

function saveData_004() {
  document.getElementById('message').innerHTML = "Saving data...";

  var id = document.getElementById('studentid').innerHTML;
  saveData(id , 4, _student_file4);
}

function refreshData_004() {
  document.getElementById('message').innerHTML = "Loading data...";

  var id = document.getElementById('studentid').innerHTML;
  postGetData(id , 4, _student_file4);
}