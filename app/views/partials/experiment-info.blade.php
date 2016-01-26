<div class="container" style="max-width: 750px;">

    @if(isset( $invalidExperimentId ) )
    <div class="alert alert-danger">
        The Experiment ID does not exist. Please go to correct experiment.
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
                <th>Job Name : {{$jobDetail->jobName}}</th>
                <td>Job ID : {{ $jobDetail->jobId}}</td>
                <td> Status : {{$jobDetail->jobStatus->jobStateName }}</td>
                <td> Creation Time : <span class="time" unix-time="{{$jobDetail->creationTime}}"></span></td>
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
            <td><?php ExperimentUtilities::list_input_files($experiment->experimentInputs); ?></td>
        </tr>
        <tr>
            <td><strong>Outputs</strong></td>
            <td><?php ExperimentUtilities::list_output_files($experiment->experimentOutputs, $experiment->experimentStatus->state, false); ?></td>
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
                <td>{{$jobDetail->stdOut}}</td>
            </tr>
            @endif
        @endforeach
    </table>

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
               title="Edit the experiment's settings" @if (!$expVal["cancelable"]) style="display: none" @endif>
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
            <a href="{{URL::to('/') }}/experiment/edit?expId={{ $experiment->experimentId }}"
               class="btn btn-default"
               role="button"
               title="Edit the experiment's settings" <?php if (!$expVal["editable"]) echo 'style="display: none"' ?>>
                <span class="glyphicon glyphicon-pencil"></span>
                Edit
            </a>
        </div>
    </form>
    @endif
    <input type="hidden" id="lastModifiedTime" value="{{ $expVal['experimentTimeOfStateChange'] }}"/>

    <!-- check of correct experiment Id ends here -->
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
                                {{ ExperimentUtilities::list_input_files( $process->processInputs) }}</p>
                            </span>
                        </li>
                        <li>
                            <span class="alert"><i class="icon-time"></i>
                                Tasks
                            </span>

                                @foreach( $process->tasks as $task)
                                    <br/>Task Id : {{ $task->taskId }}
                                    <br/>Task Type : {{ $expVal["taskTypes"][$task->taskType] }}
                                    <br/>Task Status : {{ $expVal["taskStates"][$task->taskStatus->state] }}
                                    <br/>Jobs : {{ count( $task->jobs)}}
                                    <br/>@foreach( $task->jobs as $jobIndex => $job)
                                            Job No. : {{ $jobIndex}}
                                         @endforeach

                                    <hr/>
                                @endforeach
                        </li>
                        <li>
                            <span class="alert"><i class="icon-time"></i>
                                <p>Outputs<hr/>
                                {{ ExperimentUtilities::list_output_files( $process->processOutputs, $process->processStatus->state, true) }}</p>
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
                <!--
                <li>
                    <span class="badge badge-success"><i class="icon-minus-sign"></i>Input Staging</span>
                    <ul>
                        <li>
                            <span class="alert alert-success"><i
                                    class="icon-time"></i>2015-04-17 15:21:21</span> &ndash; <a href="">PGA to

                                Airavata File Transfer Successful</a>
                        </li>
                        <li>
                            <span class="alert alert-success" abhi><i
                                    class="icon-time"></i>2015-04-17 15:21:21</span> &ndash; <a href="">Airavata to

                                Resource File Transfer Successful</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <span class="badge badge-warning"><i class="icon-minus-sign"></i>Job Description</span>
                    <ul>
                        <li>
                            <a href=""><span>
                                               Long Script of Job Description / PBS Script <br/>
                                               <br/>
                                                <p>
                                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean
                                                    commodo ligula eget dolor. Aenean massa. Cum sociis natoque
                                                    penatibus et magnis dis parturient montes, nascetur ridiculus
                                                    mus. Donec quam felis, ultricies nec, pellentesque eu, pretium
                                                    quis, sem. Nulla consequat massa quis enim. Donec pede justo,
                                                    fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo,
                                                    rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum
                                                    felis eu pede mollis pretium. Integer tincidunt. Cras dapibus.
                                                    Vivamus elementum semper nisi. Aenean vulputate eleifend tellus.
                                                    Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac,
                                                    enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a,
                                                    tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque
                                                    rutrum. Aenean
                                                </p>
                                             </span></a>
                        </li>
                    </ul>
                </li>
                <li>
                    <span class="badge badge-important"><i class="icon-minus-sign"></i>Execution</span>
                    <ul>
                        <li>
                            <a href=""><span class="alert alert-success"><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash;
                                Execution of Job Description - No errors</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <span class="badge badge-important"><i class="icon-minus-sign"></i>Experiment Complete</span>
                    <ul>
                        <li>
                            <a href=""><span class="alert alert-danger"><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash;
                                Output Transfer from Resource to Airavata UnSuccessful</a>
                            <br/>
                            <span> Some text about failure</span>
                        </li>
                        <li>
                            <a href=""><span class="alert alert-danger"><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash;
                                Output Transfer from Airavata to PGA UnSuccessful</a>
                            <br/>
                            <span> Some text about failure</span>
                        </li>
                    </ul>
                </li>
                -->

    </ul>
</div>
@endif

@section('scripts')
@parent
{{ HTML::script('js/time-conversion.js')}}
@stop