<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'CykleCMS')</title>
    
    @include('cms.layouts.partials.styles')
       
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
        @include('cms.layouts.partials.sidebar')
      <!-- End Sidebar -->

      <div class="main-panel">
        @include('cms.layouts.partials.header')

        <div class="container">
          @yield('content')
        </div>

        @include('cms.layouts.partials.footer')
      </div>

      <!-- Custom template | only visible to super admin! -->
      {{-- @include('cms.layouts.partials.custom') --}}
      <!-- End Custom template -->
    </div>

    @include('cms.layouts.partials.scripts')

    <script>
    $(document).ready(function() {
        @if(session('error'))
            swal({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                button: 'OK'
            });
        @php session()->forget('error'); @endphp
        @endif

        @if(session('success'))
            swal({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                button: 'OK'
            });
        @php session()->forget('success'); @endphp
        @endif

        @if(session('warning'))
            swal({
                icon: 'warning',
                title: 'Warning',
                text: "{{ session('warning') }}",
                button: 'OK'
            });
        @php session()->forget('warning'); @endphp
        @endif

        @if(session('info'))
            swal({
                icon: 'info',
                title: 'Information',
                text: "{{ session('info') }}",
                button: 'OK'
            });
        @php session()->forget('info'); @endphp
        @endif
    });
    </script>

  </body>
</html>
