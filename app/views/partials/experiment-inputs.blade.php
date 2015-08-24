<div class="form-group required">
    <label for="experiment-name" class="control-label">Experiment Name</label>
    <input type="text" class="form-control" name="experiment-name" id="experiment-name"
           placeholder="Enter experiment name" autofocus required="required" maxlength="50" {{ $expInputs['disabled'] }}
    value="{{
    $expInputs['experimentName'] }}">
</div>
<div class="form-group">
    <label for="experiment-description">Experiment Description</label>
    <textarea class="form-control" name="experiment-description" id="experiment-description"
              placeholder="Optional: Enter a short description of the experiment" maxlength="200" {{
    $expInputs['disabled'] }}>{{
    $expInputs['experimentDescription'] }}</textarea>
</div>
<div class="form-group required">
    <label for="project" class="control-label">Project</label>
    @if( isset( $expInputs['cloning']))
    {{ ProjectUtilities::create_project_select($expInputs['experiment']->projectId, $expInputs['expVal']['editable']) }}
    @else
    {{ ProjectUtilities::create_project_select($expInputs['project'], !$expInputs['disabled']) }}
    @endif
</div>
<div class="form-group">
    <label for="application">Application</label>
    @if( isset( $expInputs['cloning']))
    {{ ExperimentUtilities::create_application_select($expInputs['application'], false)}}
    @else
    {{ ExperimentUtilities::create_application_select($expInputs['application'], !$expInputs['disabled']) }}
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
                {{ ExperimentUtilities::list_input_files($expInputs['experiment']) }}
            </div>
            {{ ExperimentUtilities::create_inputs($expInputs['application'], false) }}
            @else
            {{ ExperimentUtilities::create_inputs($expInputs['application'], true) }}
            @endif

        </div>
        <div class="form-group required">
            <label class="control-label" for="compute-resource">Compute Resource</label>
            @if( count( $expInputs['computeResources']) > 0)
            <select class="form-control" name="compute-resource" id="compute-resource" required="required">
                <option value="">Select a resource</option>
                @foreach ($expInputs['computeResources'] as $id => $name)
                <option value="{{$id}}"
                {{ ($expInputs['resourceHostId'] == $id)? ' selected' : '' }}>{{$name}}</option>
                @endforeach
            </select>
            @else
            <h4>Application deployed Computational resources are currently unavailable
                @endif
        </div>
        <div class="queue-block">
            <div class="loading-img text-center hide">
                <img src="../assets/ajax-loader.gif"/>
            </div>
            <input type="hidden" name="selected-queue"
                   value="@if(isset($expInputs['expVal']) ){{ $expInputs['expVal']['scheduling']->queueName }} @endif"/>

            <div class="queue-view">
                @if(isset($expInputs['expVal']) )
                @include( 'partials/experiment-queue-block', array('queues'=>
                $expInputs['expVal']['computeResource']->batchQueues, 'expVal' => $expInputs['expVal']) )
                @endif
            </div>
        </div>
    </div>
    <h3>Notifications</h3>

    <div class="form-group well">
        <label for=""></label>
        <input type="checkbox" id="enableEmail" name="enableEmailNotification" value="1"> Do you want to receive email
        notifications for status changes in the experiment?<br/>

        <div class="emailSection hide">
            <h4>Enter Email Address here.</h4>

            <div class="emailAddresses">
                <input type="email" id="emailAddresses" class="form-control" name="emailAddresses[]"
                       placeholder="Email"/>
            </div>
            <button type="button" class="addEmail btn btn-default">Add another Email</button>
        </div>
    </div>

    @if( $expInputs["advancedOptions"])
    <h3>Advanced Options</h3>

    <div class="form-group well">
        <h4>Enter UserDN</h4>

        <div class="userdninfo">
            <input type="text" class="form-control" name="userDN" placeholder="user"/>
        </div>
    </div>
    @endif
