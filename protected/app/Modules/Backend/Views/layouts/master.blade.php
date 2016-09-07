<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>@yield('title')</title>
        
        @foreach($assets['css'] as $item)
        <link href="{{ asset($item) }}" rel="stylesheet"/>
        @endforeach
        

        <!--REQUIRE JS SCRIPTS-->
        @foreach($assets['js'] as $item)
        <script src="{{ asset($item) }}"></script>
        @endforeach
    </head>
    
    <body class="hold-transition skin-{{ $assets['layout']['skin-color'] }} fixed sidebar-mini">
        <!--WRAPPER-->
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="./" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>ZO</b></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>ZO</b>YO CMS</span>
                </a>
                
                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top">
                    
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            
                            <!-- User Account Menu -->
                            <li class="dropdown user user-menu">
                                <!-- Menu Toggle Button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <!-- The user image in the navbar-->
                                    <img src="{{asset('media/local/img/unknown_128.png')}}" class="user-image" alt="User Image"/>
                                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                    <span class="hidden-xs">{{$full_name}}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- The user image in the menu -->
                                    <li class="user-header">
                                        <img src="{{asset('media/local/img/unknown_128.png')}}" class="img-circle" alt="User Image" />
                                        <p>
                                            {{$full_name}}
                                            <small>{{$user_role}}</small>
                                        </p>
                                    </li>
                                    
                                    <!--li class="user-body">
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Followers</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Sales</a>
                                        </div>
                                    </li-->
                                    
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="#" class="btn btn-info btn-sm btn-flat">{{trans('backend/admin.profile')}}</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{url('logout')}}" class="btn btn-warning btn-sm btn-flat"><i class="fa fa-sign-out"></i> {{trans('backend/auth.logout')}}</a>
                                        </div>
                                    </li>
                                </ul>
                                
                            </li><!-- ./User Account Menu -->
                            
                            <!-- Control Sidebar Toggle Button -->
                            <!--li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li-->
                        </ul>
                    </div>
                </nav><!-- ./Header Navbar -->
                
            </header>
            
            <!-- Left side column. contains the sidebar -->
            @include('Backend::layouts.sidebar')
            
            <div class="content-wrapper">
                
                @yield('content')
                
            </div>
            
            <!-- Main Footer -->
            <footer class="main-footer">
                <!-- To the right -->
                <div class="pull-right hidden-xs">
                    <br/><br/>
                    Powered by <a href="http://laravel.com/" target="_blank">Laravel Framework</a>
                </div>
                <!-- Default to the left -->
                Developed by <a href="mailto:minh_dat_le@yahoo.com" target="_blank">Dat Le</a><br/> 
                <strong>e.</strong> <a href="mailto:minh_dat_le@yahoo.com" target="_blank">minh_dat_le@yahoo.com</a> | 
                <strong>m.</strong> (+84) 919 564 515<br/>
                Copyright &copy; {{$copy_right_year}}. All Rights Reserved.<br/>
            </footer>
            
        </div><!-- ./WRAPPER -->

        
        
    </body>
</html>