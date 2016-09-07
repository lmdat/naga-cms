<!DOCTYPE html>
<html>
    <head>
        {{--<meta charset="utf-8">--}}
        {{--<meta http-equiv="X-UA-Compatible" content="IE=edge">--}}
        {{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}
        {{--<meta name="description" content="">--}}
        {{--<meta name="author" content="">--}}

        {!! Head::render() !!}


        {{--REQUIRE CSS--}}
        @foreach($assets['css'] as $item)
            <link href="{{ asset(Theme::url($item)) }}" rel="stylesheet"/>
        @endforeach


        {{--REQUIRE JS--}}
        @foreach($assets['js'] as $item)
            <script src="{{ asset(Theme::url($item)) }}"></script>
        @endforeach

    </head>

    <body>
        <header class="sticky-header">
            {!! \App\Modules\Frontend\Partials\HeaderPartial::render() !!}

            {!! \App\Modules\Frontend\Partials\MainMenuPartial::render() !!}
        </header>

        <section class="container main-content">
            @yield('content')
        </section>

        <!--FOOTER-->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <h4>&copy;2016 All Right Reserved by Vincent</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="social-list">
                            <a href="#"><i class="fa fa-3 fa-facebook-square"></i></a>
                            <a href="#"><i class="fa fa-3 fa-twitter-square"></i></a>
                            <a href="#"><i class="fa fa-3 fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <script>
            $(function(){
                $(".dropdown").hover(function() {
                    $('.dropdown-menu', this).stop().fadeIn();
                }, function() {
                    $('.dropdown-menu', this).stop().fadeOut();
                });

                $.scrollUp({
                    scrollText: '<i class="fa fa-chevron-up"></i>', // Text for element
                });

                $(window).scroll(function() {
                    if ($(window).scrollTop() >= 99) {
                        $('.nav-section').addClass('navbar-fixed-top');
                    }

                    if ($(window).scrollTop() >= 100) {
                        $('.nav-section').addClass('show');
                    } else {
                        $('.nav-section').removeClass('show navbar-fixed-top');
                    }
                });
            });


        </script>

    </body>
</html>
