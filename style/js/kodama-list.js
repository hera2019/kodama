var _kodamalist = {};

$(function () {
  _kodamalist.itemid = 0;

  $('a.list-group-item').on('click', function (e) {
    //e.preventDefault();  
    var previous = $(this).closest(".list-group").children(".active");
    previous.removeClass('active'); // previous list-item
    $(this).addClass('active');
    //$(e.target).addClass('active');

    _kodamalist.itemid = $('.list-group').find('.active').data('itemid');
    //console.log(_kodamalist.itemid);
  });  
});

function editUser()
{
  if(_kodamalist.itemid > 0)
  {
    window.location.href = "useredit.php?ID="+_kodamalist.itemid;
  }
}

function deleteUser()
{
  if(_kodamalist.itemid > 0)
  {
    var text = "You will not be able to recover this record!";
    var btntext = "Yes, delete it!";
    var url = "../user/userdelete.php?ID=" + _kodamalist.itemid;
    showConfirmMessage(text, btntext, url);
  }
}

function showConfirmMessage(text, btntext, url) {
  swal({
    title: "Are you sure?",
    text: text,
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: btntext,
    closeOnConfirm: false
  }, function () {
    window.location.href = url;
    //swal("Deleted!", "Your imaginary file has been deleted.", "success");
  });
}
