@extends('Backend::layouts.master')

@section('content')
    <section class="content-header">
        <h1>

            {{ trans($lang_mod . '.ads_create_header') }}
            <small></small>
        </h1>
        <!--ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol-->
    </section>

    @if(session()->has('message-error'))
        <section class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger"><strong>Oh snap!</strong> {{ Session::get('message-error') }}</div>
                </div>
            </div>
        </section>
    @endif

    <section class="content">
        <div class="row">
            {!! Form::open(['url' => Request::url() . $qs, 'name' => 'adsForm', 'id' => 'adsForm', 'role' => 'form']) !!}
            <div class="col-md-8">
                <div class="box box-primary">
                    <!--div class="box-header">
                        <h3 class="box-title"></h3>
                    </div-->
                    <div class="box-body">

                        <div class="form-group @if($errors->has('pos_id')) has-error @endif">
                            <label for="pos_id">{{ trans($lang_mod . '.pos_id') }}</label> <span class="text-red">*</span>
                            <select name="pos_id" id="pos_id" class="form-control input-sm">
                                <option value="">---{{ trans($lang_mod . '.select_pos') }}---</option>
                                @foreach($positions as $item)
                                    <option value="{{$item->id}}" @if($item->status == 0) disabled="disabled" @endif @if($item->id == old('pos_id')) selected @endif>{{ $item->pos_name }}</option>

                                @endforeach
                            </select>

                            @if ($errors->has('pos_id'))<p><small class="text-red">{!!$errors->first('pos_id')!!}</small></p> @endif

                        </div>

                        <div class="form-group @if($errors->has('ads_title')) has-error @endif">
                            <label for="ads_title">{{ trans($lang_mod . '.ads_title') }}</label> <span class="text-red">*</span>
                            {!! Form::text('ads_title', '', ['id'=>'ads_title', 'class' => 'form-control input-sm', 'placeholder' => 'Required']) !!}
                            @if ($errors->has('ads_title'))<p><small class="text-red">{!!$errors->first('ads_title')!!}</small></p> @endif
                        </div>

                        <div class="form-group @if($errors->has('ads_content')) has-error @endif">
                            <label for="ads_content">{{ trans($lang_mod . '.ads_content') }}</label> <span class="text-red">*</span>
                            {!! Form::textarea('ads_content', '', ['id'=>'ads_content', 'class' => 'form-control input-sm', 'placeholder' => 'Required', 'rows' => 10]) !!}
                            @if ($errors->has('ads_content'))<p><small class="text-red">{!!$errors->first('ads_content')!!}</small></p> @endif
                        </div>

                        {{--<div class="form-group">--}}
                            {{--<label for="published">{{ trans($lang_mod . '.published') }}</label>--}}
                            {{--<div class="">--}}
                                {{--<label class="radio-inline">--}}
                                    {{--{!! Form::radio('published', 1, true, ['id'=>'published1']) !!} {{ trans($lang_common . '.radio_yes') }}--}}
                                {{--</label>--}}
                                {{--<label class="radio-inline">--}}
                                    {{--{!! Form::radio('published', 0, false, ['id'=>'published0']) !!} {{ trans($lang_common . '.radio_no') }}--}}
                                {{--</label>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                    </div>

                    <div class="box-footer">
                        <div class="text-right">


                            <a href="{{ url($prefix_url ) . $qs }}" class="btn bg-orange btn-sm btn-flat"><i class="fa fa-reply"></i> {{ trans($lang_common . '.btn_exit') }}</a>
                            <button type="submit" class="btn btn-success btn-sm btn-flat"><i class="fa fa-save"></i> {{ trans($lang_common . '.btn_save') }}</button>
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Trạng Thái</h3>
                    </div>

                    <div class="box-body">
                        {{--STATUS--}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <i class="fa fa-lightbulb-o"></i> <strong>{{ trans($lang_mod . '.status') }}: </strong>
                                    <span id="status_content">
                                        @if($errors->any())
                                        {{ old('status') == 1 ? trans($lang_common . '.radio_show') : trans($lang_common . '.radio_hide') }}
                                        @else
                                        {{ trans($lang_common . '.radio_show') }}
                                        @endif
                                    </span>
                                    <a id="edit_status" href="#" data-status-form="#status_form"><i class="fa fa-edit"></i></a>
                                </div>
                            </div>

                            <div id="status_form" class="row" style="display: none">
                                <div class="container-fluid">
                                    <div class="col-sm-7">
                                        {!! Form::checkbox('status', 1, true, ['id' => 'status', 'class'=>'form-control input-switch', 'data-size' => 'small', 'data-on-text'=>trans($lang_common . '.radio_show'), 'data-off-text'=>trans($lang_common . '.radio_hide')]) !!}

                                    </div>
                                    <div class="col-md-5">
                                        <button type="button" id="save_status_form" data-status-content="#status_content" data-status-form="#status_form" class="btn btn-success btn-flat btn-sm">{{ trans($lang_common . '.btn_save') }}</button>
                                        <button type="button" id="close_status_form" data-status-form="#status_form" class="btn btn-warning btn-flat btn-sm">{{ trans($lang_common . '.btn_close') }}</button>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(function(){

                                    var tmp_status_state = false;

                                    $('#edit_status').on('click', function(e){
                                        e.preventDefault();
                                        var _form_id = $(this).data('status-form');
                                        var _status_form = $(_form_id);
                                        _status_form.fadeIn();
                                        $(this).hide();

                                        tmp_status_state = $('input[name="status"]').bootstrapSwitch('state');
                                    });

                                    $('#save_status_form').on('click', function(){
                                        var _form_id = $(this).data('status-form');
                                        var _content_id = $(this).data('status-content');
                                        var _status_form = $(_form_id);
                                        var _status_content = $(_content_id);

                                        var _content = '{{ trans($lang_common . '.radio_hide') }}';
                                        if($("[name='status']").bootstrapSwitch('state')){
                                            _content = '{{ trans($lang_common . '.radio_show') }}';
                                        }
                                        _status_content.html(_content);

                                        _status_form.fadeOut();
                                        $('#edit_status').show();
                                    });

                                    $('#close_status_form').on('click', function(){
                                        var _form_id = $(this).data('status-form');
                                        var _status_form = $(_form_id);

                                        _status_form.fadeOut();
                                        $('#edit_status').show();

                                        $("[name='status']").bootstrapSwitch('state', tmp_status_state);
                                    });
                                });
                            </script>

                        </div>

                        {{--PUBLISHED--}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <i class="fa fa-eye"></i> <strong>{{ trans($lang_mod . '.publish') }}:</strong> <span id="published_at_content">{{ old('published_at') != '' ? old('published_at') : trans($lang_mod . '.publish_now') }}</span> <a id="edit_published_at" href="#" data-published-at-form="#published_at_form"><i class="fa fa-edit"></i></a>
                                </div>
                            </div>

                            <div id="published_at_form" class="row" style="display: none">
                                <div class="container-fluid">
                                    <div class="col-md-7">

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar-check-o"></i>
                                            </div>
                                            {!! Form::text('published_at', '', ['id'=>'published_at', 'class' => 'form-control input-sm', 'placeholder' => 'DD/MM/YYYY HH:mm']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <button type="button" id="save_published_at_form" data-published-at-content="#published_at_content" data-published-at-form="#published_at_form" class="btn btn-success btn-flat btn-sm">{{ trans($lang_common . '.btn_save') }}</button>
                                        <button type="button" id="close_published_at_form" data-published-at-form="#published_at_form" class="btn btn-warning btn-flat btn-sm">{{ trans($lang_common . '.btn_close') }}</button>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(function(){

                                    var tmp_published_at = '';

                                    $('#edit_published_at').on('click', function(e){
                                        e.preventDefault();
                                        var _form_id = $(this).data('published-at-form');
                                        var _highlight_form = $(_form_id);
                                        _highlight_form.fadeIn();
                                        $(this).hide();

                                        tmp_published_at = $("[name='published_at']").val();

                                    });

                                    $('#save_published_at_form').on('click', function(e){
                                        var _form_id = $(this).data('published-at-form');
                                        var _content_id = $(this).data('published-at-content');
                                        var _published_at_form = $(_form_id);
                                        var _published_at_content = $(_content_id);

                                        //console.log($("[name='is_hot']").bootstrapSwitch('state'));
                                        var _content = '{{ trans($lang_mod . '.publish_now') }}';
                                        //var m = moment($("[name='published_at']").val());
                                        var _val = $("[name='published_at']").val();
                                        if(_val != ''){
                                            _content = _val;

                                            $("[name='status']").bootstrapSwitch('state', false);
                                            $('#status_content').html('{{ trans($lang_common . '.radio_hide') }}');
                                            $('#edit_status').hide();
                                        }
                                        else{
                                            $("[name='status']").bootstrapSwitch('state', true);
                                            $('#status_content').html('{{ trans($lang_common . '.radio_show') }}');
                                            $('#edit_status').show();
                                        }

                                        _published_at_content.html(_content);

                                        _published_at_form.fadeOut();
                                        $('#edit_published_at').show();
                                    });


                                    $('#close_published_at_form').on('click', function(e){

                                        var _form_id = $(this).data('published-at-form');
                                        var _published_at_form = $(_form_id);

                                        _published_at_form.fadeOut();
                                        $('#edit_published_at').show();

                                        $("[name='published_at']").val(tmp_published_at);

                                    });
                                });
                            </script>

                        </div>

                    </div>

                    {{--<div class="box-footer">--}}
                    {{--</div>--}}

                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans($lang_mod . '.display_time') }}</h3>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                        $start_time = '';
                                        $end_time = '';
                                        if(old('display_time') != ''){
                                            $a = explode('-', old('display_time'));
                                            if(count($a) > 0){
                                                $start_time = $a[0];
                                                $end_time = $a[1];
                                            }
                                        }
                                    ?>
                                    <i class="fa fa-calendar"></i> <strong>{{ trans($lang_mod . '.start_time') }}:</strong> <span id="start_time_content">{{ $start_time != '' ? $start_time : trans($lang_common . '.unlimited') }}</span>
                                    <br/><i class="fa fa-calendar"></i> <strong>{{ trans($lang_mod . '.end_time') }}:</strong> <span id="end_time_content">{{ $end_time != '' ? $end_time : trans($lang_common . '.unlimited') }}</span> <a id="edit_display_time" href="#" data-display-time-form="#display_time_form"><i class="fa fa-edit"></i></a>

                                </div>
                            </div>

                            <div id="display_time_form" class="row" style="display: none">
                                <div class="container-fluid">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar-check-o"></i>
                                                </div>
                                                {!! Form::text('display_time', '', ['id'=>'display_time', 'class' => 'form-control input-sm', 'placeholder' => '']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-right">
                                        <button type="button" id="save_display_time_form" data-display-start-time-content="#start_time_content" data-display-end-time-content="#end_time_content" data-display-time-form="#display_time_form" class="btn btn-success btn-flat btn-sm">{{ trans($lang_common . '.btn_save') }}</button>
                                        <button type="button" id="close_display_time_form"  data-display-time-form="#display_time_form" class="btn btn-warning btn-flat btn-sm">{{ trans($lang_common . '.btn_close') }}</button>
                                    </div>
                                </div>

                            </div>

                            <script>
                                $(function(){

                                    var tmp_display_time = '';

                                    $('#edit_display_time').on('click', function(e){

                                        e.preventDefault();
                                        var _form_id = $(this).data('display-time-form');
                                        var _display_form = $(_form_id);
                                        _display_form.fadeIn();
                                        $(this).hide();

                                        tmp_display_time = $("[name='display_time']").val();

                                    });

                                    $('#save_display_time_form').on('click', function(){
                                        var _form_id = $(this).data('display-time-form');
                                        var _start_content_id = $(this).data('display-start-time-content');
                                        var _end_content_id = $(this).data('display-end-time-content');
                                        var _display_time_form = $(_form_id);
                                        var _start_content = $(_start_content_id);
                                        var _end_content = $(_end_content_id);

                                        //console.log($("[name='is_hot']").bootstrapSwitch('state'));
                                        var _start_content_val = '{{ trans($lang_common . '.unlimited') }}';
                                        var _end_content_val = '{{ trans($lang_common . '.unlimited') }}';

                                        //var m = moment($("[name='published_at']").val());
                                        var _val = $("[name='display_time']").val();
                                        if(_val != ''){
                                            var atime = _val.split('-');
                                            _start_content_val = atime[0];
                                            _end_content_val = atime[1];
                                        }

                                        _start_content.html(_start_content_val);
                                        _end_content.html(_end_content_val);

                                        //console.log(_start_content, _end_content);

                                        _display_time_form.fadeOut();
                                        $('#edit_display_time').show();
                                    });


                                    $('#close_display_time_form').on('click', function(){
                                        var _form_id = $(this).data('display-time-form');
                                        var _display_time_form = $(_form_id);

                                        _display_time_form.fadeOut();
                                        $('#edit_display_time').show();

                                        $("[name='display_time']").val(tmp_display_time);
                                    });


                                });
                            </script>

                        </div>
                    </div>

                    {{--<div class="box-footer">--}}
                    {{--</div>--}}

                </div>


            </div>

            {!! Form::close() !!}
        </div>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_minimal-blue',
                    radioClass: 'iradio_minimal-blue',
                });

                $(".input-switch").bootstrapSwitch();

                //Date picker
                $('#published_at').datetimepicker({
                    format: 'DD/MM/YYYY HH:mm',
                    useCurrent: false,
                    showClose: true,
                    locale: moment().locale('vi')
                });


                //Daterange Picker
                $('input[name="display_time"]').daterangepicker({
                    timePicker: true,
                    timePickerIncrement: 5,
                    timePicker24Hour: true,
                    autoUpdateInput: false,
                    opens: 'left',
                    locale:{
                        format: 'DD/MM/YYYY HH:mm',
                        cancelLabel: '{{ trans($lang_common . '.btn_clear') }}',
                        applyLabel: '{{ trans($lang_common . '.btn_ok') }}'

                    }

                });


                $('input[name="display_time"]').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm') + ' - ' + picker.endDate.format('DD/MM/YYYY HH:mm'));
                });

                $('input[name="display_time"]').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });

            });
        </script>
    </section>

@stop