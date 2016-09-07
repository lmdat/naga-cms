@extends('Backend::layouts.master')

@section('content')
    <section class="content-header">
        <h1>
            {{ trans($lang_mod . '.setting_create_header') }}
            @if(session()->has('message-error'))
                <small><label class="label label-danger">Oh snap! {{ Session::get('message-error') }}</label></small>
            @endif
        </h1>
        {{--<ol class="breadcrumb">--}}
            {{--<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>--}}
            {{--<li class="active">Here</li>--}}
        {{--</ol>--}}
    </section>


    <section class="content">
        <div class="row">
            <div class="col-md-7">
                {!! Form::open(['url' => Request::url() . $qs, 'name' => 'settingForm', 'id' => 'settingForm', 'role' => 'form']) !!}
                <div class="box box-primary">
                    <!--div class="box-header">
                        <h3 class="box-title"></h3>
                    </div-->
                    <div class="box-body">

                        <div class="form-group @if($errors->has('site_name')) has-error @endif">
                            <label for="site_name">{{ trans($lang_mod . '.site_name') }}</label>
                            {!! Form::text('site_name', '', ['id'=>'site_name', 'class' => 'form-control input-sm', 'placeholder' => 'Required']) !!}
                            @if ($errors->has('site_name'))<p><small class="text-red">{!!$errors->first('site_name')!!}</small></p> @endif
                        </div>

                        <div class="form-group">
                            <label for="alias">{{ trans($lang_mod . '.website_default_description') }}</label>
                            {!! Form::textarea('website_default_description', '', ['id'=>'website_default_description', 'class' => 'form-control input-sm', 'rows'=> 4, 'placeholder' => '']) !!}

                        </div>

                        <div class="form-group @if($errors->has('fb_id')) has-error @endif">
                            <label for="published">{{ trans($lang_mod . '.fb_id') }}</label>
                            {!! Form::text('fb_id', '', ['id'=>'fb_id', 'class' => 'form-control input-sm', 'placeholder' => '']) !!}
                            @if ($errors->has('fb_id'))<p><small class="text-red">{!!$errors->first('fb_id')!!}</small></p> @endif
                        </div>

                        <div class="form-group @if($errors->has('fb_page')) has-error @endif">
                            <label for="published">{{ trans($lang_mod . '.fb_page') }}</label>
                            {!! Form::text('fb_page', '', ['id'=>'fb_page', 'class' => 'form-control input-sm', 'placeholder' => '']) !!}
                            @if ($errors->has('fb_page'))<p><small class="text-red">{!!$errors->first('fb_page')!!}</small></p> @endif
                        </div>

                        <div class="form-group @if($errors->has('fb_page_id')) has-error @endif">
                            <label for="published">{{ trans($lang_mod . '.fb_page_id') }}</label>
                            {!! Form::text('fb_page_id', '', ['id'=>'fb_page_id', 'class' => 'form-control input-sm', 'placeholder' => '']) !!}
                            @if ($errors->has('fb_id'))<p><small class="text-red">{!!$errors->first('fb_id')!!}</small></p> @endif
                        </div>

                        <div class="form-group @if($errors->has('ga_id')) has-error @endif">
                            <label for="published">{{ trans($lang_mod . '.ga_id') }}</label>
                            {!! Form::text('ga_id', '', ['id'=>'ga_id', 'class' => 'form-control input-sm', 'placeholder' => '']) !!}
                            @if ($errors->has('ga_id'))<p><small class="text-red">{!!$errors->first('ga_id')!!}</small></p> @endif
                        </div>

                        <div class="form-group">
                            <label for="alias">{{ trans($lang_mod . '.custom_analytics_script') }}</label>
                            {!! Form::textarea('custom_analytics_script', '', ['id'=>'custom_analytics_script', 'class' => 'form-control input-sm', 'rows'=> 4, 'placeholder' => '']) !!}
                            @if ($errors->has('website_off_message'))<p><small class="text-red">{!!$errors->first('website_off_message')!!}</small></p> @endif
                        </div>

                        <div class="form-group">
                            <label for="published">{{ trans($lang_mod . '.default_seo_image') }}</label>
                            <div class="input-group input-group-sm">
                                {!! Form::text('default_seo_image', '', ['id'=>'default_seo_image', 'class' => 'form-control input-sm', 'placeholder' => '']) !!}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat">Select...</button>
                                </span>
                            </div>
                            @if ($errors->has('default_seo_image'))<p><small class="text-red">{!!$errors->first('default_seo_image')!!}</small></p> @endif
                        </div>


                        <div class="form-group">
                            <label for="published">{{ trans($lang_mod . '.website_on_off') }}</label>
                            <div class="">
                                <label class="radio-inline">
                                    {!! Form::radio('website_on_off', 1, true, ['id'=>'website_on_off1']) !!} {{ trans($lang_common . '.radio_on') }}
                                </label>
                                <label class="radio-inline">
                                    {!! Form::radio('website_on_off', 0, false, ['id'=>'website_on_off0']) !!} {{ trans($lang_common . '.radio_off') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group @if($errors->has('website_off_message')) has-error @endif">
                            <label for="alias">{{ trans($lang_mod . '.website_off_message') }}</label>
                            {!! Form::textarea('website_off_message', trans($lang_mod . '.website_off_default_message'), ['id'=>'website_off_message', 'class' => 'form-control input-sm', 'rows'=> 4, 'placeholder' => 'Required']) !!}
                            @if ($errors->has('website_off_message'))<p><small class="text-red">{!!$errors->first('website_off_message')!!}</small></p> @endif
                        </div>

                    </div>

                    <div class="box-footer">
                        <div class="text-right">
                            <button type="submit" class="btn btn-success btn-sm btn-flat"><i class="fa fa-save"></i> {{ trans($lang_common . '.btn_ok') }}</button>

                        </div>
                    </div>

                </div>

                {!! Form::close() !!}
            </div>
        </div>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_minimal-blue',
                    radioClass: 'iradio_minimal-blue',
                });
            });
        </script>
    </section>

@stop