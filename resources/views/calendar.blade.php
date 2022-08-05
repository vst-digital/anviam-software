<!DOCTYPE html>
<html>
<head>
    <title>VSConstruction Calendar</title>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.5.6/css/ionicons.min.css" integrity="sha512-0/rEDduZGrqo4riUlwqyuHDQzp2D1ZCgH/gFIfjMIL5az8so6ZiXyhf1Rg8i6xsjv+z/Ubc4tt1thLigEcu6Ug==" crossorigin="anonymous" />
<link rel="stylesheet" href="{{ asset('theme.css') }}" />
</head>
<body>




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
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editEventLabel">{{ __('Edit Event')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="editEname">{{ __('Event Title')}}</label>
                                <input type="text" class="form-control" id="editEname" name="editEname" placeholder="Please enter event title">
                            </div>
                            <div class="form-group">
                                <label for="editStarts">{{ __('Start')}}</label>
                                <input type="text" class="form-control datetimepicker-input" id="editStarts" name="editStarts" data-toggle="datetimepicker" data-target="#editStarts">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close')}}</button>
                            <button class="btn btn-danger delete-event" type="submit">{{ __('Delete')}}</button>
                            <button class="btn btn-success save-event" type="submit">{{ __('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal" id="addEvent" tabindex="-1" role="dialog" aria-labelledby="addEventLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEventLabel">{{ __('Add New Event')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="addEventForm">
                            <div class="form-group">
                                <label for="eventName">{{ __('Event Title')}}</label>
                                <input type="text" class="form-control" id="eventName" name="eventName" placeholder="Please enter event title">
                            </div>
                            <div class="form-group">
                                <label for="eventStarts">{{ __('Starts')}}</label>
                                <input type="text" class="form-control datetimepicker-input" id="eventStarts" name="eventStarts" data-toggle="datetimepicker" data-target="#eventStarts">
                            </div>
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
                        <button type="button" class="btn btn-success save-event">{{ __('Save')}}</button>
                        <button type="button" class="btn btn-danger delete-event" data-dismiss="modal">{{ __('Delete')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- push external js -->

        <script src="{{ asset('calendar.js') }}"></script>




</body>
</html>
