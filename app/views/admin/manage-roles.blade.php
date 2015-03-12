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

                    <h1 class="text-center">Roles</h1>

                    <table class="table table-striped table-condensed">
                        <tr>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                        @foreach( $roles as $role)
                        <tr>
                            <td>{{ $role }}</td>
                            <td>
                                <span class="glyphicon glyphicon-pencil"></span>
                                <span class="glyphicon glyphicon-remove"></span>
                            </td>
                        </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>

@stop