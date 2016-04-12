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
    {{ ProjectUtilities::create_project_select($expInputs['project'], !$expInputs['clonedExp']) }}
</div>
<div class="form-group">
    <label for="application">Application</label>
    {{ ExperimentUtilities::create_application_select($expInputs['application'], false) }}
</div>

<div class="panel panel-default">
    <div class="panel-heading">Application configuration</div>
    <div class="panel-body">
        <label>Application input</label>

        <div class="well">
            @if( $expInputs["clonedExp"])
            <div class="form-group">
                <p><strong>Current inputs</strong></p>
                {{ ExperimentUtilities::list_input_files($expInputs['experiment']->experimentInputs) }}
            </div>
            {{ ExperimentUtilities::create_inputs($expInputs['application'], false) }}
            @else
            {{ ExperimentUtilities::create_inputs($expInputs['application'], true) }}
            @endif
        </div>
        <!-- Modal to view file inputs-->
        <div class="modal fade" id="input-file-view" tabindex="-1" role="dialog" aria-labelledby="add-modal"
             aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div id="byte_range"></div>
                </div>
                <div class="modal-body">
                    <div id="byte_content"></div>
                </div>
            </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label" for="enable-auto-scheduling">
                Enable Auto Scheduling&nbsp;&nbsp;
                    <input name="enable-auto-scheduling" id="enable-auto-scheduling" type="checkbox"
                    @if(isset($expInputs["autoSchedule"]) && $expInputs["autoSchedule"])
                        {{" checked"}}
                    @endif
                    >
            </label>
        </div>

        <div class="form-group required">
            <label class="control-label" for="compute-resource">Compute Resource</label>
            @if( count( $expInputs['computeResources']) > 0)
            <select class="form-control" name="compute-resource" id="compute-resource" required="required">
                @if(count($expInputs['computeResources']) > 1)
                    <option value="">Select a resource</option>
                @endif
                @foreach ($expInputs['computeResources'] as $id => $name)
                <option value="{{$id}}"
                {{ ($expInputs['resourceHostId'] == $id)? ' selected' : '' }}>{{$name}}</option>
                @endforeach
            </select>
            @else
            <h4>Application deployed Computational resources are currently unavailable</h4>
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
            <input type="text" class="form-control" name="userDN" placeholder="user" value="{{$expInputs['userDN']}}"/>
        </div>
    </div>
</div>
@endif
