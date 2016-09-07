@extends('Backend::layouts.master')

@section('content')
    <section class="content-header">
        <h1>
            {{ trans($lang_mod . '.ads_list_header') }}
            <small></small>
        </h1>
        {{--<ol class="breadcrumb">--}}
        {{--<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>--}}
        {{--<li class="active">Here</li>--}}
        {{--</ol>--}}
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(['url' => Request::url() . $form_qs, 'name' => 'adsForm', 'id' => 'adsForm', 'role' => 'form']) !!}
                <div class="box box-primary">
                    <div class="box-header text-right">
                        <a href="{{ url($prefix_url . '/create') . $qs }}" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> {{ trans($lang_mod . '.create_new_ads') }}</a>
                    </div>

                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-1">
                                <div class="form-group">
                                    {!! Form::select('rows_per_page', $rows_per_page, '', ['class' => 'form-control input-sm', 'id' => 'rows_per_page']) !!}
                                </div>


                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>

                                <th>{{trans($lang_mod . '.ads_title')}}</th>
                                <th>{{trans($lang_mod . '.pos_id')}}</th>
                                <th width="18%">{{trans($lang_mod . '.display_time')}}</th>
                                <th width="8%">{{trans($lang_mod . '.ordering')}} <button type="submit" id="cmd_ordering" class="btn btn-xs btn-success" formaction="{{ url($prefix_url . '/ordering') . $qs }}" data-toggle="tooltip" title="Save Orders"><i class="fa fa-save"></i></button></th>
                                <th width="12%">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ads_list as $item)
                                <tr>
                                    <td>
                                        {{ $item->ads_title }}
                                        <br/><small><label>#</label>{{ $item->id }}</small>
                                        <br/><small><small><label>{{ trans($lang_common . '.created_at') }}:</label></small></small> <span class="label label-primary">{{ App\Libs\Utils\Time::niceShort($item->created_at)}}</span>

                                        | <small><small><label>{{ trans($lang_common . '.modified_at') }}:</label></small></small> @if($item->modified_at != '') <span class="label label-primary">{{ App\Libs\Utils\Time::niceShort($item->modified_at)}}</span> @else <small><small>{{ trans($lang_common . '.not_answer') }}</small></small> @endif

                                        <br/><small><small><label>{{ trans($lang_common . '.published_at') }}:</label></small></small> @if($item->published_at != '') <span class="label label-primary">{{ App\Libs\Utils\Time::niceShort($item->published_at) }}</span> @else <small><small>{{ trans($lang_common . '.not_answer') }}</small></small> @endif

                                        <?php
                                        $created_user = $item->created_by()->first();
                                        $modified_user = $item->modified_by()->first();

                                        ?>
                                        <br/><small><small><label>{{ trans($lang_common . '.created_by') }}:</label></small></small> @if($created_user != null) <span class="label label-info">{{ $created_user->first_name . ' ' . $created_user->surname }}</span> @else <small><small>{{ trans($lang_common . '.not_answer') }}</small></small> @endif
                                        | <small><small><label>{{ trans($lang_common . '.modified_by') }}:</label></small></small> @if($modified_user != null) <span class="label label-info">{{ $modified_user->first_name . ' ' . $modified_user->surname }}</span> @else <small><small>{{ trans($lang_common . '.not_answer') }}</small></small> @endif
                                    </td>
                                    <td>{{ $item->position->pos_name }}</td>
                                    <td>
                                        <small><label>{{ trans($lang_mod . '.start_time') }}:</label></small> <span class="label bg-purple">{{ $item->start_time != '' ? \App\Libs\Utils\Time::niceShort($item->start_time) : trans($lang_common . '.unlimited') }}</span>
                                        <br/><small><label>{{ trans($lang_mod . '.end_time') }}:</label></small> <span class="label bg-purple">{{ $item->end_time != '' ? \App\Libs\Utils\Time::niceShort($item->end_time) : trans($lang_common . '.unlimited') }}</span>
                                    </td>

                                    <td>
                                        {!! Form::number('ordering[]', $item->ordering, ['class' => 'form-control input-sm', 'min'=>'0', 'required'=>true]) !!}
                                        {!! Form::hidden('ids[]', $item->id) !!}
                                    </td>
                                    <td class="text-right">
                                        <?php
                                            $can_change_status = false;
                                            if($item->published_at != ''){
                                                //if(strtotime($item->published_at) < $now){
                                                //}
                                                $can_change_status = true;

                                            }
                                        ?>
                                        @if($can_change_status == true)
                                            @if($item->status == 1)
                                                <a href="{{url($prefix_url . '/published', [$item->id]) . $qs}}" class="btn btn-sm btn-info btn-flat" data-toggle="tooltip" title="{{ trans($lang_common . '.radio_show') }}"><i class="fa fa-unlock-alt"></i></a>
                                            @else
                                                <a href="{{url($prefix_url . '/published', [$item->id]) . $qs}}" class="btn btn-sm btn-default btn-flat" data-toggle="tooltip" title="{{ trans($lang_common . '.radio_hide') }}"><i class="fa fa-lock"></i></a>
                                            @endif
                                        @else
                                                <button type="button" class="btn btn-sm btn-flat" disabled><i class="fa fa-lock"></i></button>
                                        @endif
                                        <a href="{{url($prefix_url . '/edit', [$item->id]) . $qs}}" class="btn btn-sm btn-warning btn-flat" data-toggle="tooltip" title="{{ trans($lang_common . '.btn_edit') }}"><i class="fa fa-edit"></i></a>
                                        <a href="{{url($prefix_url . '/trash', [$item->id]) . $qs}}" class="btn btn-sm btn-danger btn-flat" data-toggle="tooltip" title="{{ trans($lang_common . '.btn_trash') }}"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                            <tfoot>
                            <tr>
                                <td colspan="5" class="text-right">
                                    {!! $ads_list->render() !!}
                                </td>
                            </tr>
                            </tfoot>

                        </table>
                    </div>

                    <div class="box-footer text-right">
                        <a href="{{ url($prefix_url . '/create') . $qs }}" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> {{ trans($lang_mod . '.create_new_ads') }}</a>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </section>

@stop