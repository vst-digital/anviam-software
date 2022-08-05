@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Sweet Alert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<script type="text/javascript"  src=" https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
<!-- /. NAV SIDE  -->
</head>
<body>
   <div id="page-wrapper">
    <div id="page-inner">
    </br>
    @if(session()->has('message'))
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
   @endif
   <div class="testbox">
      <form method="POST" action="{{url('admin/update_department')}}">
         @csrf
         <label>Department Name</label>
            <div class="item">
               <input id="name" type="text" name="name" required placeholder="Name" value="{{$department->name}}" />
            </div>
            <input type="hidden" name="id" value="{{$department->id}}">
            <label>Department Description</label>
             <div class="item1">
                    <textarea name="description" class="ckeditor" rows="4" cols="50" placeholder="Enter Description">
                      {!! $department->description !!}
                    </textarea>
                </div>
            <div class="btn-block">
               <button type="submit" href="/">Edit Department</button>
            </div>
      </form>
      </div>
   </div>
   <script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });
</script>
   @endsection

   