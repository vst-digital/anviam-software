@extends('layouts.admin')
@section('content')
<head>
    <title>VSConstruction Calendar</title>
    <script src="{{url('/js/moment.min.js')}}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <input type='hidden' id='loggedinuser' value="{{Auth::id()}}" />
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" id="editEvent" tabindex="-1" role="dialog" aria-labelledby="editEventLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form class="editEventForm">
                    <input type='hidden' id="editEventId" value='' />
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editEventLabel">{{ __('Edit Event')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="editEventStarts">{{ __('Starts')}}</label>
                                <input type="text" class="form-control datetimepicker-input maincls" id="editEventStarts" name="editEventStarts" data-toggle="datetimepicker" data-target="#editEventStarts">
                            </div>
                            <div class="form-group">
                                <label for="editEventEnds">{{ __('Ends')}}</label>
                                <input type="text" class="form-control datetimepicker-input maincls" id="editEventEnds" name="editEventEnds" data-toggle="datetimepicker" data-target="#editEventEnds">
                            </div>
                            <div class="form-group">
                                <label for="editEventName">{{ __('Event Title')}}</label>
                                <input type="text" class="form-control maincls" id="editEventName" name="editEventName" placeholder="Please enter event title">
                            </div>
                            <div class="form-group">
                                <label for="editEventDes">{{ __('Event Description')}}</label>
                                <textarea class="form-control maincls" id="editEventDes" name="editEventDes" placeholder="Please enter event Description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="editEventUsers">{{ __('Event Users')}}</label>
                                <select name="users[]" required class='selectuser maincls' id='editEventUsers' multiple="multiple" style='width:100%;'>
                                    @if(count($users_data) > 0)
                                        @foreach($users_data as $val)
                                            <option value='{{ $val->id }}'>{{ $val->first_name }} {{ $val->last_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group" id="editreferenceSelecter">
                                <label for="referenceto">{{ __('Reference to')}}</label>
                                <ul class="reference_selecter">
                                    <li>
                                        <input type="radio" value="0" data-value="0" name="editreferenceto" class='checkboxcls' id="editgeneral" >
                                        <label for="editgeneral">None</label>
                                    </li>
                                    <li>
                                        <input type="radio" value="1" data-value="1" name="editreferenceto" class='checkboxcls' rel='memo_adddrp' id="editMemo">
                                        <label for="editMemo">Issues</label>
                                    </li>
                                    <li>
                                        <input type="radio" value="2" data-value="2" name="editreferenceto" class='checkboxcls' rel='project_adddrp' id="editProject">
                                        <label for="editProject">Project</label>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group project_adddrp owneredit commondrp editshow2">
                                <label for="addProjects">{{ __('Project')}}</label>
                                <select name="addProjects" required class='selectproject' id='editProjects' style='width:100%;'>
                                    @if(count($list_projects) > 0)
                                        @foreach($list_projects as $val)
                                            <option value='{{ $val->id }}'>{{ $val->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group justprojectview viewonly editshow21" style='display:none'>
                                <label for="addProjects">{{ __('Project')}}</label>
                                <select name="addProjects" disabled class='selectproject' style='width:100%;'>
                                    @if(count($nonuserprojects) > 0)
                                        @foreach($nonuserprojects as $val)
                                            <option value='{{ $val->id }}'>{{ $val->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group memo_adddrp owneredit commondrp editshow1">
                                <label for="addMemos">{{ __('Issues')}}</label>
                                <select name="addmemos" required class='selectMemo' id='editMemos' style='width:100%;'>
                                    @if($memo)
                                        @foreach($memo as $val)
                                            <option value='{{ $val->id }}'>{{ $val->subject }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group justview editshow11 viewonly" style='display:none'>
                                <label for="addMemos">{{ __('Memo')}}</label>
                                <select name="addmemos" disabled="disabled" class='selectMemo' id='editMemos1' style='width:100%;'>
                                    @if($nonUserMemo)
                                        @foreach($nonUserMemo as $val)
                                            <option value='{{ $val->id }}'>{{ $val->subject }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close')}}</button>
                            <button class="btn btn-danger delete-event" id='delete_event' type="button">{{ __('Delete')}}</button>
                            <button class="btn btn-success save-event" id='save_event' type="submit">{{ __('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal" id="addEvent" tabindex="-1" role="dialog" aria-labelledby="addEventLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEventLabel">{{ __('Add New Event')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body project">
                        <form id="addEventForm">

                            <div class="form-group">
                                <label for="eventStarts">{{ __('Starts')}}</label>
                                <input type="text" class="form-control datetimepicker-input" id="eventStarts" name="eventStarts" data-provide="datepicker"  data-toggle="datetimepicker" data-target="#addEventEnds">
                            </div>
                            <div class="form-group">
                                <label for="eventEnds">{{ __('Ends')}}</label>
                                <input type="text" class="form-control datetimepicker-input" id="eventEnds" name="eventEnds" data-provide="datepicker"  data-toggle="datetimepicker" data-target="#addEventEnds">
                            </div>
                            <div class="form-group">
                                <label for="eventName">{{ __('Event Title')}}</label>
                                <input type="text" class="form-control" id="eventName" name="eventName" placeholder="Please enter event title">
                            </div>
                            <div class="form-group">
                                <label for="eventDes">{{ __('Event Description')}}</label>
                                <textarea class="form-control" id="eventDes" name="eventDes" placeholder="Please enter event Description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="eventUsers">{{ __('Event Users')}}</label>
                                <select name="users[]" required class='selectuser' id='eventUsers' multiple="multiple" style='width:100%;'>
                                    @if(count($users_data) > 0)
                                        @foreach($users_data as $val)
                                            <option value='{{ $val->id }}'>{{ $val->first_name }} {{ $val->last_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group" id="referenceSelecter">
                                <label for="referenceto">{{ __('Reference to')}}</label>
                                <ul class="reference_selecter">
                                    <li>
                                        <input type="radio" data-value="0" name="referenceto" class='checkboxcls' id="general" checked rel='nothing'>
                                        <label for="general">None</label>
                                    </li>
                                    <li>
                                        <input type="radio" data-value="1" name="referenceto" class='checkboxcls' id="adMemo" rel='memo_adddrp'>
                                        <label for="adMemo">Issues</label>
                                    </li>
                                    <li>
                                        <input type="radio" data-value="2" name="referenceto" class='checkboxcls' id="addproject" rel='project_adddrp'>
                                        <label for="addproject">Project</label>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group project_adddrp commondrp" style='display:none'>
                                <label for="addProjects">{{ __('Project')}}</label>
                                <select name="addProjects" required class='selectproject' id='addProjects' style='width:100%;'>
                                    @if(count($list_projects) > 0)
                                        @foreach($list_projects as $val)
                                            <option value='{{ $val->id }}'>{{ $val->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group memo_adddrp commondrp" style='display:none'>
                                <label for="addMemos">{{ __('Issues')}}</label>
                                <select name="addmemos" required class='selectMemo' id='addMemos' style='width:100%;'>
                                    @if($memo)
                                        @foreach($memo as $val)
                                            <option value='{{ $val->id }}'>{{ $val->subject }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">

                            <div class="form-group mb-0" id="addColor">
                                <label for="colors">{{ __('Choose Color')}}</label>
                                <ul class="color-selector">
                                    <li class="aqua">
                                        <input type="radio" data-color="#3ec5d6" checked name="colorChosen" id="addColorAqua">
                                        <label for="addColorAqua"></label>
                                    </li>
                                    <li class="blue">
                                        <input type="radio" data-color="#19B5FE" name="colorChosen" id="addColorBlue">
                                        <label for="addColorBlue"></label>
                                    </li>
                                    <li class="light-blue">
                                        <input type="radio" data-color="#89CFF0" name="colorChosen" id="addColorLightblue">
                                        <label for="addColorLightblue"></label>
                                    </li>
                                    <li class="teal">
                                        <input type="radio" data-color="#008081" name="colorChosen" id="addColorTeal">
                                        <label for="addColorTeal"></label>
                                    </li>
                                    <li class="yellow">
                                        <input type="radio" data-color="#F7CA18" name="colorChosen" id="addColorYellow">
                                        <label for="addColorYellow"></label>
                                    </li>
                                    <li class="orange">
                                        <input type="radio" data-color="#FF8000" name="colorChosen" id="addColorOrange">
                                        <label for="addColorOrange"></label>
                                    </li>
                                    <li class="green">
                                        <input type="radio" data-color="#26C281" name="colorChosen" id="addColorGreen">
                                        <label for="addColorGreen"></label>
                                    </li>
                                    <li class="lime">
                                        <input type="radio" data-color="#cad900" name="colorChosen" id="addColorLime">
                                        <label for="addColorLime"></label>
                                    </li>
                                    <li class="red">
                                        <input type="radio" data-color="#F22613" name="colorChosen" id="addColorRed">
                                        <label for="addColorRed"></label>
                                    </li>
                                    <li class="purple">
                                        <input type="radio" data-color="#BF55EC" name="colorChosen" id="addColorPurple">
                                        <label for="addColorPurple"></label>
                                    </li>
                                    <li class="fuchsia">
                                        <input type="radio" data-color="#df2de3" name="colorChosen" id="addColorFuchsia">
                                        <label for="addColorFuchsia"></label>
                                    </li>
                                    <li class="muted">
                                        <input type="radio" data-color="muted" name="colorChosen" id="addColorMuted">
                                        <label for="addColorMuted"></label>
                                    </li>
                                    <li class="navy">
                                        <input type="radio" data-color="#000080" name="colorChosen" id="addColorNavy">
                                        <label for="addColorNavy"></label>
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close')}}</button>
                        <button type="submit" class="btn btn-success save-event">{{ __('Save')}}</button>
                        <!-- <button type="button" class="btn btn-danger delete-event" data-dismiss="modal">{{ __('Delete')}}</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- push external js -->

    @push('footer')
        <script src="{{url('/js/popper.min.js')}}" ></script>
        <script src="{{url('/js/fullcalendar.js')}}" ></script>
        <script src="{{url('/js/tempusdominus-bootstrap-4.min.js')}}" ></script>
        <link rel="stylesheet" href="{{url('/css/ionicons.min.css')}}" />
        <link rel="stylesheet" href="{{url('/css/fullcalendar.css')}}" />
        <link rel="stylesheet" href="{{ asset('theme.css') }}" />
        

    @endpush
    <script src="{{ asset('calendar.js') }}"></script>
@endsection

<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
<!-- <script type="text/javascript">
     $( function() {
    $( ".datetimepicker-input" ).datetimepicker();
  } );
</script> -->


