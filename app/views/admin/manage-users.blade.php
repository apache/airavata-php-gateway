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
            <div class="col-md-12">
            @if( Session::has("message"))
                <div class="row">
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        {{ Session::get("message") }}
                    </div>
                </div>
                {{ Session::forget("message") }}
            @endif
            </div>
            <div class="container-fluid">
                <div class="col-md-12">
                
                    <h1 class="text-center">Users</h1>

                    <table class="table table-striped table-condensed">
                        <tr>
                            <th>Username</th>
                            <th>
                                Role : 
                                <select onchange="location = this.options[this.selectedIndex].value;">
                                    <option>Select a role</option>
                                    <option value="{{URL::to('/')}}/admin/dashboard/users">All</option>
                                    @foreach( (array)$roles as $role)
                                    <option value="{{URL::to('/')}}/admin/dashboard/users?role={{$role}}">{{$role}}</option>
                                    @endforeach
                                </select>
                            </th>
                        </tr>
                        @foreach( (array)$users as $user)
                        <tr class="user-row">
                            <td>{{ $user }}</td>
                            <td>
                                <button class="button btn btn-default check-roles fade" type="button" data-username="{{$user}}">Check All Roles</button>
                                <div class="user-roles"></div>
                            </td>
                        </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="check-role-block" tabindex="-1" role="dialog" aria-labelledby="add-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center">User Roles</h3>
                </div>
                <div class="modal-body">
                    <h4 class="roles-of-user"></h4>
                    <div class="roles-load">
                        Getting User Roles. Please Wait...  <img src="{{URL::to('/')}}/assets/ajax-loader.gif"/>
                    </div>
                    <div class="roles-list">
                    </div>  
                    <div class="add-roles-block hide">
                        <div class="form-group well">
                            <label class="control-label">Add a new role to the user</label>
                            <select multiple name="new-role" class="new-roles-select" class="form-control">
                                <option>Select a role</option>
                                @foreach( (array)$roles as $role)
                                <option value="{{$role}}">{{$role}}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary add-roles-submit"  data-username="">Add Roles</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="submit" class="btn" data-dismiss="modal"  value="Close"/>
                    </div>
                </div>
            </div>
            <input type="hidden" class="base-url" value="{{URL::to('/')}}"/>
        </div>
    </div>

    <div class="role-block hide">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-default role-name" disabled>Role</button>
            <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></button>
        </div>
    </div>
@stop

@section('scripts')
    @parent
    <script>

    $(".user-row").hover( 
        function(){
            $(this).find(".check-roles").addClass("in");
        },
        function(){
            $(this).find(".check-roles").removeClass("in");
        }
    );
    $(".check-roles").click( function(){

        //remove disabled roles from previous actions.
        $(".new-roles-select option").each(function()
        {
            $(this).removeAttr("disabled");
        });

        var userName = $(this).data("username");
        $("#check-role-block").modal("show");
        $(".roles-of-user").html( "User : " + userName);
        $(".roles-load").removeClass("hide");
        $(".roles-list").addClass("hide");
        $(".add-roles-submit").data("username", userName);
        $(this).find(".alert-success").remove();


        //getting user's existing roles
        $.ajax({
            type: "POST",
            url: $(".base-url").val() + "/admin/check-roles",
            data: 
            { 
              username: userName
            }
        })
        .complete(function( data ) {
            roles = JSON.parse( data.responseText );
            roleBlocks = "";
            for( var i=0; i<roles.length; i++)
            {
                //disable roles which user already has.
                $(".new-roles-select option").each(function()
                {
                    if( $(this).val() == roles[i])
                        $(this).attr("disabled", "disabled");
                    else
                        $(this).removeAttr("disabled");
                });
                $(".role-block").find(".role-name").html( roles[i]);
                var newRoleBlock = $(".role-block").html();
                roleBlocks += newRoleBlock;
                $(".roles-list").html( roleBlocks);

                $(".add-roles-block").removeClass("hide");
            }
            $(".roles-load").addClass("hide");
            $(".roles-list").removeClass("hide");


        });

        $(".add-roles-submit").click( function(){
            $(this).attr("disabled", "disabled");
            $(this).html("<img src='" + $(".base-url").val() + "/ajax-loader.gif'/>");
            userName = $(this).data("username");
            var rolesToAdd = $(".new-roles-select").val();
            $(".roles-list").find(".role-name").each( function(){
                rolesToAdd.push( $(this).html() );
            })
            $.ajax({
                type: "POST",
                url: $(".base-url").val() + "/admin/add-roles-to-user",
                data: 
                { 
                    add: true,
                    username: userName,
                    roles : rolesToAdd
                },
                success( data){
                    console.log( data);
                }
            });
            $(".add-roles-submit").html( "Add Roles");
            $(".add-roles-submit").after("<span class='alert alert-success col-md-12'>Roles have been added</span>");
        });

    });
    </script>
@stop