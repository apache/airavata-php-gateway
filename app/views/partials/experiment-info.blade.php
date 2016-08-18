{{ HTML::style('css/sharing.css') }}
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
            <td><strong>Experiment Id</strong></td>
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
            <td><?php echo $project->name; ?></td>
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
            <td><strong>Compute resource</strong></td>
            <td><?php if (!empty($expVal["computeResource"])) {
                    echo $expVal["computeResource"]->hostName;
                } ?></td>
        </tr>
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
                            <td>{{$jobDetail->jobStatus->jobStateName }}</td>
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
            <td><strong>Wall time</strong></td>
            <td>{{ $experiment->userConfigurationData->computationalResourceScheduling->wallTimeLimit }}</td>
        </tr>
        <tr>
            <td><strong>CPU count</strong></td>
            <td>{{ $experiment->userConfigurationData->computationalResourceScheduling->totalCPUCount }}</td>
        </tr>
        <tr>
            <td><strong>Node count</strong></td>
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
            <td>{{ ExperimentUtilities::list_output_files($experiment->experimentOutputs, $experiment->experimentStatus->state, false) }}</td>
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
            @if($experiment->experimentStatus->state == \Airavata\Model\Status\ExperimentState::FAILED
                    || $jobDetail->jobStatus->jobStateName == "FAILED")
            <tr>
                <th>Job Submission Response</th>
                <td>{{$jobDetail->stdOut . $jobDetail->stdErr}}</td>
            </tr>
            @endif
        @endforeach
    </table>

    <div class="form-group">
    @include('partials/sharing-display-body', array("form" => false))
    </div>

    @if(strcmp($expVal["applicationInterface"]->applicationName, "OpenMM_Stampede") === 0)
    @include('partials/streaming-data')
    @endif

    @if( !isset( $dashboard))
    <form action="{{URL::to('/') }}/experiment/summary" method="post" role="form">
        <div class="btn-toolbar">
            <input name="launch"
                   type="submit"
                   class="btn btn-success"
                   value="Launch"
                   title="Launch the experiment" @if ( !$expVal["editable"]) style="display: none" @endif>
            <a id="cancel_exp_link" href="{{URL::to('/') }}/experiment/cancel?expId={{ $experiment->experimentId }}"
               class="btn btn-default" onclick="return confirm('Are you sure you want to cancel this experiment?')"
               role="button"
               title="Cancel experiment" @if (!$expVal["cancelable"]) style="display: none" @endif>
                <input name="cancel" type="submit" class="btn btn-warning"
                       value="Cancel" <?php if (!$expVal["cancelable"]) echo 'disabled'; ?> >
            </a>
<!--            <input name="clone"-->
<!--                   type="submit"-->
<!--                   class="btn btn-primary"-->
<!--                   value="Clone"-->
<!--                   title="Create a clone of the experiment. Cloning is the only way to change an experiment's settings-->
<!--                    after it has been launched.">-->
            <a href="{{URL::to('/') }}/experiment/clone?expId={{ $experiment->experimentId }}"
               class="btn btn-primary"
               role="button"
               title="Create a clone of the experiment. Cloning is the only way to change an experiment's settings
                    after it has been launched." target="_blank">
                <span class="glyphicon glyphicon-pencil"></span>
                Clone
            </a>
            <input type="hidden" name="expId" value="{{ Input::get('expId') }}"/>
            <a href="{{URL::to('/') }}/experiment/edit?expId={{ $experiment->experimentId }}&savedExp=true"
               class="btn btn-default"
               role="button"
               title="Edit experiment" <?php if (!$expVal["editable"]) echo 'style="display: none"' ?>>
                <span class="glyphicon glyphicon-pencil"></span>
                Edit
            </a>
        </div>
    </form>
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
                                        <dt>Task Status : </dt> <dd>{{ $expVal["taskStates"][$task->taskStatus->state] }}</dd>
                                    @if( is_object( $task->taskError))
                                        <dt>Task Error Id : </dt><dd>{{ $task->taskError->errorId }}</dd>
                                        <dt>Task Error Msg : </dt><dd>{{ $task->taskError->userFriendlyMessage }} <a tabindex="0" class="popover-taskinfo btn btn-sm btn-default" role="button" data-toggle="popover" data-html="true" title="Detailed Task Information" data-content="{{ str_replace( ',', '<br/><br/>', $task->taskError->actualErrorMessage ) }}">More Info</a></dd>
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
                                {{ ExperimentUtilities::list_process_output_files( $process->processOutputs, $process->processStatus->state) }}</p>
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

@section('scripts')
@parent
{{ HTML::script('js/time-conversion.js')}}
<script>
    var users = {{ $users }};
</script>
{{ HTML::script('js/sharing/sharing_utils.js') }}
{{ HTML::script('js/sharing/share.js') }}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.bundle.min.js"></script>
{{ HTML::script('js/simstream.js') }}
<script>
    checkAuth("http://localhost:8888/auth", "ws://localhost:8888/experiment/openmm");
</script>

@stop
