@include('includes.admin.head')
@include('includes.admin.header')
@include('includes.admin.sidebar')
@yield('content')


@include('includes.admin.footer')

  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none" style='display:none;'>
    @csrf
  </form>
