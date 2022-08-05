<body>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <?php
                if(auth()->user()->role==2) { ?>
                    <a class="navbar-brand" href="{{url('/admin/dashboard')}}"><img src="{{url('/admincss/images/logo.png')}}"></i> <span class="logo_title"> Construction</span></a>
                <?php
                }else if(auth()->user()->role==1) { ?>
                    <a class="navbar-brand" href="{{url('/admin/companies')}}"><img src="{{url('/admincss/images/logo.png')}}"></i> <span class="logo_title"> Construction</span></a>
                <?php
                }else{ ?>
                    <a class="navbar-brand" href="{{url('/admin/project_list')}}"><img src="{{url('/admincss/images/logo.png')}}"></i> <span class="logo_title"> Construction</span></a>
                <?php
                } ?>


            </div>


            

            <ul class="nav navbar-top-links navbar-right">

                @if(count($user_projects)>0)
                 <li>
                  <?php  $project_id = Session::get('project_id'); ?>
                    <select class="nav ProjectsCss">
                            <option name="name" value="">Projects</option>
                            @foreach($user_projects as $val)
                            <option name ="project_id" value="{{$val->id}}" <?php if($val->id == $project_id){ echo "Selected"; } ?>> {{ $val->name}}</option>
                            @endforeach
                    </select>
                </li>
                @endif
                <li>
                    <a style='color:#cecece;' href="{{ url('admin/calendar') }}"><i class="fa fa-calendar"></i><span style='color:#cecece;'> Calendar</span></a>
                </li>

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i><span style='color:#cecece;'>{{Auth::user()->first_name}} {{Auth::user()->last_name}}</span> &nbsp;<i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                       <!--  <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li> -->
                       <!--  <li class="divider"></li> -->
                        <li><a href="{{route('logout')}}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
        </nav>
<input type="hidden" value='{{ auth()->user()->id }}' id = 'loggedin_user'/>
<input type="hidden" value='{{ csrf_token() }}' id = 'csrf_token_header'/>
<input type="hidden" value="{{ route('group.get-users-of-group') }}" id = 'get_user_group_route'/>
<input type="hidden" value="{{ route('group.add-user-to-group') }}" id = 'add_user_to_group_route'/>
<input type="hidden" value="{{ route('group.check-group') }}" id = 'check_group_route'/>
<input type="hidden" value='{{ route("message.group.conversation", ":id") }}' id = 'message_group_conversation_route'/>


<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.ProjectsCss').on('change', function() {
            var project_id = $(this).val();
            $.ajax({
                type:'POST',
                url:'{{url("admin/projectsAll")}}',
                data:   {_token:"{{ csrf_token() }}",project_id:project_id},
                success:function(data) {
                  location.reload();
                }
            });
    });

        $('.navbar-toggle').on("click", function(){
            $('#page-wrapper').toggleClass('toggle_css');
            $('.navbar-side').toggleClass('navbar-side-active');
        });
  });

</script>

<style type="text/css">
    #page-wrapper.toggle_css{
        margin-left: 0;
    }
    .navbar-side.navbar-side-active{
        width: 0;
        display: none;
    }

</style>
