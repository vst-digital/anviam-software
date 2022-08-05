  <footer><p>Copyright © 2021 <a href="https://anviam.com/">Anviam.</a> All rights reserved. </p>


        </footer>
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <div id="cover-spin"></div>


<!-- <div id="exampleModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

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
           // if($chatgroup){
               // echo '<br /><fieldset style="padding:15px;"><legend>Groups:</legend>';
              //  echo '<ul style="float:left;padding:0px;width:100%;" class="ulgroupappend">';
               // foreach($chatgroup as $val){
                 //   echo "<li class='groupli' style='margin: 10px 0;text-decoration: none;list-style: none;padding: 15px;line-height:15px;cursor:pointer;background: #65CAEF;border-radius: 20px;color: #fff;font-size: 20px;' rel='".$val->chatgroup[0]->id."'>".$val->chatgroup[0]->group_name."</li>";
              //  }
               // echo '</ul></fieldset>';
           // }
            ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="groupmemberModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <a type="button" class="close  refreshcurrent" data-dismiss="modal">&times;</a>
        <h4 class="modal-title">Add members</h4>
      </div>
      <div class="modal-body">
        <label id = 'group_label' style='font-size:25px;'></label>
        <div class='row'>
            <?php
           // if($users){
                //echo '<br /><fieldset style="padding:15px;"><legend>Users:</legend>';
                //echo '<ul style="float:left;padding:0px;width:100%;" class="groupusersli">';
                //foreach($users as $val){
                   // echo "<li style='margin: 10px 0;text-decoration: none;list-style: none;padding: 15px;line-height:15px;background: #65CAEF;border-radius: 20px;color: #fff;font-size: 20px;' rel='".$val->id."'><span>$val->first_name $val->last_name</span> <i style='float:right;cursor:pointer;' class='fa fa-plus-circle adduser usercls".$val->id."'></i></li>";
               // }
                //echo '</ul></fieldset>';
           // }
            ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default refreshcurrent" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div> -->
    <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->

    <!-- Bootstrap Js -->
    <script src="{{url('/js/select2.min.js')}}"></script>

    <!-- Metis Menu Js -->
    <script src="{{url('/admincss/dashboard/js/jquery.metisMenu.js')}}"></script>
    <!-- Morris Chart Js -->
    <script src="{{url('/admincss/dashboard/js/morris/raphael-2.1.0.min.js')}}"></script>
    <script src="{{url('/admincss/dashboard/js/morris/morris.js')}}"></script>


    <script src="{{url('/admincss/dashboard/js/easypiechart.js')}}"></script>
    <script src="{{url('/admincss/dashboard/js/easypiechart-data.js')}}"></script>

    <script src="{{url('/admincss/dashboard/js/Lightweight-Chart/jquery.chart.js')}}"></script>
    <!-- Chart Js -->
    <script type="text/javascript" src="{{url('/admincss/dashboard/js/Chart.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/admincss/dashboard/js/chartjs.js')}}"></script>
    <!-- Custom Js -->
    <script src="{{url('/admincss/dashboard/js/custom-scripts.js')}}"></script>
    <script src="{{url('/js/sweetalert.min.js')}}"></script>
    <script src="{{url('/js/jquery-ui.js')}}"></script>
    <script src="{{url('/js/moment.min.js')}}"></script>
    <script src="{{url('/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{url('/js/popper.min.js')}}" ></script>
    <script src="{{url('/js/jquery.fancybox.js')}}"></script>
    <script src="{{url('/js/dropzone.min.js')}}"></script>
    @stack('footer')
    <script src="{{url('/js/custom.js')}}"></script>

</body>

</html>
