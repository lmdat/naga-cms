@extends('Backend::layouts.master')

@section('content')
<section class="content-header">
    <h1>
        Edit User
        <small></small>
    </h1>
    <!--ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol-->
</section>

<section class="content">
    <div class="row">
        <div class="col-md-6 col-sm-6">
            {!! Form::open(['url' => Request::url() . $qs, 'method' => 'put', 'name' => 'adminForm', 'id' => 'adminForm', 'role' => 'form']) !!}
                <div class="box box-primary">
                    <!--div class="box-header">
                        <h3 class="box-title"></h3>
                    </div-->
                    <div class="box-body">

                        <div class="form-group @if($errors->has('first_name')) has-error @endif">
                            <label for="first_name">First Name</label>
                            {!! Form::text('first_name', $user->first_name, ['id'=>'first_name', 'class' => 'form-control', 'required'=>true, 'placeholder' => 'Required']) !!}
                            @if ($errors->has('first_name'))<p><small class="text-red">{!!$errors->first('first_name')!!}</small></p> @endif
                        </div>

                        <div class="form-group  @if($errors->has('surname')) has-error @endif">
                            <label for="surname">Surname</label>
                            {!! Form::text('surname', $user->surname, ['id'=>'surname', 'class' => 'form-control', 'required'=>true, 'placeholder' => 'Required']) !!}
                             @if ($errors->has('surname'))<p><small class="text-red">{!!$errors->first('surname')!!}</small></p> @endif
                        </div>
                        
                        <div class="form-group  @if($errors->has('email')) has-error @endif">
                            <label for="email">Email</label>
                            {!! Form::email('email', $user->email, ['class' => 'form-control', 'required'=>true, 'placeholder' => 'Required|Email|Unique']) !!}
                            @if ($errors->has('email'))<p><small class="text-red">{!!$errors->first('email')!!}</small></p> @endif
                        </div>
                        
                        <div class="form-group  @if($errors->has('password')) has-error @endif">
                            <label for="password">Password</label><small><i>(Leave blank for nochange password)</i></small>
                            {!! Form::password('password', ['id'=>'password', 'class' => 'form-control', 'placeholder' => 'Required|Min:6']) !!}
                            @if ($errors->has('password'))<p><small class="text-red">{!!$errors->first('password')!!}</small></p> @endif
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            {!! Form::password('password_confirmation', ['id'=>'password_confirmation', 'class' => 'form-control']) !!}
                            
                        </div>

                        <div class="form-group @if($errors->has('roles')) has-error @endif">
                            <label for="roles[]">Role</label>
                            @foreach($roles as $role)
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('roles[]', $role->id, $roles_checked[$role->id], ['id' => 'role_' . $role->id]) !!} {{$role->role_name}}
                                    </label>
                                </div>
                            @endforeach
                            {{--{!! Form::select('role_id', $roles, '', ['class' => 'form-control', 'id' => 'role_id']) !!}--}}
                            @if ($errors->has('roles'))<p><small class="text-red">{!!$errors->first('roles')!!}</small></p> @endif
                        </div>

                        <div class="form-group">
                            <label for="active">Active</label>
                            <div class="">
                                <label class="radio-inline">
                                    {!! Form::radio('active', 1, $user->active == 1, ['id'=>'active1']) !!} Yes
                                </label>
                                <label class="radio-inline">
                                    {!! Form::radio('active', 0, $user->active == 0, ['id'=>'active0']) !!} No
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="box-footer">
                        <div class="text-right">

                            <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-save"></i> Save</button>
                            <a href="{{ url($prefix_url) . $qs }}" class="btn btn-danger btn-flat"><i class="fa fa-ban"></i> Cancel</a>
                        </div>
                    </div>

                </div>
            {!! Form::hidden('id', $user->id) !!}
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