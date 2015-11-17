@extends('layout.basic')

@section('page-header')
@parent
{{ HTML::style('css/admin.css')}}
{{ HTML::style('css/datetimepicker.css')}}
@stop

@section('content')

<div id="wrapper">
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    @include( 'partials/dashboard-block')
    <div id="page-wrapper">

<div class="container-fluid">
    @if( Session::has("message"))
    <div class="col-md-12">
        <span class="alert alert-success">{{ Session::get("message") }}</span>
    </div>
    {{ Session::forget("message") }}
    @endif

    @if ( isset( $allCRs) )
    @if (sizeof($allCRs) == 0)
    <?php $registerDataStorageURL = URL::to('/') . "/ds/create";?>
    {{ CommonUtilities::print_warning_message('No Data Storage Resources are registered. <br/> <a href="{{$registerDataStorageURL}}" class="btn btn-primary"></a>') }}
    @else
    <br/>
    <div class="col-md-12">
        <div class="panel panel-default form-inline">
            <div class="panel-heading">
                <h3 style="margin:0;">Search Data Storage Resources</h3>
            </div>
            <div class="panel-body">
                <div class="form-group search-text-block">
                    <label>Data Storage Resource Name </label>
                    <input type="search" class="form-control filterinput"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="table-responsive">
                <table class="table">

                    <tr>
                        <th>Id</th>
                        <th>Login Username</th>
                        <th>File System Root Location</th>
                        <th>Resource Specific Credential Store Token</th>
                        @if(Session::has("admin"))
                        <th>Edit</th>
                        @endif
                        <th>View</th>
                        @if(Session::has("admin"))
                        <th>Delete</th>
                        @endif
                    </tr>

                    @foreach($allDSRs as $resource)
                    <?php
                        $dsId = $resource->dataMovememtResourceId;
                        $userName = $resource->loginUserName;
                        $fileSystemRootLocation = $resource->fileSystemRootLocation;
                        $resourceSpecificCredentialStoreToken = $resource->resourceSpecificCredentialStoreToken;
                    ?>
                    <tr id="dsDetails">
                        <td>{{ $dsId }}</td>
                        <td>{{ $userName }}</td>
                        <td>{{ $fileSystemRootLocation }}</td>
                        <td>{{ resourceSpecificCredentialStoreToken }}</td>
                        @if(Session::has("admin"))
                        <td><a href="{{URL::to('/')}}/ds/edit?crId={{ $dsId }}" title="Edit">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                        </td>
                        @endif
                        <td>
                            <a href="{{URL::to('/')}}/ds/view?crId={{ $crId }}" title="Edit">
                            <span class="glyphicon glyphicon-list"></span>
                            </a>
                        </td>
                        @if(Session::has("admin"))
                        <td>
                            <a href="#" title="Delete">
                                <span class="glyphicon glyphicon-trash del-ds" data-toggle="modal"
                                      data-target="#delete-ds-block" data-dsid="{{$dsId}}"></span>
                            </a>
                        </td>
                        @endif
                    </tr>
                    @endforeach

                </table>
            </div>
        </div>
        @endif
        @endif

        <div class="modal fade" id="delete-ds-block" tabindex="-1" role="dialog" aria-labelledby="add-modal"
             aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{URL::to('/')}}/ds/delete-ds" method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="text-center">Delete Data Storage Resource Confirmation</h3>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" class="form-control delete-crId" name="del-crId"/>
                            Do you really want to delete Data Storage Resource, <span class="delete-ds-id"></span>? This action cannot be undone.
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

    </div>
</div>
</div>

    @stop
    @section('scripts')
    @parent
    <script type="text/javascript">
        $('.filterinput').keyup(function () {
            var value = $(this).val();
            if (value.length > 0) {
                $("table tr").each(function (index) {
                    if (index != 0) {

                        $row = $(this);

                        var id = $row.find("td:first").text();
                        id = $.trim(id);
                        id = id.substr(0, value.length);
                        if (id == value) {
                            $(this).slideDown();
                        }
                        else {
                            $(this).slideUp();
                        }
                    }
                });
            } else {
                $("table tr").slideDown();
            }
            return false;
        });

        $(".del-ds").click(function () {
            $(".delete-ds-id").html("'" + $(this).data("delete-ds-id") + "'");
            $(".delete-dsId").val($(this).data("dsid"));
        });
    </script>
    @stop