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
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                {{ Session::get("message") }}
                            </div>
                        </div>
                        {{ Session::forget("message") }}
                    @endif

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
                        <tr>
                            <td>{{ $user }}</td>
                            <td>
                                <button class="button btn btn-default check-role" type="button">Check Role</button>
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
                        User roles will be displayed and modified here.    
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" data-dismiss="modal"  value="Ok"/>
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
    $(".check-role").click( function(){
        $("#check-role-block").modal("show");
    });


    </script>
@stop