<!-- String replace is done as Jquery creates problems when using period(.) in id or class. -->
<div id="cr-{{ str_replace( '.', "_", $computeResource->computeResourceId) }}" class="@if(isset( $show) ) @if( !$show) hide @endif @else hide @endif">
<h3 class="text-center">Set Preferences</h3>
<div class="form-group">
    <label class="control-label col-md-3">Override by Airavata</label>

    <div class="col-md-9">
        <select class="form-control" name="overridebyAiravata">
            <option value="1"
            @if( isset( $preferences) ) @if( 1 == $preferences->overridebyAiravata) selected @endif @endif>True</option>
            <option value="0"
            @if( isset( $preferences) ) @if( 0 == $preferences->overridebyAiravata) selected @endif
            @endif>False</option>
        </select>
    </div>
</div><br/>
<div class="form-group">
    <label class="control-label col-md-3">Login Username</label>

    <div class="col-md-9">
        <input type="text" name="loginUserName" class="form-control"
               value="@if( isset( $preferences) ){{$preferences->loginUserName}}@endif"/>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">Preferred Job Submission Protocol</label>

    <div class="col-md-9">
        <select name="preferredJobSubmissionProtocol" class="form-control">
            @foreach( (array)$computeResource->jobSubmissionInterfaces as $index => $jsi)
            <option value="{{$jsi->jobSubmissionProtocol}}"
            @if( isset( $preferences) ) @if( $preferences->preferredJobSubmissionProtocol ==
            $jsi->jobSubmissionProtocol) selected @endif @endif>{{
            $crData["jobSubmissionProtocols"][$jsi->jobSubmissionProtocol] }}</option>
            @endforeach
        </select>

    </div>
</div>
<br/>
<div class="form-group">
    <label class="control-label col-md-3">Preferred Data Movement Protocol</label>

    <div class="col-md-9">
        <select name="preferredDataMovementProtocol" class="form-control">
            @foreach( (array)$computeResource->dataMovementInterfaces as $index => $dmi)
            <option value="{{ $dmi->dataMovementProtocol}}"
            @if( isset( $preferences) ) @if( $preferences->preferredDataMovementProtocol == $dmi->dataMovementProtocol)
            selected @endif @endif>{{ $crData["dataMovementProtocols"][$dmi->dataMovementProtocol] }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">Preferred Batch Queue</label>

    <div class="col-md-9">
        <select name="preferredBatchQueue" class="form-control">
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
    <input type="submit" class="form-control btn btn-primary" value="Set preferences"/>
</div>
</div>