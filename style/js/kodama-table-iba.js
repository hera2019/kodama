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
  student2: {
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
    'text_applicationyear': '',
    'text_applicationmonth': '',
    'text_applicationdate': '',
    'text_signatureapplicant': '',
  },
};

$(function () {
  $('#mainTable').editableTableWidget();
});

$(document).ready(function () {
  refreshData_001();
});

function saveData_001() {
  document.getElementById('message').innerHTML = "Saving data...";

  var id = document.getElementById('studentid').innerHTML;
  saveData(id , 1, _student_file1);
}

function refreshData_001() {
  document.getElementById('message').innerHTML = "Loading data...";

  var id = document.getElementById('studentid').innerHTML;
  postGetData(id , 1, _student_file1);
}