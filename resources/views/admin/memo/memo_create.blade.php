@extends('layouts.admin')
@section('content')



      <script type="text/javascript" src="{{url('paint/lib/jquery.ui.core.1.10.3.min.js')}}"></script>
      <script type="text/javascript" src="{{url('paint/lib/jquery.ui.widget.1.10.3.min.js')}}"></script>
      <script type="text/javascript" src="{{url('paint/lib/jquery.ui.mouse.1.10.3.min.js')}}"></script>
      <script type="text/javascript" src="{{url('paint/lib/jquery.ui.draggable.1.10.3.min.js')}}"></script>
      
      <!-- wColorPicker -->
      <link rel="Stylesheet" type="text/css" href="{{url('paint/lib/wColorPicker.min.css')}}" />
      <script type="text/javascript" src="{{url('paint/lib/wColorPicker.min.js')}}"></script>

      <!-- wPaint -->
      <link rel="Stylesheet" type="text/css" href="{{url('paint/wPaint.min.css')}}" />
      <script type="text/javascript" src="{{url('paint/wPaint.min.js')}}"></script>
      <script type="text/javascript" src="{{url('paint/plugins/main/wPaint.menu.main.min.js')}}"></script>
      <script type="text/javascript" src="{{url('paint/plugins/text/wPaint.menu.text.min.js')}}"></script>
      <script type="text/javascript" src="{{url('paint/plugins/shapes/wPaint.menu.main.shapes.min.js')}}"></script>
      <script type="text/javascript" src="{{url('paint/plugins/file/wPaint.menu.main.file.min.js')}}"></script>

</head>
<body>
<div id="page-wrapper">

    <div class="header">
    </div>
<div id="page-inner">



    <div class="btn_form" ><a href="{{ url()->previous() }}">
        <button  class="btn btn-primary float-right">Back</button></a>
    </div>

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif

        
    <form  id="myAwesomeForm" method="POST" action="{{url('admin/save_issue')}}" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Name<span class="text-danger"> *</span></label> 
                     <select name="project_id" required class="selectproject form-control">
                        @if(count($list_projects) > 0)
                            @foreach($list_projects as $val)
                                <option value='{{ $val->id }}'>{{ $val->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Ticket No.<span class="text-danger"> *</span></label> 
                    <input type="number" name="project_number" required placeholder="Project Number" />
                </div>
            </div>

            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Correspondence Number<span class="text-danger"> *</span></label> <input type="number" name="correspondence_no" required placeholder="Correspondence Number" />                     
                </div>

                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Date Time<span class="text-danger"> *</span></label> 
                    <input type="datetime-local" id="birthdaytime" name="datetime" required>
                    <!-- <input type="text" name="datetime" id = "start_date"s required placeholder="Date Time" class="datetimepicker"/> -->

                </div>
            </div>

            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">To<span class="text-danger"> *</span></label>
                    <select name="users[]" required class='selectto form-control' multiple="multiple" >
                        @if(count($users_data) > 0)
                            @foreach($users_data as $val)
                                <option value='{{ $val->id }}'>{{ $val->first_name }} {{ $val->last_name }}</option>
                            @endforeach
                        @endif
                    </select>

                </div>

                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Subject<span class="text-danger"> *</span></label> 
                    <input type="text" name="subject" required placeholder="Subject" />
                    
                </div>
            </div>

            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex range"> 
                    <label class="form-control-label px-3">Response required<span class="text-danger"> *</span></label> 
                    <input type="range" min="1" max="100" value="1" onchange="updateTextInput(this.value);" required>
                    <input type="text" name="response" id="textInput" value="" placeholder="Days" disabled="" required>

                    
                    <!-- <div class="col-sm-2">
                        <input name='response[]' class="form-check-input checkboxcls" type="checkbox" value="1" id = '1day'>
                        <label class="form-check-label checkboxlabelcls" for="1day">1 Day</label>
                    </div>
                    <div class="col-sm-2">
                        <input name='response[]' class="form-check-input checkboxcls" type="checkbox" value="2" id="2day">
                        <label class="form-check-label checkboxlabelcls" for="2day">2 Days</label>
                    </div>
                    <div class="col-sm-2">
                        <input name='response[]' class="form-check-input checkboxcls" type="checkbox" value="7" id="7day">
                        <label class="form-check-label checkboxlabelcls" for="7day">7 Days</label>
                    </div> -->
                    <input type="hidden" name="image" id="image_link">
                </div>

                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">File<span class="text-danger"> *</span></label> 
                    <div class="row">
                    <div class="col-sm-6">
                        <label class="form-check-label col-md-12 p-0">Attachment</label>
                        <input type='file' name="attachment[]" placeholder="attachment" required="" multiple/>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-check-label col-md-12" >Tag</label>
                        <input type='file' name="tag[]" placeholder="tag" multiple/>
                    </div>
                </div>                    
                    
                </div>
            </div>

            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> 
                    <label class="form-control-label px-3">Location<span class="text-danger"> *</span></label>
                    <input name="location" placeholder="Location" required>                     
                </div>

                <div class="form-group col-sm-12 flex-column d-flex"> 
                    <label class="form-control-label px-3">Message<!-- <span class="text-danger"> *</span> --></label> 
                    <textarea id="w3review" class="ckeditor" name="memo" rows="4" cols="50" placeholder="Enter Issue" required></textarea>
                </div>
            </div>
            
            
            <div class="row justify-content-between text-left">
                <div class="form-group col-12 flex-column d-flex paintImg_css"> 
                    <label class="form-control-label px-3">Paint Image</label> 
                    <div class="overlay-spinner" style="display: none;"></div>
                    <div id="wPaint" class="form-control" style="position:relative; width:100%; height:300px; background-color:#7a7a7a; margin:70px auto 20px auto;"></div>
                    <!-- <center style="margin-bottom: 50px;"> -->
                    <!-- <form id="myAwesomeForm"> -->
                         <!-- <input type="button" value="toggle menu" onclick="console.log($('#wPaint').wPaint('menuOrientation')); $('#wPaint').wPaint('menuOrientation', $('#wPaint').wPaint('menuOrientation') === 'vertical' ? 'horizontal' : 'vertical');"/> -->
                        <!-- </center> -->
                    <!-- </form> -->
                        
                </div>
            </div>

            <div class="row justify-content-between text-left">
                <div class="form-group col-12 flex-column d-flex paintImg_css"> 
                    <div id="small">
                           <!-- <a  id="img" class="delete_img">Delete Image</a> -->
                           <p id="img" class="delete_img">Delete Image</p>
                           <div id="paint_img">
                               
                           </div>
                        </div>    
                      <center id="wPaint-img"></center>
                </div>
            </div>


            <div class="row justify-content-end">
                <div class="form-group col-sm-3"> <button type="submit"  class="btn-block btn-primary" href="/">Create</button> </div>
            </div>
        </form>
</div>




<script type="text/javascript">
        var images = [
          'https://staging.vstconstruction.anviam.in/paint/test/uploads/wPaint.png',
        ];
        function saveImg(image) {
            var _this = this;
            var form = document.getElementById("myAwesomeForm");
            var ImageURL = image;
            var block = ImageURL.split(";");
            var contentType = block[0].split(":")[1];
            var realData = block[1].split(",")[1];
            var formDataToUpload = new FormData(form);
            formDataToUpload.append("image", image);
             $(".overlay-spinner").show();
          $.ajax({
            type: 'POST',
            crossDomain: true,
            url: '{{url("admin/paint_image_uploade")}}',
            data: { _token: "{{csrf_token()}}",image: image},
            success: function (resp) {
                      $('#paint_img').html('<img src="https://staging.vstconstruction.anviam.in/paintImage/' + resp + '" />');                      
                   var image_link = resp;
                   $("#img").attr('href',image_link);
                   
                   $('#image_link').val(resp);
                   setTimeout(function() {
                            $(".overlay-spinner").hide();
                 }, 1000)
              _this._displayStatus('Image saved successfully');
              resp = $.parseJSON(resp);
              images.push(resp.img);
              $('#wPaint-img').attr('src', image);
            }
          });
        }
        function loadImgBg () {
          this._showFileModal('bg', images);
        }

        function loadImgFg () {
          this._showFileModal('fg', images);
        }

        $('#wPaint').wPaint({
          menuOffsetLeft: -35,
          menuOffsetTop: -50,
          saveImg: saveImg,
          loadImgBg: loadImgBg,
          loadImgFg: loadImgFg
        });
    $.fn.wPaint.menus.main = {
        img: './plugins/main/img/icons-menu-main.png',
        items: {
        undo: {
          icon: 'generic',
          title: 'Undo',
          index: 0,
          callback: function () { this.undo(); }
        }
      }
    }

    $.extend($.fn.wPaint.cursors, {
      pencil: 'url("./plugins/main/img/cursor-pencil.png") 0 11.99, default',
    });

    $.extend($.fn.wPaint.defaults, {
      mode:        'pencil',  
      lineWidth:   '3',       
      fillStyle:   '#FFFFFF',
      strokeStyle: '#FFFF00'  
    });

    $.fn.wPaint.extend({
      undo: function () {
      if (this.undoArray[this.undoCurrent - 1]) {
        this._setUndo(--this.undoCurrent);
      }
      this._undoToggleIcons();
      }
    });

    $(document).ready(function(){
        $(".delete_img").click(function() {
        var img_name = $('#img').attr("href");
        $(".overlay-spinner").show();
            $.ajax({
                type:'POST',
                url:'{{url("admin/deletePaintImage")}}' ,
                data:   { _token: "{{csrf_token()}}", img_name: img_name},
                success:function(data) {
                    // $("#msg").html(data.msg);
                    setTimeout(function() { 
                    $(".overlay-spinner").hide();
                    }, 1000)
                }
            });
        });
    });
 

</script>


   <script>
        $(document).ready(function(){              
          $("#img").click(function(){
            $("#small").hide();
            });
          $(".wPaint-menu-icon-img").click(function(){
            $("#small").show();
            });
        });
        // Input range value function
        function updateTextInput(val) {
          document.getElementById('textInput').value=val; 
        }        
    </script>

    <!-- <script src="{{ asset('ckeditor/ckeditor.js')}}"></script> -->

<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();


    });

        
</script>


    <script>
        $(document).ready(function(){
          $(".wPaint-menu-icon").click(function(){
            $(".delete_img").show();
          });
        });
    </script>

    <style>
        p#img {
    display: none;
}
    </style>
  
@endsection



