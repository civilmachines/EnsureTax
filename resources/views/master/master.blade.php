<!DOCTYPE html>
<html lang="en-US" data-ng-app="101housing" data-ng-controller="appCtrl">
<head>
    <base href="/">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="101housing.com">
    <meta name="author" content="101housing.com">
    <link rel="icon" href="images/favicon.png">
    <title>EnsureTax.com</title>
    <script>
        var baseUrl = '{{url("/")}}/';
        var csrfToken = '{{csrf_token()}}';
    </script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa|Dosis|Ubuntu|Varela+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/vendor.min.css')}}" media="screen"/>
    @yield('css')
</head>
<body data-ng-class="bodyClass">
@yield('content')
<script type='text/javascript' src="{{asset('js/vendor.min.js')}}"></script>
<script>
    //Smooth scroll 3
    $(document).ready(function(){
        $('a[href^="#"]').on('click',function (e) {
            e.preventDefault();

            var target = this.hash;
            $target = $(target);

            $('html, body').stop().animate({
                'scrollTop': $target.offset().top-90
            }, 900, 'swing', function () {
                window.location.hash = target;
            });
        });
    });

</script>

@yield('script')
</body>
</html>
