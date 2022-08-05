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
   <div class="header">
   </div>
   <div id="page-inner">
   <!-- <div class="row">
      <div class="col-md-6">
         <h2 class="page-header"></h2>
      </div>
   </div> -->
   @if(session()->has('message'))
   <div class="alert alert-success">
      {{ session()->get('message') }}
   </div>
   @endif
   @if ($errors->any())
   <div class="alert alert-danger">
      <ul>
         @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
         @endforeach
      </ul>
   </div>
   @endif
   <div class="btn_form" href="google.com"><a href="{{ url()->previous() }}"><button  class="btn btn-primary float-right">Back</button></a>
   </div>
   <div class="testbox">
      <form method="POST" action="{{url('admin/save_department')}}">
         @csrf
         <label>Department Name</label>
            <div class="item">
               <input id="name" type="text" name="name" required placeholder="Name" />
            </div>
            <label>Department Description</label>
             <div class="item1">
                    <textarea name="description" class="ckeditor" rows="4" cols="50" placeholder="Enter Description"></textarea>
                </div>
            <div class="btn-block">
               <button type="submit" href="/">Add Department</button>
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

   