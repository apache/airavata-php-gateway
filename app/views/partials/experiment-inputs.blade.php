<div class="form-group required">
    <label for="experiment-name" class="control-label">Experiment Name</label>
    <input type="text" class="form-control" name="experiment-name" id="experiment-name" placeholder="Enter experiment name" autofocus required="required" {{ $expInputs['disabled'] }} value="{{ $expInputs['experimentName'] }}">
</div>
<div class="form-group">
    <label for="experiment-description">Experiment Description</label>
    <textarea class="form-control" name="experiment-description" id="experiment-description" placeholder="Optional: Enter a short description of the experiment" {{ $expInputs['disabled'] }}>{{ $expInputs['experimentDescription'] }}</textarea>
</div>
<div class="form-group required">
    <label for="project" class="control-label">Project</label>
    @if( isset( $expInputs['cloning']))
        {{ Utilities::create_project_select($expInputs['experiment']->projectID, $expInputs['expVal']['editable']) }}
    @else
        {{ Utilities::create_project_select($expInputs['project'], !$expInputs['disabled']) }}
    @endif
</div>
    <div class="form-group">
    <label for="application">Application</label>
    @if( isset( $expInputs['cloning']))
        {{ Utilities::create_application_select($expInputs['application'], false)}}
    @else
        {{ Utilities::create_application_select($expInputs['application'], !$expInputs['disabled']) }}
    @endif
</div>

<div class="panel panel-default">
    <div class="panel-heading">Application configuration</div>
    <div class="panel-body">
        <label>Application input</label>
        <div class="well">
            <input type="hidden" id="allowedFileSize" value="{{$expInputs['allowedFileSize']}}"/>
            @if( isset( $expInputs['cloning']))
                <div class="form-group">
                    <p><strong>Current inputs</strong></p>
                    {{ Utilities::list_input_files($expInputs['experiment']) }}
                </div>
                {{ Utilities::create_inputs($expInputs['application'], false) }}
            @else
                {{ Utilities::create_inputs($expInputs['application'], true) }}
            @endif

        </div>
        <div class="form-group">
            <label for="compute-resource">Compute Resource</label>
            @if( isset( $expInputs['cloning']))
                {{ Utilities::create_compute_resources_select($expInputs['experiment']->applicationId, $expInputs['expVal']['scheduling']->resourceHostId) }}
            @else
                {{ Utilities::create_compute_resources_select($expInputs['application'], null) }}
            @endif
        </div>

        <div class="form-group">
            <label for="node-count">Queue Name</label>
            <input type="text" class="form-control" name="queue-name" id="queue-name" 
            value="@if(isset($expInputs['expVal']) ){{ $expInputs['expVal']['scheduling']->queueName }}  @else{{$expInputs['queueName']}} @endif"
            @if(isset($expInputs['expVal']) ) @if(!$expInputs['expVal']['editable']){{ disabled }} @endif @endif>
        </div>
        <div class="form-group">
            <label for="node-count">Node Count</label>
            <input type="number" class="form-control" name="node-count" id="node-count" min="1"
            value="@if(isset($expInputs['expVal']) ){{ $expInputs['expVal']['scheduling']->nodeCount }}@else{{$expInputs['nodeCount']}}@endif"
            @if(isset($expInputs['expVal']) ) @if(!$expInputs['expVal']['editable']){{disabled}} @endif @endif>
        </div>
        <div class="form-group">
            <label for="cpu-count">Total Core Count</label>
            <input type="number" class="form-control" name="cpu-count" id="cpu-count" min="1"
            value="@if(isset($expInputs['expVal']) ){{ $expInputs['expVal']['scheduling']->totalCPUCount }}@else{{$expInputs['cpuCount']}}@endif"
            @if(isset($expInputs['expVal'])) @if(!$expInputs['expVal']['editable']){{disabled}} @endif @endif>
        </div>
        <div class="form-group">
            <label for="wall-time">Wall Time Limit</label>
            <div class="input-group">
                <input type="number" class="form-control" name="wall-time" id="wall-time" min="0"
                value="@if(isset($expInputs['expVal']) ){{ $expInputs['expVal']['scheduling']->wallTimeLimit }}@else{{$expInputs['wallTimeLimit']}}@endif"
                @if(isset($expInputs['expVal'])) @if(!$expInputs['expVal']['editable']){{disabled}} @endif @endif>
                <span class="input-group-addon">minutes</span>
            </div>
        </div>
        <div class="form-group">
            <label for="wall-time">Total Physical Memory</label>
            <div class="input-group">
                <input type="number" class="form-control" name="total-physical-memory" id="wall-time" min="0"
                value="@if(isset($expInputs['expVal']) ){{ $expInputs['expVal']['scheduling']->totalPhysicalMemory }}@endif"
                @if(isset($expInputs['expVal'])) @if(!$expInputs['expVal']['editable']){{disabled}} @endif @endif>
                <span class="input-group-addon">MB</span>
            </div>
        </div>
    </div>
</div>
<h3>Notifications</h3>
<div class="form-group well">
	<label for=""></label>
	<input type="checkbox" id="enableEmail" name="enableEmailNotification" value="1">Do you want to receive email notifications for status changes in the experiment?<br/>
	<div class="emailSection hide">
		<h4>Enter Email Address here.</h4>
		<div class="emailAddresses">
			<input type="email" id="emailAddresses" class="form-control" name="emailAddresses[]" placeholder="Email"/>
		</div>
		<button type="button" class="addEmail btn btn-default">Add another Email</button>
	</div>
</div>

