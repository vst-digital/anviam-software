@extends('layouts.admin')
@section('content')

      <div id="page-wrapper">
        <!-- <div class="row chat-row">
            <div class="col-md-9">
                <h1>
                    Message Section
                </h1>

                <p class="lead">
                    Select user from the list to begin conversation.
                </p>
            </div>
            <div class="col-md-3">
                <div class="users">
                    <h5>Users</h5>

                    <ul class="list-group list-chat-item">
                        @if($users->count())
                            @foreach($users as $user)
                                <li class="chat-user-list">
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
                        @endif
                    </ul>
                </div>
            </div>
        </div> -->

	<!--
        <div id="chatwrapper">
            <div id="menu">
                <p class="welcome">Welcome, <b></b></p>
                <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
            </div>

            <div id="chatbox"></div>

            <form name="message" action="" method='post'>
                <input name="usermsg" type="text" id="usermsg" />
                <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
            </form>
        </div>
    </div>-->




    <div class="header">
       <h2 class="page-header">
          Dashboard <small>Welcome {{Auth::user()->first_name}} {{Auth::user()->last_name}}</small>
       </h2>
       <ol class="breadcrumb">
          <li><a href="#">Home</a></li>
          <li><a href="#">Dashboard</a></li>
          <li class="active">Data</li>
       </ol>
    </div>

   <div id="page-inner">
    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12">
          <div class="board">
             <div class="panel panel-primary">
                <div class="number">
                   <h3>
                      <h3>{{$total_user}}</h3>
                      <small>Users</small>
                   </h3>
                </div>
                <div class="icon">
                   <i class="fa fa-user fa-5x yellow"></i>
                </div>
             </div>
          </div>
       </div>
       <div class="col-md-6 col-sm-12 col-xs-12">
          <div class="board">
             <div class="panel panel-primary">
                <div class="number">
                   <h3>
                      <h3>{{$total_project}}</h3>
                      <small>Projects</small>
                   </h3>
                </div>
                <div class="icon">
                   <i class="fa fa-eye fa-5x red"></i>
                </div>
             </div>
          </div>
       </div>
<!--        <div class="col-md-3 col-sm-12 col-xs-12">
          <div class="board">
             <div class="panel panel-primary">
                <div class="number">
                   <h3>
                      <h3>32,850</h3>
                      <small>Sales</small>
                   </h3>
                </div>
                <div class="icon">
                   <i class="fa fa-shopping-cart fa-5x blue"></i>
                </div>
             </div>
          </div>
       </div> -->
<!--        <div class="col-md-3 col-sm-12 col-xs-12">
          <div class="board">
             <div class="panel panel-primary">
                <div class="number">
                   <h3>
                      <h3>56,150</h3>
                      <small>Comments</small>
                   </h3>
                </div>
                <div class="icon">
                   <i class="fa fa-comments fa-5x green"></i>
                </div>
             </div>
          </div>
       </div> -->
    </div>

    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default chartJs">
                <div class="panel-heading">
                    <div class="card-title">
                        <div class="title">User Chart</div>
                    </div>
                </div>
                <div class="panel-body">
                    <canvas id="line-chart" class="chart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="panel panel-default chartJs">
                <div class="panel-heading">
                    <div class="card-title">
                        <div class="title">Project Chart</div>
                    </div>
                </div>
                <div class="panel-body">
                    <canvas id="bar-chart" class="chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="row">
      <div class="col-xs-6 col-md-3">
        <div class="panel panel-default">
          <div class="panel-body easypiechart-panel">
            <h4>Profit</h4>
            <div class="easypiechart" id="easypiechart-blue" data-percent="82" ><span class="percent">82%</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xs-6 col-md-3">
        <div class="panel panel-default">
          <div class="panel-body easypiechart-panel">
            <h4>Sales</h4>
            <div class="easypiechart" id="easypiechart-orange" data-percent="55" ><span class="percent">55%</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xs-6 col-md-3">
        <div class="panel panel-default">
          <div class="panel-body easypiechart-panel">
            <h4>Customers</h4>
            <div class="easypiechart" id="easypiechart-teal" data-percent="84" ><span class="percent">84%</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xs-6 col-md-3">
        <div class="panel panel-default">
          <div class="panel-body easypiechart-panel">
            <h4>No. of Visits</h4>
            <div class="easypiechart" id="easypiechart-red" data-percent="46" ><span class="percent">46%</span>
            </div>
          </div>
        </div>
      </div>
    </div>
 -->

   <!--   <div class="row">
       <div class="col-md-5">
          <div class="panel panel-default">
             <div class="panel-heading">
                User Chart
             </div>
             <div class="panel-body">
                <div id="morris-line-chart"></div>
             </div>
          </div>
       </div>
       <div class="col-md-7">
          <div class="panel panel-default">
             <div class="panel-heading">
                Bar Chart Example
             </div>
             <div class="panel-body">
                <div id="morris-bar-chart"></div>
             </div>
          </div>
       </div>
     </div>

        <div class="row">
           <div class="col-md-9 col-sm-12 col-xs-12">
              <div class="panel panel-default">
                 <div class="panel-heading">
                    Area Chart
                 </div>
                 <div class="panel-body">
                    <div id="morris-area-chart"></div>
                 </div>
              </div>
           </div>
           <div class="col-md-3 col-sm-12 col-xs-12">
              <div class="panel panel-default">
                 <div class="panel-heading">
                    Donut Chart Example
                 </div>
                 <div class="panel-body">
                    <div id="morris-donut-chart"></div>
                 </div>
              </div>
           </div>
        </div>
 -->
                <!-- /. ROW  -->





                 <!-- <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Tasks Panel
                            </div>
                            <div class="panel-body">
                                <div class="list-group">

                                    <a href="#" class="list-group-item">
                                        <span class="badge">7 minutes ago</span>
                                        <i class="fa fa-fw fa-comment"></i> Commented on a post
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <span class="badge">16 minutes ago</span>
                                        <i class="fa fa-fw fa-truck"></i> Order 392 shipped
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <span class="badge">36 minutes ago</span>
                                        <i class="fa fa-fw fa-globe"></i> Invoice 653 has paid
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <span class="badge">1 hour ago</span>
                                        <i class="fa fa-fw fa-user"></i> A new user has been added
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <span class="badge">1.23 hour ago</span>
                                        <i class="fa fa-fw fa-user"></i> A new user has added
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <span class="badge">yesterday</span>
                                        <i class="fa fa-fw fa-globe"></i> Saved the world
                                    </a>
                                </div>
                                <div class="text-right">
                                    <a href="#">More Tasks <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-8 col-sm-12 col-xs-12">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Responsive Table Example
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>S No.</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>User Name</th>
                                                <th>Email ID.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>John</td>
                                                <td>Doe</td>
                                                <td>John15482</td>
                                                <td>name@site.com</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Kimsila</td>
                                                <td>Marriye</td>
                                                <td>Kim1425</td>
                                                <td>name@site.com</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Rossye</td>
                                                <td>Nermal</td>
                                                <td>Rossy1245</td>
                                                <td>name@site.com</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Richard</td>
                                                <td>Orieal</td>
                                                <td>Rich5685</td>
                                                <td>name@site.com</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Jacob</td>
                                                <td>Hielsar</td>
                                                <td>Jac4587</td>
                                                <td>name@site.com</td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Wrapel</td>
                                                <td>Dere</td>
                                                <td>Wrap4585</td>
                                                <td>name@site.com</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div> -->


<script type="text/javascript">

$(function() {
  var ctx, data, myLineChart, options;
  Chart.defaults.global.responsive = true;
  ctx = $('#line-chart').get(0).getContext('2d');
  options = {
    scaleShowGridLines: true,
    scaleGridLineColor: "rgba(0,0,0,.05)",
    scaleGridLineWidth: 1,
    scaleShowHorizontalLines: true,
    scaleShowVerticalLines: true,
    bezierCurve: false,
    bezierCurveTension: 0.4,
    pointDot: true,
    pointDotRadius: 4,
    pointDotStrokeWidth: 1,
    pointHitDetectionRadius: 20,
    datasetStroke: true,
    datasetStrokeWidth: 2,
    datasetFill: true,
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
  };
  data = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep','Oct','Nov','Dec'],
    datasets: [
      {
        label: "User list",
        fillColor: "rgba(34, 167, 240,0.2)",
        strokeColor: "#22A7F0",
        pointColor: "#22A7F0",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#22A7F0",
        data: {{$user_chart}}
      }
    ]
  };
  myLineChart = new Chart(ctx).Line(data, options);
});


$(function() {
  var ctx, data, myBarChart, option_bars;
  Chart.defaults.global.responsive = true;
  ctx = $('#bar-chart').get(0).getContext('2d');
  option_bars = {
    scaleBeginAtZero: true,
    scaleShowGridLines: true,
    scaleGridLineColor: "rgba(0,0,0,.05)",
    scaleGridLineWidth: 1,
    scaleShowHorizontalLines: true,
    scaleShowVerticalLines: false,
    barShowStroke: true,
    barStrokeWidth: 1,
    barValueSpacing: 5,
    barDatasetSpacing: 3,
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
  };
  data = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep','Oct','Nov','Dec'],
    datasets: [
      {
        label: "My First dataset",
        fillColor: "rgba(26, 188, 156,0.6)",
        strokeColor: "#1ABC9C",
        pointColor: "#1ABC9C",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#1ABC9C",
        data: {{$project_chart}}
      }
    ]
  };
  myBarChart = new Chart(ctx).Bar(data, option_bars);
});


</script>
    <script>
        $(function (){
            let user_id = "{{ auth()->user()->id }}";
            let ip_address = '127.0.0.1';
            let socket_port = '8005';
            let socket = io(ip_address + ':' + socket_port);

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
        });

    </script>
@endsection




