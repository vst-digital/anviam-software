@extends('layouts.admin')

@section('content')
	<script src="{{ asset('js/main.js') }}" ></script>
    <script src="{{ asset('js/socket.io.js') }}" ></script>
    <script src="{{ asset('js/moment.min.io.js') }}" ></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js" crossorigin="anonymous"></script> -->

    <link href="{{ asset('css/chat.css') }}" rel="stylesheet">

    <link href="{{ asset('recorder/style.css') }}" rel="stylesheet">


	<div id="page-wrapper">
        <div class="header">
        </div>

        <div id="page-inner">
            <div class="row">     
                <a href="{{url('admin/user_create')}}" class="btn btn-success btn-lg pull-right addgroup-btncss">
                  <span class="glyphicon glyphicon-plus"></span> Create Chat
                </a>       
            </div> 
            <div class="row chat-row">
                <div class='col-sm-12 col-md-3'>
                    @if(isset($users) && $users->count())
                        <ul class='list-chat list-chat-mod'>
                            @foreach($users as $user)
                            <li class="chat-user-list
                            @if(isset($friendInfo) && $friendInfo->count()) @if(@$user->id == @$friendInfo->id) active ($loop->first)@endif @endif">
                                <a href="{{ route('message.conversation', $user->id) }}">
                                <div class="chat-image">
                                    {!! makeImageFromName($user->first_name) !!}
                                    <i class="fa fa-circle user-status-icon user-icon-{{ $user->id }}" title="away"></i>
                                </div>
                                <div class="chat-name font-weight-bold">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        
                    @endif
                </div>
                <div class='col-sm-12 col-md-9'>
                    <div id="frame">
                        <div class="content">
                            @if(isset($friendInfo) && $friendInfo->count())
                                <div class="chat-section">
                                    <div class="chat-header">
                                        <div class="chat-image">
                                            {!! makeImageFromName(@$friendInfo->first_name) !!}
                                        </div>
                                        <div class="chat-name font-weight-bold">
                                            {{ @$friendInfo->first_name }} {{ @$friendInfo->last_name }}
                                        </div>
                                        <input type='hidden' value='0' class='group_id'/>

                                    </div>

                                    <div class="messages" id='message_box'>

                                        <ul id="messageWrapper">
                                            <?php
                                            //echo '<pre>';print_r($messages->toArray());die;
                                            if(count($messages) > 0){
                                                foreach($messages as $val){
                                                    $image = '';
                                                    $class='sent';
                                                    if($chattype == 'user'){
                                                        if(auth()->user()->id == $val->sender_id){
                                                            $class='replies';
                                                        }
                                                    }else{
                                                        if(auth()->user()->id != $val->sender_id){
                                                            $class='replies';
                                                        }
                                                    }
                                            ?>
                                                    <li class='<?php echo $class; ?>'>
                                                        <div class="chat-image">
                                                            {!! makeImageFromName($val->sender_users->first_name) !!}
                                                        </div>
                                                        <!-- <div class="chat-name font-weight-bold">
                                                        {{ $val->reciever_users->first_name }} {{ $val->reciever_users->last_name }}
                                                        <span class="small time text-gray-500" title="<?php echo date('Y-m-d h:i:s A',strtotime($val->created_at)); ?>">
                                                        <?php echo date('Y-m-d h:i:s A',strtotime($val->created_at)); ?>
                                                        </span>
                                                        </div> -->
                                                        <p class="file_Css">
                                                            <?php
                                                            if(@$val->message->type == 2){
                                                                $imgarr = array('jpg','jpeg','jpe','jif','jfif','jfi','png','gif','webp','tiff','tif','bmp','dib');
                                                                $ext = pathinfo($val->message->message);
                                                                //echo $ext['extension'] . '<br/>';die;
                                                                if(in_array($ext['extension'],$imgarr)){
                                                                    echo '<img src="'.@$val->message->message.'" style="width:270px;"/>';
                                                                }else if($ext['extension'] == 'pdf'){
                                                                    echo '<img src="'.@url('/images/pdf.png').'" style="width:270px;"/>';
                                                                }else if($ext['extension'] == 'wav'){
                                                                    echo '<audio controls src="'.@$val->message->message.'" style="width:270px;"/></audio>';
                                                                }else{
                                                                    echo '<img src="'.@url('/images/docs.png').'" style="width:270px;"/>';
                                                                }
                                                                echo "<br /><a href='".url('/admin/downloadfile/').'/'.$val->message->id."' target='_blank'>Download</a>";
                                                            } else{
                                                                echo @$val->message->message;
                                                            }
                                                            ?>
                                                        </p>
                                                        <div class="time file_Css">
                                                        {{ @$val->sender_users->first_name }} {{ @$val->sender_users->last_name }}
                                                        <?php echo date('Y-m-d h:i:s A',strtotime($val->created_at)); ?>
                                                        </div>

                                                    </li>
                                            <?php
                                                }
                                            }
                                            ?>  

                                        </ul>
                                       
                                         <div id='appendimage'></div>
                                          <div id="recordingsList"></div>
                                         <div id="formats"></div>

                                    </div>
                                    <div class="record_btns">
                                        <table class="recording_btn" style="display: none;">
                                                    <tr>                                                    
                                                        <td class="tool-tip"><button id="pauseButton" disabled><img src="{{url('images/pause.png')}}"></button>
                                                        <span class="tooltiptext">Pause</span>                    
                                                        </td>


                                                        <td class="tool-tip"><button id="stopButton" disabled  data-toggle="tool_tip" data-placement="left" title="Stop"><img src="{{url('images/stop.png')}}"></button>
                                                         <span class="tooltiptext">Stop</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                    </div>
                                    <div class="chat-box">
                                        <input type="text" name="" class="chat-input bg-white" id="chatInput" contenteditable="" placeholder="Write your message here">
                                        <div class="recording">
                                            <a id="recordButton" class="control_btn"><i class="fa fa-microphone" aria-hidden="true"></i></a>
                                        </div>
                                        <div class="chat-input-toolbar">
                                            <input type='file' "btn btn-light btn-sm btn-file-upload tool-items" id='inputfileupload' onClick = "document.getElementById('appendimage')" style='width: 65%;float: left;margin: 10px;'/>
                                            <i class="fa fa-paperclip"></i>
                                            <button title="Add File" onClick = "document.getElementById('appendimage').style.height = '0px';" class="btn btn-light btn-sm btn-file-upload tool-items upload_chatfile" style='margin:10px;'>
                                                Send
                                            </button>
                                            <input type='hidden' id='recordingblob' />
                                            <input type='hidden' id='recordingname' />
                                            <!-- <button style="display:none;" title="Italic" class="btn btn-light btn-sm tool-items"
                                                    onclick="document.execCommand('italic', false, '');">
                                                <i class="fa fa-italic tool-icon"></i>
                                            </button> -->
                                        </div>
                                       
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

    <script src="{{ asset('recorder/recorder.js') }}" ></script>
    <script src="{{ asset('recorder/app.js') }}" ></script>

    <script>
        $(document).ready(function(){
          $(".control_btn").click(function(){
            $(".recording_btn").show();
          });
        });
        $("#stopButton").click(function(){
            $(".recording_btn").hide();
        });
        $(".fa-times-circle-o").click(function() {  
            alert('hello');          
            $('#appendimage').html(null);
        });
    </script>

       

    <script>
        $(document).on("click",".refreshcurrent",function() {
            location.reload();
        });

        $(function (){
            let $chatInput = $(".chat-input");
            let $upload_chatfile = $(".upload_chatfile");
            let $chatInputToolbar = $(".chat-input-toolbar");
            let $chatBody = $(".chat-body");
            let $messageWrapper = $("#messageWrapper");
            let $groupname = $("#groupname");

            let user_id = "{{ auth()->user()->id }}";
            let ip_address = '65.1.255.169';
            let socket_port = '8080';
            let socket = io(ip_address + ':' + socket_port);
            let friendId = "{{ @$friendInfo->count() ? $friendInfo->id : '' }}";



            socket.on('connect', function() {
                socket.emit('user_connected', user_id);
            });

            socket.on('updateUserStatus', (data) => {
                let $userStatusIcon = $('.user-status-icon');
                $userStatusIcon.removeClass('text-success');
                $userStatusIcon.attr('title', 'Away');

                $.each(data, function (key, val) {
                    if (val !== null && val !== 0) {
                        let $userIcon = $(".user-icon-"+key);
                        $userIcon.addClass('text-success');
                        $userIcon.attr('title','Online');
                    }
                });
            });
            $upload_chatfile.click(function (e){
                let recordingurl  = $('#recordingblob').val();
                let recordingname = $('#recordingname').val();
                let file_data = $('#inputfileupload').prop('files')[0];
                document.getElementById("inputfileupload").value = "";
                document.getElementById("recordingblob").value = "";
                document.getElementById("recordingname").value = "";
                $('#recordingsList').html('');
                if(recordingurl && recordingname){
                    fetch(recordingurl)
                    .then(res => res.blob()) // Gets the response and returns it as a blob
                    .then(blob => {
                        sendMessage('',blob,'wav');
                    });
                }
                if(file_data){
                    sendMessage('',file_data);
                }
                let message = $('#chatInput').val();
                $chatInput.val("");
                if(message != ''){
                    sendMessage(message);
                }
                return false;
            });
            $chatInput.keypress(function (e) {
                let message = $('#chatInput').val();
                if (e.which === 13 && !e.shiftKey) {
                    $chatInput.val("");
                    if(message != ''){
                        sendMessage(message);
                    }
                    return false;
                }
            });

            function sendMessage(message,file='',ext='') {
                $('#cover-spin').show(0);
                let url = "{{ route('message.send-message') }}";
                let form = $(this);
                let formData = new FormData();
                let token = "{{ csrf_token() }}";
                let groupid = $('.group_id').val();
                var resmessage = '';
                formData.append('message', message);
                formData.append('file', file);
                formData.append('_token', token);
                formData.append('receiver_id', friendId);
                formData.append('group_id', groupid);
                formData.append('ext', ext);
                $('#appendimage').html('');
                $.ajax({
                   url: url,
                   type: 'POST',
                   data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'JSON',
                   success: function (response) {
                        $('#cover-spin').hide(0);
                       if (response.success) {
                            var message = getmessage(response.data);
                            appendMessageToSender(message);
                            return false;
                       }

                   },
                    error: function (textStatus, errorThrown) {
                        $('#cover-spin').hide(0);
                    }
                });
                //appendMessageToSender(message);
            }

            function appendMessageToSender(message) {
                let name = '{{ $myInfo->first_name }} {{ $myInfo->last_name }}';
                let image = '{!! makeImageFromName($myInfo->first_name) !!}';

                let userInfo = '<div class="chat-image">\n' + image +'</div>\n';

                let messageContent = '<p>\n' + message +'</p><div class="time"><strong>'+name+' &nbsp;</strong> '+getCurrentDateTime()+'"\n</div>';
                let newMessage = '<li class="sent">'
                    +userInfo + messageContent +
                    '</li>';

                $messageWrapper.append(newMessage);
                var element = document.getElementById("message_box");
                element.scrollTop = element.scrollHeight;
            }

            function appendMessageToReceiver(message) {
                let name = '{{ @$friendInfo->count() ? @$friendInfo->first_name : '' }} {{ @$friendInfo->count() ? @$friendInfo->last_name : '' }}';
                let image = '{!! makeImageFromName(@$friendInfo->count() ? @$friendInfo->first_name : '') !!}';

                let userInfo = '<div class="chat-image">\n' + image + '</div>\n';

                var messageres = getmessage(message);
                let messageContent = '<p>\n' + messageres +'</p><div class="time"><strong>'+name+' &nbsp;</strong> '+timeFormat(message.created_at)+'"\n</div>';


                let newMessage = '<li class="replies">'
                    +userInfo + messageContent +
                    '</li>';

                $messageWrapper.append(newMessage);
                var element = document.getElementById("message_box");
                element.scrollTop = element.scrollHeight;
            }

            socket.on("private-channel:App\\Events\\PrivateMessageEvent", function (message)
            {
               appendMessageToReceiver(message);
            });

        });
        var element = document.getElementById("message_box");
        element.scrollTop = element.scrollHeight;

        function getmessage(response){
            var message = '';
            if(response.file == 1){

                var filename = response.image;
                var imgarr = ['jpg','jpeg','jpe','jif','jfif','jfi','png','gif','webp','tiff','tif','bmp','dib'];
                var ext = filename.split('.').pop();

                if(imgarr.includes(ext)){
                    message = response.content;
                }else if(ext == 'pdf'){
                    message = '<img src="{{ @url("/images/pdf.png") }}" style="width:200px;"/>';
                }else if(ext == 'wav'){
                    message = response.content;
                }else{
                    message = '<img src="{{ @url("/images/docs.png") }}" style="width:200px;"/>';
                }
                message += "<br /><a href='{{ url('/admin/downloadfile/') }}/"+response.message_id+"' target='_blank'>Download</a>";
                console.log(ext);
            }else{
                message = response.content;
            }
            return message;
        }
        $("#inputfileupload").change(function () {
            const file = this.files[0];
            var ext = $(this).val().split('.').pop().toLowerCase();
            var fileExtension = ['jpg','jpeg','jpe','jif','jfif','jfi','png','gif','webp','tiff','tif','bmp','dib'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) != -1) {
                image = '<img class="reimg" src="'+URL.createObjectURL(file)+'" style="width:200px;"/><i id="clear" class="fa fa-times-circle-o" aria-hidden="true"></i>';
            }else if(ext == 'pdf'){
                image = '<img class="reimg" src="{{ @url("/images/pdf.png") }}" style="width:200px;"/><i id="clear" class="fa fa-times-circle-o" aria-hidden="true"></i>';
            }else{
                image = '<img class="reimg" src="{{ @url("/images/docs.png") }}" style="width:200px;"/><i id="clear" class="fa fa-times-circle-o" aria-hidden="true"></i>';
            }
            $('#appendimage').html(image);

            
        });
    </script>
   


    <script>
        $(document).ready(function() {          
           // $('#clear').on('click', function(e) {
            $(document).on('click','#clear',function(){
              var $el = $('#inputfileupload').val();
              $('#inputfileupload').val('');
              $('.reimg').remove();
              $('.fa-times-circle-o').remove();
           });
            
        });
    </script>


    <script type="text/javascript">        
       // $(document).on('click','.fa-times-circle-o',function(){
       //  alert("hello");   

       //  $('#appendimage').html('');
       //   });

        $(document).on('click','#inputfileupload',function(){
            $('#appendimage').show();
         });
    </script>


 


@endsection


