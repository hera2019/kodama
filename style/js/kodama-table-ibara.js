//信息ID
var _student_file3 = {
  student: {
  },
  student2: {
  },
  studentdata: {
    'text_relationship4': '',
    'text_relationshipname4': '',
    'text_relationshipoccupation4': '',
    'text_relationshipaddress4': '',
    'text_relationship5': '',
    'text_relationshipname5': '',
    'text_relationshipoccupation5': '',
    'text_relationshipaddress5': '',
    'text_relationship6': '',
    'text_relationshipname6': '',
    'text_relationshipoccupation6': '',
    'text_relationshipaddress6': '',
    'text_relationship7': '',
    'text_relationshipname7': '',
    'text_relationshipoccupation7': '',
    'text_relationshipaddress7': '',
    'text_relationship8': '',
    'text_relationshipname8': '',
    'text_relationshipoccupation8': '',
    'text_relationshipaddress8': '',
    'text_relationship9': '',
    'text_relationshipname9': '',
    'text_relationshipoccupation9': '',
    'text_relationshipaddress9': '',
    'text_relationship10': '',
    'text_relationshipname10': '',
    'text_relationshipoccupation10': '',
    'text_relationshipaddress10': '',
    'text_relationship11': '',
    'text_relationshipname11': '',
    'text_relationshipoccupation11': '',
    'text_relationshipaddress11': '',  
    'text_eduschoolname6': '',
    'text_eduschoollocation6': '',
    'text_eduschoolyear6': '',
    'text_eduschoolname7': '',
    'text_eduschoollocation7': '',
    'text_eduschoolyear7': '',
    'text_jpschoolname3': '',
    'text_jpschoollocation3': '',
    'text_jpschoolname4': '',
    'text_jpschoollocation4': '',
    'text_employername3': '',
    'text_occupation3': '',
    'text_occupationlocation3': '',
    'text_employername4': '',
    'text_occupation4': '',
    'text_occupationlocation4': '',
    'text_entrypurpose3': '',
    'text_visastatus3': '',
    'text_entrypurpose4': '',
    'text_visastatus4': '',
    'text_entrypurpose5': '',
    'text_visastatus5': '',
    'text_entrypurpose6': '',
    'text_visastatus6': '',
    'text_entrypurpose7': '',
    'text_visastatus7': '',
    'text_entrypurpose8': '',
    'text_visastatus8': '',
    'text_entrypurpose9': '',
    'text_visastatus9': '',
    'text_entrypurpose10': '',
    'text_visastatus10': '',
    'time_relationshipbirthday4': '',
    'time_relationshipbirthday5': '',
    'time_relationshipbirthday6': '',
    'time_relationshipbirthday7': '',
    'time_relationshipbirthday8': '',
    'time_relationshipbirthday9': '',
    'time_relationshipbirthday10': '',
    'time_relationshipbirthday11': '',
    'time_eduadmission6': '',
    'time_edugraduation6': '',
    'time_eduadmission7': '',
    'time_edugraduation7': '',
    'time_jpadmission3': '',
    'time_jpgraduation3': '',
    'time_jpadmission4': '',
    'time_jpgraduation4': '',
    'time_employstart3': '',
    'time_employend3': '',
    'time_employstart4': '',
    'time_employend4': '',
    'time_prevjpentry3': '',
    'time_prevjpdeparture3': '',
    'time_prevjpentry4': '',
    'time_prevjpdeparture4': '',
    'time_prevjpentry5': '',
    'time_prevjpdeparture5': '',
    'time_prevjpentry6': '',
    'time_prevjpdeparture6': '',
    'time_prevjpentry7': '',
    'time_prevjpdeparture7': '',
    'time_prevjpentry8': '',
    'time_prevjpdeparture8': '',
    'time_prevjpentry9': '',
    'time_prevjpdeparture9': '',
    'time_prevjpentry10': '',
    'time_prevjpdeparture10': '',
  },
};

$(function () {
  $('#mainTable').editableTableWidget();
});

$(document).ready(function () {
  refreshData_003();
});

function saveData_003() {
  var id = document.getElementById('studentid').innerHTML;
  saveData(id , 3, _student_file3);
}

function refreshData_003() {
  document.getElementById('message').innerHTML = "Loading data...";

  var id = document.getElementById('studentid').innerHTML;
  postGetData(id , 3, _student_file3);
}