@extends('layout.basic')

@section('page-header')
@parent
{{ HTML::style('css/admin.css')}}
@stop

@section('content')

<div id="wrapper">
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    @include( 'partials/dashboard-block')
    <div id="page-wrapper">

        <div class="container-fluid">
            <div class="col-md-12">
                @if( Session::has("message"))
                <div class="row">
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                                class="sr-only">Close</span></button>
                        {{ Session::get("message") }}
                    </div>
                </div>
                {{ Session::forget("message") }}
                @endif

                <h1 class="text-center">Roles</h1>

                <table class="table table-striped table-condensed">
                    <tr>
                        <th>
                            Role
                        </th>
                        <th>Actions</th>
                    </tr>
                    @foreach( $roles as $role)
                    <tr>
                        <td class="role-name">{{ $role }}</td>
                        <td>
                            <span class="glyphicon glyphicon-pencil edit-role-name"></span>&nbsp;&nbsp;
                            <a href="{{URL::to('/')}}/admin/dashboard/users?role={{$role}}">
                                <span class="glyphicon glyphicon-user role-users"></span>&nbsp;&nbsp;
                            </a>
                            <span class="glyphicon glyphicon-trash delete-role"></span>&nbsp;&nbsp;
                        </td>
                    </tr>
                    @endforeach
                </table>
                <div class="col-md-12">
                    <button type="button" class="btn btn-default toggle-add-role"><span
                            class="glyphicon glyphicon-plus"></span>Add a new Role
                    </button>
                </div>
                <div class="add-role col-md-6">
                    <form role="form" action="{{URL::to('/')}}/admin/add-role" method="POST" class="add-role-form">
                        <div class="form-group">
                            <label>Enter Role Name</label>
                            <input type="text" name="role" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="form-control btn btn-primary" value="Add"/>
                        </div>
                    </form>
                </div>

                <div class="edit-role hide">
                    <form class="edit-role-form">
                        <div class="form-group col-md-4">
                            <input type="text" name="new-role-name" class="new-role-name form-control"/>
                            <input type="hidden" name="original-role-name" class="original-role-name" value=""/>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="submit" class="form-control btn btn-primary" value="Edit"/>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-role-block" tabindex="-1" role="dialog" aria-labelledby="add-modal"
     aria-hidden="true">
    <div class="modal-dialog">

        <form action="{{URL::to('/')}}/admin/delete-role" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center">Delete Role Confirmation</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control delete-roleName" name="role"/>
                    Do you really want to delete the role - <span class="delete-role-name"></span>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="submit" class="btn btn-danger" value="Delete"/>
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel"/>
                    </div>
                </div>
            </div>

        </form>


    </div>
</div>


@stop

@section('scripts')
@parent
<script>
    $(".toggle-add-role").click(function () {
        $(".add-role").slideDown();
    });

    $(".edit-role-name").click(function () {
        var roleNameSpace = $(this).parent().parent().find(".role-name");
        if (roleNameSpace.find(".edit-role-form").length) {
            roleNameSpace.html(roleNameSpace.find(".original-role-name").val());
        }
        else {
            var role = roleNameSpace.html();
            roleNameSpace.html($(".edit-role").html());
            roleNameSpace.find(".original-role-name").val(role);
            roleNameSpace.find(".new-role-name").val(role);
        }
    });

    $(".delete-role").click(function () {
        $("#delete-role-block").modal("show");
        var roleName = $(this).parent().parent().find(".role-name").html();
        $(".delete-role-name").html(roleName);
        $(".delete-roleName").val(roleName);
    });
</script>
@stop