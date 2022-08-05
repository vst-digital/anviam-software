$('.selectuser').select2({placeholder: "Select an user"});
$('.selectto').select2({placeholder: "To"});
$('.selectproject').select2({placeholder: "Select Project"});
$('.selectfolder').select2({placeholder: "Select Folder"});
$('.selectMemo').select2({placeholder: "Select memo"});


$(document.body).on("change",".selectfolder",function(){
    if(this.value == 'addnew'){
        $('#modeladdfolder').modal('show');
        $('.append_error').hide();
        $('.selectfolder').val(null).trigger('change');
    }
});
$(document.body).on("keyup","#folderName",function(){
    let foldername = $(this).val();
    let url = $('#check-folder').val();
    let form = $(this);
    let formData = new FormData();
    let token = $('#csrfToken').val();
    formData.append('foldername', foldername);
    formData.append('_token', token);
    $('.append_error').hide();
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'JSON',
        success: function (response) {
            if (!response.status) {
                $('.append_error').show();
                console.log(response);
                return false;

            }
        }
    });
});
$('.addfolder').on('submit',function() {
    //console.log($('#folderName').val());
    let foldername = $('#folderName').val();
    let url = $('#add-folder').val();
    let form = $(this);
    let formData = new FormData();
    let token = $('#csrfToken').val();
    formData.append('foldername', foldername);
    formData.append('_token', token);
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'JSON',
        success: function (response) {
            if (response.status) {
                //console.log(response.data.folder);
                swal('Folder Addedd successfully!');
                $('#modeladdfolder').modal('hide');
                $('.selectfolder').prepend('<option value="'+response.data.id+'" selected="selected">'+response.data.folder+'</option>');
                $('#folderName').val('');
            }else{
                $('.append_error').show();
                return false;
            }

        }
    });
    return false;
});
$('.datepicker').datepicker({
    dateFormat: 'dd-mm-yy'
});
$("#start_date").datepicker({
    dateFormat: "dd-M-yy",
    minDate: 0,
    onSelect: function () {
        var dt2 = $('#end_date');
        var startDate = $(this).datepicker('getDate');
        //add 30 days to selected date
        startDate.setDate(startDate.getDate() + 30);
        var minDate = $(this).datepicker('getDate');
        var dt2Date = dt2.datepicker('getDate');
        //difference in days. 86400 seconds in day, 1000 ms in second
        var dateDiff = (dt2Date - minDate)/(86400 * 1000);

        //dt2 not set or dt1 date is greater than dt2 date
        if (dt2Date == null || dateDiff < 0) {
                dt2.datepicker('setDate', minDate);
        }
        //dt1 date is 30 days under dt2 date
        else if (dateDiff > 30){
                dt2.datepicker('setDate', startDate);
        }
        //sets dt2 maxDate to the last day of 30 days window
        dt2.datepicker('option', 'maxDate', startDate);
        //first day which can be selected in dt2 is selected date in dt1
        dt2.datepicker('option', 'minDate', minDate);
    }
});
$('#end_date').datepicker({
    dateFormat: "dd-M-yy",
    minDate: 0
});
$('.datetimepicker').datetimepicker();

$(function () {
    $('#eventStarts,#eventEnds').datetimepicker({
        useCurrent: false,
        minDate: moment()
    });
    $('#eventStarts').datetimepicker().on('dp.change', function (e) {
        var incrementDay = moment(new Date(e.date));
        incrementDay.add(1, 'days');
        $('#eventEnds').data('DateTimePicker').minDate(incrementDay);
        $(this).data("DateTimePicker").hide();
    });

    $('#eventEnds').datetimepicker().on('dp.change', function (e) {
        var decrementDay = moment(new Date(e.date));
        decrementDay.subtract(1, 'days');
        $('#eventStarts').data('DateTimePicker').maxDate(decrementDay);
         $(this).data("DateTimePicker").hide();
    });

});
$('.fancybox').fancybox({
    'centerOnScroll': false,
    'padding': 0,
    beforeShow: function(){
        $("body").css({'overflow-y':'hidden'});
    },
    afterClose: function(){
        $("body").css({'overflow-y':'visible'});
    },
});
$('.delete-confirm').on('click', function (event) {
    event.preventDefault();
    const url = $(this).attr('href');
    swal({
        title: 'Are you sure?',
        text: 'This record and it`s details will be permanently deleted!',
        icon: 'warning',
        buttons: ["Cancel", "Yes!"],
    }).then(function(value) {
        if (value) {
            window.location.href = url;
        }
    });
});

$(document).on('click', '.editprojectType', function() {
    var projectype = $(this).data('id');
    let url = $('#getprojecttype').val();
    console.log(url);
    let form = $(this);
    let formData = new FormData();
    let token = $('#csrfToken').val();
    formData.append('projecTypeId', projectype);
    formData.append('_token', token);
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'JSON',
        success: function (response) {
            if (response.status) {
                $('.edit_id').val(response.data.id);
                $('.projecttypename').val(response.data.name);
                $("projecttypestatus").val(response.data.status).trigger();
                $(".getprojecttypestatus").val(response.data.status);
                $('.projecttypedes').val(response.data.description);
            }

        }
    });
});

$(document).on('click', '.editRoles', function() {
    var roleId = $(this).data('id');
    let url = $('#getroleroute').val();
    console.log(url);
    let form = $(this);
    let formData = new FormData();
    let token = $('#csrfToken').val();
    formData.append('roleId', roleId);
    formData.append('_token', token);
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'JSON',
        success: function (response) {
            if (response.status) {
                $('#roleId').val(response.data.id);
                $('#roleName').val(response.data.name);
                $('#summary-ckeditor').val(response.data.description);
            }

        }
    });
});

$(document).ready(function() {
    // $.noConflict();
    $("#example").DataTable();
});
$('.editroleform').on('submit',function() {
    if($('#roleName').val().trim()){
        return true;
    }
    $('#roleName').val('');
    return false;
});
$('.addroleform').on('submit',function() {
    if($('#roleName1').val().trim()){
        return true;
    }
    $('#roleName1').val('');
    return false;
});

let $groupname = $("#groupname");
$(document).on("click",".addgroup",function() {

    let value = $groupname.val();
    let url = $("#check_group_route").val();
    let form = $(this);
    let formData = new FormData();
    let token = $("#csrf_token_header").val();
    let user_id = $("#loggedin_user").val();
    var resmessage = '';
    formData.append('group', value);
    formData.append('_token', token);
    formData.append('user_id', user_id);
    if(value != ''){
        $('#cover-spin').show(0);
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            success: function (response) {
                $('#cover-spin').hide(0);
                if (response.status) {
                    var html = "<li class='groupli' style='margin: 10px 0;text-decoration: none;list-style: none;padding: 15px;line-height:15px;cursor:pointer;background: #65CAEF;border-radius: 20px;color: #fff;font-size: 20px;' rel='"+response.data.id+"'>"+response.data.group_name+"</li>";
                    $('.ulgroupappend').append(html);
                    $('#group_label').html(response.data.group_name);
                    $('#group_label').attr('rel',response.data.id);

                    $('#groupmemberModal').modal('show');
                    $('#exampleModal').modal('hide');

                    if ($(".grouplableli").length == 0) {
                        var grouplabelul = "<li class='grouplableli'><h3 style='border-bottom: 1px solid;'>Groups</h3></li>";
                        $('.grouplabelul').append(grouplabelul);
                    }

                    var groupname=response.data.group_name;

                    var image = '{!! makeImageFromName("groupname") !!}';
                    var link = $("#message_group_conversation_route").val();
                    var url = link.replace(':id', response.data.id);

                    var groupli = '<li class="chat-user-list"><a href="'+url+'"><div class="chat-image">'+image+'</div><div class="chat-name font-weight-bold">'+groupname+'</div></a></li>';
                    $('.grouplabelul').append(groupli);

                    return false;
                }else{
                    $('.groupexists').show();
                    $('.groupvalidation').hide();
                    return false;
                }

            },
            error: function (textStatus, errorThrown) {
                $('#cover-spin').hide(0);
            }
        });
    }else{
        $('.groupvalidation').show();
        $('.groupexists').hide();
    }
    return false;
});


$(document).on("click",".refreshcurrent",function() {
    location.reload();
});
$(document).on("click",".groupli",function() {
    var groupId = $(this).attr('rel');
    $('#group_label').html($(this).find('.chat-name').text());
    $('#group_label').attr('rel',groupId);

    let url = $("#get_user_group_route").val();
    let form = $(this);
    let formData = new FormData();
    let token = $("#csrf_token_header").val();
    formData.append('groupId', groupId);
    formData.append('_token', token);
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'JSON',
        success: function (response) {
            if (response.status) {
                var userids = response.data;
                $(userids).each(function(kye,value){
                    console.log(value);
                    //alert("usercls"+value);
                    if ($(".usercls"+value).length > 0) {
                        $("select option[value="+value+"]").attr("selected","selected");
                        $('.chosen-drop').find("usercls"+value).addClass("result-selected");
                        // $(".usercls"+value).removeClass('fa-plus-circle');
                        // $(".usercls"+value).attr('fa-minus-circle');
                    }

                });
            }

        }
    });


    $('#groupmemberModal').modal('show');
    $('#exampleModal').modal('hide');
});

$(document).on("click",".adduser",function() {
    $('#cover-spin').show(0);
    var groupId     = $('#group_label').attr('rel');
    var userId     = $(this).parent().attr('rel');
    var grouplabel  = $(this).prev().text();

    let url = $("#add_user_to_group_route").val();//"{{ route('group.add-user-to-group') }}";
    let form = $(this);
    let formData = new FormData();
    let token = $("#csrf_token_header").val();
    formData.append('groupId', groupId);
    formData.append('_token', token);
    formData.append('user_id', userId);
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'JSON',
        success: function (response) {
            $('#cover-spin').hide(0);
            if (response.status) {
                $("select option[value="+value+"]").attr("selected","selected");
                $('.chosen-drop').find("usercls"+value).addClass("result-selected");
                // form.removeClass('icon-check-empty');
                // form.addClass('icon-check-sign');
                return false;
            }else{
                $("select option[value="+value+"]").attr("selected",null);
                  $('.chosen-drop').find("usercls"+value).removeClass("result-selected");
                form.removeClass('icon-check-sign');
                form.addClass('icon-check-empty');
                return false;
            }
        },
        error: function (textStatus, errorThrown) {
            $('#cover-spin').hide(0);
        }
    });
});

$(document).on("click",".addGroupMember",function() {
    $('#cover-spin').show(0);
    var groupId     = $('#group_label').attr('rel');
    var userId     = $(this).parent().attr('rel');
    var grouplabel  = $(this).prev().text();
    var selectUsers = $(".selectUsers").val();
     //alert(selectUsers);
    let url = $("#add_user_to_group_route").val();//"{{ route('group.add-user-to-group') }}";
    let form = $(this);
    let formData = new FormData();
    let token = $("#csrf_token_header").val();
    formData.append('groupId', groupId);
    formData.append('_token', token);
    formData.append('user_id', selectUsers);
    // formData.append('user_id', userId);
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'JSON',
        success: function (response) {
            $('#cover-spin').hide(0);
            if (response.status) {
                $("select option[value="+value+"]").attr("selected","selected");
                $('.chosen-drop').find("usercls"+value).addClass("result-selected");
                // form.removeClass('icon-check-empty');
                // form.addClass('icon-check-sign');
                return false;
            }else{
                $("select option[value="+value+"]").attr("selected",null);
                  $('.chosen-drop').find("usercls"+value).removeClass("result-selected");
                form.removeClass('icon-check-sign');
                form.addClass('icon-check-empty');
                return false;
            }
        },
        error: function (textStatus, errorThrown) {
            $('#cover-spin').hide(0);
        }
    });
     $('#groupmemberModal').modal('show');
    $('#exampleModal').modal('hide');
});
//$(".storage-dropdown").slideUp();
$(".dropdown_vst").on('click',function(){
    $(this).next(".dropdown_vst_sub").slideToggle();
});

Dropzone.autoDiscover = false;
//$(function () {
    var currentFile = null;
    var fileList = new Array;
    var fileData = new Array;
    var i = 0;

    let token = $('#csrfToken').val();
    var url = $('#formuploadurl').val();
    var rmurl = $('#rmurl').val();
    var getAttachments = $('#getAttachments').val();
    var myDropzone = new Dropzone("div#myDropzone", {
        url: url,
        addRemoveLinks: true,
        params: {
            _token: token
        },
        init: function() {
            $.get(getAttachments, function(data) {
                $.each(data, function(key,value){

                    var mockFile = { name: value.name, size: value.size };

                    myDropzone.options.addedfile.call(myDropzone, mockFile);

                    myDropzone.options.thumbnail.call(myDropzone, mockFile, value.fullimage);
                    myDropzone.emit("complete", mockFile);
                });

            });
            this.on("success", function(file, serverFileName) {
                fileList[i] = {"serverFileName" : serverFileName, "fileName" : file.name,"fileId" : i };
                fileData[i] = serverFileName;
                //console.log(fileList);
                i++;
                $('#dropzoneimage').val(fileData.join(','));
            });

            this.on("removedfile", function(file) {
                var rmvFile = "";
                for(f=0;f<fileList.length;f++){
                    if(fileList[f].fileName == file.name)
                    {
                        rmvFile = fileList[f].serverFileName;
                    }
                }
                if (rmvFile){
                    $.ajax({
                        url: rmurl,
                        type: "POST",
                        data: { "fileList" : rmvFile,_token: token }
                    });
                }
            });
        },
    });

    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).val()).select();
        document.execCommand("copy");
        $temp.remove();
      }
//});

