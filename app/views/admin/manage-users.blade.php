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
                            <th>Role</th>
                        </tr>
                        @foreach( $users as $user)
                        <tr>
                            <td>{{ $user }}</td>
                            <td><button class="button btn btn-default" type="button">Check Role</button></td>
                        </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>

@stop