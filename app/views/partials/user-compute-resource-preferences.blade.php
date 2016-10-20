<!-- partial template variables:
    computeResource - (required, ComputeResourceDescription) the compute resource object
    preferences - (optional, UserComputeResourcePreference) the saved preference data
    show - (optional, boolean)
    allowDelete - (optional, boolean)
-->
<!-- String replace is done as Jquery creates problems when using period(.) in id or class. -->
<div id="cr-{{ str_replace( '.', "_", $computeResource->computeResourceId) }}" class="@if(isset( $show) ) @if( !$show) hide @endif @else hide @endif">
<div class="form-group">
    <label class="control-label col-md-3">Login Username</label>

    <div class="col-md-9">
        <input type="text" name="loginUserName" class="form-control"
               value="@if( isset( $preferences) ){{$preferences->loginUserName}}@endif"/>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">Preferred Batch Queue</label>

    <div class="col-md-9">
        <select name="preferredBatchQueue" class="form-control">
            <option value="">Select a Queue from list</option>
            @foreach( (array)$computeResource->batchQueues as $index => $queue)
            <option value="{{ $queue->queueName}}"
            @if( isset( $preferences) ) @if( $preferences->preferredBatchQueue == $queue->queueName) selected @endif
            @endif>{{ $queue->queueName}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">Scratch Location</label>

    <div class="col-md-9">
        <input type="text" name="scratchLocation" class="form-control"
               value="@if( isset( $preferences) ){{$preferences->scratchLocation}}@endif"/>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3">Allocation Project Number</label>

    <div class="col-md-9">
        <input type="text" name="allocationProjectNumber" class="form-control"
               value="@if( isset( $preferences) ){{$preferences->allocationProjectNumber}}@endif"/>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3">Resource Specific Credential Store Token</label>

    <div class="col-md-9">
        <select class="form-control gateway-credential-store-token" name="resourceSpecificCredentialStoreToken" >
            <option value="">Select a Credential Token from Store</option>
            @foreach( $tokens as $token => $description )
                <option value="{{$token}}" @if( isset( $preferences) ) @if( $token == $preferences->resourceSpecificCredentialStoreToken) selected @endif @endif>{{$description}}</option>
            @endforeach
            <option value="">DO-NO-SET</option>
        </select>
        <!--
        <input type="text" name="resourceSpecificCredentialStoreToken" class="form-control"
               value="@if( isset( $preferences) ){{$preferences->resourceSpecificCredentialStoreToken}}@endif"/>
        -->
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3">Quality of Service</label>

    <div class="col-md-9">
        <input type="text" name="qualityOfService" class="qualityOfService form-control"
               value="@if( isset( $preferences) ){{$preferences->qualityOfService}}@endif" data-toggle="popover" data-placement="bottom" data-content="Format: <queue name1>=<qos1>,<queue name2>=<qos2>"/>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-md-3">Reservation Name</label>

    <div class="col-md-9">
        <input type="text" name="reservation" class="form-control"
               value="@if( isset( $preferences) ){{$preferences->reservation}}@endif"/>
    </div>
</div>
<?php
//to add or remove time according to local hours.
$reservationStartTime = "";
if( isset( $preferences) && trim($preferences->reservationStartTime) != '') {
    $reservationStartTimeLocal = CommonUtilities::convertUTCToLocal($preferences->reservationStartTime/1000);
    $reservationStartTime = date('m/d/Y h:i:s A', $reservationStartTimeLocal);
}

$reservationEndTime = "";
if( isset( $preferences) && $preferences->reservationEndTime != '') {
    $reservationEndTimeLocal = CommonUtilities::convertUTCToLocal($preferences->reservationEndTime/1000);
    $reservationEndTime = date('m/d/Y h:i:s A', $reservationEndTimeLocal);
}

?>

<div class="row">
    <div class="form-group col-md-6">
        <label class="control-label col-md-3">Reservation Start Time</label>

        <div class="input-group date datetimepicker1">
            <input type="text" name="reservationStartTime" class="form-control"
                   value="{{$reservationStartTime}}"/>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>

    <div class="form-group col-md-6">
        <label class="control-label col-md-3">Reservation End Time</label>

        <div class="input-group date datetimepicker2">
            <input type="text" name="reservationEndTime" class="form-control"
                   value="{{$reservationEndTime}}"/>
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12 text-center">
        <input type="submit" class="btn btn-primary" value="Save"/>
        <button type="button" class="btn btn-danger remove-user-compute-resource @if(isset( $allowDelete ) ) @if( !$allowDelete) hide @endif @else hide @endif"
            data-toggle="modal"
            data-target="#remove-user-compute-resource-block"
            data-cr-name="{{$computeResource->hostName}}"
            data-cr-id="{{$computeResource->computeResourceId}}">
            Remove
        </button>
    </div>
</div>

</div>
