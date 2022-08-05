@extends('layouts.admin')
@section('content')

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    
    <!-- Styles -->
 
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css">
    <link href="{{ asset('vendor/file-manager/css/file-manager.css') }}" rel="stylesheet">


    
</head>
<div id="page-wrapper">
	<div id="page-inner">
    
        <div class="row">
            <!-- <button class="open">Open</button> -->
            <div class="col-md-12" id="fm-main-block">
                <div id="fm"></div>
            </div>

            <div class="popup-overlay file-popup">
              <!--Creates the popup content-->
              <div class="popup-content inactive">
                 <button class="close"><i class="fa fa-times" aria-hidden="true"></i></button> 
                
                <h2>User List</h2>
                <!-- <span id="getImageUrl"></span> -->
                <input type="hidden" name="url" class="getImageUrl">

                <?php
                if($users){
                    echo '<br /><fieldset style="padding:15px;"><legend></legend>';
                    echo '<ul style="float:left;padding:0px;width:100%;" class="groupusersli">';
                    foreach($users as $val){
                        echo "<li id='User_id' style='margin: 10px 0;text-decoration: none;list-style: none;padding: 15px;line-height:15px;background: #65CAEF;border-radius: 20px;color: #fff;font-size: 20px;' rel='".$val->id."'><span>$val->first_name $val->last_name</span></li>";
                    }
                    echo '</ul></fieldset>';
                }
                ?>

                <button type="button" class="btn btn-primary">Send</button>
               </div>
            </div>
        </div>
    </div>
</div>



 
  
    <!-- File manager -->
    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('fm-main-block').setAttribute('style', 'height:' + window.innerHeight + 'px');
  
        fm.$store.commit('fm/setFileCallBack', function(fileUrl) {
            $('.getImageUrl').val(fileUrl);
            window.opener.fmSetLink(fileUrl);
            window.close();
        });
      });
    </script>

    <script type="text/javascript">
        //appends an "active" class to .popup and .popup-content when the "Open" button is clicked
        $(".poupbtn").on("click", function() {
          $(".popup-overlay, .popup-content").addClass("active");
        });

        //removes the "active" class to .popup and .popup-content when the "Close" button is clicked 
        $(".close").on("click", function() {
          $(".popup-overlay, .popup-content").removeClass("active");
        });
    </script>

    <script>
          $(document).ready(function(){
           $(".poupbtn").hide();
         });

          $(document).on('click', '.unselectable', function () {
             $(".poupbtn").show();
          });
     </script>


     <script>
        $(document).ready(function(){
            $( "#User_id" ).click(function() {
             var userid = $(this).attr('rel');
             var filepath = $(".getImageUrl").val();
             
             $.ajax({
                type:'POST',
                url:'{{url("admin/filefolderpath")}}' ,
                data:  { _token: "{{csrf_token()}}", userid:userid, filepath:filepath},
                success:function(data) {
                  
                }
                });
            });
        });
     </script>

     <script>
        $(document).ready(function() {
        $("#User_id").click(function () {            
            $("#User_id").removeClass("active");
            $("#User_id").addClass("active");       
        });
        });
     </script>

     <style>
        li#User_id.active {
            background: #000 !important;
        }
        .file-popup .btn.btn-primary {
            width: 10%;
            font-size: 18px;
            text-transform: capitalize;
            float: left;
            margin: 0 20px;
        }
     </style>

     

@endsection