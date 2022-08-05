<?php  
    $permissions_id =  auth()->user()->permissions->pluck('module_id')->toArray();
    $admin = array(1,2,3,4);
    $communication = array(7,8,9);
    $show_admin = array_intersect($permissions_id,$admin);
    $show_communication = array_intersect($permissions_id,$communication);
?>
<nav id="sidebar-collapse" class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
             @if(auth()->user()->role==1) 
                <li>
                    <a href="{{ url('admin/companies') }}"><i class="fa fa-users"></i> Companies</a>
                </li>
                <li>
                    <a href="{{ url('admin/add_companies') }}"><i class="fa fa-qrcode"></i> Add Company</a>
                </li>
            @endif

            @if(auth()->user()->role==2) 
                <li>
                    <a class="{{ request()->is('admin/dashboard') ? 'active-menu' : '' }}" href="{{url('/admin/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard </a>
                </li>
                <li class='dropdown_vst_li'>
                    <?php
                        $style='style="display:none;"';
                        $class = '';
                        if(request()->is('admin/user') || request()->is('admin/user_create') || request()->is('admin/view_role_user/*') || request()->is('admin/edit_user/*') || request()->is('admin/user')){
                        $style='style="display:block;"';
                        $class = 'active-menu';
                        }else if(request()->is('admin/role')){
                        $style='style="display:block;"';
                        $class = 'active-menu';
                        }else if(request()->is('admin/project_list') || request()->is('admin/project_create') || request()->is('admin/edit_project/*') || request()->is('admin/project_list')){
                        $style='style="display:block;"';
                        $class = 'active-menu';
                        }else if(request()->is('admin/project_type')){
                        $style='style="display:block;"';
                        $class = 'active-menu';
                        }
                    ?>
                    <a href="javascript:void(0)" class="<?php echo $class; ?> dropdown_vst"><i class="fa fa-users"></i> Admin  <span class="caret"></span></a>
                    <ul class="dropdown_vst_sub" <?php echo $style; ?>>
                      <li>
                            <a class="{{ request()->is('admin/role') ? 'active-menu' : '' }}" href="{{ url('admin/role') }}"><i class="fa fa-qrcode"></i> Roles </a>
                        </li>
                        <li>
                            <a class="{{ request()->is('admin/user') ? 'active-menu' : '' }} {{ request()->is('admin/user_create') ? 'active-menu' : '' }}  {{ request()->is('admin/view_role_user/*') ? 'active-menu' : '' }} {{ request()->is('admin/edit_user/*') ? 'active-menu' : '' }}" href="{{ url('admin/user') }}"><i class="fa fa-users"></i> Users </a>
                        </li>
                        <li>
                             <a class="{{ request()->is('admin/project_type') ? 'active-menu' : '' }}" href="{{ url('admin/project_type') }}"><i class="fa fa-qrcode"></i>Project Types</a>
                        </li>
                        <li>
                            <a class="{{ request()->is('admin/project_list') ? 'active-menu' : '' }} {{ request()->is('admin/project_create') ? 'active-menu' : '' }} {{ request()->is('admin/edit_project/*') ? 'active-menu' : '' }}" href="{{ url('admin/project_list') }}"><i class="fa fa-tasks"></i>Project</a>
                        </li>
                    </ul>
                </li> 
                <li class='dropdown_vst_li'>
                    <?php
                        $style='style="display:none;"';
                        $class = '';
                        if(request()->is('admin/document_storage') || request()->is('admin/storage_list_documents')){
                        $style='style="display:block;"';
                        $class = 'active-menu';
                        }else if(request()->is('admin/document_storage')){
                        $style='style="display:block;"';
                        $class = 'active-menu';
                        }else if(request()->is('admin/storage_list_documents')){
                        $style='style="display:block;"';
                        $class = 'active-menu';
                        }else if(request()->is('admin/upload_document')){
                        $style='style="display:block;"';
                        $class = 'active-menu';
                        }
                    ?>
                    <a href="{{URL::to('admin/upload_document')}}" class="<?php echo $class; ?>"><i class="fa fa-users"></i> Document Control  </a>
                </li> 
                 <li>
                    <?php
                    $style='style="display:none;"';
                    $class = '';
                    if(request()->is('admin/department') || request()->is('admin/department')){
                    $style='style="display:block;"';
                    $class = 'active-menu';
                    }
                    ?>
                    <a href="{{URL::to('admin/department')}}" class="<?php echo $class; ?>"><i class="fa fa-users"></i> Department  </a>
                </li>
                <li class='dropdown_vst_li'>
                    <?php
                    $style='style="display:none;"';
                    $class = '';
                    if(request()->is('admin/groupconversation') || request()->is('admin/groupconversation/*') || request()->is('admin/conversation/*') || request()->is('admin/conversation') || request()->is('admin/issue_list') || request()->is('admin/edit_issue/*') || request()->is('admin/issue_create') || request()->is('admin/check_issue/*') ){
                    $style='style="display:block;"';
                    $class = 'active-menu';
                    }
                    ?>
                    <a href="javascript:void(0)" class="<?php echo $class; ?> dropdown_vst"><i class="fa fa-comments"></i> Communication   <span class="caret"></span> </a>
                    <ul class="dropdown_vst_sub" <?php echo $style; ?>>
                    <li class='dropdown_vst_li'>
                    <a href="javascript:void(0)" class="<?php echo $class; ?> dropdown_vst"><i class="fa fa-comment"></i> Chats <span class="caret"></span> </a>
                        <ul class="dropdown_vst_sub" <?php echo $style; ?>>
                            <li>
                                <a href="{{ route('message.group.conversation') }}" class="{{ request()->is('admin/groupconversation') ? 'active-menu' : '' }} {{ request()->is('admin/groupconversation/*') ? 'active-menu' : '' }}"><i class="fa fa-comments"></i> Group Chat</a> <i class="fa fa-plus float-right" style='cursor:pointer;margin:2px 0 0; color: #fff;position: absolute;right: 13px;top: 51px;' data-toggle="modal" data-target="#exampleModal"></i>
                            </li>
                            <li>
                                <a href="{{ route('message.conversation') }}" class="{{ request()->is('admin/conversation') ? 'active-menu' : '' }} {{ request()->is('admin/conversation/*') ? 'active-menu' : '' }}"><i class="fa fa-comment"></i> individual chats </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                         <a class="{{ request()->is('admin/issue_create') ? 'active-menu' : '' }} {{ request()->is('admin/edit_issue/*') ? 'active-menu' : '' }} {{ request()->is('admin/issue_list') ? 'active-menu' : '' }} {{ request()->is('admin/check_issue/*') ? 'active-menu' : '' }}" href="{{ url('admin/issue_list') }}"><i class="fa fa-envelope"></i> Issues  </a>
                    </li>
                </li> 
            @endif 

            @if(auth()->user()->role==3)
            <li>
                    <a class="{{ request()->is('admin/dashboard') ? 'active-menu' : '' }}" href="{{url('/admin/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard </a>
            </li>
            <li>
              <a class="{{ request()->is('admin/calendar') ? 'active-menu' : '' }}" href="{{ url('admin/calendar') }}"><i class="fa fa-calendar"></i>Calendar</a>
            </li> 
            <li class='dropdown_vst_li'>
                <?php
                    $style='style="display:none;"';
                    $class = '';
                    if(request()->is('admin/user') || request()->is('admin/user_create') || request()->is('admin/view_role_user/*') || request()->is('admin/edit_user/*') || request()->is('admin/user')){
                    $style='style="display:block;"';
                    $class = 'active-menu';
                    }else if(request()->is('admin/role')){
                    $style='style="display:block;"';
                    $class = 'active-menu';
                    }else if(request()->is('admin/project_list') || request()->is('admin/project_create') || request()->is('admin/edit_project/*') || request()->is('admin/project_list')){
                    $style='style="display:block;"';
                    $class = 'active-menu';
                    }else if(request()->is('admin/project_type')){
                    $style='style="display:block;"';
                    $class = 'active-menu';
                    }
                ?>
                @if( $show_admin)
                    <a href="javascript:void(0)" class="<?php echo $class; ?> dropdown_vst"><i class="fa fa-users"></i> Admin  <span class="caret"></span></a>
                   
                    <ul class="dropdown_vst_sub" <?php echo $style; ?>>
                        @foreach(auth()->user()->permissions as $permissions)
                            @if($permissions->module_id== 1)
                                <li>
                                <a class="{{ request()->is('admin/role') ? 'active-menu' : '' }}" href="{{ url('admin/role') }}"><i class="fa fa-qrcode"></i> Roles </a>
                                </li>
                            @endif
                            @if($permissions->module_id== 2)
                                <li>
                                <a class="{{ request()->is('admin/user') ? 'active-menu' : '' }} {{ request()->is('admin/user_create') ? 'active-menu' : '' }}  {{ request()->is('admin/view_role_user/*') ? 'active-menu' : '' }} {{ request()->is('admin/edit_user/*') ? 'active-menu' : '' }}" href="{{ url('admin/user') }}"><i class="fa fa-users"></i> Users </a>
                                </li>
                            @endif
                            @if($permissions->module_id== 3)
                                <li>
                                <a class="{{ request()->is('admin/project_type') ? 'active-menu' : '' }}" href="{{ url('admin/project_type') }}"><i class="fa fa-qrcode"></i>Project Types</a>
                                </li>
                            @endif
                            @if($permissions->module_id== 4)
                                <li>
                                <a class="{{ request()->is('admin/project_list') ? 'active-menu' : '' }} {{ request()->is('admin/project_create') ? 'active-menu' : '' }} {{ request()->is('admin/edit_project/*') ? 'active-menu' : '' }}" href="{{ url('admin/project_list') }}"><i class="fa fa-tasks"></i>Project</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif  
            </li> 
            <li class='dropdown_vst_li'>
                <?php
                    $style='style="display:none;"';
                    $class = '';
                    if(request()->is('admin/document_storage') || request()->is('admin/storage_list_documents')){
                    $style='style="display:block;"';
                    $class = 'active-menu';
                    }else if(request()->is('admin/document_storage')){
                    $style='style="display:block;"';
                    $class = 'active-menu';
                    }else if(request()->is('admin/storage_list_documents')){
                    $style='style="display:block;"';
                    $class = 'active-menu';
                    }else if(request()->is('admin/upload_document')){
                    $style='style="display:block;"';
                    $class = 'active-menu';
                    }
                ?>
                @foreach(auth()->user()->permissions as $permissions)
                    @if($permissions->module_id== 5)
                        <a href="{{URL::to('admin/upload_document')}}" class="<?php echo $class; ?>"><i class="fa fa-users"></i> Document Control  </a>
                    @endif
                @endforeach
            </li>   
            <li>
                <?php
                    $style='style="display:none;"';
                    $class = '';
                    if(request()->is('admin/department') || request()->is('admin/department')){
                    $style='style="display:block;"';
                    $class = 'active-menu';
                    }
                ?>
                @foreach(auth()->user()->permissions as $permissions)
                    @if($permissions->module_id== 6)
                        <a href="{{URL::to('admin/department')}}" class="<?php echo $class; ?>"><i class="fa fa-users"></i> Department  </a>
                    @endif
                @endforeach
            </li>      
            <li class='dropdown_vst_li'>
                <?php
                    $style='style="display:none;"';
                    $class = '';
                    if(request()->is('admin/groupconversation') || request()->is('admin/groupconversation/*') || request()->is('admin/conversation/*') || request()->is('admin/conversation') || request()->is('admin/issue_list') || request()->is('admin/edit_issue/*') || request()->is('admin/issue_create') || request()->is('admin/check_issue/*') ){
                    $style='style="display:block;"';
                    $class = 'active-menu';
                    }
                ?>
                @if($show_communication)
                    <a href="javascript:void(0)" class="<?php echo $class; ?> dropdown_vst"><i class="fa fa-comments"></i> Communication<span class="caret"></span></a>
                @endif
                
                <ul class="dropdown_vst_sub" <?php echo $style; ?>>
                   <li><a href="javascript:void(0)" class="<?php echo $class; ?> dropdown_vst"><i class="fa fa-comments"></i> Chat<span class="caret"></span></a>
                       <ul  class="dropdown_vst_sub" <?php echo $style; ?>>
                        @foreach(auth()->user()->permissions as $permissions)
                            @if($permissions->module_id== 7)
                                <li>
                                    <a href="{{ route('message.group.conversation') }}" class="{{ request()->is('admin/groupconversation') ? 'active-menu' : '' }} {{ request()->is('admin/groupconversation/*') ? 'active-menu' : '' }}"><i class="fa fa-comments"></i> Group Chat</a> <i class="fa fa-plus float-right" style='cursor:pointer;margin:2px 0 0; color: #fff;position: absolute;right: 13px;top: 51px;' data-toggle="modal" data-target="#exampleModal"></i>
                                </li>  
                            @endif
                            @if($permissions->module_id== 8)
                                <li>
                                    <a href="{{ route('message.conversation') }}" class="{{ request()->is('admin/conversation') ? 'active-menu' : '' }} {{ request()->is('admin/conversation/*') ? 'active-menu' : '' }}"><i class="fa fa-comment"></i> individual chats </a>
                                </li>
                            @endif
                        @endforeach
                    </ul></li>
                    @foreach(auth()->user()->permissions as $permissions)
                            @if($permissions->module_id== 9)
                                <li>
                                    <a class="{{ request()->is('admin/issue_create') ? 'active-menu' : '' }} {{ request()->is('admin/edit_issue/*') ? 'active-menu' : '' }} {{ request()->is('admin/issue_list') ? 'active-menu' : '' }} {{ request()->is('admin/check_issue/*') ? 'active-menu' : '' }}" href="{{ url('admin/issue_list') }}"><i class="fa fa-envelope"></i> Issues  </a>
                                </li>
                            @endif
                    @endforeach
                    
                </ul>
                
            </li> 
            @endif
        </ul>
        
    </div>
</nav>



