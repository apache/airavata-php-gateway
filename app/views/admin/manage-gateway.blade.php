@extends('layout.basic')

@section('page-header')
@parent
{{ HTML::style('css/admin.css')}}
@stop

@section('content')



<!-- contains all compute resource choices that might get selected on adding a new one to a gateway -->
@foreach( (array)$computeResources as $index => $cr)
@include('partials/compute-resource-preferences', array('computeResource' => $cr, 'crData' => $crData))
@endforeach

<!-- contains all storage resource choices that might get selected on adding a new one to a gateway -->
@foreach( (array)$storageResources as $index => $sr)
    @include('partials/storage-resource-preferences', array('storageResource' => $sr, 'srData' => $srData))
@endforeach

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

        <div class="col-md-12">
            <ul class="nav nav-tabs nav-justified" id="tabs" role="tablist">
                <li class="active"><a href="#tab-currentGateway" data-toggle="tab">Gateway - {{ Session::get("gateway_id") }}</a></li>
                @if( Session::has('super-admin'))
                    <li><a href="#tab-allGateways" data-toggle="tab">Approved Gateways</a></li>
                    <li><a href="#tab-requestedGateways" data-toggle="tab">Gateway Requests</a></li>
                @endif
            </ul>
        </div>
        <div class="container-fluid">

            <div class="tab-content col-md-12">

                <div class="tab-pane active" id="tab-currentGateway">

                    <div class="panel-group" id="accordion2">
                        <h3>Edit your Gateway Profile</h3>
                        @foreach( $gateways as $indexGP => $gp )
                            @if( $gp->gatewayId == Session::get("gateway_id"))
                                @include('partials/gateway-preferences-block', array("gp" => $gp, "accName" => "accordion2") )
                            @endif
                        @endforeach
                    </div>
                </div>

                @if( Session::has('super-admin'))

                <div class="tab-pane" id="tab-requestedGateways">

                    <div class="row">
                        <form id="add-tenant-form" action="{{ URL::to("/") }}/admin/add-gateway">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-default toggle-add-tenant"><span
                                        class="glyphicon glyphicon-plus"></span>Add a new gateway
                                </button>
                            </div>
                            @include('partials/add-gateway-block')
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Gateway Requests</h3>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Gateway Name</th>
                                        <th>Admin Name</th>
                                        <th>Gateway URL</th>
                                        <th>Project Details</th>
                                        <th>Project Abstract</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach( $gateways as $indexGP => $gp )
                                    <tr>
                                        <td>{{$gp->gatewayName }}</td>
                                        <td>{{ $gp->gatewayAdminFirstName }} {{ $gp->gatewayAdminLastName }} </td>
                                        <td>{{ $gp->gatewayURL }}</td>
                                        <td>{{ $gp->reviewProposalDescription}}</td>
                                        <td>{{ $gp->gatewayPublicAbstract}}</td>
                                        @if( $gp->gatewayApprovalStatus == 0)
                                            <td>
                                                <form action="{{URL::to('/')}}/admin/update-gateway-request" method="GET">
                                                    <input type="hidden" name="gateway_id" value="{{$gp->gatewayId}}">
                                                    <textarea style="width:100%; height:80px" width="100%" name="comments" placeholder="Comments"></textarea>
                                                    <br/>
                                                    <input type="submit" name="status" class="btn btn-primary" value="Approve"/>
                                                    <input type="submit" name="status" class="btn btn-danger" value="Deny"/>
                                                </form>
                                            </td>
                                        @elseif( $gp->gatewayApprovalStatus == 1)
                                            <td>Approved @if( $gp->declinedReason != "")<br/><br/>Comment: {{$gp->declinedReason}} @endif</td>
                                        @elseif( $gp->gatewayApprovalStatus == 5)
                                            <td>Denied @if( $gp->declinedReason != "")<br/><br/>Comment: {{$gp->declinedReason}} @endif</td>
                                        @endif
                                    </tr>
                                @endforeach
                                <!-- foreach code ends -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab-allGateways">


                    <div class="row">
                        <div class="col-md-6">
                            <h3>Check all Gateway Profiles</h3>
                        </div>
                        <div class="col-md-6" style="margin-top:2%">
                            <input type="text" class="col-md-12 filterinput" placeholder="Search by Gateway Name"/>
                        </div>
                    </div>

                    <div class="panel-group super-admin-gateways-view" id="accordion1">
                        @foreach( $gateways as $indexGP => $gp )
                            @include('partials/gateway-preferences-block', array("gp" => $gp, "accName" => "accordion1"))
                        @endforeach
                    </div>
                </div>

                @endif
            </div>
            <!-- ends tabs -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>


<div class="add-compute-resource-block hide">
    <div class="well">
        <form action="{{URL::to('/')}}/gp/add-crp" method="POST">
            <input type="hidden" name="gatewayId" id="gatewayId" value="">

            <div class="input-group">
                <select name="computeResourceId" class="cr-select form-control">
                    <option value="">Select a compute Resource and set its preferences</option>
                    @foreach( (array)$unselectedCRs as $index => $cr)
                    <option value="{{ $cr->computeResourceId}}">{{ $cr->hostName }}</option>
                    @endforeach
                </select>
                <span class="input-group-addon remove-cr" style="cursor:pointer;">x</span>
            </div>
            <div class="cr-pref-space form-horizontal"></div>
        </form>
    </div>
</div>

<div class="add-data-storage-preference-block hide">
    <div class="well">
        <form action="{{URL::to('/')}}/gp/add-srp" method="POST">
            <input type="hidden" name="gatewayId" id="gatewayId" value="">

            <div class="input-group">
                <select name="storageResourceId" class="sr-select form-control">
                    <option value="">Select a Data Storage Resource and set its preferences</option>
                    @foreach( (array)$unselectedSRs as $index => $sr)
                        <option value="{{ $sr->storageResourceId}}">{{ $sr->hostName }}</option>
                    @endforeach
                </select>
                <span class="input-group-addon remove-cr" style="cursor:pointer;">x</span>
            </div>
            <div class="sr-pref-space form-horizontal"></div>
        </form>
    </div>
</div>

<!-- Remove a Compute Resource from a Gateway -->
<div class="modal fade" id="remove-compute-resource-block" tabindex="-1" role="dialog" aria-labelledby="add-modal"
     aria-hidden="true">
    <div class="modal-dialog">

        <form action="{{URL::to('/')}}/gp/remove-cr" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center">Remove Compute Resource Confirmation</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control remove-crId" name="rem-crId"/>
                    <input type="hidden" class="form-control cr-gpId" name="gpId"/>

                    Do you really want to remove the Compute Resource, <span class="remove-cr-name"> </span> from the
                    selected Gateway?
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="submit" class="btn btn-danger" value="Remove"/>
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel"/>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- Remove a Storage Resource from a Gateway -->
<div class="modal fade" id="remove-storage-resource-block" tabindex="-1" role="dialog" aria-labelledby="add-modal"
     aria-hidden="true">
    <div class="modal-dialog">

        <form action="{{URL::to('/')}}/gp/remove-sr" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center">Remove Storage Resource Confirmation</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control remove-srId" name="rem-srId"/>
                    <input type="hidden" class="form-control sr-gpId" name="gpId"/>

                    Do you really want to remove the Storage Resource, <span class="remove-sr-name"> </span> from the
                    selected Gateway?
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="submit" class="btn btn-danger" value="Remove"/>
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel"/>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- Add a Gateway -->
<div class="modal fade" id="add-gateway-loading" tabindex="-1" role="dialog" aria-labelledby="add-modal"
     aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-center">Registering the gateway</h3>
            </div>
            <div class="modal-body text-center">
                <h5>Please DO NOT reload the page. This can take a couple of minutes.</h5>
                <img src="{{URL::to('/')}}/assets/ajax-loader.gif"/>
            </div>
        </div>
    </div>
</div>

@stop


@section('scripts')
@parent
{{ HTML::script('js/gateway.js') }}
<script>
    //make first tab of accordion open by default.
    //temporary fix
    $("#accordion2 .accordion-toggle").first().addClass("in").removeClass("collapsed");

    $(".credential-store-token-change > form").submit( function(e){
        $(this).prepend( "<img id='loading-gif' src='{{URL::to('/')}}/assets/ajax-loader.gif'/>");
        e.preventDefault();
        cstField = $("#gateway-credential-store-token");
        $.ajax({
            url: "{{URL::to('/')}}/gp/credential-store-token-change",
            method: "POST",
            data: { cst : cstField.val(), gateway_id: cstField.data("gpid") }
        }).done( function( data){
            $("#loading-gif").remove();
            alert( data);
        });
       
    });

    $(".set-cr-preference").submit( function( ev){
        var crForm = $(this);
        crForm.find(".loading-gif").removeClass("hide");

        ev.preventDefault();
        var datastring = crForm.serialize();
        $.ajax({
            type: "POST",
            url: "{{URL::to('/')}}/gp/update-crp",
            data: datastring,
            success: function(data) {
                if( data == 1)
                    crForm.find(".alert-success").removeClass("hide");
                else
                    crForm.find(".alert-danger").removeClass("hide");
            }
        }).complete( function(){
            crForm.find(".loading-gif").addClass("hide");
            setTimeout( function(){
                crForm.find(".alert-success").addClass("hide");
                crForm.find(".alert-danger").addClass("hide");
            }, 5000);
        });

    });

    $(".set-sr-preference").submit( function( ev){
        var srForm = $(this);
        srForm.find(".loading-gif").removeClass("hide");

        ev.preventDefault();
        var datastring = srForm.serialize();
        $.ajax({
            type: "POST",
            url: "{{URL::to('/')}}/gp/update-srp",
            data: datastring,
            success: function(data) {
                if( data == 1)
                    srForm.find(".alert-success").removeClass("hide");
                else
                    srForm.find(".alert-danger").removeClass("hide");
            }
        }).complete( function(){
            srForm.find(".loading-gif").addClass("hide");
            setTimeout( function(){
                srForm.find(".alert-success").addClass("hide");
                srForm.find(".alert-danger").addClass("hide");
            }, 5000);
        });

    });


    $(".add-tenant").slideUp();

    $(".toggle-add-tenant").click(function () {
        $('html, body').animate({
            scrollTop: $(".toggle-add-tenant").offset().top
        }, 500);
        $(".add-tenant").slideDown();
    });

    $("#add-tenant-form").submit(function (event) {
        event.preventDefault();
        event.stopPropagation();
        var formData = $("#add-tenant-form").serialize();
        $("#add-gateway-loading").modal("show");
        $(".loading-gif").removeClass("hide");
        $.ajax({
            type: "POST",
            data: formData,
            url: '{{ URL::to("/") }}/admin/add-gateway',
            success: function (data) {
                if( data.gateway == $(".gatewayName").val() ){
                    $(".gateway-success").html("Gateway has been added. The page will be reloaded in a moment.").removeClass("hide");
                    setTimeout( function(){
                        location.reload();
                    }, 3000);
                }
                else if( data == 0){
                    $(".gateway-error").html( "An unknown error occurred while trying to create the gateway.")
                                        .removeClass("hide");
                }
                else{
                    errors = data;
                    $(".gateway-error").html("").removeClass("hide");
                    for( input in data)
                    {
                        $(".gateway-error").append(" -- " + input + " : " + data[input] + "<br/><br/>");
                    }
                }
            },
            error: function( data){
                var error = $.parseJSON( data.responseText);
                $(".gateway-error").html(error.error.message).removeClass("hide");
            }
        }).complete(function () {
            $("#add-gateway-loading").modal("hide");
            $(".loading-gif").addClass("hide");
        });
    });

    disableInputs( $(".super-admin-gateways-view"));

    function disableInputs( elem){
      elem.find("input").each( function( i,e){
          if( $(e).attr("type")=='submit' || $(e).attr("type")=='button' || $(e).attr("type")=='checkbox')
              $(e).attr("disabled", "true");
           else
              $(e).prop("readonly", "true");
        });
        elem.find("textarea").prop("readonly", "true");
        elem.find("select").attr("disabled", "true");
        elem.find(".hide").prop("readonly", "true");
        elem.find("button").attr("disabled", "true");
        elem.find(".glyphicon").hide();
    }

</script>
@stop