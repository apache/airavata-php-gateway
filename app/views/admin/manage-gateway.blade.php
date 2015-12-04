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

            <div class="row">

                <div class="col-md-6">
                    <h3>Gateway Preferences</h3>
                </div>
                @if( Session::has("scigap_admin"))
                <div class="col-md-6" style="margin-top:3.5%">
                    <input type="text" class="col-md-12 filterinput" placeholder="Search by Gateway Name"/>
                </div>
                @endif
            </div>
            <div class="panel-group" id="accordion2">
                @foreach( $gateways as $indexGP => $gp )
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle collapsed gateway-name" data-toggle="collapse"
                               data-parent="#accordion2" href="#collapse-gateway-{{$indexGP}}">
                                {{ $gp->gatewayName }}
                            </a>
                            @if(Session::has("admin"))
                            <div class="pull-right col-md-2 gateway-options fade">
                                <span class="glyphicon glyphicon-pencil edit-gateway" style="cursor:pointer;"
                                      data-toggle="modal" data-target="#edit-gateway-block"
                                      data-gp-id="{{ $gp->gatewayId }}" data-gp-name="{{ $gp->gatewayName }}"></span>
                            </div>
                            @endif
                        </h4>
                    </div>
                    <div id="collapse-gateway-{{$indexGP}}" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="app-interface-block">
                                <div class="row">
                                     @if(Session::has("admin"))
                                    <div class="col-md-10 credential-store-token-change">
                                        <form>
                                            <div class="form-group">
                                                <label class="control-label col-md-12">{{ Session::get('theme') }} Credential Store Token</label>
                                                <div class="col-md-9">
                                                    <select class="form-control gateway-credential-store-token" name="resourceSpecificCredentialStoreToken"  data-gpid="{{$gp->gatewayId}}" >
                                                        @if( isset( $gp->profile->credentialStoreToken) )
                                                        <option value="{{$gp->profile->credentialStoreToken}}">{{$gp->profile->credentialStoreToken}}</option>
                                                        @else
                                                        <option value="">Select a Credential Token from Store</option>
                                                        @endif
                                                        @foreach( $tokens as $token => $publicKey)
                                                        <option value="{{$token}}">{{$token}}</option>
                                                        @endforeach
                                                    </select>
                                                    <!--
                                                    <input type="text" name="resourceSpecificCredentialStoreToken"  data-gpid="{{$gp->gatewayId}}" class="form-control credential-store-token"
                                                           value="@if( isset( $gp->profile->credentialStoreToken) ){{$gp->profile->credentialStoreToken}}@endif"/>
                                                    -->
                                                </div>
                                                <div class="col-md-3">
                                                        <input type="submit" class="form-control btn btn-primary" value="Set"/>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-10">
                                        <button class="btn btn-default add-cr" data-gpid="{{$gp->gatewayId}}"><span
                                                class="glyphicon glyphicon-plus"></span> Add a Compute Resource Preference
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                    
                                    <div class="col-md-10">
                                        @if( isset( $gp->profile->computeResourcePreferences) )
                                        <div>
                                            <h3>Compute Resource Preferences :</h3>
                                        </div>
                                        <div class="accordion-inner">
                                            <div class="panel-group" id="accordion-{{$indexGP}}">
                                                @foreach( (array)$gp->profile->computeResourcePreferences as $indexCRP
                                                => $crp )
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle collapsed gateway-name"
                                                               data-toggle="collapse" data-parent="#accordion"
                                                               href="#collapse-crp-{{$indexGP}}-{{$indexCRP}}">
                                                                {{ $crp->crDetails->hostName }}
                                                            </a>
                                                            @if(Session::has("admin"))
                                                            <div class="pull-right col-md-2 gateway-options fade">
                                                                <span class="glyphicon glyphicon-remove remove-resource"
                                                                      style="cursor:pointer;" data-toggle="modal"
                                                                      data-target="#remove-resource-block"
                                                                      data-cr-name="{{$crp->crDetails->hostName}}"
                                                                      data-cr-id="{{$crp->computeResourceId}}"
                                                                      data-gp-id="{{ $gp->gatewayId }}"></span>
                                                            </div>
                                                            @endif
                                                        </h4>
                                                    </div>
                                                    <div id="collapse-crp-{{$indexGP}}-{{$indexCRP}}"
                                                         class="panel-collapse collapse">
                                                        <div class="panel-body">
                                                            <div class="app-compute-resource-preferences-block">
                                                                <form action="{{URL::to('/')}}/gp/update-crp"
                                                                      method="POST">
                                                                    <input type="hidden" name="gatewayId" id="gatewayId"
                                                                           value="{{$gp->gatewayId}}">
                                                                    <input type="hidden" name="computeResourceId"
                                                                           id="gatewayId"
                                                                           value="{{$crp->computeResourceId}}">

                                                                    <div class="form-horizontal">
                                                                        @include('partials/gateway-preferences',
                                                                        array('computeResource' => $crp->crDetails,
                                                                        'crData' => $crData, 'preferences'=>$crp,
                                                                        'show'=>true))
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                        <!--
                                        Adding a user as admin will shift to roles. Removing from here.
                                        <h4><span class="glyphicon glyphicon-plus"></span> Add a user as Admin to this Gateway</h4>
                                        <form action="{{URL::to('/')}}/admin/addgatewayadmin" method="POST" role="form" enctype="multipart/form-data">
                                            <div class="form-group required">
                                                <label for="experiment-name" class="control-label">Enter Username</label>
                                                <input type="text" class="form-control" name="username" id="experiment-name" placeholder="username" autofocus required="required">
                                                <input type="hidden" name="gateway_name" value="{{ $gp->gatewayName }}"/>
                                            </div>
                                            <div class="btn-toolbar">
                                                <input name="add" type="submit" class="btn btn-primary" value="Add Admin"/>
                                            </div>
                                        </form>
                                        -->
                                    </div>

                                    <div class="col-md-10">
                                        <button class="btn btn-default add-dsp" data-gpid="{{$gp->gatewayId}}"><span
                                                class="glyphicon glyphicon-plus"></span> Add a Data Storage Preference
                                        </button>
                                    </div>

                                    <div class="col-md-10">
                                        @if( isset( $gp->profile->dataStoragePreferences) )
                                        <div>
                                            <h3>Data Storage Preferences :</h3>
                                        </div>

                                        <div class="accordion-inner">
                                            <div class="panel-group" id="accordion-{{$indexGP}}">
                                                @foreach( (array)$gp->profile->dataStoragePreferences as $indexDSP
                                                => $dsp )
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle collapsed gateway-name"
                                                               data-toggle="collapse" data-parent="#accordion"
                                                               href="#collapse-dsp-{{$indexGP}}-{{$indexDSP}}">
                                                                {{ $dsp->dataMovememtResourceId }}
                                                            </a>
                                                            @if(Session::has("admin"))
                                                            <div class="pull-right col-md-2 gateway-options fade">
                                                                <span class="glyphicon glyphicon-remove remove-resource"
                                                                      style="cursor:pointer;" data-toggle="modal"
                                                                      data-target="#remove-resource-block"
                                                                      data-dsp-id="{{$ds->computeResourceId}}"
                                                                      data-gp-id="{{ $gp->gatewayId }}"></span>
                                                            </div>
                                                            @endif
                                                        </h4>
                                                    </div>
                                                    <div id="collapse-dsp-{{$indexGP}}-{{$indexDSP}}"
                                                         class="panel-collapse collapse">
                                                        <div class="panel-body">
                                                            <div class="app-data-storage-preferences-block">
                                                                <form action="{{URL::to('/')}}/gp/update-dsp"
                                                                      method="POST">
                                                                    <input type="hidden" name="gatewayId" id="gatewayId"
                                                                           value="{{$gp->gatewayId}}">
                                                                    <input type="hidden" name="dataStorageId"
                                                                           id="gatewayId"
                                                                           value="{{$crp->dataMovememtResourceId}}">

                                                                    <div class="form-horizontal">
                                                                        @include('partials/gateway-preferences',
                                                                        array('computeResource' => $crp->crDetails,
                                                                        'crData' => $crData, 'preferences'=>$crp,
                                                                        'show'=>true))
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
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
                    @foreach( (array)$computeResources as $index => $cr)
                    <option value="{{ $cr->computeResourceId}}">{{ $cr->hostName }}</option>
                    @endforeach
                </select>
                <span class="input-group-addon remove-cr" style="cursor:pointer;">x</span>
            </div>
            <div class="pref-space form-horizontal"></div>
        </form>
    </div>
</div>


<!-- Remove a Compute Resource from a Gateway -->
<div class="modal fade" id="remove-resource-block" tabindex="-1" role="dialog" aria-labelledby="add-modal"
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

                    Do you really want to remove the Compute Resource, <span class="remove-cr-name"> </span>from the
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

<!-- contains all compute resource choices that might get selected on adding a new one to a gateway -->
@foreach( (array)$computeResources as $index => $cr)
@include('partials/gateway-preferences', array('computeResource' => $cr, 'crData' => $crData))
@endforeach


@stop


@section('scripts')
@parent
{{ HTML::script('js/gateway.js') }}
<script>
    //make first tab of accordion open by default.
    //temporary fix
    $("#accordion2").children(".panel").children(".collapse").addClass("in");

    $(".credential-store-token-change > form").submit( function(e){
        $(this).prepend( "<img id='loading-gif' src='{{URL::to('/')}}/assets/ajax-loader.gif'/>");
        e.preventDefault();
        cstField = $(".gateway-credential-store-token");
        if( $.trim( cstField.val()) != ""){
            $.ajax({
                url: "{{URL::to('/')}}/gp/credential-store-token-change",
                method: "POST",
                data: { cst : cstField.val(), gateway_id: cstField.data("gpid") }
            }).done( function( data){
                $("#loading-gif").remove();
                alert( data);
            });
        }
        else
            alert("Please enter a valid Credential Store Token.");
    });
</script>
@stop