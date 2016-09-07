@extends('Backend::layouts.master')

@section('content')
    <section class="content-header">
        <h1>
            {{ trans($lang_mod . '.menu_list_header') }}
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
                {!! Form::open(['url' => Request::url() . $form_qs, 'name' => 'menuForm', 'id' => 'menuForm', 'role' => 'form']) !!}
                <div class="box box-primary">
                    <div class="box-header text-right">
                        <a href="{{ url($prefix_url . '/create') . $qs }}" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> {{ trans($lang_mod . '.create_new_menu') }}</a>
                    </div>

                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="40px">#</th>
                                <th>{{trans($lang_mod . '.menu_title')}}</th>
                                <th width="20%">{{trans($lang_mod . '.menu_pos')}}</th>
                                <th width="15%">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($menus as $item)
                                <tr>
                                    <td>{{ $item->id }}.</td>
                                    <td>{{ $item->menu_title }}</td>
                                    <td>{{ $item->menu_pos }}</td>
                                    <td class="text-right">

                                        <a href="{{url($prefix_url . '/restore', [$item->id]) . $qs}}" class="btn btn-sm btn-warning btn-flat" data-toggle="tooltip" title="{{ trans($lang_common . '.btn_restore') }}"><i class="fa fa-undo"></i></a>
                                        <a href="{{url($prefix_url . '/delete', [$item->id]) . $qs}}" class="btn btn-sm btn-danger btn-flat" data-toggle="tooltip" title="{{ trans($lang_common . '.btn_delete') }}"><i class="fa fa-remove"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>

                    <div class="box-footer text-right">
                        <a href="{{ url($prefix_url . '/create') . $qs }}" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> {{ trans($lang_mod . '.create_new_menu') }}</a>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </section>

@stop