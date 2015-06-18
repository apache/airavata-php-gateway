@extends('layout.basic')

@section('page-header')
    @parent
    {{ HTML::style('css/bootstrap-toggle.min.css')}}
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
                        @foreach( (array)$resources as $resource ) 
                        <tr class="user-row">
                            <td class="resource-id">{{ $resource -> computeResourceId }}</td>
                            <td class="resource-name">{{ $resource -> hostName }}</td>
                            <td class="resource-switch">
                                @if ( $resource -> active )
                                    <input class="resource-checkbox" type="checkbox" checked data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-onstyle="success" data-offstyle="danger">
                                @else
                                    <input class="resource-checkbox" type="checkbox" unchecked data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-onstyle="success" data-offstyle="danger">
                                @endif
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
    {{ HTML::script('js/bootstrap-toggle.js')}}
    <script>
        $(".resource-checkbox").bootstrapToggle();
        $(".resource-switch").click( function() {
            userRow = $(this).parent();
            computeResourceId = userRow.children(".resource-id").text();
            console.log(computeResourceId);
            
            // this is flipped because button click grabs state of checkbox AT click time
            checkedState = !($(this).find(".resource-checkbox").prop("checked"));
            console.log(checkedState);

            $.post("/airavata-php-gateway/public/admin/update-resource-availability", {"computeResourceId":computeResourceId, "checkedState":checkedState}, function(data) {
                console.log(data);
            });
        });
    </script>
@stop
