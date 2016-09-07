
@extends('Backend::layouts.master')

@section('content')

    <section class="content-header">
        <h1>
            {{ trans($lang_mod . '.news_create_header') }}
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
        {!! Form::open(['url' => Request::url() . $qs, 'name' => 'newsForm', 'id' => 'newsForm', 'role' => 'form']) !!}
        <div class="row">
            <div class="col-md-8">
                <div class="box box-primary">

                    <div class="box-body">
                        <div class="form-group @if($errors->has('cat_id')) has-error @endif">
                            <label for="cat_id">{{ trans($lang_mod . '.cat_id') }}</label> <span class="text-red">*</span>
                            <select name="cat_id" id="cat_id" class="form-control input-sm">
                                <option value="">---{{ trans($lang_mod . '.select_category') }}---</option>
                                @foreach($tree_data as $item)
                                    <option value="{{$item->id}}" @if($item->parent_id == 0) disabled @endif @if(old('cat_id') == $item->id) selected @endif>{{ $item->cat_name_tmp }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('cat_id'))<p><small class="text-red">{!!$errors->first('cat_id')!!}</small></p> @endif
                        </div>

                        <div class="form-group @if($errors->has('title')) has-error @endif">
                            <label for="">{{ trans($lang_mod . '.title') }}</label> <span class="text-red">*</span>
                            {!! Form::text('title', '', ['id'=>'title', 'class' => 'form-control input-sm', 'placeholder' => 'Required']) !!}
                            @if ($errors->has('title'))<p><small class="text-red">{!!$errors->first('title')!!}</small></p> @endif
                        </div>

                        <div class="form-group">
                            <label for="">{{ trans($lang_mod . '.alias') }}</label>
                            {!! Form::text('alias', '', ['id'=>'alias', 'class' => 'form-control input-sm', 'placeholder' => '']) !!}

                        </div>

                        <div class="form-group">
                            <label for="intro_content">{{ trans($lang_mod . '.intro_content') }}</label>
                            {!! Form::textarea('intro_content', '', ['class' => 'form-control intro-content-editor', 'placeholder' => '', 'id'=>'intro_content', 'style' => 'height: 90px']) !!}
                        </div>

                        <div class="form-group @if($errors->has('main_content')) has-error @endif">
                            <label for="main_content">{{ trans($lang_mod . '.main_content') }}</label> <span class="text-red">*</span>
                            <div style="margin-bottom: 2px;">
                                <input type="file" name="file_uploader" data-unique-id="1" multiple=true />
                            </div>
                            {!! Form::textarea('main_content', '', ['class' => 'form-control main-content-editor', 'placeholder' => '', 'id'=>'main_content']) !!}
                            @if ($errors->has('main_content'))<p><small class="text-red">{!!$errors->first('main_content')!!}</small></p> @endif
                        </div>

                        {{--<div class="form-group">--}}
                            {{--<label for="news_relations">{{ trans($lang_mod . '.news_relations') }}</label>--}}
                            {{--<div id="news_relations" class="list-group">--}}

                            {{--</div>--}}
                        {{--</div>--}}

                    </div>

                </div>

                {{--NEWS RELATIONS--}}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans($lang_mod . '.news_relations') }} <span id="total_relation"></span></h3>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            <div id="news_relations_container" class="col-md-12 news-relations-container">
                                <div id="news_relations" class="list-group news-relations-list">
                                    @if($relations != null)
                                        @foreach($relations as $k => $r)
                                            <div id="relation_item_{{ $r->id }}" class="list-group-item">
                                                <a href="#"><h5 class="list-group-item-heading">{{ $r->title }}</h5></a>
                                                <small class="list-group-item-text"><small>{{ $r->intro_content }}</small></small>
                                                <div class="row">
                                                    <div class="col-md-12 text-right">
                                                        <button id="delete_relation_{{ $r->id }}" type="button" class="btn btn-xs btn-flat btn-danger btn-delete-relations" data-relation-id="{{ $r->id }}"><i class="fa fa-remove"></i> {{ trans($lang_common . '.btn_delete') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            {!! Form::hidden('relation_ids', $news_relation_ids, ['id'=>'relation_ids']) !!}
                        </div>
                        <script>
                            $(function(){
                                $('#news_relations_container').slimScroll({
                                    height: '200px'
                                });
                            });
                        </script>
                    </div>
                </div>

            </div>

            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans($lang_mod . '.status_block_header') }}</h3>

                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <button id="btn_save_draft" type="button" class="btn btn-flat bg-navy btn-sm pull-left"><i class="fa fa-save"></i> {{ trans($lang_common . '.btn_draft') }}</button>
                                    {!! Form::hidden('save_draft', 0, ['id'=>'save_draft']) !!}

                                    <a href="{{ url($prefix_url . '/preview', []) . $qs }}" class="btn btn-flat btn-info btn-sm pull-right"><i class="fa fa-eye"></i> {{ trans($lang_common . '.btn_preview') }}</a>
                                </div>
                                <script>
                                    $(function(){
                                        $('#btn_save_draft').on('click', function(){
                                            $(window).off("beforeunload");
                                            $('input:hidden[id="save_draft"]').val(1);
                                            $('#newsForm').submit();
                                        });
                                    });
                                </script>
                            </div>
                        </div>


                        {{--STATUS--}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <i class="fa fa-lightbulb-o"></i> <strong>{{ trans($lang_mod . '.status') }}: </strong>
                                    <span id="status_content">{{ old('status') == null ? trans($lang_mod . '.' . config('backend.news_status.NEWS_DRAFT_STATUS')) : $news_status[old('status')] }}</span>
                                    <a id="edit_status" href="#" data-status-form="#status_form"><i class="fa fa-edit"></i></a>
                                </div>
                            </div>
                            <div id="status_form" class="row" style="display: none">
                                <div class="container-fluid">
                                    <div class="col-sm-7">
                                        {!! Form::select('status', $news_status, '', ['class' => 'form-control input-sm', 'id' => 'status']) !!}
                                    </div>
                                    <div class="col-md-5">
                                        <button type="button" id="save_status_form" data-status-content="#status_content" data-status-form="#status_form" class="btn btn-success btn-flat btn-sm">{{ trans($lang_common . '.btn_save') }}</button>
                                        <button type="button" id="close_status_form" data-status-form="#status_form" class="btn btn-warning btn-flat btn-sm">{{ trans($lang_common . '.btn_close') }}</button>
                                    </div>
                                </div>

                            </div>
                            <script>
                                $(function(){
                                    var tmp_status = '';
                                    $('#edit_status').on('click', function(e){
                                        e.preventDefault();
                                        var _form_id = $(this).data('status-form');
                                        var _status_form = $(_form_id);
                                        _status_form.fadeIn();
                                        $(this).hide();

                                        tmp_status = $('#status option:selected').val();

                                    });

                                    $('#save_status_form').on('click', function(e){
                                        var _form_id = $(this).data('status-form');
                                        var _content_id = $(this).data('status-content');
                                        var _status_form = $(_form_id);
                                        var _status_content = $(_content_id);
                                        _status_content.html($('#status option:selected').text());

                                        _status_form.fadeOut();
                                        $('#edit_status').show();
                                    });

                                    $('#close_status_form').on('click', function(e){
                                        var _form_id = $(this).data('status-form');
                                        var _status_form = $(_form_id);

                                        _status_form.fadeOut();
                                        $('#edit_status').show();
                                        $('#status').val(tmp_status);

//                                        window.setTimeout( function(){
//                                            $('#edit_status').show();
//                                        }, 500);

                                    });
                                });
                            </script>
                        </div>

                        {{--HOT NEWS--}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <i class="fa fa-fire"></i> <strong>{{ trans($lang_mod . '.is_hot') }}:</strong>
                                    <span id="hot_content">{{ old('is_hot') == 1 ? trans($lang_common . '.radio_yes') : trans($lang_common . '.radio_no') }}</span>
                                    <a id="edit_hot" href="#" data-hot-form="#hot_form"><i class="fa fa-edit"></i></a>
                                </div>
                            </div>

                            <div id="hot_form" class="row" style="display: none">
                                <div class="container-fluid">
                                    <div class="col-sm-7">
                                        {!! Form::checkbox('is_hot', 1, false, ['id' => 'is_hot', 'class'=>'form-control input-switch', 'data-size' => 'small', 'data-on-text'=>trans($lang_common . '.radio_yes'), 'data-off-text'=>trans($lang_common . '.radio_no')]) !!}
                                    </div>

                                    <div class="col-sm-5">
                                        <button type="button" id="save_hot_form" data-hot-content="#hot_content" data-hot-form="#hot_form" class="btn btn-success btn-flat btn-sm">{{ trans($lang_common . '.btn_save') }}</button>
                                        <button type="button" id="close_hot_form" data-hot-form="#hot_form" class="btn btn-warning btn-flat btn-sm">{{ trans($lang_common . '.btn_close') }}</button>
                                    </div>
                                </div>
                            </div>

                            <script>
                                $(function(){

                                    var tmp_hot = false;

                                    var tmp_highlight_time_1 = '';
                                    var tmp_highlight_1 = false;

                                    $('#edit_hot').on('click', function(e){
                                        e.preventDefault();
                                        var _form_id = $(this).data('hot-form');
                                        var _hot_form = $(_form_id);
                                        _hot_form.fadeIn();
                                        $(this).hide();

                                        tmp_hot = $("[name='is_hot']").bootstrapSwitch('state');

                                    });

                                    $('#save_hot_form').on('click', function(e){
                                        var _form_id = $(this).data('hot-form');
                                        var _content_id = $(this).data('hot-content');
                                        var _hot_form = $(_form_id);
                                        var _hot_content = $(_content_id);

                                        //console.log($("[name='is_hot']").bootstrapSwitch('state'));
                                        var _content = '{{ trans($lang_common . '.radio_no') }}';
                                        if($("[name='is_hot']").bootstrapSwitch('state')){
                                            _content = '{{ trans($lang_common . '.radio_yes') }}';

                                            tmp_highlight_1 = $("[name='is_highlight']").bootstrapSwitch('state');
                                            tmp_highlight_time_1 = $("#highlight_time").val();

                                            $("[name='is_highlight']").bootstrapSwitch('state', false);
                                            $('#highlight_content').html('{{ trans($lang_common . '.radio_no') }}');
                                            $("#highlight_time").val('');
                                            $('#edit_highlight').hide();


                                        }
                                        else{
                                            $("[name='is_highlight']").bootstrapSwitch('state', tmp_highlight_1);

                                            $("#highlight_time").val(tmp_highlight_time_1);

                                            if(tmp_highlight_1)
                                                $('#highlight_content').html('{{ trans($lang_common . '.radio_yes') }}');
                                            else
                                                $('#highlight_content').html('{{ trans($lang_common . '.radio_no') }}');
                                            $('#edit_highlight').show();

                                        }
                                        _hot_content.html(_content);

                                        _hot_form.fadeOut();
                                        $('#edit_hot').show();
                                    });

                                    $('#close_hot_form').on('click', function(e){
                                        var _form_id = $(this).data('hot-form');
                                        var _hot_form = $(_form_id);

                                        _hot_form.fadeOut();
                                        $('#edit_hot').show();
                                        $("[name='is_hot']").bootstrapSwitch('state', tmp_hot);

                                    });


                                });
                            </script>

                        </div>

                        {{--PUBLISHED--}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <i class="fa fa-eye"></i> <strong>{{ trans($lang_mod . '.publish') }}:</strong>
                                    <span id="published_at_content">{{ strval(old('published_at')) == '' ? trans($lang_mod . '.publish_now') : old('published_at') }}</span>
                                    <a id="edit_published_at" href="#" data-published-at-form="#published_at_form"><i class="fa fa-edit"></i></a>
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
                                        var __published_at_content = $(_content_id);

                                        //console.log($("[name='is_hot']").bootstrapSwitch('state'));
                                        var _content = '{{ trans($lang_mod . '.publish_now') }}';
                                        //var m = moment($("[name='published_at']").val());
                                        var _val = $("[name='published_at']").val();
                                        if(_val != ''){
                                            _content = _val;
                                        }
                                        __published_at_content.html(_content);

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

                    <div class="box-footer">

                        <a href="{{ url($prefix_url . '/cancel/create' ) . $qs }}" class="btn btn-flat bg-orange btn-sm pull-left"><i class="fa fa-reply"></i> {{ trans($lang_common . '.btn_exit') }}</a>

                        <button id="btn_ok" type="button" class="btn btn-flat btn-success btn-sm pull-right"><i class="fa fa-save"></i> {{ trans($lang_common . '.btn_ok') }}</button>
                    </div>
                    <script>
                        $(function(){
                            $('#btn_ok').on('click', function(e){
                                //e.preventDefault();
                                $(window).off("beforeunload");
                                $('#newsForm').submit();
                            });
                        });
                    </script>

                </div>

                {{--HIGHLIGHT--}}
                <div class="box box-primary">
                    <div class="box-header with-border">

                        <h3 class="box-title">{{ trans($lang_mod . '.highlight_block_header') }}</h3>
                    </div>

                    <div class="box-body">


                        {{--HOME_HIGHLIGHT--}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <i class="fa fa-folder-open"></i> <strong>{{ trans($lang_mod . '.is_highlight') }}:</strong>
                                    <span id="highlight_content">{{ strval(old('is_highlight')) == '' ? trans($lang_common . '.radio_no') : trans($lang_common . '.radio_yes') }}</span>
                                    <a id="edit_highlight" href="#" data-highlight-form="#highlight_form"><i class="fa fa-edit"></i></a>
                                </div>
                            </div>

                            <div id="highlight_form" class="row" style="display: none">
                                <div class="container-fluid">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::checkbox('is_highlight', 1, false, ['id' => 'is_highlight', 'class'=>'form-control input-switch', 'data-size' => 'small', 'data-on-text'=>trans($lang_common . '.radio_yes'), 'data-off-text'=>trans($lang_common . '.radio_no')]) !!}
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans($lang_mod . '.period_time') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar-check-o"></i>
                                                </div>
                                                {!! Form::text('highlight_time', '', ['id'=>'highlight_time', 'class' => 'form-control input-sm', 'placeholder' => '', 'disabled'=>true]) !!}
                                            </div>

                                        </div>


                                    </div>

                                    <div class="col-md-12 text-right">
                                        <button type="button" id="save_highlight_form" data-highlight-content="#highlight_content" data-highlight-form="#highlight_form" class="btn btn-success btn-flat btn-sm">{{ trans($lang_common . '.btn_save') }}</button>
                                        <button type="button" id="close_highlight_form" data-highlight-form="#highlight_form" class="btn btn-warning btn-flat btn-sm">{{ trans($lang_common . '.btn_close') }}</button>
                                    </div>
                                </div>

                            </div>

                            <script>
                                $(function(){
                                    var tmp_highlight_time = '';
                                    var tmp_highlight = false;

                                    $('#edit_highlight').on('click', function(e){
                                        e.preventDefault();
                                        var _form_id = $(this).data('highlight-form');
                                        var _highlight_form = $(_form_id);
                                        _highlight_form.fadeIn();
                                        $(this).hide();

                                        tmp_highlight_time = $("[name='highlight_time']").val();
                                        tmp_highlight = $("[name='is_highlight']").bootstrapSwitch('state');
                                    });

                                    $('#save_highlight_form').on('click', function(e){
                                        var _form_id = $(this).data('highlight-form');
                                        var _content_id = $(this).data('highlight-content');
                                        var _highlight_form = $(_form_id);
                                        var _highlight_content = $(_content_id);

                                        //console.log($("[name='is_hot']").bootstrapSwitch('state'));
                                        var _content = '{{ trans($lang_common . '.radio_no') }}';
                                        if($("[name='is_highlight']").bootstrapSwitch('state')){
                                            _content = '{{ trans($lang_common . '.radio_yes') }}';
                                        }
                                        _highlight_content.html(_content);

                                        _highlight_form.fadeOut();
                                        $('#edit_highlight').show();
                                    });

                                    $('#close_highlight_form').on('click', function(e){

                                        var _form_id = $(this).data('highlight-form');
                                        var _highlight_form = $(_form_id);

                                        _highlight_form.fadeOut();
                                        $('#edit_highlight').show();

                                        $("[name='is_highlight']").bootstrapSwitch('state', tmp_highlight);

                                        $('input[name="highlight_time"]').prop('disabled', !tmp_highlight)

                                        $("[name='highlight_time']").val(tmp_highlight_time);



                                    });

                                    $('input[name="is_highlight"]').on('switchChange.bootstrapSwitch', function(event, state) {
                                        if(state == false){
                                            $('input[name="highlight_time"]').val('');
                                        }

                                        $('input[name="highlight_time"]').prop('disabled', !state)
                                    });
                                });
                            </script>

                        </div>

                        {{--CAT_HIGHLIGHT--}}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <i class="fa fa-folder-open"></i> <strong>{{ trans($lang_mod . '.is_cat_highlight') }}:</strong>
                                    <span id="cat_highlight_content">{{ strval(old('is_cat_highlight')) == '' ? trans($lang_common . '.radio_no') : trans($lang_common . '.radio_yes') }}</span>
                                    <a id="edit_cat_highlight" href="#" data-cat-highlight-form="#cat_highlight_form"><i class="fa fa-edit"></i></a>
                                </div>
                            </div>

                            <div id="cat_highlight_form" class="row" style="display: none">
                                <div class="container-fluid">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::checkbox('is_cat_highlight', 1, false, ['id' => 'is_cat_highlight', 'class'=>'form-control input-switch', 'data-size' => 'small', 'data-on-text'=>trans($lang_common . '.radio_yes'), 'data-off-text'=>trans($lang_common . '.radio_no')]) !!}
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans($lang_mod . '.period_time') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar-check-o"></i>
                                                </div>
                                                {!! Form::text('cat_highlight_time', '', ['id'=>'cat_highlight_time', 'class' => 'form-control input-sm', 'placeholder' => '', 'disabled'=>true]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-right">
                                        <button type="button" id="save_cat_highlight_form" data-cat-highlight-content="#cat_highlight_content" data-cat-highlight-form="#cat_highlight_form" class="btn btn-success btn-flat btn-sm">{{ trans($lang_common . '.btn_save') }}</button>
                                        <button type="button" id="close_cat_highlight_form" data-cat-highlight-form="#cat_highlight_form" class="btn btn-warning btn-flat btn-sm">{{ trans($lang_common . '.btn_close') }}</button>
                                    </div>
                                </div>

                            </div>

                            <script>
                                $(function(){
                                    var tmp_cat_highlight_time = '';
                                    var tmp_cat_highlight = false;

                                    $('#edit_cat_highlight').on('click', function(e){
                                        e.preventDefault();
                                        var _form_id = $(this).data('cat-highlight-form');
                                        var _cat_highlight_form = $(_form_id);
                                        _cat_highlight_form.fadeIn();
                                        $(this).hide();

                                        tmp_cat_highlight_time = $("[name='cat_highlight_time']").val();
                                        tmp_cat_highlight = $("[name='is_cat_highlight']").bootstrapSwitch('state');
                                    });

                                    $('#save_cat_highlight_form').on('click', function(e){
                                        var _form_id = $(this).data('cat-highlight-form');
                                        var _content_id = $(this).data('cat-highlight-content');
                                        var _cat_highlight_form = $(_form_id);
                                        var _cat_highlight_content = $(_content_id);

                                        //console.log($("[name='is_hot']").bootstrapSwitch('state'));
                                        var _content = '{{ trans($lang_common . '.radio_no') }}';
                                        if($("[name='is_cat_highlight']").bootstrapSwitch('state')){
                                            _content = '{{ trans($lang_common . '.radio_yes') }}';
                                        }
                                        _cat_highlight_content.html(_content);

                                        _cat_highlight_form.fadeOut();
                                        $('#edit_cat_highlight').show();
                                    });

                                    $('#close_cat_highlight_form').on('click', function(e){

                                        var _form_id = $(this).data('cat-highlight-form');
                                        var _cat_highlight_form = $(_form_id);

                                        _cat_highlight_form.fadeOut();
                                        $('#edit_cat_highlight').show();

                                        $("[name='is_cat_highlight']").bootstrapSwitch('state', tmp_cat_highlight);

                                        $('input[name="cat_highlight_time"]').prop('disabled', !tmp_cat_highlight)

                                        $("[name='cat_highlight_time']").val(tmp_cat_highlight_time);



                                    });

                                    $('input[name="is_cat_highlight"]').on('switchChange.bootstrapSwitch', function(event, state) {
                                        if(state == false){
                                            $('input[name="cat_highlight_time"]').val('');
                                        }

                                        $('input[name="cat_highlight_time"]').prop('disabled', !state)
                                    });
                                });
                            </script>

                        </div>

                    </div>

                    {{--<div class="box-footer">--}}
                    {{--</div>--}}
                </div>

                {{--NEWS_TYPE--}}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans($lang_mod . '.news_type') }}</h3>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                        @foreach($news_type as $k => $type)
                            <div class="checkbox">
                                <label>
                                    {!! Form::radio('news_type', $k, ($k == $checked_type), ['id' => 'news_type_' . $k]) !!} <i class="{{ $type[0] }}"></i> <strong>{{ $type[1] }}</strong>
                                </label>
                            </div>
                        @endforeach
                        </div>
                    </div>

                    {{--<div class="box-footer">--}}
                    {{--</div>--}}
                </div>


                {{--FEATURED IMAGE--}}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans($lang_mod . '.featured_image') }}</h3>
                    </div>

                    <div class="box-body">
                        <div id='thumbnail_featured_image' class="thumbnail" style='display: ;'>
                            <?php
                            $h_img = 150;
                            $w_img = intval($h_img * 4 / 3);
                            ?>
                            <img src="{{asset('media/local/img/cover_featured_image.png')}}" data-unique-id="2" style="zoom: 70%"/>
                            {!! Form::hidden('featured_image', '', ['id'=>'featured_image']) !!}
                        </div>
                        <button id="select_featured_image" type="button" class="btn btn-xs btn-flat bg-maroon" data-unique-id="2"><i class="fa fa-photo"></i> {{ trans($lang_common . '.btn_select_image') }}</button>
                        <button id="clear_featured_image" type="button" class="btn btn-xs btn-flat bg-orange pull-right" style='display: none;' ><i class="fa fa-remove"></i> {{ trans($lang_common . '.btn_delete') }}</button>
                    </div>

                    {{--<div class="box-footer">--}}
                    {{--</div>--}}
                </div>

                {{--TAGS--}}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans($lang_mod . '.tags') }}</h3>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            {!! Form::text('tags', '', ['id'=>'tags', 'class' => 'form-control input-sm', 'placeholder' => '']) !!}
                        </div>
                        <script>
                            $(function(){
                                $("input[id='tags']").tagsinput({
                                    tagClass: 'label bg-olive'
                                });

                                $('.bootstrap-tagsinput').css('width', '100%');

                                var rids = [];

                                $("input[id='tags']").on('change', function(){

                                    $.ajax({
                                        url: '{{ url($prefix_url . '/related-news', []) }}',
                                        type: 'POST',
                                        data: { tags: $('input[id="tags"]').val() },
                                        cache: false,

                                        beforeSend: function(){

                                        },

                                        success: function(response){
                                            console.log(response);
                                            var relation_list = $('#news_relations');
                                            var relation_ids = $('#relation_ids');
                                            var total_relation = $('#total_relation');

                                            relation_list.html('');
                                            rids = [];
                                            for(var i=0; i<response.length; i++){
                                                rids[i] = response[i].id;

                                                var item = $([
                                                        '<div id="relation_item_'+response[i].id+'" class="list-group-item">',
                                                        '   <a href="#"><h5 class="list-group-item-heading">'+response[i].title+'</h5></a>',
                                                        '   <small class="list-group-item-text"><small>'+response[i].intro_content+'</small></small>',
                                                        '   <div class="row">',
                                                        '       <div class="col-md-12 text-right">',
                                                        '           <button id="delete_relation_'+response[i].id+'" type="button" class="btn btn-xs btn-flat btn-danger btn-delete-relations" data-relation-id="'+response[i].id+'"><i class="fa fa-remove"></i> {{ trans($lang_common . '.btn_delete') }}</button>',
                                                        '       </div>',
                                                        '   </div>',
                                                        '<div/>'
                                                ].join(''));

                                                relation_list.append(item);


                                            }

                                            relation_ids.val(rids.join(','));

                                            console.log(rids.length);

                                            if(rids.length > 0){
                                                total_relation.html('('+rids.length+')');
                                            }
                                            else{
                                                total_relation.html('');
                                            }


                                            relation_list.find('button.btn-delete-relations').on('click', function(){
                                                var cur_id = $(this).data('relation-id');
                                                var j = rids.indexOf(cur_id);
                                                if(j != -1){
                                                    $('#relation_item_' + cur_id).fadeOut(300, function(){
                                                        $(this).remove();
                                                    });
                                                    rids.splice(j, 1);
                                                    //relation_ids.val(rids.join(','));
                                                }

                                                if(rids.length > 0){
                                                    total_relation.html('('+rids.length+')');
                                                }
                                                else{
                                                    total_relation.html('');
                                                }
                                                //console.log(relation_ids.val());
                                            });


                                        },

                                        error: function(response, status, error){
                                            console.log(response.responseText);
                                        }
                                    });

                                });

                            });
                        </script>
                    </div>

                    {{--<div class="box-footer">--}}
                    {{--</div>--}}
                </div>

                {{--SEO--}}
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans($lang_mod . '.seo_header_block') }}</h3>
                    </div>

                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs " role="tablist">
                                <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a></li>
                                <li role="presentation" class=""><a href="#social" aria-controls="social" role="tab" data-toggle="tab">Social</a></li>
                            </ul>

                            <div class="tab-content">
                                {{--GENERAL--}}
                                <div class="tab-pane fade in active" id="general">
                                    <div content="col-md-12">
                                        <div class="form-group">
                                            <label>{{ trans($lang_mod . '.meta_title') }}</label>
                                            {!! Form::text('meta_title', '', ['id'=>'meta_title', 'class' => 'form-control input-sm', 'placeholder' => '']) !!}
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans($lang_mod . '.meta_keywords') }}</label>
                                            {!! Form::text('meta_keywords', '', ['id'=>'meta_keywords', 'class' => 'form-control input-sm', 'placeholder' => '']) !!}
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans($lang_mod . '.meta_description') }}</label>
                                            {!! Form::textarea('meta_description', '', ['id'=>'meta_description', 'class' => 'form-control input-sm', 'rows' => 3, 'placeholder' => '']) !!}
                                        </div>

                                    </div>

                                </div>

                                {{--SOCIAL--}}
                                <div class="tab-pane fade in" id="social">
                                    <div content="col-md-12">
                                        <div class="form-group">
                                            <label>{{ trans($lang_mod . '.og_title') }}</label>
                                            {!! Form::text('og_title', '', ['id'=>'og_title', 'class' => 'form-control input-sm', 'placeholder' => '']) !!}
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans($lang_mod . '.og_description') }}</label>
                                            {!! Form::textarea('og_description', '', ['id'=>'og_description', 'class' => 'form-control input-sm', 'rows' => 3, 'placeholder' => '']) !!}
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans($lang_mod . '.og_image') }}</label>
                                            {!! Form::text('og_image', '', ['id'=>'og_image', 'class' => 'form-control input-sm', 'rows' => 3, 'placeholder' => '']) !!}
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    {{--<div class="box-footer">--}}
                    {{--</div>--}}
                </div>

            </div>
        </div>
        {!! Form::hidden('id', $draft->id) !!}
        {!! Form::close() !!}
    </section>

    <script>

        $(window).on("beforeunload", function() {
            return "{{ trans($lang_mod . '.leave_page') }}";
        });

        $.ajaxSetup({
            headers: {
                //'X-CSRF-TOKEN': $('input[name="_token"]').val()
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function(){

            //console.log($('meta[name="csrf-token"]').attr('content'));

            $('input[name="news_type"]').iCheck({
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
            $('input[name="cat_highlight_time"], input[name="highlight_time"]').daterangepicker({
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


            $('input[name="highlight_time"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm') + ' - ' + picker.endDate.format('DD/MM/YYYY HH:mm'));
            });

            $('input[name="highlight_time"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            $('input[name="cat_highlight_time"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm') + ' - ' + picker.endDate.format('DD/MM/YYYY HH:mm'));
            });

            $('input[name="cat_highlight_time"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });


            $('#title').keyup(function(){
                $('#meta_title').prop('placeholder', $(this).val());
            });

            {{--$('textarea#intro_content').tinymce({--}}
                {{--//selector: 'textarea',--}}
                {{--height: 100,--}}
                {{--theme: 'modern',--}}
                {{--menubar: false,--}}

                {{--toolbar: 'bold italic | alignleft aligncenter alignright alignjustify',--}}
                {{--content_css: [--}}
                    {{--'{{ asset('media/local/css/editor.css') }}'--}}
                {{--]--}}
            {{--});--}}

            $('textarea#main_content').tinymce({
                //selector: 'textarea',
                height: 350,
                theme: 'modern',
                entity_encoding : 'raw',
                encoding: 'utf-8',
                convert_urls : false,
                plugins: [
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality',
                    'emoticons paste textcolor colorpicker textpattern imagetools'
                ],
                toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                toolbar2: 'print preview media | forecolor backcolor emoticons',
                image_advtab: true,
                image_caption: true,
                // templates: [
                //     { title: 'Test template 1', content: 'Test 1' },
                //     { title: 'Test template 2', content: 'Test 2' }
                // ],
                content_css: [
                    '{{ asset('media/local/css/editor.css') }}'
                ]
            });


            $("input[name='file_uploader']").ViiUploader({
                uniqueId            :   '10',
                uploadUrl           :   '{{ url($prefix_url . '/upload-file', []) }}',
                params              :   {news_id: '{{ $draft->id }}'},
                getImagesUrl        :   '{{ url($prefix_url . '/media-list', [$draft->id]) }}',
                //csrfMetaName      :   '',
                coverUpload         :   '{{asset('media/local/img/cover-upload.png')}}',
                btnOpenModalClass   :   'btn btn-xs btn-success btn-flat',
                insertImage         : function(files, from_url){

                    for(var key in files){
                        tinymce.get("main_content").execCommand('mceInsertContent', false, '<img src="'+files[key].uri+'"/> ');
                    }
                }
            });

            $("#select_featured_image").ViiUploader({
                acccept             :   'image/*',
                uniqueId            :   '11',
                uploadUrl           :   '{{ url($prefix_url . '/upload-file', []) }}',
                params              :   {news_id: '{{ $draft->id }}'},
                getImagesUrl        :   '{{ url($prefix_url . '/media-list', [$draft->id]) }}',
                coverUpload         :   '{{ asset('media/local/img/cover-upload.png') }}',
                fileUploadName      :   'file_uploader',
                insertImage         : function(files, from_url){
                    for(var key in files){
                        //console.log(files[key]);
                        $('#thumbnail_featured_image > img').attr('src', files[key].uri);
                        if(!from_url){
                            $('input:hidden[id="featured_image"]').val(files[key].file_name);
                        }
                        else{
                            $('input:hidden[id="featured_image"]').val(files[key].uri);
                        }

                        $('#thumbnail_featured_image').show();
                        break;
                    }

                    $('#clear_featured_image').show();

                }
            });

            $('#clear_featured_image').on('click', function(){
                $('#thumbnail_featured_image > img').attr('src', '{{ asset('media/local/img/cover_featured_image.png') }}');
                $('input:hidden[id="featured_image"]').val('');
                $(this).hide();
            });

        });


    </script>

@stop