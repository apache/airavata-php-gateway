{{ HTML::style('css/sharing.css') }}
<style>
#experiment-form {
    margin-bottom: 20px;
}
</style>
<div class="container" style="max-width: 750px;">
    <!--
    @if(isset( $invalidExperimentId ) )
    <div class="alert alert-danger">
        The Experiment ID does not exist. Please go to correct experiment.
    </div>
    @else
    -->
    @if( Session::has("permissionDenied" ) )
    <div class="alert alert-danger">
        {{Session::forget("permissionDenied") }}
    </div>
    @else
    <h1>
        Experiment Summary
        @if( !isset($dashboard))
        <small><a id="refresh-experiment"
                  title="Refresh"><span class="glyphicon glyphicon-refresh refresh-exp"></span></a></small>
            <small><small>Enable Auto Refresh </small></small>
            <div class="btn-group btn-toggle">
                @if($autoRefresh == true)
                    <button class="btn btn-xs btn-primary active">ON</button>
                    <button class="btn btn-xs btn-default">OFF</button>
                @else
                    <button class="btn btn-xs btn-default">ON</button>
                    <button class="btn btn-xs btn-primary active">OFF</button>
                @endif
            </div>
        @endif
    </h1>


    <table class="table table-bordered">
        <tr>
            <td><strong>Experiment ID</strong></td>
            <td><?php echo $experiment->experimentId; ?></td>
        </tr>
        <tr>
            <td><strong>Name</strong></td>
            <td><?php echo $experiment->experimentName; ?></td>
        </tr>
        <tr>
            <td><strong>Description</strong></td>
            <td><?php echo $experiment->description; ?></td>
        </tr>
        <tr>
            <td><strong>Project</strong></td>
            @if (isset($project))
            <td>{{{ $project->name }}}</td>
            @else
            <td><em>You don't have access to this project.</em></td>
            @endif
        </tr>
        <tr>
            <td><strong>Owner</strong></td>
            <td><?php echo $experiment->userName; ?></td>
        </tr>
        <tr>
            <td><strong>Application</strong></td>
            <td><?php if (!empty($expVal["applicationInterface"])) {
                    echo $expVal["applicationInterface"]->applicationName;
                } ?></td>
        </tr>
        <tr>
            <td><strong>Compute Resource</strong></td>
            <td><?php if (!empty($expVal["computeResource"])) {
                    echo $expVal["computeResource"]->hostName;
                } ?></td>
        </tr>
        @if( $experiment->userConfigurationData->useUserCRPref )
        <tr>
            <td><strong>Uses My Compute Resource Account</strong></td>
            <td>
                Yes
            </td>
        </tr>
        @endif
        <tr>
            <td><strong>Experiment Status</strong></td>
            <td class="exp-status"><?php echo $expVal["experimentStatusString"]; ?></td>
        </tr>

        @foreach( $expVal["jobDetails"] as $index => $jobDetail)
            <tr>
                <th>Job</th>
                <td>
                    <table class="table table-bordered">
                        <tr>
                            <td>Name</td>
                            <td>ID</td>
                            <td>Status</td>
                            <td>Creation Time</td>
                        </tr>
                        <tr>
                            <td>{{$jobDetail->jobName}}</td>
                            <td>{{ $jobDetail->jobId}}</td>
                            <td>{{$jobDetail->jobStatuses[0]->jobStateName }}</td>
                            <td class="time" unix-time="{{$jobDetail->creationTime}}"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        @endforeach
        <!--
        @if( isset( $expVal["jobState"]) )
            <tr>
                <td><strong>Job Status</strong></td>
                <td>{{ $expVal["jobState"] }}</td>
            </tr>
        @endif
        -->
        @if( isset( $experiment->enableEmailNotification))
            <tr>
                <td><strong>Notifications To:</strong></td>
                <td>
                    @if(isset($experiment->emailAddresses))
                        @foreach( $experiment->emailAddresses as $email)
                            {{ $email}}<br/>
                        @endforeach
                    @endif
                </td>
            </tr>
        @endif

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
            <td><strong>Creation Time</strong></td>
            <td class="time" unix-time="{{ $expVal["experimentCreationTime"] }}"></td>
        </tr>
        <tr>
            <td><strong>Last Modified Time</strong></td>
            <td class="time" unix-time="{{ $expVal["experimentTimeOfStateChange"] }}"></td>
        </tr>
        <tr>
            <td><strong>Enable Auto Schedule</strong></td>
            <td><?php echo $experiment->userConfigurationData->airavataAutoSchedule==1?"true":"false"; ?></td>
        </tr>
        <tr>
            <td><strong>Wall Time</strong></td>
            <td>{{ $experiment->userConfigurationData->computationalResourceScheduling->wallTimeLimit }}</td>
        </tr>
        <tr>
            <td><strong>CPU Count</strong></td>
            <td>{{ $experiment->userConfigurationData->computationalResourceScheduling->totalCPUCount }}</td>
        </tr>
        <tr>
            <td><strong>Node Count</strong></td>
            <td>{{ $experiment->userConfigurationData->computationalResourceScheduling->nodeCount }}</td>
        </tr>
        <tr>
            <td><strong>Queue</strong></td>
            <td>{{ $experiment->userConfigurationData->computationalResourceScheduling->queueName }}</td>
        </tr>
        <tr>
            <td><strong>Inputs</strong></td>
            <td>{{ ExperimentUtilities::list_input_files($experiment->experimentInputs) }}</td>
        </tr>
        <tr>
            <td><strong>Outputs</strong></td>
            <td>{{ ExperimentUtilities::list_output_files($experiment->experimentOutputs, $experiment->experimentStatus[0]->state, false) }}</td>
        </tr>
        <tr>
            <td><strong>Storage Directory</strong></td>
            <?php
                if(0 === strpos($experiment->userConfigurationData->experimentDataDir, Config::get("pga_config.airavata")['experiment-data-absolute-path'])){
                    $expDataDir = str_replace(Config::get("pga_config.airavata")['experiment-data-absolute-path'], "", $experiment->userConfigurationData->experimentDataDir);
                }else{
                    $expDataDir = $experiment->userConfigurationData->experimentDataDir;
                }
            ?>
            <td><a href="{{URL::to('/')}}/files/browse?path={{$expDataDir}}" target="_blank">Open</a></td>
        </tr>
        <!-- an experiment is editable only when it has not failed. otherwise, show errors. -->
{{--        @if( $expVal["editable"] == false)--}}
        <tr>
            <td><strong>Errors</strong></td>
            <td>
            @if( $experiment->errors != null)
                @foreach( (array)$experiment->errors as $error)
                {{ $error->actualErrorMessage }}
                @endforeach
            @endif
            </td>
        </tr>
        {{--@endif--}}
        @foreach( $expVal["jobDetails"] as $index => $jobDetail)
            @if($experiment->experimentStatus[0]->state == \Airavata\Model\Status\ExperimentState::FAILED
                    || $jobDetail->jobStatuses[0]->jobStateName == "FAILED")
            <tr>
                <th>Job Submission Response</th>
                <td>{{$jobDetail->stdOut . $jobDetail->stdErr}}</td>
            </tr>
            @endif
        @endforeach
    </table>

    @if(strcmp($expVal["applicationInterface"]->applicationName, "OpenMM_Stampede") === 0)
    @include('partials/streaming-data')
    @endif

    @if( !isset( $dashboard))

    <form id="experiment-form" action="{{URL::to('/') }}/experiment/summary" method="post" role="form">

        <div class="form-group">
        @if(Config::get('pga_config.airavata')["data-sharing-enabled"])
            @if($is_owner)
            <!-- Only allow editing sharing here if the experiment isn't editable -->
            @include('partials/sharing-display-body', array("form" => !$expVal["editable"]))
            @else
            @include('partials/sharing-display-body', array("form" => false))
            @endif
        @endif
        </div>
        <div class="btn-toolbar">
            <button name="launch"
                    type="submit"
                    class="btn btn-success"
                    title="Launch the experiment" @if ( !$expVal["editable"]) style="display: none" @endif>
                    Launch
            </button>
            <button name="cancel"
                   type="submit"
                   class="btn btn-default" onclick="return confirm('Are you sure you want to cancel this experiment?')"
                   title="Cancel experiment" @if (!$expVal["cancelable"]) style="display: none" @endif>
                <span class="glyphicon glyphicon-remove"></span>
                Cancel
            </button>
            <input type="hidden" name="expId" value="{{ Input::get('expId') }}"/>
            <a href="{{URL::to('/') }}/experiment/edit?expId={{ $experiment->experimentId }}&savedExp=true"
               class="btn btn-default"
               role="button"
               title="Edit experiment" <?php if (!$expVal["editable"]) echo 'style="display: none"' ?>>
                <span class="glyphicon glyphicon-pencil"></span>
                Edit
            </a>
            @if(Config::get('pga_config.airavata')["data-sharing-enabled"] && $is_owner && !$expVal["editable"])
            <button name="update-sharing"
                   type="submit"
                   class="btn btn-primary"
                   title="Update sharing settings">
                <span class="glyphicon glyphicon-share"></span>
                Update Sharing
            </button>
            @endif
        </div>
    </form>
    <div id="clone-panel" class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Clone Experiment</h3>
        </div>
        <div class="panel-body">
            @if( Session::has("cloning-error"))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                {{{ Session::get("cloning-error") }}}
            </div>
            {{ Session::forget("cloning-error") }}
            @endif
            <form class="form-inline" action="{{ URL::to('/') }}/experiment/clone" method="post">
                <input type="hidden" name="expId" value="{{ Input::get('expId') }}"/>
                <div class="form-group">
                    <label for="projectId">Project</label>
                    <select class="form-control" name="projectId" required>
                        @foreach($writeableProjects as $project)
                        <option value="{{{ $project->projectID }}}"
                            @if( $project->projectID == $experiment->projectId)
                            selected
                            @endif
                        >{{{ $project->name }}}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                   class="btn btn-primary"
                   role="button"
                   title="Create a clone of the experiment. Cloning is the only way to change an experiment's settings after it has been launched.">
                    <span class="glyphicon glyphicon-pencil"></span>
                    Clone
                </a>
            </form>
        </div>
    </div>
    @endif
    <input type="hidden" id="lastModifiedTime" value="{{ $expVal['experimentTimeOfStateChange'] }}"/>

    <!-- check of correct experiment Id ends here -->
    @endif

    @endif
</div>

@if( isset($dashboard))
<h2 class="text-center">Detailed Experiment Information</h2>
<div class="tree">
    <ul>
        <li>
            <span><i class="icon-calendar"></i>{{ $detailedExperiment->experimentName }}</span>
            <ul>
                @foreach( $detailedExperiment->processes as $index => $process)
                <li>
                    <span class="badge badge-success"><i class="icon-minus-sign"></i>Process - {{ $process->processId }}</span>
                    <ul>
                        <li>
                            <span class="alert"><i class="icon-time"></i>
                                <p>Inputs<br/>
                                {{ ExperimentUtilities::list_process_input_files( $process->processInputs) }}</p>
                            </span>
                        </li>
                        <li>
                            <span class="alert"><i class="icon-time"></i>
                                Tasks
                            </span>

                                @foreach( $process->tasks as $task)
                                    <dl class="well dl-horizontal">
                                        <dt>Task Id : </dt> <dd>{{ $task->taskId }}</dd>
                                        <dt>Task Type : </dt> <dd>{{ $expVal["taskTypes"][$task->taskType] }}</dd>
                                        <dt>Task Status : </dt> <dd>{{ $expVal["taskStates"][$task->taskStatuses[0]->state] }}</dd>
                                    @if( is_object( $task->taskErrors))
                                        <dt>Task Error Id : </dt><dd>{{ $task->taskErrors[0]->errorId }}</dd>
                                        <dt>Task Error Msg : </dt><dd>{{ $task->taskErrors[0]->userFriendlyMessage }} <a tabindex="0" class="popover-taskinfo btn btn-sm btn-default" role="button" data-toggle="popover" data-html="true" title="Detailed Task Information" data-content="{{ str_replace( ',', '<br/><br/>', $task->taskError->actualErrorMessage ) }}">More Info</a></dd>
                                    @endif
                                    @if( count( $task->jobs) > 0 )
                                        <dt>Jobs : </dt><dd>{{ count( $task->jobs)}}</dd>
                                    @endif
                                    @foreach( $task->jobs as $jobIndex => $job)
                                        <dl class="well dl-horizontal">
                                            <dt>Job Id. :</dt> <dd>{{ $job->jobId }}</dd>
                                            <dt>Job Name : </dt><dd>{{ $job->jobName }}</dd>
                                            <dt>Job Description :</dt><dd>{{ $job->jobDescription }}</dd>
                                        </dl>
                                     @endforeach
                                    </dl>
                                    <hr/>
                                @endforeach
                        </li>
                        <li>
                            <span class="alert"><i class="icon-time"></i>
                                <p>Outputs<hr/>
                                {{ ExperimentUtilities::list_process_output_files( $process->processOutputs, $process->processStatuses[0]->state) }}</p>
                            </span>
                        </li>
                    </ul>
                </li>
                @endforeach
                <li>
                    <span class="alert"><i class="icon-time"></i>
                        Errors : <br/>
                        @if( $detailedExperiment->errors != null)
                            @foreach( $detailedExperiment->errors as $error)
                                Error Id : {{ $error->errorId}}<br/>
                                Error Message : {{ $error->actualErrorMessage}}
                            @endforeach
                        @else
                            No errors
                        @endif
                    </span>
                </li>
            </ul>
        </li>


    </ul>
</div>
@endif

@if(Config::get('pga_config.airavata')["data-sharing-enabled"] and isset($is_owner))
    @if($is_owner)
    @include('partials/sharing-form-modal')
    @endif
@endif
@section('scripts')
@parent
{{ HTML::script('js/time-conversion.js')}}
@if(Config::get('pga_config.airavata')["data-sharing-enabled"] and isset($users) and isset($owner))
    <script>
        var users = {{ $users }};
        var owner = {{ $owner }};
        $('#project-share').data({url: "{{URL::to('/')}}/experiment/unshared-users", resourceId: "{{Input::get('expId')}}"})
    </script>
    {{ HTML::script('js/sharing/sharing_utils.js') }}
    {{ HTML::script('js/sharing/share.js') }}
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.bundle.min.js"></script>
{{ HTML::script('js/simstream.js') }}
<script>
    checkAuth("http://localhost:8888/auth", "ws://localhost:8888/experiment/openmm");
</script>

@stop
