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
            <td><?php echo $expVal["applicationInterface"]->applicationName; ?></td>
        </tr>
        <tr>
            <td><strong>Compute resource</strong></td>
            <td><?php echo $expVal["computeResource"]->hostName; ?></td>
        </tr>
        <tr>
            <td><strong>Experiment Status</strong></td>
            <td class="exp-status"><?php echo $expVal["experimentStatusString"]; ?></td>
        </tr>
        <?php
        if ($expVal["jobState"]) echo '
        <tr>
            <td><strong>Job Status</strong></td>
            <td>' . $expVal["jobState"] . '</td>
        </tr>
        ';
        ?>
        <tr>
            <td><strong>Creation time</strong></td>
            <td><?php echo $expVal["experimentCreationTime"]; ?></td>
        </tr>
        <tr>
            <td><strong>Update time</strong></td>
            <td><?php echo $expVal["experimentTimeOfStateChange"]; ?></td>
        </tr>
        <tr>
            <td><strong>Inputs</strong></td>
            <td><?php Utilities::list_input_files($experiment); ?></td>
        </tr>
        <tr>
            <td><strong>Outputs</strong></td>
            <td><?php Utilities::list_output_files($experiment, $expVal["experimentStatusString"]); ?></td>
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
                   title="Launch the experiment" <?php if(!$expVal["editable"] ) echo 'disabled'  ?>>
            <a href="{{URL::to('/') }}/experiment/cancel?expId={{ $experiment->experimentID }}"
               class="btn btn-default"
               role="button"
               title="Edit the experiment's settings" <?php if(!$expVal["cancelable"] ) echo 'disabled'  ?>>
                <input name="cancel" type="submit" class="btn btn-warning" value="Cancel" <?php if(!$expVal["cancelable"]) echo 'disabled';  ?> >
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
               title="Edit the experiment's settings" <?php if(!$expVal["editable"] ) echo 'disabled'  ?>>
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