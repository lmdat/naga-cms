@extends('Backend::layouts.authentication')


@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="./">{{trans($lang_mod . '.panel_name')}}</a>
    </div>
    
    <div class="login-box-body">
        @if(!Session::has('message-error'))
        <div class="login-box-msg">{{ trans($lang_mod . '.title') }}</div>
        @else
        <div class="alert alert-danger"><strong>Oh snap!</strong> {{ Session::get('message-error') }}</div>
        @endif
        
        {!! Form::open(['url' => Request::url(), 'name' => 'loginForm', 'id' => 'loginForm', 'role' => 'form']) !!}
            <div class="form-group has-feedback">
                {!! Form::email('email', '', ['class' => 'form-control input-sm', 'required'=>true, 'placeholder' => trans($lang_mod . '.email')]) !!}
                <i class="fa fa-envelope form-control-feedback"></i>
            </div>
            <div class="form-group has-feedback">
                {!! Form::password('password', ['id'=>'password', 'class' => 'form-control input-sm', 'required'=>true, 'placeholder' => trans($lang_mod . '.password')]) !!}
                <i class="fa fa-key form-control-feedback"></i>
            </div>
            <div class="row">
                <div class="col-xs-7">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember_me" value="1"> {{trans($lang_mod . '.remember_me')}}
                        </label>
                    </div>   
                </div>
                <div class="col-xs-5">
                    <button type="submit" class="btn btn-success btn-sm btn-block btn-flat"><i class="fa fa-sign-in"> </i> {{trans($lang_mod . '.login')}}</button>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_minimal-green',
            radioClass: 'iradio_minimal-green',
        });
    });
</script>
@stop