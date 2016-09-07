<!--NAV-->
<section class="nav-section">
    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div>

            <div id="navbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="{{ $root_url }}" class="home"><i class="fa fa-home fa-2"></i></a></li>
                    {!! $menu_data !!}
                </ul>

                {!! Form::open(['url' => url('search'), 'method'=>'get', 'name' => 'searchForm', 'id' => 'searchForm', 'role' => 'search' , 'class'=>'navbar-form navbar-right']) !!}
                    <div class="input-group">
                        {!! Form::text('q', request()->input('q', ''), ['id'=>'q', 'class' => 'form-control', 'placeholder' => trans($lang_common . '.input_search')]) !!}
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-search" type="submit"><i class="fa fa-search"></i></button>
                            </span>
                    </div>
                    <script>

                    </script>
                {!! Form::close() !!}
            </div>
        </div>
    </nav>
</section>