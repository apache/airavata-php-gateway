{{ HTML::style('css/sharing.css') }}
<style>
#experiment-form {
    margin-bottom: 20px;
}
</style>
<div class="container" style="max-width: 750px;">
    @if(isset( $invalidExperimentId ) )
    <div class="alert alert-danger">
        The Experiment ID does not exist. Please go to correct experiment.
    </div>
    @else
    @if( Session::has("permissionDenied" ) )
    <div class="alert alert-danger">
        {{Session::forget("permissionDenied") }}
    </div>
    @else
    @if( Session::has("cloning-error"))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        {{{ Session::get("cloning-error") }}}
    </div>
    @endif
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
            <td>{{{ $experiment->experimentId }}}</td>
        </tr>
        <tr>
            <td><strong>Name</strong></td>
            <td>{{{ $experiment->experimentName }}}</td>
        </tr>
        <tr>
            <td><strong>Description</strong></td>
            <td>{{{ $experiment->description }}}</td>
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
            <td>{{{ $experiment->userName }}}</td>
        </tr>
        <tr>
            <td><strong>Application</strong></td>
            <td><?php if (!empty($expVal["applicationInterface"])) {
                    echo $expVal["applicationInterface"]->applicationName;
                } ?></td>
        </tr>
        <tr class="compute-resource-summary-page">
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
            <td class="exp-status">  
{{{ $expVal["experimentStatusString"] }}} <br/>
@if( $expVal["experimentStatusString"]!="COMPLETED" && $expVal["experimentStatusString"]!="COMPLETE") 
 If the job is failed, please refer <A href="https://dreg.dnasequence.org/pages/doc#failure">here</A> to find the reasons.<br/>
@endif
</td>
        </tr>

        @foreach( $expVal["jobDetails"] as $index => $jobDetail)
            <tr>
                <th>Job</th>
                <td>
@if( $jobDetail->jobStatuses[0]->jobStateName == "FAILED" )
If the job is failed, please refer <A href="https://dreg.dnasequence.org/pages/doc#failure">here</A> to find the reasons. <br/>
@endif
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
{{-- Commented by dREG 
        <tr>
            <td><strong>Last Modified Time</strong></td>
            <td class="time" unix-time="{{ $expVal["experimentTimeOfStateChange"] }}"></td>
        </tr>
        <tr class="wall-time-summary-page">
            <td><strong>Wall Time</strong></td>
            <td>{{ $experiment->userConfigurationData->computationalResourceScheduling->wallTimeLimit }}</td>
        </tr>
        <tr class="cpu-count-summary-page">
            <td><strong>CPU Count</strong></td>
            <td>{{ $experiment->userConfigurationData->computationalResourceScheduling->totalCPUCount }}</td>
        </tr>
        <tr class="node-count-summary-page">
            <td><strong>Node Count</strong></td>
            <td>{{ $experiment->userConfigurationData->computationalResourceScheduling->nodeCount }}</td>
        </tr>
        <tr class="queue-summary-page">
            <td><strong>Queue</strong></td>
            <td>{{ $experiment->userConfigurationData->computationalResourceScheduling->queueName }}</td>
        </tr>
--}}
        <tr>
            <td><strong>Inputs</strong></td>
            <td>{{ ExperimentUtilities::list_input_files($experiment->experimentInputs) }}</td>
        </tr>
        <tr>
            <td><strong>Outputs</strong></td>
{{-- Commented by dREG
            <td>{{ ExperimentUtilities::list_output_files($experiment->experimentOutputs, $experiment->experimentStatus[0]->state, false) }}</td>
        </tr>
--}}

{{-- Added by dREG --}}
<?php
    if(0 === strpos($experiment->userConfigurationData->experimentDataDir, Config::get("pga_config.airavata")['experiment-data-absolute-path'])){
      $expDataDir = str_replace(Config::get("pga_config.airavata")['experiment-data-absolute-path'], "", $experiment->userConfigurationData->experimentDataDir);
    }else{
      $expDataDir = $experiment->userConfigurationData->experimentDataDir;
    }

    $dataRoot = Config::get("pga_config.airavata")["experiment-data-absolute-path"];


    $param_prefix = "out";
    if( count( $experiment->experimentInputs) > 0 ) 
       foreach( $experiment->experimentInputs as $input)
       {
          if ($input->applicationArgument == "prefix") {
              $param_prefix = $input->value;
          }
       } 
?>
            <td>

@if(  $expVal["applicationInterface"]->applicationName !== "dTOX prediction"  ) 
                <select id="download">
                    <option value=''>Select results</option>
@if(file_exists($dataRoot . '/' . $expDataDir. '/ARCHIVE/'.$param_prefix.'.tar.gz') )
                    <option value=<?php echo $param_prefix.".tar.gz" ?>>Full results</option>  
@endif
@if(file_exists($dataRoot . '/' . $expDataDir. '/ARCHIVE/'.$param_prefix.'.dREG.infp.bed.gz') )
                    <option value=<?php echo $param_prefix.".dREG.infp.bed.gz"?>>dREG informative sites</option>
@endif
@if(file_exists($dataRoot . '/' . $expDataDir. '/ARCHIVE/'.$param_prefix.'.dREG.peak.full.bed.gz') )
                    <option value=<?php echo $param_prefix.".dREG.peak.full.bed.gz"?>>dREG peaks </option> 
@endif
@if(file_exists($dataRoot . '/' . $expDataDir. '/ARCHIVE/'.$param_prefix.'.dREG.peak.score.bed.gz') )
                    <option value=<?php echo $param_prefix.".dREG.peak.score.bed.gz"?>>dREG peak(only with scores)</option>
@endif
                </select> &nbsp;&nbsp;
@else
                <select id="download">
                    <option value=''>Select results</option>
@if(file_exists($dataRoot . '/' . $expDataDir. '/ARCHIVE/'.$param_prefix.'.tar.gz') )
                    <option value=<?php echo $param_prefix.".tar.gz" ?>>Full results</option>
@endif
@if(file_exists($dataRoot . '/' . $expDataDir. '/ARCHIVE/'.$param_prefix.'.dTOX.bound.bed.gz') )
                    <option value=<?php echo $param_prefix.".dTOX.bound.bed.gz"?>>dTOX bound regions </option>
@endif
                </select> &nbsp;&nbsp;


@endif


<button id="retLinks" style="color: #fff; background-color: #3e5a43; border-color: #46b8da; border: 1px solid transparent;" >Download&nbsp;<span class="glyphicon glyphicon-save"  style="width:20px"></span></button>
            </td>
        </tr>
        <tr>
            <td><strong>Genome Browser</strong></td>
            <td>

@if(  $expVal["applicationInterface"]->applicationName !== "dTOX prediction"  )
                <select id="genomebuilder">
                    <option value="">Select genome </option>
                    <option value="hg19">hg19</option>
                    <option value="hg38">hg38</option>  
                    <option value="mm10">mm10</option>
                </select>
@else
                <select id="genomebuilder">
                    <option value="">Select genome </option>
                    <option value="hg19">hg19</option>
                    <option value="mm10">mm10</option>
                </select>
@endif
 
                &nbsp;or input&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="text" id="customeGB" style="width:40px"/> &nbsp;&nbsp; 
<!--  	        <a href="#1" target="_blank" id="gbLinks">Switch to genome browser&nbsp;<span class="glyphicon glyphicon-new-window"  style="width:20px"></span></a> -->
<button id="gbLinks" style="color: #fff; background-color: #3e5a43; border-color: #46b8da; border: 1px solid transparent;">Switch to genome browser&nbsp;<span class="glyphicon glyphicon-new-window" style="width:20px"></span></button>
            </td>
        </tr>
{{-- dREG --}}
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

    @if( !isset( $dashboard))

    <form id="experiment-form" action="{{URL::to('/') }}/experiment/summary" method="post" role="form">

{{-- Commented by dREG 
        <div class="form-group">
        @if(Config::get('pga_config.airavata')["data-sharing-enabled"] && isset($updateSharingViaAjax))
            @include('partials/sharing-display-body', array("form" => !$updateSharingViaAjax))
        @endif
        </div>
--}}
        <div class="btn-toolbar">
            <button name="launch"
                    type="submit"
                    class="btn btn-success"
                    title="Launch the experiment" @if ( !$expVal["editable"]) style="display: none" @endif>
                <span class="glyphicon glyphicon-play"></span>
                    Launch
            </button>
            <button name="cancel"
                   type="submit"
                   class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this experiment?')"
                   title="Cancel experiment" @if (!$expVal["cancelable"]) style="display: none" @endif>
                <span class="glyphicon glyphicon-stop"></span>
                Cancel
            </button>
            <input type="hidden" name="expId" value="{{{ Input::get('expId') }}}"/>
            <a href="{{URL::to('/') }}/experiment/edit?expId={{ urlencode($experiment->experimentId) }}"
               class="btn btn-primary"
               role="button"
               title="Edit experiment" <?php if (!$expVal["editable"]) echo 'style="display: none"' ?>>
                <span class="glyphicon glyphicon-pencil"></span>
                Edit
            </a>
            @if( count($writeableProjects) > 0 )
            <button type="button"
               id="clone-button"
               name="clone"
               class="btn btn-info"
               role="button"
               title="Create a clone of the experiment. Cloning is the only way to change an experiment's settings after it has been launched.">
                <span class="glyphicon glyphicon-edit"></span>
                Clone
            </button>
            @endif
            @if(Config::get('pga_config.airavata')["data-sharing-enabled"] && isset($canEditSharing) && $canEditSharing)
            <button id="update-sharing" name="update-sharing"
                   type="button"
                   class="btn btn-warning"
                   title="Update sharing settings">
                <span class="glyphicon glyphicon-share"></span>
                Update Sharing
            </button>
            @endif
        </div>
    </form>

    @endif
    <input type="hidden" id="lastModifiedTime" value="{{ $expVal['experimentTimeOfStateChange'] }}"/>

    <div class="modal fade" id="clone-experiment-modal" tabindex="-1" role="dialog" aria-labelledby="clone-experiment-modal-title"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="text-center" id="clone-experiment-modal-title">Clone experiment</h3>
                </div>
                <div class="modal-body">
                    <form class="form-inline" action="{{ URL::to('/') }}/experiment/clone" method="post">
                        <input type="hidden" name="expId" value="{{{ Input::get('expId') }}}"/>
                        <div class="form-group">
                            <label for="projectId">Project</label>
                            <select class="form-control" name="projectId" required>
                                @foreach($writeableProjects as $project)
                                    <option value="{{{ $project->projectID }}}"
                                        @if( $project->projectID == $experiment->projectId)
                                            selected
                                        @endif
                                        >{{{ $project->optionLabel }}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit"
                            class="btn btn-info"
                            role="button"
                            title="Create a clone of the experiment. Cloning is the only way to change an experiment's settings after it has been launched.">
                            <span class="glyphicon glyphicon-edit"></span>
                            Clone
                        </a>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- check of correct experiment Id ends here -->
    @endif

    @endif
</div>

@if( isset($dashboard))
<h2 class="text-center">Detailed Experiment Information</h2>
<div class="tree">
    <ul>
        <li>
            <span><i class="icon-calendar"></i>{{{ $detailedExperiment->experimentName }}}</span>
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
                                        <dt>Task Status Time : </dt> <dd class="time" unix-time="{{{ $task->taskStatuses[0]->timeOfStateChange}}}"></dd>
                                        <dt>Task Status Reason : </dt> <dd>{{{ $task->taskStatuses[0]->reason }}}</dd>
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
                                            <dt>Job Description :</dt><dd><pre>{{{ $job->jobDescription }}}</pre></dd>
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

@if(Config::get('pga_config.airavata')["data-sharing-enabled"] and isset($canEditSharing) && $canEditSharing)
    @include('partials/sharing-form-modal', array("entityName" => "experiment"))
@endif
@section('scripts')
@parent
{{ HTML::script('js/time-conversion.js')}}
@if(Config::get('pga_config.airavata')["data-sharing-enabled"] and !isset($invalidExperimentId))
    <script>
        var users = {{ $users }};
        var owner = {{ $owner }};
        var projectOwner = {{ $projectOwner }};
        $('#update-sharing').data({url: "{{URL::to('/')}}/experiment/unshared-users", resourceId: {{json_encode(Input::get('expId'))}} });
        @if($updateSharingViaAjax)
        $('#share-box-button').data({ajaxUpdateUrl: "{{URL::to('/')}}/experiment/update-sharing?expId={{urlencode(Input::get('expId'))}}", resourceId: {{json_encode(Input::get('expId'))}} });
        @endif
    </script>
    {{ HTML::script('js/sharing/sharing_utils.js') }}
    {{ HTML::script('js/sharing/share.js') }}
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.bundle.min.js"></script>
{{ HTML::script('js/simstream.js') }}
<script>
    checkAuth("http://localhost:8888/auth", "ws://localhost:8888/experiment/openmm");
</script>

<script>
{{-- Added by dREG --}}
    $('#retLinks').on('click', function(e) {
        var file = $('#download').val();
        if (file == '') return false;
        window.open('/download?path={{$expDataDir}}/ARCHIVE/' + file);
        return false; 
    });

<?php
	$filelist="";
        if( count( $experiment->experimentInputs) > 0 ) 
            foreach( $experiment->experimentInputs as $input)
                if ($input->type == Airavata\Model\Application\Io\DataType::URI) {
		
                    $dataProductModel = Airavata::getDataProduct(Session::get('authz-token'), $input->value);
                    $currentOutputPath = "";
                    foreach ($dataProductModel->replicaLocations as $rp) 
                      if($rp->replicaLocationCategory == Airavata\Model\Data\Replica\ReplicaLocationCategory::GATEWAY_DATA_STORE){
                        $currentOutputPath = $rp->filePath;
                      break;
                    }
                    $path = str_replace($dataRoot.$expDataDir, "", parse_url($currentOutputPath, PHP_URL_PATH));
                    $filelist = $filelist . $path. "\n";
                }
                else
                {
                    $filelist = $filelist . $input->value. "\n";
                }

    $filelist = $expDataDir ."\n". $filelist;
    // in case no prefix label for output file in the interface, put "out" at the end
    $filelist =  $filelist . "out\n";

    $protocol = 'http';
    if ( isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') 
        $protocol = 'https';
?>

    $('#gbLinks').on('click', function(e) {
        var gb = $('#customeGB').val();
        if (gb == '')
            gb = $('#genomebuilder').val(); 
        if (gb == '') 
            return false;

@if(  $expVal["applicationInterface"]->applicationName !== "dTOX prediction"  ) 
        var gbUrl = "http://epigenomegateway.wustl.edu/browser/?datahub={{ $protocol .'://'. $_SERVER['HTTP_HOST']. '/gbrowser/'. RBase64::encode( $filelist ) }}&genome=";
@else
        var gbUrl = "http://epigenomegateway.wustl.edu/browser/?datahub={{ $protocol .'://'. $_SERVER['HTTP_HOST']. '/gbrowser1/'. RBase64::encode( $filelist ) }}&genome=";
@endif
        window.open(gbUrl + gb);
        return false; 
    });
{{-- dREG --}}


    $('#clone-button').on('click', function(e){

        e.stopPropagation();
        e.preventDefault();

        // If experiment can be cloned into more than one project, give the user
        // the chance to pick which one, otherwise just submit the cloning form
        var projectCount = $('#clone-experiment-modal select[name=projectId] option').size();
        if (projectCount > 1) {
            $("#clone-experiment-modal").modal("show");
        } else {
            $("#clone-experiment-modal form").submit();
        }
        return false;
    });
    $('[data-toggle="tooltip"]').tooltip();
</script>


@stop
