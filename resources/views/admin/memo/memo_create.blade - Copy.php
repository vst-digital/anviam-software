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

        <form method="POST" action="{{url('admin/save_issue')}}" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> <label class="form-control-label px-3">First name<span class="text-danger"> *</span></label> <input type="text" id="fname" name="fname" placeholder="Enter your first name" onblur="validate(1)"> </div>
                <div class="form-group col-sm-6 flex-column d-flex"> <label class="form-control-label px-3">Last name<span class="text-danger"> *</span></label> <input type="text" id="lname" name="lname" placeholder="Enter your last name" onblur="validate(2)"> </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> <label class="form-control-label px-3">Business email<span class="text-danger"> *</span></label> <input type="text" id="email" name="email" placeholder="" onblur="validate(3)"> </div>
                <div class="form-group col-sm-6 flex-column d-flex"> <label class="form-control-label px-3">Phone number<span class="text-danger"> *</span></label> <input type="text" id="mob" name="mob" placeholder="" onblur="validate(4)"> </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-sm-6 flex-column d-flex"> <label class="form-control-label px-3">Job title<span class="text-danger"> *</span></label> <input type="text" id="job" name="job" placeholder="" onblur="validate(5)"> </div>
            </div>
            <div class="row justify-content-between text-left">
                <div class="form-group col-12 flex-column d-flex"> <label class="form-control-label px-3">What would you be using Flinks for?<span class="text-danger"> *</span></label> <input type="text" id="ans" name="ans" placeholder="" onblur="validate(6)"> </div>
            </div>
            <div class="row justify-content-end">
                <div class="form-group col-sm-6"> <button type="submit" class="btn-block btn-primary">Request a demo</button> </div>
            </div>
        </form>



    <div class="testbox">
        <form method="POST" action="{{url('admin/save_issue')}}" enctype="multipart/form-data">
            @csrf
            <div class="company_info">
                <div class="row">
                </div>
                <label class="font-weight-bold">Enter Issue details below:</label>
            </div>

            <div class="colums project">
                <div class="item">
                    <select name="project_id" required class='selectproject form-control' >
                        @if(count($list_projects) > 0)
                            @foreach($list_projects as $val)
                                <option value='{{ $val->id }}'>{{ $val->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="item">
                    <input type="text" name="project_number" required placeholder="Project Number" />
                </div>
                <div class="item">
                    <input type="text" name="correspondence_no" required placeholder="Correspondence Number" />
                </div>
                <div class="item">
                    <input type="text" name="datetime" required placeholder="Date Time" class="datetimepicker"/>
                    <!-- <input type="text" name="time" required placeholder="Time" id="timepicker" class='col-md-5' style='float:right;'/> -->
                </div>
                <div class="item">
                    <!-- <input type="text" name="name" required placeholder="Name" /> -->
                    <select name="users[]" required class='selectto' multiple="multiple" >
                        @if(count($users_data) > 0)
                            @foreach($users_data as $val)
                                <option value='{{ $val->id }}'>{{ $val->first_name }} {{ $val->last_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="item">
                    <input type="text" name="subject" required placeholder="Subject" />
                </div>
                <div class="item columnlabel">
                    <label class="form-check-label checkboxlabelcls col-md-12" >Response required</label>
                    <div class="col-sm-2">
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
                    </div>
                </div>
                <div class="item attach_tag">
                    <div class="col-sm-6">
                        <label class="form-check-label col-md-12 p-0">Attachment</label>
                        <input type='file' name="attachment[]" placeholder="attachment" multiple/>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-check-label col-md-12" >Tag</label>
                        <input type='file' name="tag[]" placeholder="tag" multiple/>
                    </div>
                </div>
                <div class="item">
                    <input name="location" placeholder="Location" required>
                </div>
                <div class="item">
                    <textarea id="w3review" name="memo" rows="4" cols="50" placeholder="Enter Issue" required></textarea>
                </div>
                <div class="item">

                </div>

            </div>
            &nbsp;
            <div class="btn-block">
                <button type="submit" href="/">Create</button>
            </div>
        </form>
    </div>
</div>


<div class="overlay-spinner" style="display: none;"></div>
    <div id="wPaint" style="position:relative; width:500px; height:200px; background-color:#7a7a7a; margin:70px auto 20px auto;"></div>

    <!-- <center style="margin-bottom: 50px;"> -->
    <form id="myAwesomeForm">
         <!-- <input type="button" value="toggle menu" onclick="console.log($('#wPaint').wPaint('menuOrientation')); $('#wPaint').wPaint('menuOrientation', $('#wPaint').wPaint('menuOrientation') === 'vertical' ? 'horizontal' : 'vertical');"/> -->
        <!-- </center> -->
    </form>
        <div style="border: 1px solid;" id="small">
           <!-- <a  id="img" class="delete_img">Delete Image</a> -->
           <p id="img" class="delete_img">Delete Image</p>
           <div id="paint_img"></div>
        </div>    
      <center id="wPaint-img"></center>

<script type="text/javascript">
        var images = [
          'http://localhost/paint/test/uploads/wPaint.png',
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
                      $('#paint_img').html('<img src="http://127.0.0.1:8000/paintImage/' + resp + '" />');                      
                   var image_link = resp;
                   $("#img").attr('href',image_link);
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
        });
        
    </script>
  
@endsection



