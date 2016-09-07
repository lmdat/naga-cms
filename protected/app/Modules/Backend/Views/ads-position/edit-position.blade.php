@extends('Backend::layouts.master')

@section('content')
    <section class="content-header">
        <h1>
            {{ trans($lang_mod . '.position_edit_header') }}
            <small></small>
        </h1>
        <!--ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol-->
    </section>

    <section class="content">
        {!! Form::open(['url' => Request::url() . $qs, 'method' => 'put', 'name' => 'posForm', 'id' => 'posForm', 'role' => 'form']) !!}
        <div class="row">
            <div class="col-md-4">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{ trans($lang_mod . '.home_page') }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>{{ trans($lang_mod . '.fix_position') }}</legend>

                                    @foreach(config('constant.ADS_POSITION.HOME_FIX') as $k => $v)
                                        @if(isset($positions[$v]))
                                        <button type="button" name="btn_{{ str_replace('.', '_', $v) }}" id="btn_{{ str_replace('.', '_', $v) }}" class="btn btn-flat @if($positions[$v]->status == 1) bg-olive @endif btn-xs btn-block home-fix">{{ $v }}</button>
                                        @else
                                        <button type="button" name="btn_{{ str_replace('.', '_', $v) }}" id="btn_{{ str_replace('.', '_', $v) }}" class="btn btn-flat btn-xs btn-block home-fix">{{ $v }}</button>
                                        @endif
                                    @endforeach

                                    @foreach(config('constant.ADS_POSITION.HOME_FIX') as $k => $v)
                                        @if(isset($positions[$v]))
                                        <input type="hidden" name="ads_position[]" id="{{ str_replace('.', '_', $v) }}" value="{{ $v }}|{{ $positions[$v]->status }}|{{ $positions[$v]->is_fix }}"/>
                                        @else
                                        <input type="hidden" name="ads_position[]" id="{{ str_replace('.', '_', $v) }}" value="{{ $v }}|0|1"/>
                                        @endif
                                    @endforeach

                                </fieldset>
                            </div>

                            <script>
                                $(function(){

                                    $('button.home-fix').on('click', function(){
                                        var hidden_field = $('#' + $(this).prop('id').replace('btn_', ''));
                                        var is_fix = 1;
                                        if($(this).hasClass('bg-olive')){
                                            hidden_field.val($(this).text() + "|" + 0 + "|" + is_fix);
                                            $(this).removeClass('bg-olive');
                                        }
                                        else{
                                            hidden_field.val($(this).text() + "|" + 1+ "|" + is_fix);
                                            $(this).addClass('bg-olive');
                                        }
                                        console.log(hidden_field.val());
                                    });

                                });
                            </script>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>{{ trans($lang_mod . '.cat_dynamic_position') }}</legend>

                                    <div class="form-group">
                                        <label class="label label-success">{{ trans($lang_mod . '.total_parent_cat') }}: {{ $total_cat }}</label>

                                        <div class="radio">
                                            <label>
                                                {!! Form::radio('cat_group', '1', $cat_group == 1, ['class'=>'cat-group']) !!}
                                                1 Danh mục/group
                                            </label>
                                        </div>

                                        <div class="radio">
                                            <label>

                                                {!! Form::radio('cat_group', '2', $cat_group == 2, ['class'=>'cat-group']) !!}
                                                2 Danh mục/group
                                            </label>
                                        </div>

                                        <div class="radio">
                                            <label>
                                                {!! Form::radio('cat_group', '3', $cat_group == 3, ['class'=>'cat-group']) !!}
                                                3 Danh mục/group
                                            </label>
                                        </div>

                                    </div>
                                    <?php
                                        $item_per_group = $total_cat / $cat_group;
                                        if(($total_cat % $cat_group) > 0)
                                            $item_per_group++;
                                    ?>
                                    <div id="insert_full_ads_block" class="form-group">
                                    @for($i=0;$i<$item_per_group;$i++)
                                        <?php $v = config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_FULL_BELOW_CATEGORY_GROUP') . ($i+1);?>
                                        <button type="button" name="btn_{{ str_replace('.', '_', $v) }}" id="btn_{{ str_replace('.', '_', $v) }}" class="btn btn-flat @if($positions[$v]->status == 1) bg-olive @endif btn-xs btn-block full-below-cat">{{ $v }}</button>
                                    @endfor

                                    @for($i=0;$i<$item_per_group;$i++)
                                            <?php $v = config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_FULL_BELOW_CATEGORY_GROUP') . ($i+1);?>
                                        <input type="hidden" name="ads_position[]" id="{{ str_replace('.', '_', $v) }}" value="{{ $v }}|{{ $positions[$v]->status }}|{{ $positions[$v]->is_fix }}"/>
                                    @endfor
                                    </div>

                                    <div id="insert_right_ads_block" class="form-group">
                                    @for($i=0;$i<$item_per_group;$i++)
                                        <?php $v = config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_RIGHT_CATEGORY_GROUP') . ($i+1);?>
                                        <button type="button" name="btn_{{ str_replace('.', '_', $v) }}" id="btn_{{ str_replace('.', '_', $v) }}" class="btn btn-flat @if($positions[$v]->status == 1) bg-olive @endif btn-xs btn-block right-cat">{{ $v }}</button>
                                    @endfor

                                    @for($i=0;$i<$item_per_group;$i++)
                                            <?php $v = config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_RIGHT_CATEGORY_GROUP') . ($i+1);?>
                                        <input type="hidden" name="ads_position[]" id="{{ str_replace('.', '_', $v) }}" value="{{ $v }}|{{ $positions[$v]->status }}|{{ $positions[$v]->is_fix }}"/>
                                    @endfor
                                    </div>

                                </fieldset>
                                <script>
                                    var total_cat = {{$total_cat}};
                                    var cat_group = {{$cat_group}};
                                    var full_ads_block_tmp = "";
                                    var right_ads_block_tmp = "";
                                    $(function(){

                                        full_ads_block_tmp = $('#insert_full_ads_block').html();
                                        right_ads_block_tmp = $('#insert_right_ads_block').html();

                                        $('input[class="cat-group"]').on('ifChecked', function(e){

                                            var item_per_group = $(this).val();
                                            if(item_per_group == cat_group){
                                                $('#insert_full_ads_block').html(full_ads_block_tmp);
                                                $('#insert_right_ads_block').html(right_ads_block_tmp);
                                            }
                                            else{
                                                add_ads_block_button(total_cat, item_per_group);
                                            }

                                        });

                                        $('body').on('click', 'button.full-below-cat, button.right-cat', function(){

                                            var hidden_field = $('#' + $(this).prop('id').replace('btn_', ''));
                                            var is_fix = 0;
                                            if($(this).hasClass('bg-olive')){
                                                hidden_field.val($(this).text() + "|" + 0 + "|" + is_fix)
                                                $(this).removeClass('bg-olive');
                                            }
                                            else{
                                                hidden_field.val($(this).text() + "|" + 1 + "|" + is_fix)
                                                $(this).addClass('bg-olive');
                                            }

                                            console.log(hidden_field.val());
                                        });


                                    });

                                    function add_ads_block_button(total, item_per_group){

                                        var total_group = parseInt(total / item_per_group);

                                        if(total % item_per_group > 0)
                                            total_group++;


                                        var full_ads_block = $('#insert_full_ads_block');
                                        var right_ads_block = $('#insert_right_ads_block');


                                        full_ads_block.html('');
                                        right_ads_block.html('');
                                        var hidden_input_1 = '';
                                        var hidden_input_2 = '';
                                        for(var i=0;i<total_group;i++){
                                            var k = i + 1;
                                            var btn_full_ads_text = '{{ config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_FULL_BELOW_CATEGORY_GROUP') }}' + k;
                                            var btn_right_ads_text = '{{ config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_RIGHT_CATEGORY_GROUP') }}' + k;
                                            full_ads_block.append('<button type="button" name="btn_{{ str_replace('.', '_', config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_FULL_BELOW_CATEGORY_GROUP')) }}' + k + '" id="btn_{{ str_replace('.', '_', config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_FULL_BELOW_CATEGORY_GROUP')) }}' + k + '" class="btn btn-flat btn-xs btn-block full-below-cat">'+btn_full_ads_text+'</button>');

                                            right_ads_block.append('<button type="button" name="btn_{{ str_replace('.', '_', config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_RIGHT_CATEGORY_GROUP')) }}' + k + '" id="btn_{{ str_replace('.', '_', config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_RIGHT_CATEGORY_GROUP')) }}' + k + '" class="btn btn-flat btn-xs btn-block right-cat">'+btn_right_ads_text+'</button>');

                                            hidden_input_1 += '<input type="hidden" name="ads_position[]" id="{{ str_replace('.', '_', config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_FULL_BELOW_CATEGORY_GROUP')) }}'+k+'" value="'+btn_full_ads_text+'|0|0"/>';

                                            hidden_input_2 += '<input type="hidden" name="ads_position[]" id="{{ str_replace('.', '_', config('constant.ADS_POSITION.HOME_DYNAMIC.HOME_RIGHT_CATEGORY_GROUP')) }}'+k+'" value="'+btn_right_ads_text+'|0|0"/>';
                                        }

                                        full_ads_block.append(hidden_input_1);
                                        right_ads_block.append(hidden_input_2);

                                    }
                                </script>
                            </div>
                        </div>

                    </div>

                    {{--<div class="box-footer">--}}

                    {{--</div>--}}

                </div>

            </div>

            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{ trans($lang_mod . '.cat_page') }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>{{ trans($lang_mod . '.fix_position') }}</legend>

                                    @foreach(config('constant.ADS_POSITION.CATEGORY_FIX') as $k => $v)
                                        @if(isset($positions[$v]))
                                        <button type="button" name="btn_{{ str_replace('.', '_', $v) }}" id="btn_{{ str_replace('.', '_', $v) }}" class="btn btn-flat @if($positions[$v]->status == 1) bg-olive @endif btn-xs btn-block category-fix">{{ $v }}</button>
                                        @else
                                        <button type="button" name="btn_{{ str_replace('.', '_', $v) }}" id="btn_{{ str_replace('.', '_', $v) }}" class="btn btn-flat btn-xs btn-block category-fix">{{ $v }}</button>
                                        @endif
                                    @endforeach

                                    @foreach(config('constant.ADS_POSITION.CATEGORY_FIX') as $k => $v)
                                        @if(isset($positions[$v]))
                                        <input type="hidden" name="ads_position[]" id="{{ str_replace('.', '_', $v) }}" value="{{ $v }}|{{ $positions[$v]->status }}|{{ $positions[$v]->is_fix }}"/>
                                        @else
                                        <input type="hidden" name="ads_position[]" id="{{ str_replace('.', '_', $v) }}" value="{{ $v }}|0|1"/>
                                        @endif

                                    @endforeach

                                </fieldset>
                                <script>
                                    $(function(){
                                        $('button.category-fix').on('click', function(){
                                            var hidden_field = $('#' + $(this).prop('id').replace('btn_', ''));
                                            var is_fix = 1;
                                            if($(this).hasClass('bg-olive')){
                                                hidden_field.val($(this).text() + "|" + 0 + "|" + is_fix);
                                                $(this).removeClass('bg-olive');
                                            }
                                            else{
                                                hidden_field.val($(this).text() + "|" + 1 + "|" + is_fix);
                                                $(this).addClass('bg-olive');
                                            }
                                            //console.log(hidden_field.val());
                                        });
                                    });
                                </script>
                            </div>
                        </div>

                    </div>

                    {{--<div class="box-footer">--}}

                    {{--</div>--}}
                </div>

            </div>

            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{ trans($lang_mod . '.detail_page') }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>{{ trans($lang_mod . '.fix_detail_news_position') }}</legend>

                                    @foreach(config('constant.ADS_POSITION.DETAIL_NEWS_FIX') as $k => $v)
                                        @if(isset($positions[$v]))
                                        <button type="button" name="btn_{{ str_replace('.', '_', $v) }}" id="btn_{{ str_replace('.', '_', $v) }}" class="btn btn-flat @if($positions[$v]->status == 1) bg-olive @endif btn-xs btn-block detail-news-fix">{{ $v }}</button>
                                        @else
                                        <button type="button" name="btn_{{ str_replace('.', '_', $v) }}" id="btn_{{ str_replace('.', '_', $v) }}" class="btn btn-flat btn-xs btn-block detail-news-fix">{{ $v }}</button>
                                        @endif

                                    @endforeach

                                    @foreach(config('constant.ADS_POSITION.DETAIL_NEWS_FIX') as $k => $v)
                                        @if(isset($positions[$v]))
                                        <input type="hidden" name="ads_position[]" id="{{ str_replace('.', '_', $v) }}" value="{{ $v }}|{{ $positions[$v]->status }}|{{ $positions[$v]->is_fix }}"/>
                                        @else
                                        <input type="hidden" name="ads_position[]" id="{{ str_replace('.', '_', $v) }}" value="{{ $v }}|0|1"/>
                                        @endif
                                    @endforeach

                                </fieldset>
                                <script>
                                    $(function(){

                                        $('button.detail-news-fix').on('click', function(){
                                            var hidden_field = $('#' + $(this).prop('id').replace('btn_', ''));
                                            var is_fix = 1;
                                            if($(this).hasClass('bg-olive')){
                                                hidden_field.val($(this).text() + "|" + 0 + "|" + is_fix);
                                                $(this).removeClass('bg-olive');
                                            }
                                            else{
                                                hidden_field.val($(this).text() + "|" + 1 + "|" + is_fix);
                                                $(this).addClass('bg-olive');
                                            }
                                            console.log(hidden_field.val());
                                        });
                                    });
                                </script>

                            </div>

                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <legend>{{ trans($lang_mod . '.fix_detail_video_position') }}</legend>

                                    @foreach(config('constant.ADS_POSITION.DETAIL_VIDEO_FIX') as $k => $v)
                                        @if(isset($positions[$v]))
                                        <button type="button" name="btn_{{ str_replace('.', '_', $v) }}" id="btn_{{ str_replace('.', '_', $v) }}" class="btn btn-flat @if($positions[$v]->status == 1) bg-olive @endif btn-xs btn-block detail-video-fix">{{ $v }}</button>
                                        @else
                                        <button type="button" name="btn_{{ str_replace('.', '_', $v) }}" id="btn_{{ str_replace('.', '_', $v) }}" class="btn btn-flat btn-xs btn-block detail-video-fix">{{ $v }}</button>
                                        @endif
                                    @endforeach

                                    @foreach(config('constant.ADS_POSITION.DETAIL_VIDEO_FIX') as $k => $v)
                                        @if(isset($positions[$v]))
                                        <input type="hidden" name="ads_position[]" id="{{ str_replace('.', '_', $v) }}" value="{{ $v }}|{{ $positions[$v]->status }}|{{ $positions[$v]->is_fix }}"/>
                                        @else
                                        <input type="hidden" name="ads_position[]" id="{{ str_replace('.', '_', $v) }}" value="{{ $v }}|0|1"/>
                                        @endif
                                    @endforeach

                                </fieldset>
                                <script>
                                    $(function(){

                                        $('button.detail-video-fix').on('click', function(){
                                            var hidden_field = $('#' + $(this).prop('id').replace('btn_', ''));
                                            var is_fix = 1;
                                            if($(this).hasClass('bg-olive')){
                                                hidden_field.val($(this).text() + "|" + 0 + "|" + is_fix);
                                                $(this).removeClass('bg-olive');
                                            }
                                            else{
                                                hidden_field.val($(this).text() + "|" + 1 + "|" + is_fix)
                                                $(this).addClass('bg-olive');
                                            }
                                            console.log(hidden_field.val());
                                        });
                                    });
                                </script>
                            </div>

                        </div>

                    </div>

                    {{--<div class="box-footer">--}}

                    {{--</div>--}}
                </div>


            </div>

        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn bg-orange btn-flat"><i class="fa fa-save"></i> {{ trans($lang_common . '.btn_update') }}</button>
            </div>
        </div>

        {!! Form::close() !!}
    </section>
    <script>


        $(function () {

            $('input').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue',
            });

        });


    </script>
@stop