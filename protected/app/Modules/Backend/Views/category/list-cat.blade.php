@extends('Backend::layouts.master')

@section('content')
    <section class="content-header">
        <h1>
            {{ trans($lang_mod . '.category_list_header') }}
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
                {!! Form::open(['url' => Request::url() . $form_qs, 'name' => 'sectionForm', 'id' => 'sectionForm', 'role' => 'form']) !!}
                <div class="box box-primary">
                    <div class="box-header text-right">
                        <a href="{{ url($prefix_url . '/create') . $qs }}" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> {{ trans($lang_mod . '.create_new_cat') }}</a>
                    </div>

                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="40px">#</th>
                                    <th>{{trans($lang_mod . '.cat_name')}}</th>
                                    <th width="20%">{{trans($lang_mod . '.alias')}}</th>
                                    <th width="8%">{{trans($lang_mod . '.ordering')}} <button type="submit" id="cmd_ordering" class="btn btn-xs btn-success" formaction="{{ url($prefix_url . '/ordering') . $qs }}" data-toggle="tooltip" title="Save Orders"><i class="fa fa-save"></i></button></th>
                                    <th width="15%">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($tree_data as $item)
                                <tr>
                                    <td>{{ $item->id }}.</td>
                                    <td>{{ $item->cat_name_tmp }}</td>
                                    <td>{{ $item->alias }}</td>
                                    <td>
                                        {!! Form::number('ordering[]', $item->ordering, ['class' => 'form-control input-sm', 'min'=>'1', 'required'=>true]) !!}
                                        {!! Form::hidden('ids[]', $item->id) !!}
                                    </td>
                                    <td class="text-right">

                                        @if($item->published == 1)
                                            <a href="{{url($prefix_url . '/published', [$item->id]) . $qs}}" class="btn btn-sm btn-info btn-flat" data-toggle="tooltip" title="{{ trans($lang_common . '.unlock_status') }}"><i class="fa fa-unlock-alt"></i></a>
                                        @else
                                            <a href="{{url($prefix_url . '/published', [$item->id]) . $qs}}" class="btn btn-sm btn-default btn-flat" data-toggle="tooltip" title="{{ trans($lang_common . '.lock_status') }}"><i class="fa fa-lock"></i></a>
                                        @endif
                                        <a href="{{url($prefix_url . '/edit', [$item->id]) . $qs}}" class="btn btn-sm btn-warning btn-flat" data-toggle="tooltip" title="{{ trans($lang_common . '.btn_edit') }}"><i class="fa fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger btn-flat" data-toggle="tooltip" title="{{ trans($lang_common . '.btn_trash') }}"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>

                    <div class="box-footer text-right">
                        <a href="{{ url($prefix_url . '/create') . $qs }}" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> {{ trans($lang_mod . '.create_new_cat') }}</a>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </section>

@stop