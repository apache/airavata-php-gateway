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
                    <button type="button" class="close" data-dismiss="alert"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    {{ Session::get("message") }}
                </div>
            </div>
            {{ Session::forget("message") }}
            @endif
        </div>
        <div class="container-fluid">
            <div class="success-message"></div>
            <div class="col-md-12">
<!--                <h1 class="text-center well alert alert-danger">Proposed(Dummy) UI for maintaining availability of-->
<!--                    Resources. More fields can be added.</h1>-->
                <h1 class="text-center">Resources</h1>

                <table class="table table-striped table-condensed">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>
                            Enabled
                        </th>
                    </tr>
                    @foreach( (array)$resources as $resource)
                    <?php
                        $resourceId = $resource->computeResourceId;
                        $resourceName = $resource->hostName;
//Fixme
//                        $enabled = $resource->enabled;
                        $enabled = true;
                    ?>
                    <tr class="user-row">
                        <td>{{ $resourceId }}</td>
                        <td>{{ $resourceName }}</td>
                        <td>
                            @if(!$enabled)
                            <div class="checkbox">
                                <input class="resource-status" resourceId="{{$resourceId}}" type="checkbox">
                            </div>
                            @else
                            <div class="checkbox">
                                <input class="resource-status" type="checkbox" resourceId="{{$resourceId}}" checked>
                             </div>
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
<script>
    $('.resource-status').click(function() {
        var $this = $(this);
        if ($this.is(':checked')) {
            //enable compute resource
            $resourceId = $this.attr("resourceId");
            $.ajax({
                type: 'POST',
                url: "{{URL::to('/')}}/admin/enable-cr",
                data: {
                    'resourceId': $resourceId
                },
                async: true,
                success: function (data) {
                    console.log("enabled cr " + $resourceId);
                    $(".success-message").html("<span class='alert alert-success col-md-12'>Successfully enabled compute resource</span>");
                }
            });
        } else {
            //disabled compute resource
            $resourceId = $this.attr("resourceId");
            $.ajax({
                type: 'POST',
                url: "{{URL::to('/')}}/admin/disable-cr",
                data: {
                    'resourceId': $resourceId
                },
                async: true,
                success: function (data) {
                    console.log("disabled cr " + $resourceId);
                    $(".success-message").html("<span class='alert alert-success col-md-12'>Successfully disabled compute resource</span>");
                }
            });
        }
    });
</script>
@stop
