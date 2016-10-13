@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')
<!-- TODO: datepicker for reservation date doesn't work yet -->
@foreach( (array)$computeResources as $index => $cr)
@include('partials/user-compute-resource-preferences', array('computeResource' => $cr))
@endforeach
<div class="container">
    @if( Session::has("message"))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span
                    aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            {{ Session::get("message") }}
        </div>
    {{ Session::forget("message") }}
    @endif
    <h1>Compute Resource Accounts</h1>
    <button class="btn btn-default add-user-cr">
        <span class="glyphicon glyphicon-plus"></span> Add a Compute Resource Account
    </button>
    <div class="panel-group" id="accordion">
        @foreach( (array)$userResourceProfile->userComputeResourcePreferences as $indexUserCRP => $user_crp )
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed"
                       data-toggle="collapse" data-parent="#accordion"
                       href="#collapse-user-crp-{{$indexUserCRP}}">
                        HOSTNAME TODO: {{$user_crp->computeResourceId}}
                    </a>
                    <div class="pull-right col-md-2 fade">
                        <span class="glyphicon glyphicon-remove remove-compute-resource"
                              style="cursor:pointer;" data-toggle="modal"
                              data-target="#remove-compute-resource-block"
                              data-cr-name="TODO"
                              data-cr-id="{{$user_crp->computeResourceId}}"
                              data-gp-id="{{ $userResourceProfile->gatewayID }}"></span>
                    </div>
                </h4>
            </div>
            <div id="collapse-user-crp-{{$indexUserCRP}}"
                 class="panel-collapse collapse">
                <div class="panel-body">
                    <form class="set-cr-preference" action="{{URL::to('/')}}/account/update-user-crp"
                          method="POST">
                        <input type="hidden" name="gatewayId" id="gatewayId"
                               value="{{$userResourceProfile->gatewayID}}">
                        <input type="hidden" name="computeResourceId"
                               id="gatewayId"
                               value="{{$user_crp->computeResourceId}}">

                        <div class="form-horizontal">
                            @include('partials/user-compute-resource-preferences',
                            array('computeResource' => $user_crp->crDetails,
                            'preferences'=>$user_crp, 'show'=>true))
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<div class="add-user-compute-resource-block hide">
    <div class="well">
        <!-- TODO: need to implement /add-user-crp -->
        <form action="{{URL::to('/')}}/account/add-user-crp" method="POST">
            <input type="hidden" name="gatewayId" id="gatewayId" value="{{$userResourceProfile->gatewayID}}">

            <div class="input-group">
                <select name="computeResourceId" class="cr-select form-control">
                    <option value="">Select a Compute Resource and configure your account</option>
                    @foreach( (array)$unselectedCRs as $index => $cr)
                    <option value="{{ $cr->computeResourceId}}">{{ $cr->hostName }}</option>
                    @endforeach
                </select>
                <!-- TODO: implement the remove behavior -->
                <span class="input-group-addon remove-cr" style="cursor:pointer;">x</span>
            </div>
            <div class="user-cr-pref-space form-horizontal"></div>
        </form>
    </div>
</div>
<pre>
    {{var_dump($userResourceProfile)}}
</pre>
@stop

@section('scripts')
@parent
<script>

$('.add-user-cr').on('click', function(){

    $(this).after( $(".add-user-compute-resource-block").html() );
});
$("body").on("change", ".cr-select", function(){
    crId = $(this).val();
    //This is done as Jquery creates problems when using period(.) in id or class.
    crId = crId.replace(/\./g,"_");
    $(".user-cr-pref-space").html($("#cr-" + crId).html());
});
</script>
@stop