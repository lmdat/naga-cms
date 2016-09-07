@extends('Backend::layouts.master')

@section('content')
<section class="content-header">
    <h1>
        List User
        <small></small>
    </h1>
    <!--ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol-->
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            {!! Form::open(['url' => Request::url() . $form_qs, 'name' => 'adminForm', 'id' => 'adminForm', 'role' => 'form']) !!}
                <div class="box box-primary">
                    <div class="box-header text-right">
                        <a href="{{ url($prefix_url . '/create') . $qs }}" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> Create User</a>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th >Name</th>
                                    <th width="20%">Email</th>
                                    <th width="30%">Roles</th>
                                    <th width='15%'>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $item)
                                <tr>
                                    <td><a href="{{url($prefix_url . '/edit', [$item->id]) . $qs}}">{{$item->first_name}} {{$item->surname}}</a></td>
                                    <td>{{$item->email}}</td>
                                    <td>
                                    <?php
                                        $roles = $item->roles()->orderBy('power', 'DESC')->get();
                                        $n = count($roles);
                                    ?>
                                    @for($i=0; $i<$n; $i++)
                                        <i class="fa fa-check-square-o"></i><small>{{$roles[$i]->role_name}}</small>&nbsp;
                                        @if($i % $n == 2) <br/> @endif
                                    @endfor
                                    </td>
                                    
                                    <td class="text-right">
                                        
                                        @if($item->active == 1)
                                        <a href="{{url($prefix_url . '/active', [$item->id]) . $qs}}" class="btn btn-sm btn-info btn-flat" data-toggle="tooltip" title="Unlock"><i class="fa fa-unlock-alt"></i></a>
                                        @else
                                        <a href="{{url($prefix_url . '/active', [$item->id]) . $qs}}" class="btn btn-sm btn-default btn-flat" data-toggle="tooltip" title="Locked"><i class="fa fa-lock"></i></a>
                                        @endif
                                        <a href="{{url($prefix_url . '/edit', [$item->id]) . $qs}}" class="btn btn-sm btn-warning btn-flat" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                        <a href="{{url($prefix_url . '/delete', [$item->id]) . $qs}}" class="btn btn-sm btn-danger btn-flat" data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right">
                                        {!!$users->render()!!}
                                    </td>
                                </tr>
                            </tfoot>
                      </table>
                        
                    </div>
                    
                    <div class="box-footer text-right">
                        <div>
                            <a href="{{ url($prefix_url . '/create') . $qs }}" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> Create User</a>
                        </div>
                    </div>
                </div>
                
            {!! Form::close() !!}
        </div>
        
    </div>
</section>
<script type="text/javascript">
    $(function(){
        $('ul.pagination').addClass('pagination-sm no-margin');
    });
</script>
@stop