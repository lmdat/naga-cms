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
                <div class="box box-primary">
                    <!--div class="box-header">
                        <h3 class="box-title"></h3>
                    </div-->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="category-list">
                                    @if(count($tree_cat) > 0)
                                        @foreach($tree_cat as $item)
                                            <div class="checkbox">
                                                <label>
                                                    <input name="cat_list" type="checkbox" value="{{ $item->id . '|'. $item->cat_name }}">
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
                        <button id="btn_select_all" type="button" class="btn btn-xs btn-flat pull-left">Chọn Tất Cả</button>
                        <button id="btn_add_item" type="button" class="btn btn-xs btn-flat bg-olive pull-right">Thêm Vào Menu <i class="fa fa-angle-double-right"></i></button>
                    </div>

                    <script>
                        $(function(){
                            $('.category-list').slimScroll({
                                height: '300'
                            });


                            $('button#btn_add_item').on('click', function(){

                                var ids = $('input[name="cat_list"]:checked').map(function(){
                                    return $(this).val();
                                }).get().join(',');

                                console.log(ids);

                                if(ids == ''){
                                    alert('Bạn chưa chọn danh mục để tạo menu.');
                                }

                                $(window).off("beforeunload");

                                $.ajax({
                                    url: '{{ url($prefix_url . '/create-item', []) }}',
                                    type: 'POST',
                                    data: {
                                        menu_id: '{{ $menu->id }}',
                                        cat_id: ids,
                                        custom_url: '',
                                        custom_url_title: '',
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
                                @if(count($menu_items) > 0)
                                <div class="dd" id="nestable" style="width: 100%">
                                    <ol class="dd-list">
                                        @foreach($menu_items as $mitem)
                                        <li id="menu_item_{{$mitem->id}}" class="dd-item" data-id="{{ $mitem->id }}">

                                            <div class="dd-handle dd4-handle">
                                                <span class="menu-title">{{ $mitem->item_name }}</span>
                                                <span class="pull-right menu-type" style="margin-right: 25px;">{{ $mitem->cat_id == 0 ? 'Custom URL' : 'Category' }}</span>
                                            </div>

                                            <a id="btn_toggle_form_{{$mitem->id}}" href="#" class="dd4-btn-toggle" data-toggle-state='0'><i class="fa fa-caret-right"></i></a>
                                            <div id="menu_item_form_{{$mitem->id}}" class="container-fluid menu-item-form" style="display: none;">
                                                <div class="form-group">
                                                    <label for="item_name">Tên Menu</label>
                                                    {!! Form::text('item_name[]', $mitem->item_name, ['id'=>'item_name', 'class' => 'form-control input-sm', 'placeholder' => 'Required']) !!}
                                                </div>

                                                @if($mitem->cat_id != 0)
                                                <div class="form-group">
                                                    <label for="item_name">Danh Mục URL</label>
                                                    <p class="form-control-static"><a href="{{ config('app.url') . '/' . $mitem->alias }}">{{ config('app.url') . '/' . $mitem->alias }}</a></p>
                                                </div>
                                                @endif


                                                <div class="form-group" @if($mitem->cat_id != 0) style="display: none;" @endif>
                                                    <label for="external_url">Custom URL</label>
                                                    {!! Form::text('custom_url[]', $mitem->custom_url, ['id'=>'custom_url', 'class' => 'form-control input-sm', 'placeholder' => 'Required']) !!}
                                                </div>

                                                <div class="form-group text-right">
                                                    <button type="button" class="btn btn-xs bg-red btn-delete-menu-item" data-menu-item-id="menu_item_{{$mitem->id}}">Xóa</button>
                                                    <button type="button" class="btn btn-xs bg-orange btn-close-menu-item" data-btn-toggle-form-id="btn_toggle_form_{{$mitem->id}}">Đóng</button>
                                                </div>

                                                {!! Form::hidden('item_id[]', $mitem->id) !!}
                                            </div>
                                        </li>
                                        @endforeach

                                    </ol>
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
                                });
                            </Script>
                        </div>


                    </div>

                    <div class="box-footer">
                        <div class="text-right">
                            <a href="{{ url($prefix_url ) . $qs }}" class="btn bg-orange btn-flat"><i class="fa fa-reply"></i> {{ trans($lang_common . '.btn_exit') }}</a>
                            <button id="btn_ok" type="button" class="btn btn-success btn-flat"><i class="fa fa-save"></i> {{ trans($lang_common . '.btn_save') }}</button>

                        </div>
                    </div>

                </div>

            </div>
        </div>
            {!! Form::hidden('id', $menu->id) !!}
            {!! Form::hidden('menu_hierarchy', '', ['id'=>'menu_hierarchy']) !!}
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