@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')

<div class="container">
    @if( Session::has("message"))
    <div class="col-md-12">
        <span class="alert alert-success">{{ Session::get("message") }}</span>
    </div>
    {{ Session::forget("message") }}
    @endif

    @if ( isset( $allCRs) )
    @if (sizeof($allCRs) == 0)
    {{ CommonUtilities::print_warning_message('No Compute Resources are registered. Please use "Register Compute
    Resource" to
    register a new resources.') }}
    @else
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <h3 style="margin:0;">Existing Compute Resources :</h3>
            </div>
            <input type="text" class="pull-right filterinput col-md-6" placeholder="Search by Compute Resource Name"/>
        </div>
        <div class="row">
            <div class="table-responsive">
                <table class="table">

                    <tr>

                        <th>Name</th>
                        <th>Id</th>
                        <th>Edit</th>
                        <th>View</th>
                        <th>Delete</th>
                    </tr>

                    @foreach ($allCRs as $crId => $crName)

                    <tr id="crDetails">
                        <td>{{ $crName }}</td>
                        <td>{{ $crId }}</td>
                        <td><a href="{{URL::to('/')}}/cr/edit?crId={{ $crId }}" title="Edit">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                        </td>
                        <td>
                            <a href="{{URL::to('/')}}/cr/view?crId={{ $crId }}" title="Edit">
                            <span class="glyphicon glyphicon-list"></span>
                            </a>
                        </td>
                        <td>
                            <a href="#" title="Delete">
                                <span class="glyphicon glyphicon-trash del-cr" data-toggle="modal"
                                      data-target="#delete-cr-block" data-delete-cr-name="{{$crName}}"
                                      data-deployment-count="{{$connectedDeployments[$crId]}}"
                                      data-crid="{{$crId}}"></span>
                            </a>
                        </td>
                    </tr>
                    @endforeach

                </table>
            </div>
        </div>
        @endif
        @endif

        <div class="modal fade" id="delete-cr-block" tabindex="-1" role="dialog" aria-labelledby="add-modal"
             aria-hidden="true">
            <div class="modal-dialog">

                <form action="{{URL::to('/')}}/cr/delete-cr" method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="text-center">Delete Compute Resource Confirmation</h3>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" class="form-control delete-crId" name="del-crId"/>
                            The Compute Resource, <span class="delete-cr-name"></span> is connected to <span
                                class="deploymentCount">0</span> deployments.
                            Do you really want to delete it? This action cannot be undone.
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

        $(".del-cr").click(function () {
            $(".delete-cr-name").html("'" + $(this).data("delete-cr-name") + "'");
            $(".delete-crId").val($(this).data("crid"));
            $(".deploymentCount").html($(this).data("deployment-count"));
        });
    </script>
    @stop