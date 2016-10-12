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
    <h1>Compute Resource Accounts</h1>
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-default add-user-cr">
                <span class="glyphicon glyphicon-plus"></span> Add a Compute Resource Account
            </button>
        </div>
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