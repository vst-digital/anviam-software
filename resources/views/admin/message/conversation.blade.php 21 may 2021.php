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
                <div id="frame">
                    <div class="content">
                        <div class="chat-section">
                            <div class="chat-header">
                                @if($chattype == 'user')
                                    <div class="chat-image">
                                        {!! makeImageFromName($friendInfo->first_name) !!}
                                    </div>
                                    <div class="chat-name font-weight-bold">
                                        {{ $friendInfo->first_name }} {{ $friendInfo->last_name }}
                                    </div>
                                    <input type='hidden' value='0' class='group_id'/>
                                @else
                                    <div class="chat-image">
                                        {!! makeImageFromName($currentgrp->group_name) !!}
                                    </div>
                                    <div class="chat-name font-weight-bold">
                                        {{ $currentgrp->group_name }}
                                    </div>
                                    <input type='hidden' value='{{ $currentgrp->id }}' class='group_id'/>
                                @endif
                            </div>

                            <div class="messages" id='message_box'>
                                <ul id="messageWrapper">
                                    <?php
                                    //echo '<pre>';print_r($messages->toArray());die;
                                    if(count($messages) > 0){
                                        foreach($messages as $val){
                                            $image = '';
                                            $class='replies';
                                            if($chattype == 'user'){
                                                if(auth()->user()->id == $val->sender_id){
                                                    $class='sent';
                                                }
                                            }else{
                                                if(auth()->user()->id != $val->sender_id){
                                                    $class='sent';
                                                }
                                            }
                                    ?>
                                            <li class='<?php echo $class; ?>'>
                                                <div class="chat-image">
                                                    {!! makeImageFromName($val->reciever_users->first_name) !!}
                                                </div>
                                                <!-- <div class="chat-name font-weight-bold">
                                                {{ $val->reciever_users->first_name }} {{ $val->reciever_users->last_name }}
                                                <span class="small time text-gray-500" title="<?php echo date('Y-m-d h:i:s A',strtotime($val->created_at)); ?>">
                                                <?php echo date('Y-m-d h:i:s A',strtotime($val->created_at)); ?>
                                                </span>
                                                </div> -->
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
                                            </li>
                                    <?php
                                        }
                                    }
                                    ?>

                                </ul>
                            </div>

                            <div class="chat-box">
                                <div class="chat-input bg-white" id="chatInput" contenteditable="">

                                </div>

                                <div class="chat-input-toolbar">
                                    <input type='file' "btn btn-light btn-sm btn-file-upload tool-items" id='inputfileupload' style='width: 65%;float: left;margin: 10px;'/>
                                    <button title="Add File" class="btn btn-light btn-sm btn-file-upload tool-items upload_chatfile" style='margin:10px;'>
                                        <i class="fa fa-paperclip"></i> Upload
                                    </button>
                                    <!-- <button style="display:none;" title="Italic" class="btn btn-light btn-sm tool-items"
                                            onclick="document.execCommand('italic', false, '');">
                                        <i class="fa fa-italic tool-icon"></i>
                                    </button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
<div id="cover-spin"></div>
    <script>
        $(document).on("click",".refreshcurrent",function() {
            location.reload();
        });
        $(document).on("click",".groupli",function() {
            var groupId = $(this).attr('rel');
            $('#group_label').html($(this).text());
            $('#group_label').attr('rel',groupId);


            let url = "{{ route('group.get-users-of-group') }}";
            let form = $(this);
            let formData = new FormData();
            let token = "{{ csrf_token() }}";
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
                            if ($(".usercls"+value).length > 0) {
                                $(".usercls"+value).removeClass('fa-plus-circle');
                                $(".usercls"+value).addClass('fa-minus-circle');
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

            let url = "{{ route('group.add-user-to-group') }}";
            let form = $(this);
            let formData = new FormData();
            let token = "{{ csrf_token() }}";
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
                        form.removeClass('fa-plus-circle');
                        form.addClass('fa-minus-circle');
                        return false;
                    }else{
                        form.removeClass('fa-minus-circle');
                        form.addClass('fa-plus-circle');
                        return false;
                    }

                },
                error: function (textStatus, errorThrown) {
                    $('#cover-spin').hide(0);
                }
            });
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
            let friendId = "{{ $friendInfo->id }}";


            $(document).on("click",".addgroup",function() {

                let value = $groupname.val();
                let url = "{{ route('group.check-group') }}";
                let form = $(this);
                let formData = new FormData();
                let token = "{{ csrf_token() }}";
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
                                var link = '{{ route("message.group.conversation", ":id") }}';
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
                let file_data = $('#inputfileupload').prop('files')[0];
                if(file_data){
                    sendMessage('',file_data);
                }
                return false;
            });
            $chatInput.keypress(function (e) {
               let message = $(this).html();
               if (e.which === 13 && !e.shiftKey) {
                   $chatInput.html("");
                   sendMessage(message);
                   return false;
               }
            });

            function sendMessage(message,file='') {
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

                let userInfo = '<div class="chat-image">\n' + image +
                    '</div>\n' +
                    '\n' +
                    // '<div class="chat-name font-weight-bold">\n' +
                    // name +
                    // '<span class="small time text-gray-500" title="'+getCurrentDateTime()+'">\n' +
                    // getCurrentTime()+'</span>\n' +
                    // '</div>\n' +
                    '</div>\n';

                let messageContent = '<p>\n' + message +'</p>';
                let newMessage = '<li class="sent">'
                    +userInfo + messageContent +
                    '</li>';

                $messageWrapper.append(newMessage);
                var element = document.getElementById("message_box");
                element.scrollTop = element.scrollHeight;
            }

            function appendMessageToReceiver(message) {
                let name = '{{ $friendInfo->first_name }} {{ $friendInfo->last_name }}';
                let image = '{!! makeImageFromName($friendInfo->first_name) !!}';

                let userInfo = '<div class="chat-image">\n' + image +
                    '</div>\n' +
                    '\n' +
                    // '<div class="chat-name font-weight-bold">\n' +
                    // name +
                    // '<span class="small time text-gray-500" title="'+getCurrentDateTime()+'">\n' +
                    // getCurrentTime()+'</span>\n' +
                    // '</div>\n' +
                    '</div>\n';
                var messageres = getmessage(message);
                let messageContent = '<p>\n' + messageres +'</p>';


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
    </script>
@endsection


