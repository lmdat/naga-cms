@extends('Backend::layouts.master')

@section('content')
    <section class="content-header">
        <h1>
            {{ trans($lang_mod . '.category_edit_header') }}
            <small></small>
        </h1>
        <!--ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol-->
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-7">
                {!! Form::open(['url' => Request::url() . $qs, 'method' => 'put', 'name' => 'catForm', 'id' => 'catForm', 'role' => 'form']) !!}
                <div class="box box-primary">
                    <!--div class="box-header">
                        <h3 class="box-title"></h3>
                    </div-->
                    <div class="box-body">

                        <div class="form-group">
                            <label for="section_id">{{ trans($lang_mod . '.parent_cat') }}</label>
                            {!! Form::select('parent_id', $tree_data, ($cat->parent_id == 0) ? '' : $cat->parent_id, ['class' => 'form-control input-sm', 'id' => 'parent_id']) !!}
                        </div>

                        <div class="form-group @if($errors->has('cat_name')) has-error @endif">
                            <label for="province_name">{{ trans($lang_mod . '.cat_name') }}</label>
                            {!! Form::text('cat_name', $cat->cat_name, ['id'=>'cat_name', 'class' => 'form-control', 'placeholder' => 'Required']) !!}
                            @if ($errors->has('cat_name'))<p><small class="text-red">{!!$errors->first('cat_name')!!}</small></p> @endif
                        </div>

                        <div class="form-group @if($errors->has('alias')) has-error @endif">
                            <label for="alias">{{ trans($lang_mod . '.alias') }}</label>
                            {!! Form::text('alias', $cat->alias, ['id'=>'alias', 'class' => 'form-control', 'placeholder' => '']) !!}
                            @if ($errors->has('alias'))<p><small class="text-red">{!!$errors->first('alias')!!}</small></p> @endif
                        </div>

                        <div class="form-group">
                            <label for="published">{{ trans($lang_mod . '.published') }}</label>
                            <div class="">
                                <label class="radio-inline">
                                    {!! Form::radio('published', 1, $cat->published == 1, ['id'=>'published1']) !!} {{ trans($lang_common . '.radio_yes') }}
                                </label>
                                <label class="radio-inline">
                                    {!! Form::radio('published', 0, $cat->published == 0, ['id'=>'published0']) !!} {{ trans($lang_common . '.radio_no') }}
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="box-footer">
                        <div class="text-right">

                            <a href="{{ url($prefix_url ) . $qs }}" class="btn bg-orange btn-flat"><i class="fa fa-reply"></i> {{ trans($lang_common . '.btn_exit') }}</a>
                            <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-save"></i> {{ trans($lang_common . '.btn_save') }}</button>

                        </div>
                    </div>

                </div>
                    {!! Form::hidden('id', $cat->id) !!}
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