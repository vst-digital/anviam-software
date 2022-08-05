@extends('layouts.admin')

@section('content')
	<script src="{{ asset('js/main.js') }}" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js" crossorigin="anonymous"></script>

    <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
	<div id="page-wrapper">
        <div class="header">
        </div>
        <div id="page-inner">
            <div class="row chat-row">
                <div class='col-md-3'>
                    @if(isset($chatgroup) && $chatgroup->count())
                        <ul class='list-chat list-chat-mod'>
                            @foreach(@$chatgroup as $chat)
                                @if(@$chattype == 'group')
                                    <li class="chat-user-list  @if($chat->chatgroup[0]->id == @$currentgrp->id) active @endif">
                                @else
                                    <li class="chat-user-list">
                                @endif
                                    <a href="{{ route('message.group.conversation', $chat->chatgroup[0]->id) }}">
                                    <div class="chat-image">
                                        {!! makeImageFromName($chat->chatgroup[0]->group_name) !!}
                                    </div>
                                    <div class="chat-name font-weight-bold">
                                        {{ $chat->chatgroup[0]->group_name }}
                                    </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                @if($currentgrp)
                    <div class='col-md-9'>
                        <div id="frame">
                            <div class="content">
                                <div class="chat-section">


                                    @if(@$currentgrp)
                                        <div class="chat-header groupli" rel='{{ @$currentgrp->id }}' style='cursor:pointer;'>
                                            @if(@$currentgrp)
                                                <div class="chat-image">
                                                    {!! makeImageFromName($currentgrp->group_name) !!}
                                                </div>
                                                <div class="chat-name font-weight-bold">
                                                    {{ $currentgrp->group_name }}
                                                </div>
                                                <input type='hidden' value='{{ $currentgrp->id }}' class='group_id'/>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="messages" id='message_box'>
                                        <ul id="messageWrapper">
                                            <?php
                                            if(count($messages) > 0){
                                                foreach($messages as $val){
                                                    $image = '';
                                                    $class='sent';
                                                    if($chattype == 'user'){
                                                        if(auth()->user()->id == $val->sender_id){
                                                            $class='sent';
                                                        }
                                                    }else{
                                                        if(auth()->user()->id != $val->sender_id){
                                                            $class='replies';
                                                        }
                                                    }
                                                    ?>
                                                    <li class='<?php echo $class; ?>'>
                                                        <div class="chat-image">
                                                        @if(@$val->sender_users->first_name)
                                                            {!! makeImageFromName($val->sender_users->first_name) !!}
                                                        @else
                                                            {!! makeImageFromName('U N') !!}
                                                        @endif
                                                        </div>
                                                        <p>
                                                            <?php
                                                            if(@$val->message->type == 2){
                                                                $imgarr = array('jpg','jpeg','jpe','jif','jfif','jfi','png','gif','webp','tiff','tif','bmp','dib');
                                                                $ext = pathinfo($val->message->message);
                                                                //echo $ext['extension'] . '<br/>';die;
                                                                if(in_array($ext['extension'],$imgarr)){
                                                                    echo '<img src="'.@$val->message->message.'" style="width:200px;"/>';
                                                                }else if($ext['extension'] == 'pdf'){
                                                                    echo '<img src="'.@url('/images/pdf.png').'" style="width:200px;"/>';
                                                                }else{
                                                                    echo '<img src="'.@url('/images/docs.png').'" style="width:200px;"/>';
                                                                }
                                                                echo "<br /><a href='".url('/admin/downloadfile/').'/'.$val->message->id."' target='_blank'>Download</a>";
                                                            } else{
                                                                echo @$val->message->message;
                                                            }
                                                            ?>
                                                        </p>
                                                        <div class="time">
                                                        <strong>
                                                        {{ @$val->sender_users->first_name }} {{ @$val->sender_users->last_name }}
                                                        </strong>&nbsp; <?php echo date('Y-m-d h:i A',strtotime($val->created_at)); ?>
                                                        </div>
                                                    </li>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                        <div class="chat-box">
                                        <input type="text" name="" class="chat-input bg-white" id="chatInput" contenteditable="" placeholder="Write your message here">
                                        <div class="chat-input-toolbar">
                                            <input type='file' class="btn-file-upload tool-items" id='inputfileupload' style='width: 65%;float: left;margin: 10px;'/>
                                            <i class="fa fa-paperclip"></i>
                                            <button title="Add File" class="btn btn-light btn-sm btn-file-upload tool-items upload_chatfile" style='margin:10px;'>
                                                Send
                                            </button>
                                        </div>
                                        <div id='appendimage'></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif
            </div>
        </div>


<!-- Modal -->
<div id="exampleModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <a type="button" class="close" data-dismiss="modal">&times;</a>
        <h4 class="modal-title">Add Group</h4>
      </div>
      <div class="modal-body">
        <span style='display:none;color:#ff0000;' class='groupexists'>Group already exists.</span>
        <span style='display:none;color:#ff0000;' class='groupvalidation'>Please enter groupname.</span>
        <input type='text' name='group_name' id = 'groupname' />
        <button class='btn addgroup'>Add Group</button>
        <div class='row'>
            <?php
            if($chatgroup){
                echo '<br /><fieldset style="padding:15px;"><legend>Groups:</legend>';
                echo '<ul style="float:left;padding:0px;width:100%;" class="ulgroupappend">';
                foreach($chatgroup as $val){
                    echo "<li class='groupli' style='margin: 10px 0;text-decoration: none;list-style: none;padding: 15px;line-height:15px;cursor:pointer;background: #65CAEF;border-radius: 20px;color: #fff;font-size: 20px;' rel='".$val->chatgroup[0]->id."'>".$val->chatgroup[0]->group_name."</li>";
                }
                echo '</ul></fieldset>';
            }
            ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="groupmemberModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <a type="button" class="close  refreshcurrent" data-dismiss="modal">&times;</a>
        <h4 class="modal-title">Add members</h4>
      </div>
      <div class="modal-body">
        <label id = 'group_label' style='font-size:25px;'></label>
        <div class='row'>
            <?php
            if($users){
                echo '<br /><fieldset style="padding:15px;"><legend>Users:</legend>';
                echo '<ul style="float:left;padding:0px;width:100%;" class="groupusersli">';
                foreach($users as $val){
                    echo "<li style='margin: 10px 0;text-decoration: none;list-style: none;padding: 15px;line-height:15px;background: #65CAEF;border-radius: 20px;color: #fff;font-size: 20px;' rel='".$val->id."'><span>$val->first_name $val->last_name</span> <i style='float:right;cursor:pointer;' class='fa fa-plus-circle adduser usercls".$val->id."'></i></li>";
                }
                echo '</ul></fieldset>';
            }
            ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default refreshcurrent" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

    <script>
        $(function (){
            let $chatInput = $(".chat-input");
            let $upload_chatfile = $(".upload_chatfile");
            let $chatInputToolbar = $(".chat-input-toolbar");
            let $chatBody = $(".chat-body");
            let $messageWrapper = $("#messageWrapper");
            let $groupname = $("#groupname");

            let user_id = "{{ auth()->user()->id }}";
            let group_id = "{{ @$currentgrp->id }}";
            let ip_address = '65.1.255.169';
            let socket_port = '8080';
            let socket = io(ip_address + ':' + socket_port);
            let groupId = "{{ @$currentgrp->id }}";
            let groupName = "{{ @$currentgrp->group_name }}";

            socket.on('connect', function() {
                let data = {group_id:group_id, user_id:user_id, room:"group"+group_id };
                socket.emit('user_connected', user_id);
                socket.emit('joinGroup', data);
            });

            $upload_chatfile.click(function (e){
                let file_data = $('#inputfileupload').prop('files')[0];
                document.getElementById("inputfileupload").value = "";
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

            function sendMessage(message,file='') {
                $('#cover-spin').show(0);
                let url = "{{ route('message.send-group-message') }}";
                let form = $(this);
                let formData = new FormData();
                let token = "{{ csrf_token() }}";
                let groupid = $('.group_id').val();
                var resmessage = '';
                formData.append('message', message);
                formData.append('file', file);
                formData.append('_token', token);
                //formData.append('receiver_id', friendId);
                formData.append('group_id', groupid);
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
                var userid = "{{ auth()->user()->id }}";
                let name = message.sender_name;
                let image = '{!! makeImageFromName(@$currentgrp->group_name) !!}';

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

            socket.on("groupMessage", function (message)
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
                }else{
                    message = '<img src="{{ @url("/images/docs.png") }}" style="width:200px;"/>';
                }
                message += "<br /><a href='{{ url('/admin/downloadfile/') }}/"+response.message_id+"' target='_blank'>Download</a>";
                //console.log(ext);
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
                image = '<img src="'+URL.createObjectURL(file)+'" style="width:200px;"/>';
            }else if(ext == 'pdf'){
                image = '<img src="{{ @url("/images/pdf.png") }}" style="width:200px;"/>';
            }else{
                image = '<img src="{{ @url("/images/docs.png") }}" style="width:200px;"/>';
            }
            $('#appendimage').html(image);
        });
    </script>
@endsection


