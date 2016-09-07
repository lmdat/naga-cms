@extends('Backend::layouts.master')

@section('content')
    <section class="content-header">
        <h1>
            {{trans($lang_mod . '.news_list_header')}}
            <small></small>
        </h1>
        {{--<ol class="breadcrumb">--}}
            {{--<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>--}}
            {{--<li class="active">Here</li>--}}
        {{--</ol>--}}
    </section>

    <section class="content">
        {!! Form::open(['url' => Request::url() . $form_qs, 'method'=>'post', 'name' => 'newsForm', 'id' => 'newsForm', 'role' => 'form']) !!}
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ trans($lang_mod . '.cat_id') }}</label>
                                    <select name="cat_id" id="cat_id" class="form-control input-sm">
                                        <option value="">---{{ trans($lang_mod . '.all_category') }}---</option>
                                    @foreach($tree_data as $item)
                                        @if($item->parent_id == 0)
                                        <option disabled="disabled">{{ $item->cat_name_tmp }}</option>
                                        @else
                                        <option value="{{ $item->id }}" @if($item->id == $cat_id) selected @endif>{{ $item->cat_name_tmp }}</option>
                                        @endif
                                    @endforeach
                                    </select>
                                </div>
                                <script>
                                    $(function(){
                                        $('#cat_id').on('change', function(){
                                            $('#newsForm').submit();
                                        });
                                    });
                                </script>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    @foreach($news_type as $k => $type)
                                        <label class="radio-inline">
                                            {!! Form::radio('news_type', $k, ($k == $checked_type), ['id' => 'news_type_' . $k]) !!} {{ $type[1] }}
                                        </label>
                                    @endforeach
                                        <label class="radio-inline">
                                            {!! Form::radio('news_type', 0, ($checked_type == 0), ['id' => 'news_type_0']) !!} {{ trans($lang_mod . '.select_all') }}
                                        </label>

                                </div>
                                <div class="form-group">
                                    <label class="radio-inline">
                                        {!! Form::radio('miscellaneous', 'is_highlight', ($misc == 'is_highlight'), ['id' => 'miscellaneous_home_highlight']) !!} {{ trans($lang_mod . '.is_highlight') }}

                                    </label>
                                    <label class="radio-inline">
                                        {!! Form::radio('miscellaneous', 'is_hot', ($misc == 'is_hot'), ['id' => 'miscellaneous_hot']) !!} {{ trans($lang_mod . '.is_hot') }}

                                    </label>
                                    <label class="radio-inline">
                                        {!! Form::radio('miscellaneous', 'all', ($misc == 'all'), ['id' => 'miscellaneous_all']) !!} {{ trans($lang_mod . '.select_all') }}
                                    </label>

                                </div>
                                <script>
                                    $(function(){
                                        $('input:radio[name="news_type"], input:radio[name="miscellaneous"]').on('ifChecked', function(){
                                            $('#newsForm').submit();
                                        });
                                    });
                                </script>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{ trans($lang_mod . '.search_text') }}</label>
                                    <div class="input-group input-group-sm">
                                        {!! Form::text('q', $q, ['id'=>'q', 'class' => 'form-control input-sm', 'placeholder' => '']) !!}
                                        <span class="input-group-btn">
                                          <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header text-right">
                        <a href="{{ url($prefix_url . '/create') . $qs }}" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> {{ trans($lang_mod . '.create_new_news') }}</a>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="form-group">
                                    {!! Form::select('rows_per_page', $rows_per_page, $display_rows, ['class' => 'form-control input-sm', 'id' => 'rows_per_page']) !!}
                                </div>
                                <script>
                                    $(function(){
                                        $('#rows_per_page').on('change', function(){
                                            $('#newsForm').submit();
                                        });
                                    });
                                </script>

                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>

                                    <th>{{ trans($lang_mod . '.title') }}</th>
                                    <th>{{ trans($lang_mod . '.featured_image') }}</th>
                                    <th>{{ trans($lang_mod . '.cat_id') }}</th>
                                    <th width="15%">{{ trans($lang_mod . '.status') }}</th>
                                    <th width="12%">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($news_list as $item)
                                <tr>

                                    <td>
                                        <i class="fa @if($item->news_type == 1) fa-newspaper-o @else fa-video-camera @endif"></i> <a href="#">{{ $item->title != '' ? $item->title : trans($lang_common . '.untitled') }}</a>
                                        @if($item->is_hot == 1)<img src="{{asset('media/local/img/hot_16.png')}}" style="vertical-align:top">@endif
                                        @if($item->is_highlight == 1)<img src="{{asset('media/local/img/focus_red_16.png')}}" style="vertical-align:top">@endif

                                        <br/><small><label>#</label>{{ $item->id }}</small>
                                        <br/><small><label>{{ trans($lang_common . '.created_at') }}:</label></small> <span class="label label-primary">{{ App\Libs\Utils\Time::niceShort($item->created_at)}}</span>

                                         | <small><label>{{ trans($lang_common . '.modified_at') }}:</label></small> @if($item->modified_at != '') <span class="label label-primary">{{ App\Libs\Utils\Time::niceShort($item->modified_at)}}</span> @else <small>{{ trans($lang_common . '.not_answer') }}</small> @endif

                                        <br/><small><label>{{ trans($lang_common . '.approved_at') }}:</label></small> @if($item->approved_at != '') <span class="label label-primary">{{ App\Libs\Utils\Time::niceShort($item->approved_at) }}</span> @else <small>{{ trans($lang_common . '.not_answer') }}</small> @endif

                                         | <small><label>Ngày Phát Hành:</label></small> @if($item->published_at != '') <span class="label label-primary">{{ App\Libs\Utils\Time::niceShort($item->published_at) }}</span> @else <small>{{ trans($lang_common . '.not_answer') }}</small> @endif

                                        <?php
                                            $created_user = $item->created_by()->first();
                                            $modified_user = $item->modified_by()->first();

                                        ?>
                                        <br/><small><label>{{ trans($lang_common . '.created_by') }}:</label></small> @if($created_user != null) <span class="label label-info">{{ $created_user->first_name . ' ' . $created_user->surname }}</span> @else <small>{{ trans($lang_common . '.not_answer') }}</small> @endif
                                         | <small><label>{{ trans($lang_common . '.modified_by') }}:</label></small> @if($modified_user != null) <span class="label label-info">{{ $modified_user->first_name . ' ' . $modified_user->surname }}</span> @else <small>{{ trans($lang_common . '.not_answer') }}</small> @endif

                                    </td>

                                    <td></td>

                                    <td>{{$item->cat_name}}</td>

                                    <td><span class="label {{ $news_status_color[$item->status] }}">{{ $news_status[$item->status] }}</span></td>

                                    <td class="text-right">

                                        @if($item->status == config('constant.NEWS_PUBLISHED_STATUS'))
                                            <a href="{{url($prefix_url . '/published', [$item->id]) . $qs}}" class="btn btn-sm btn-info btn-flat" data-toggle="tooltip" title="{{ trans($lang_mod . '.unpublished_status') }}"><i class="fa fa-unlock-alt"></i></a>
                                        @elseif($item->status == config('constant.NEWS_UNPUBLISHED_STATUS'))
                                            <a href="{{url($prefix_url . '/published', [$item->id]) . $qs}}" class="btn btn-sm btn-default btn-flat" data-toggle="tooltip" title="{{ trans($lang_mod . '.published_status') }}"><i class="fa fa-lock"></i></a>
                                        @else

                                        @endif

                                        <a href="{{ url($prefix_url . '/edit', [$item->id]) . $qs }}" class="btn btn-sm btn-warning btn-flat" data-toggle="tooltip" title="{{ trans($lang_common . '.btn_edit') }}"><i class="fa fa-edit"></i></a>
                                        <a href="{{ url($prefix_url . '/trash', [$item->id]) . $qs }}" class="btn btn-sm btn-danger btn-flat" data-toggle="tooltip" title="{{ trans($lang_common . '.btn_trash') }}"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>

                            <tfoot>
                            <tr>
                                <td colspan="5" class="text-right">
                                    {!! $news_list->render() !!}
                                </td>
                            </tr>
                            </tfoot>

                        </table>
                    </div>

                    <div class="box-footer text-right">
                        <a href="{{ url($prefix_url . '/create') . $qs }}" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> {{ trans($lang_mod . '.create_new_news') }}</a>
                    </div>

                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
    <script>
        $(function(){
            $("#cat_id").select2();

            $('input').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue',
            });
        });


    </script>

@stop