@extends('Backend::layouts.master')

@section('content')
    <section class="content-header">
        <h1>
            {{ trans($lang_mod . '.menu_edit_header') }}
            <small></small>
        </h1>
        <!--ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol-->
    </section>

    <section class="content">
        {!! Form::open(['url' => Request::url() . $qs, 'method'=>'put', 'name' => 'menuForm', 'id' => 'menuForm', 'role' => 'form']) !!}
        <div class="row">
            <div class="col-md-4">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ trans($lang_mod . '.menu_source') }}</h3>
                    </div>
                    <div class="box-body">

                        <div class="box-group" id="accordion">

                            <div class="panel box box-warning">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                            {{ trans($lang_mod . '.category_title') }}
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="category-list">
                                                    @if(count($tree_cat) > 0)
                                                        @foreach($tree_cat as $item)
                                                            <div class="checkbox">
                                                                <label>

                                                                    <input name="cat_list" type="checkbox" value="{{ $item->id . '|'. $item->cat_name }}" @if($item->published == 0) disabled @endif>

                                                                    {{ $item->cat_name_tmp }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box-footer">
                                        <button id="btn_select_all" type="button" data-toggle-state="0" class="btn btn-xs btn-flat pull-left">{{ trans($lang_mod . '.btn_check_all') }}</button>
                                        <button id="btn_add_item_cat" type="button" class="btn btn-xs btn-flat bg-purple pull-right">{{ trans($lang_mod . '.btn_add_to_menu') }} <i class="fa fa-angle-double-right"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="panel box box-success">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                            {{ trans($lang_mod . '.custom_url_title') }}
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="menu_custom_url">{{ trans($lang_mod . '.url') }}</label> <span class="text-red">*</span>
                                                    {!! Form::text('menu_custom_url', 'http://', ['id'=>'menu_custom_url', 'class' => 'form-control input-sm', 'placeholder' => 'Required']) !!}
                                                </div>

                                                <div class="form-group">
                                                    <label for="menu_custom_url_text">{{ trans($lang_mod . '.url_text') }}</label> <span class="text-red">*</span>
                                                    {!! Form::text('menu_custom_url_text', '', ['id'=>'menu_custom_url_text', 'class' => 'form-control input-sm', 'placeholder' => "Required"]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box-footer">
                                        <button id="btn_add_item_url" type="button" class="btn btn-xs btn-flat bg-purple pull-right">{{ trans($lang_mod . '.btn_add_to_menu') }} <i class="fa fa-angle-double-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <script>
                    $(function(){
                        $('.category-list').slimScroll({
                            height: '200'
                        });

                        $('button#btn_select_all').on('click', function(){
                            var state = $(this).data('toggle-state');
                            if(state == '0'){
                                $('input[name="cat_list"]').iCheck('check');
                                $(this).data('toggle-state', '1');
                                $(this).html('{{ trans($lang_mod . '.btn_uncheck_all') }}');
                            }
                            else{
                                $('input[name="cat_list"]').iCheck('uncheck');
                                $(this).data('toggle-state', '0');
                                $(this).html('{{ trans($lang_mod . '.btn_check_all') }}');
                            }

                        });

                        $('button#btn_add_item_cat').on('click', function(){

                            var ids = $('input[name="cat_list"]:checked').map(function(){
                                return $(this).val();
                            }).get().join(',');

                            console.log(ids);

                            if(ids == ''){
                                alert('Bạn chưa chọn danh mục để tạo menu.');
                                return false;
                            }

                            $(window).off("beforeunload");

                            $.ajax({
                                url: '{{ url($prefix_url . '/create-item', []) }}',
                                type: 'POST',
                                data: {
                                    menu_id: '{{ $menu->id }}',
                                    cat_id: ids,
                                },
                                cache: false,

                                beforeSend: function(){

                                },

                                success: function(response){
                                    console.log(response);
                                    location.reload();
                                },

                                error: function(response, status, error){
                                    console.log(response.responseText);
                                }
                            });
                        });

                        $('button#btn_add_item_url').on('click', function(){
                            var _menu_custom_url = $('input[id="menu_custom_url"]');
                            var _menu_custom_url_text = $('input[id="menu_custom_url_text"]');

                            var b = true;

                            if(_menu_custom_url.val() == '' || _menu_custom_url.val() == 'http://'){
                                _menu_custom_url.parent().addClass('has-error');
                                b = false;
                            }

                            if(_menu_custom_url_text.val() == ''){
                                _menu_custom_url_text.parent().addClass('has-error');
                                b = false;
                            }

                            if(!b){
                                return false;
                            }

                            $(window).off("beforeunload");

                            $.ajax({
                                url: '{{ url($prefix_url . '/create-item', []) }}',
                                type: 'POST',
                                data: {
                                    menu_id: '{{ $menu->id }}',
                                    custom_url: _menu_custom_url.val(),
                                    item_name: _menu_custom_url_text.val(),
                                },
                                cache: false,

                                beforeSend: function(){

                                },

                                success: function(response){
                                    console.log(response);
                                    location.reload();
                                },

                                error: function(response, status, error){
                                    console.log(response.responseText);
                                }
                            });

                        });
                    });
                </script>
            </div>

            <div class="col-md-8">

                <div class="box box-primary">
                    <!--div class="box-header">
                        <h3 class="box-title"></h3>
                    </div-->
                    <div class="box-body">
                        <div class="form-group @if($errors->has('menu_title')) has-error @endif">
                            <label for="menu_title">{{ trans($lang_mod . '.menu_title') }}</label> <span class="text-red">*</span>
                            {!! Form::text('menu_title', $menu->menu_title, ['id'=>'menu_title', 'class' => 'form-control input-sm', 'placeholder' => 'Required']) !!}
                            @if ($errors->has('menu_title'))<p><small class="text-red">{!!$errors->first('menu_title')!!}</small></p> @endif
                        </div>

                        <div class="form-group @if($errors->has('menu_pos')) has-error @endif">
                            <label for="menu_pos">{{ trans($lang_mod . '.menu_pos') }}</label> <span class="text-red">*</span>
                            {!! Form::text('menu_pos', $menu->menu_pos, ['id'=>'menu_pos', 'class' => 'form-control input-sm', 'placeholder' => 'Required']) !!}
                            @if ($errors->has('menu_pos'))<p><small class="text-red">{!!$errors->first('menu_pos')!!}</small></p> @endif
                        </div>

                        <div class="form-group">
                            <label for="published">{{ trans($lang_mod . '.status') }}</label>
                            <div class="">
                                <label class="radio-inline">
                                    {!! Form::radio('status', 1, $menu->status == 1, ['id'=>'status1']) !!} {{ trans($lang_common . '.radio_yes') }}
                                </label>
                                <label class="radio-inline">
                                    {!! Form::radio('status', 0, $menu->status == 0, ['id'=>'status0']) !!} {{ trans($lang_common . '.radio_no') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="published">{{ trans($lang_mod . '.menu_item_list') }}</label>

                            <div class="menu-items-container">
                                @if($menu_items != '')
                                <div id="nestable" class="dd" style="width: 100%">
                                    {!! $menu_items !!}
                                </div>
                                @endif

                            </div>
                            <Script>
                                $(function(){
                                    $('#nestable').nestable({

                                    }).on('change', function(e){

                                        console.log(window.JSON.stringify($('.dd').nestable('serialize')));
                                    });

                                    $('button.btn-close-menu-item').on('click', function(){

                                        var btn_toggle = $('#' + $(this).data('btn-toggle-form-id'));

                                        btn_toggle.next().fadeOut();
                                        btn_toggle.data('toggle-state', '0');
                                        btn_toggle.children()
                                                .first()
                                                .removeClass('fa-caret-down')
                                                .addClass('fa-caret-right');
                                    });

                                    $('button.btn-delete-menu-item').on('click', function(){
                                        var item = $('#' + $(this).data('menu-item-id'));
                                        var menu_id = $(this).data('menu-id');

                                        if($('#deleted_menu_id').val() == ''){
                                            $('#deleted_menu_id').val(menu_id);
                                        }
                                        else{
                                            var tmp = $('#deleted_menu_id').val();
                                            tmp += ',' + menu_id;
                                            $('#deleted_menu_id').val(tmp);
                                        }

                                        item.fadeOut(300, function(){
                                            $(this).remove();
                                            //console.log(window.JSON.stringify($('.dd').nestable('serialize')));
                                        });
                                    });

                                    $('a.dd4-btn-toggle').on('click', function(e){
                                        e.preventDefault();
                                        if($(this).data('toggle-state') == '0'){
                                            $(this).next().fadeIn();
                                            $(this).data('toggle-state', '1');
                                            $(this).children()
                                                    .first()
                                                    .removeClass('fa-caret-right')
                                                    .addClass('fa-caret-down');
                                        }
                                        else{

                                            $(this).next().fadeOut();
                                            $(this).data('toggle-state', '0');
                                            $(this).children()
                                                    .first()
                                                    .removeClass('fa-caret-down')
                                                    .addClass('fa-caret-right');
                                        }

                                    });

                                    $('input.dd4-item-name:text').on('change keyup paste', function(){
                                        var menu_title = $('#' + $(this).data('menu-title'));
                                        menu_title.html($(this).val());
                                    });
                                });
                            </Script>
                        </div>


                    </div>

                    <div class="box-footer">
                        <div class="text-right">
                            <a href="{{ url($prefix_url ) . $qs }}" class="btn bg-orange btn-sm btn-flat"><i class="fa fa-reply"></i> {{ trans($lang_common . '.btn_exit') }}</a>
                            <button id="btn_ok" type="button" class="btn btn-success btn-sm btn-flat"><i class="fa fa-save"></i> {{ trans($lang_common . '.btn_save') }}</button>

                        </div>
                    </div>

                </div>

            </div>
        </div>
            {!! Form::hidden('id', $menu->id) !!}
            {!! Form::hidden('menu_hierarchy', '', ['id'=>'menu_hierarchy']) !!}
            {!! Form::hidden('deleted_menu_id', '', ['id'=>'deleted_menu_id']) !!}
        {!! Form::close() !!}

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

            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_minimal-blue',
                    radioClass: 'iradio_minimal-blue',
                });

                $('#btn_ok').on('click', function(e){

                    $(window).off("beforeunload");

                    $('input[id="menu_hierarchy"]').val(window.JSON.stringify($('.dd').nestable('serialize')));
                    $('#menuForm').submit();
                });
            });
        </script>
    </section>

@stop