///Filemamager jquery
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('fm-main-block').setAttribute('style', 'height:' + window.innerHeight + 'px');

    fm.$store.commit('fm/setFileCallBack', function(fileUrl) {
        $('.getImageUrl').val(fileUrl);
        window.opener.fmSetLink(fileUrl);
        window.close();
    });
});

//appends an "active" class to .popup and .popup-content when the "Open" button is clicked
$(".poupbtn").on("click", function() {
    $(".popup-overlay, .popup-content").addClass("active");
});

//removes the "active" class to .popup and .popup-content when the "Close" button is clicked 
$(".close").on("click", function() {
    $(".popup-overlay, .popup-content").removeClass("active");
});

$(document).on('click', '.unselectable', function () {
    $(".poupbtn").show();
});

$(document).ready(function(){
    $(".poupbtn").hide();

    all_user_id = [];            
    $( ".share_button" ).click(function() {
        var userid = $(this).attr('rel');
        var filepath = $(".getImageUrl").val();
        var values = $("li.selectoption.active").map(function(){
                        return $(this).attr("rel");
                    }).get();
        if(values == ''){
            swal('Please select atleast one user!');
            return false;
        }
        if(filepath == ''){
            swal('Please select atleast one file by double click on that!');
            return false;
        }
        var url = $('#fileshareurl').val();
        var csrf = $('#csrf_token_header').val();
        $(".overlay-spinner").show();
        $.ajax({
            type:'POST',
            url:url ,
            data:  { _token: csrf, userid:values, filepath:filepath},
            success:function(data) {
                if(data){
                    $(".overlay-spinner").hide();
                    swal('Files shared successfully.');
                    $(".popup-overlay, .popup-content").removeClass("active");
                    $('.selectoption').removeClass("active");
                    $('.getImageUrl').val('');
                }
            }
        });
    });

    $(".selectoption").click(function () {
        if($(this).hasClass("active")){
            $(this).removeClass("active");    
        }else{
            $(this).addClass("active");
        }
        
    });
});