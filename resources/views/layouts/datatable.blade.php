<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title> @isset($title) {{ ($title . ' | ') }} @endisset {{ config('get.SYSTEM_APPLICATION_NAME') }}</title>

  @include('elements.styles')
  <!-- Push Page Styles  Balde -->
  @stack('styles')
 
</head>

<body>
 <div class="app-container fixed-sidebar body-tabs-shadow app-theme-gray">    <!-- Navbar -->
    @include('components.navbar')
    <!-- /.navbar -->

     <div class="app-main">
    <!-- Main Sidebar Container -->
    @include('components.main-sidebar')
    <!-- Content Wrapper. Contains page content -->
   <div class="app-main__outer">
 <div class="app-main__inner">
      @yield('content')

 </div>
       <!-- footer start --> 
                    
     @include('components.footer')

      <!-- footer end --> 

    </div>
    <!-- /.content-wrapper -->

    

 
    
    </div>
  </div>
@include('components.logoutmodal')
  <!-- REQUIRED SCRIPTS -->
  @include('elements.scripts')
  
  
 
    <script type="text/javascript" src="{{ asset('architect/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('architect/vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('architect/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('architect/vendors/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

  @stack('scripts')
  
  <script type="text/javascript">
      $(document).ready(function() {
          setTimeout(handleNotification, 10000);
          
      });
      function handleNotification(){
          $.ajax({
              url: "{{ route('conversations.notifications') }}",
              type: 'get',
              success: function(data){
                $(document).find('.notifications-wrapper').html(data.html);
              },
              complete:function(data){
                  setTimeout(handleNotification, 10000);
              }
          });
      }
  </script>
</body>

</html>