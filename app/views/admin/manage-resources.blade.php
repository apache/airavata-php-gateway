@extends('layout.basic')

@section('page-header')
    @parent
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.1.0/css/bootstrap-toggle.min.css" rel="stylesheet">
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
                    <h1 class="text-center well alert alert-danger" >Proposed(Dummy) UI for maintaining availability of Resources. More fields can be added.</h1>
                    <h1 class="text-center">Resources</h1>

                    <table class="table table-striped table-condensed">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>
                                Status
                            </th>
                        </tr>
                        @foreach( (array)$resources as $resourceId => $resourceName )
                        <tr class="user-row">
                            <td class="resource-id">{{ $resourceId }}</td>
                            <td class="resource-name">{{ $resourceName }}</td>
                            <td class="resource-switch">
                                <!--This is a random selection -->
                                <input class="resource-checkbox" type="checkbox" checked data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-onstyle="success" data-offstyle="danger">
                                <!-- 
                                @if( strpos( $resourceName, "a") )
                                    <div class="btn-group btn-toggle"> 
                                        <button class="btn btn-xs btn-default">ON</button>
                                        <button class="btn btn-xs btn-danger active">Switch OFF</button>
                                    </div>
                                @else
                                    <div class="btn-group btn-toggle"> 
                                        <button class="btn btn-xs btn-success active">Switch ON</button>
                                        <button class="btn btn-xs btn-default">OFF</button>
                                    </div>
                                @endif
                                -->
                            </td>
                        </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    @parent
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.1.0/js/bootstrap-toggle.min.js"></script>
    <script>
        $(".resource-checkbox").bootstrapToggle();
        $(".resource-switch").click( function() {
            userRow = $(this).parent();
            console.log(userRow.children(".resource-id").text());
            
            // this is flipped because button click grabs state of checkbox AT click time
            checkedState = !($(this).find(".resource-checkbox").prop("checked"));
            console.log(checkedState);
        });
    </script>
@stop
