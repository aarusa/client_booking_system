<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'PupSplash')</title>
    
    @include('cms.layouts.partials.styles')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>document.addEventListener('DOMContentLoaded', function() { if(window.feather) { feather.replace(); } });</script>
    <style>
    body, h1, h2, h3, h4, h5, h6, .simple-widget, .simple-widget-label, .simple-widget-value, .simple-widget-icon, .btn, .form-control, .navbar, .sidebar, .card {
        font-family: 'Poppins', 'Public Sans', Arial, sans-serif !important;
    }
    .simple-widget {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #eee;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        border-radius: 12px;
        padding: 1.2rem 1.2rem;
        min-height: 100px;
        gap: 0.8rem;
        transition: box-shadow 0.2s;
    }
    .simple-widget:hover {
        box-shadow: 0 4px 16px rgba(104,97,206,0.08);
    }
    .simple-widget-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #f3f2fa;
        color: #6861CE;
        margin-right: 0.5rem;
        flex-shrink: 0;
    }
    .simple-widget-icon svg {
        width: 22px;
        height: 22px;
        color: #6861CE;
        stroke-width: 2.2;
    }
    .simple-widget-value {
        font-size: 1.55rem;
        font-weight: 600;
        color: #222;
        line-height: 1.1;
    }
    .simple-widget-label {
        font-size: 0.85rem;
        color: #888;
        margin-top: 0.5rem;
        font-weight: 400;
        letter-spacing: 0.01em;
    }
    .card-title {
        font-size: 1.2rem !important;
        font-weight: 600;
        color: #222;
        margin-bottom: 0.5rem;
        letter-spacing: 0.01em;
    }
    @media (max-width: 767px) {
        .simple-widget {
            flex-direction: column;
            align-items: flex-start;
            padding: 1rem 0.7rem;
            min-height: 80px;
        }
        .simple-widget-icon {
            margin-right: 0;
            margin-bottom: 0.3rem;
        }
    }
    .alert-success {
      background: #E6F4EA !important;
      color: #1B5E20 !important;
      border-left: 4px solid #A5D6A7 !important;
    }
    .alert-info {
      background: #E8F0FE !important;
      color: #174EA6 !important;
      border-left: 4px solid #90CAF9 !important;
    }
    .alert-warning {
      background: #FFF8E1 !important;
      color: #8D6E00 !important;
      border-left: 4px solid #FFE082 !important;
    }
    .alert-danger {
      background: #FDEAEA !important;
      color: #B71C1C !important;
      border-left: 4px solid #FFABAB !important;
    }
    </style>
       
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
