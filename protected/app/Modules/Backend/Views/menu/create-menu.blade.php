@extends('Backend::layouts.master')

@section('content')
    <section class="content-header">
        <h1>
            {{ trans($lang_mod . '.menu_create_header') }}
            <small></small>
        </h1>
        <!--ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol-->
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-7">
                {!! Form::open(['url' => Request::url() . $qs, 'name' => 'menuForm', 'id' => 'menuForm', 'role' => 'form']) !!}
                <div class="box box-primary">
                    <!--div class="box-header">
                        <h3 class="box-title"></h3>
                    </div-->
                    <div class="box-body">
                        <div class="form-group @if($errors->has('menu_title')) has-error @endif">
                            <label for="menu_title">{{ trans($lang_mod . '.menu_title') }}</label> <span class="text-red">*</span>
                            {!! Form::text('menu_title', '', ['id'=>'menu_title', 'class' => 'form-control input-sm', 'placeholder' => 'Required']) !!}
                            @if ($errors->has('menu_title'))<p><small class="text-red">{!!$errors->first('menu_title')!!}</small></p> @endif
                        </div>

                        <div class="form-group @if($errors->has('menu_pos')) has-error @endif">
                            <label for="menu_pos">{{ trans($lang_mod . '.menu_pos') }}</label> <span class="text-red">*</span>
                            {!! Form::text('menu_pos', '', ['id'=>'menu_pos', 'class' => 'form-control input-sm', 'placeholder' => 'Required']) !!}
                            @if ($errors->has('menu_pos'))<p><small class="text-red">{!!$errors->first('menu_pos')!!}</small></p> @endif
                        </div>

                        <div class="form-group">
                            <label for="published">{{ trans($lang_mod . '.status') }}</label>
                            <div class="">
                                <label class="radio-inline">
                                    {!! Form::radio('status', 1, true, ['id'=>'status1']) !!} {{ trans($lang_common . '.radio_yes') }}
                                </label>
                                <label class="radio-inline">
                                    {!! Form::radio('status', 0, false, ['id'=>'status0']) !!} {{ trans($lang_common . '.radio_no') }}
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="box-footer">
                        <div class="text-right">
                            <a href="{{ url($prefix_url ) . $qs }}" class="btn bg-orange btn-sm btn-flat"><i class="fa fa-reply"></i> {{ trans($lang_common . '.btn_exit') }}</a>
                            <button id="btn_ok" type="button" class="btn btn-success btn-sm btn-flat"><i class="fa fa-save"></i> {{ trans($lang_common . '.btn_save') }}</button>

                        </div>
                    </div>

                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <script>

            $(window).on("beforeunload", function() {
                return "{{ trans($lang_mod . '.leave_page') }}";
            });

            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_minimal-blue',
                    radioClass: 'iradio_minimal-blue',
                });

                $('#btn_ok').on('click', function(e){

                    $(window).off("beforeunload");
                    $('#menuForm').submit();
                });
            });
        </script>
    </section>

@stop