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


<div id="page-wrapper">

<div class="header">
</div>
<div id="page-inner">
    <div class="btn_form" >
        <a href="{{ url()->previous() }}">
            <button  class="btn btn-primary float-right">Back</button>
        </a>
    </div>

    @if(session()->has('message'))
        <div class="alert alert-success">
        {{ session()->get('message') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="company_info">
                <label class="font-weight-bold">Check Issue details below:</label>
            </div>
    <div class="testbox">
        <form method="POST" id="user_thread" action="{{url('admin/reply_issue')}}" enctype="multipart/form-data">
            @csrf
            <input type='hidden' value='<?php echo $memo->id; ?>' name='memoid' />


            <div class="colums project">
                <div class="item">
                  <!--   <select name="project_id" class='selectproject form-control' disabled>
                        @if(count($list_projects) > 0)
                            @foreach($list_projects as $val)
                                <option value='{{ $val->id }}' <?php if($val->id == $memo->project_id){ echo "Selected"; } ?>>{{ $val->name }}</option>
                            @endforeach
                        @else
                            <option>No user</option>
                        @endif
                    </select> -->
                    <p><b>Project Name</b></p>
                    <p>
                        @if(count($list_projects) > 0)
                            @foreach($list_projects as $val)
                                <?php if($val->id == $memo->project_id){ echo $val->name; } ?>
                            @endforeach
                        @endif
                    </p>
                </div>
                <?php
                if($memo->response != '' && $memo->response != 'null'){
                    $response_required = json_decode($memo->response);
                }else{
                    $response_required = array();
                }
                 ?>
                <div class="item">
                    <p><b>Project Number</b></p>
                    <p>{{ $memo->project_number }}</p>
                </div>
                <div class="item">
                    <p><b>Correspondence Nnmber</b></p>
                    <p>{{ $memo->correspondence_no }}</p>
                </div>
                <div class="item">
                    <p><b>Date & Time</b></p>
                    <p>{{ $memo->datetime }}</p>
                    <!-- <input type="text" name="time" placeholder="Time" id="timepicker" class='col-md-5' style='float:right;'/> -->
                </div>
                <div class="item">
                    <p><b>To</b></p>
                    <p>
                    @if(count($users_data) > 0)
                        <?php $name = array();?>
                        @foreach($users_data as $val)
                            <?php
                            if(in_array($val->id,$memousers)){
                                array_push($name,$val->first_name.' '.$val->last_name);
                            }

                            ?>
                        @endforeach
                        <?php echo implode(',', $name); ?>
                    @endif
                    </p>
                </div>
                <div class="item">
                    <p><b>Subject</b></p>
                    <p>{{ $memo->subject }}</p>
                </div>
                <div class="item columnlabel">
                    <label class="form-check-label checkboxlabelcls col-md-12 pl-0" >Response required</label>
                    <input type="range" min="1" max="100" value="0" onchange="updateTextInput(this.value);">
                    <input type="text" name="response" id="textInput" value="" placeholder="Days">
                    <!-- <div class="col-sm-2">
                        <input name='response[]' class="form-check-input checkboxcls" type="checkbox" value="1" id = '1day' <?php //if(in_array('1',$response_required)){ echo 'checked'; } ?> disabled>
                        <label class="form-check-label checkboxlabelcls" for="1day">1 Day</label>
                    </div>
                    <div class="col-sm-2">
                        <input name='response[]' class="form-check-input checkboxcls" type="checkbox" value="2" id="2day" <?php //if(in_array('2',$response_required)){ echo 'checked'; } ?> disabled>
                        <label class="form-check-label checkboxlabelcls" for="2day">2 Days</label>
                    </div>
                    <div class="col-sm-2">
                        <input name='response[]' <?php //if(in_array('7',$response_required)){ echo 'checked'; } ?> class="form-check-input checkboxcls" type="checkbox" value="7" id="7day" disabled>
                        <label class="form-check-label checkboxlabelcls" for="7day">7 Days</label>
                    </div> -->
                </div>
                <?php
                $attachment = $tag = array();
                $attachment = json_decode($memo->attachment);
                $tag        = json_decode($memo->tag);
                ?>
                <div class="item attach_tag">
                    <div class="col-sm-6 pl-0">
                        <label class="form-check-label col-md-12 p-0">Attachment</label>
                        <!-- <input type='file' name="attachment[]" placeholder="attachment" multiple/> -->
                        @if($attachment)
                            @foreach($attachment as $val)
                            <span style='float:left;width:100px'>
                                <a href='{{ @url("/uploads/memo/attachment/$val") }}' data-fancybox-group="attachment" class='fancybox'>
                                    <img src='{{ @url("/uploads/memo/attachment/$val") }}' width='100%'/>
                                </a>
                                <!-- <a href="javascript:void(0);">Remove</a> -->
                            </span>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-sm-6 pl-0">
                        <label class="form-check-label col-md-12" >Tag</label>
                        <!-- <input type='file' name="tag[]" placeholder="tag" multiple/> -->
                        @if($tag)
                            @foreach($tag as $val)
                            <span style='float:left;width:100px'>
                                <a href='{{ @url("/uploads/memo/tag/$val") }}' data-fancybox-group="attachment" class='fancybox'>
                                    <img src='{{ @url("/uploads/memo/tag/$val") }}' width='100%'/>
                                </a>
                                <!-- <a>Remove</a> -->
                            </span>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="item col-md-6 pl-0">
                    <p><b>Location</b></p>
                    <p>{{ $memo->location }}</p>
                </div>
                <div class="item col-md-6 pl-0">
                    <p><b>PaintImage</b></p>
                    @if($memo->image)
                    <p><img src='{{ @url("/paintImage/$memo->image") }}' width='100%'/></p>
                    @endif
                </div>
                <div class="item company_memo col-md-12 pl-0">
                    <p><b>Issues</b></p>
                    <p>{!! $memo->memo !!}</p>
                </div>
            </div>
            @if($memo->memoThreads)
                @foreach($memo->memoThreads as $val)
                    <?php
                        $attachmentthread = $tag = array();
                        $attachmentthread = json_decode($val->attachment);
                        $tagthread        = json_decode($val->tag);
                    ?>
                    <div class="col-md-12 threads">
                        <strong>{{$val['memoUsers']->first_name}} {{$val['memoUsers']->last_name}}</strong>
                        <p>{{$val->memo}}</p>

                        <div class="item attach_tag">
                            <div class="col-sm-6">
                                <label class="form-check-label col-md-12 p-0">Attachment</label>
                                <!-- <input type='file' name="attachment[]" placeholder="attachment" multiple/> -->
                                @if($attachmentthread)
                                    @foreach($attachmentthread as $val)
                                    <span style='float:left;width:100px'>
                                        <a href='{{ @url("/uploads/memo/attachment/$val") }}' data-fancybox-group="attachment" class='fancybox' style='float:left;'>
                                            <img src='{{ @url("/uploads/memo/attachment/$val") }}' width='100%'/>
                                        </a>
                                        <!-- <a href="javascript:void(0);">Remove</a> -->
                                    </span>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-sm-6">
                                <label class="form-check-label col-md-12" >Tag</label>
                                <!-- <input type='file' name="tag[]" placeholder="tag" multiple/> -->
                                @if($tagthread)
                                    @foreach($tagthread as $val)
                                    <span style='float:left;width:100px'>
                                        <a href='{{ @url("/uploads/memo/tag/$val") }}' data-fancybox-group="attachment" class='fancybox' style='float:left;'>
                                            <img src='{{ @url("/uploads/memo/tag/$val") }}' width='100%'/>
                                        </a>
                                        <!-- <a>Remove</a> -->
                                    </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="col-md-12 show_all">
                <p></p>Reply
            </div>
            <div class="col-md-12 reply_thread">
                <div class="item">
                    <label class="form-check-label" >Reply Issue</label>
                    <textarea id="w3review" name="memo" rows="6" cols="50" placeholder="Enter Issue" required></textarea>
                </div>
                <div class="item attach_tag">
                    <div class="col-sm-6">
                        <label class="form-check-label col-md-12 p-0">Attachment</label>
                        <input type='file' name="attachment[]" placeholder="attachment" multiple/>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-check-label col-md-12" >Tag</label>
                        <input type='file' name="tag[]" placeholder="tag" multiple/>
                        <input type="hidden" name="image" id="image_link">
                    </div>


                    <div class="col-md-12 paintToll">                       
                        <div class="form-group flex-column d-flex paintImg_css"> 
                             <label class="form-control-label px-3">Paint Image<span class="text-danger"> *</span>
                             </label> 
                            <div class="overlay-spinner" style="display: none;"></div>
                            <div id="wPaint" class="form-control" style="position:relative; width:920px; height:400px; background-color:#7a7a7a; margin:70px auto 20px auto;">
                            </div>                    
                        </div>  
                        <div class="form-group flex-column d-flex paintImg_css"> 
                            <div id="small">                              
                                <p id="img" class="delete_img">Delete Image</p>
                                <div id="paint_img" name="image">                               
                                </div>
                            </div>    
                        <center id="wPaint-img"></center>
                        </div>
                    </div>
                                    
                    
                </div>
                
                <div class="btn-block text-right">
                    <button type="submit" class="reply_btn">Reply</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
$(document).ready(function(){
    $(".threads").click(function(){
            // $(".threads").removeClass("active");
            $(this).toggleClass("active");
    });

    $(".reply_btn").click(function(){
            $('.reply_thread').toggleClass("active");
    });

    $(".colums.project").click(function(){
            $('.colums.project').toggleClass("active");
    });

    var threadLenght = $(".threads").length;
    console.log(threadLenght);
    if(threadLenght != 0){
        $(".show_all p").text(threadLenght,);
    }else{
        $(".show_all").hide();
    }


    $(".show_all").click(function(){
        $('.threads').show(300);
        $('.show_all').hide(300);
    });

     function updateTextInput(val) {
          document.getElementById('textInput').value=val; 
    } 
});
</script>


<script type="text/javascript">
        var images = [
          'https://vstconstruction.anviam.in/paint/test/uploads/wPaint.png',
        ];
        function saveImg(image) {
            var _this = this;
            var form = document.getElementById("user_thread");
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
                      $('#paint_img').html('<img src="https://vstconstruction.anviam.in/paintImage/' + resp + '" />');                      
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
        
       
    </script>
@endsection



