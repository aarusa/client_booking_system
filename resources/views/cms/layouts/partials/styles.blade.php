<link rel="icon" href="{{ asset('assets/cms/img/kaiadmin/favicon.ico') }}" type="image/x-icon"/>

<!-- Fonts and icons -->
<script src="{{ asset('assets/cms/js/plugin/webfont/webfont.min.js') }}"></script>
<script>
    WebFont.load({
    google: { families: ["Public Sans:300,400,500,600,700"] },
    custom: {
        families: [
        "Font Awesome 5 Solid",
        "Font Awesome 5 Regular",
        "Font Awesome 5 Brands",
        "simple-line-icons",
        ],
        urls: ["{{ asset('assets/cms/css/fonts.min.css') }}"],
    },
    active: function () {
        sessionStorage.fonts = true;
    },
    });
</script>

<!-- CSS Files -->
<link rel="stylesheet" href="{{ asset('assets/cms/css/bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/cms/css/plugins.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/cms/css/kaiadmin.min.css') }}" />

<!-- CSS Just for demo purpose, don't include it in your project -->
<link rel="stylesheet" href="{{ asset('assets/cms/css/demo.css') }}" />
 