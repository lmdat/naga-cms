<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        @foreach($assets['css'] as $item)
        <link href="{{ asset($item) }}" rel="stylesheet"/>
        @endforeach


        <!--REQUIRE JS SCRIPTS-->
        @foreach($assets['js'] as $item)
        <script src="{{ asset($item) }}"></script>
        @endforeach

    </head>

    <body class="hold-transition {{$login_or_lock}}">
        @yield('content')
    </body>
</html>