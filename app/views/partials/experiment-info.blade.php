<div class="container" style="max-width: 750px;">

    @if(isset( $invalidExperimentId ) )
    <div class="alert alert-danger">
        The Experiment ID does not exist. Please go to correct experiment.
    </div>
    @else
    <h1>
        Experiment Summary
        @if( !isset($dashboard))
        <small><a href="{{ URL::to('/') }}/experiment/summary?expId={{ $experiment->experimentID }}"
                  title="Refresh"><span class="glyphicon glyphicon-refresh refresh-exp"></span></a></small>
        @endif
    </h1>


    <table class="table">
        <tr>
            <td><strong>Experiment Id</strong></td>
            <td><?php echo $experiment->experimentID; ?></td>
        </tr>
        <tr>
            <td><strong>Name</strong></td>
            <td><?php echo $experiment->name; ?></td>
        </tr>
        <tr>
            <td><strong>Description</strong></td>
            <td><?php echo $experiment->description; ?></td>
        </tr>
        <tr>
            <td><strong>Project</strong></td>
            <td><?php echo $project->name; ?></td>
        </tr>
        <tr>
            <td><strong>Application</strong></td>
            <td><?php if (!empty($expVal["applicationInterface"])) {
                    echo $expVal["applicationInterface"]->applicationName;
                } ?></td>
        </tr>
        <tr>
            <td><strong>Compute resource</strong></td>
            <td><?php if (!empty($expVal["computeResource"])) {
                    echo $expVal["computeResource"]->hostName;
                } ?></td>
        </tr>
        <tr>
            <td><strong>Experiment Status</strong></td>
            <td class="exp-status"><?php echo $expVal["experimentStatusString"]; ?></td>
        </tr>
        @if( isset($dashboard))
        <tr>
            <td><strong>Job ID</strong></td>

            <?php
            foreach ($jobDetails as $job) echo '
                <td>' . $job->jobID . '</td>
            ';
            ?>
        </tr>
        <tr>
            <td><strong>Job Name</strong></td>

            <?php
            foreach ($jobDetails as $job) echo '
                <td>' . $job->jobName . '</td>
            ';
            ?>
        </tr>
        @endif
        <?php
        if ($expVal["jobState"]) echo '
        <tr>
            <td><strong>Job Status</strong></td>
            <td>' . $expVal["jobState"] . '</td>
        </tr>
        ';
        ?>

        @if( isset($dashboard))
        <tr>
            <td><strong>Working Dir</strong></td>

            <?php
            foreach ($jobDetails as $job) echo '
                <td>' . $job->workingDir . '</td>
            ';
            ?>
        </tr>
        <tr>
            <td><strong>Job Description</strong></td>

            <?php
            foreach ($jobDetails as $job) echo '
                <td>' . nl2br($job->jobDescription) . '</td>
            ';
            ?>
        </tr>
        @endif

        <tr>
            <td><strong>Creation time</strong></td>
            <td class="time" unix-time="<?php echo $expVal["experimentCreationTime"]; ?>"></td>
        </tr>
        <tr>
            <td><strong>Last Modified Time</strong></td>
            <td class="time" unix-time="<?php echo $expVal["experimentTimeOfStateChange"]; ?>"></td>
        </tr>
        <tr>
            <td><strong>Wall time</strong></td>
            <td><?php echo $experiment->userConfigurationData->computationalResourceScheduling->wallTimeLimit; ?></td>
        </tr>
        <tr>
            <td><strong>CPU count</strong></td>
            <td><?php echo $experiment->userConfigurationData->computationalResourceScheduling->totalCPUCount; ?></td>
        </tr>
        <tr>
            <td><strong>Node count</strong></td>
            <td><?php echo $experiment->userConfigurationData->computationalResourceScheduling->nodeCount; ?></td>
        </tr>
        <tr>
            <td><strong>Queue</strong></td>
            <td><?php echo $experiment->userConfigurationData->computationalResourceScheduling->queueName; ?></td>
        </tr>
        <tr>
            <td><strong>Inputs</strong></td>
            <td><?php ExperimentUtilities::list_input_files($experiment); ?></td>
        </tr>
        <tr>
            <td><strong>Outputs</strong></td>
            <td><?php ExperimentUtilities::list_output_files($experiment, $expVal["experimentStatusString"]); ?></td>
        </tr>
        @if( $expVal["experimentStatusString"] == "FAILED")
        <tr>
            <td><strong>Errors</strong></td>
            <td>
                @foreach( (array)$experiment->errors as $error)
                {{ $error->actualErrorMessage }}
                @endforeach
            </td>
        </tr>
        @endif

    </table>

    @if( !isset( $dashboard))
    <form action="{{URL::to('/') }}/experiment/summary" method="post" role="form">
        <div class="btn-toolbar">
            <input name="launch"
                   type="submit"
                   class="btn btn-success"
                   value="Launch"
                   title="Launch the experiment" <?php if (!$expVal["editable"]) echo 'style="display: none"' ?>>
            <a href="{{URL::to('/') }}/experiment/cancel?expId={{ $experiment->experimentID }}"
               class="btn btn-default"
               role="button"
               tit  le="Edit the experiment's settings" <?php if (!$expVal["cancelable"]) echo 'style="display: none"' ?>>
                <input name="cancel" type="submit" class="btn btn-warning"
                       value="Cancel" <?php if (!$expVal["cancelable"]) echo 'disabled'; ?> >
            </a>
            <input name="clone"
                   type="submit"
                   class="btn btn-primary"
                   value="Clone"
                   title="Create a clone of the experiment. Cloning is the only way to change an experiment's settings
                    after it has been launched.">
            <input type="hidden" name="expId" value="{{ Input::get('expId') }}"/>
            <a href="{{URL::to('/') }}/experiment/edit?expId={{ $experiment->experimentID }}"
               class="btn btn-default"
               role="button"
               title="Edit the experiment's settings" <?php if (!$expVal["editable"]) echo 'style="display: none"' ?>>
                <span class="glyphicon glyphicon-pencil"></span>
                Edit
            </a>
        </div>
    </form>
    @endif
    <input type="hidden" id="expObj" value="{{ htmlentities( json_encode( $experiment)) }}"/>

    <!-- check of correct experiment Id ends here -->
    @endif
</div>

@section('scripts')
@parent
{{ HTML::script('js/time-conversion.js')}}
@stop