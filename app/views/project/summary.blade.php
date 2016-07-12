@extends('layout.basic')

@section('page-header')
@parent
{{ HTML::style('css/sharing.css') }}
@stop

@section('content')

<div class="container" style="max-width: 80%;">
    <?php
        $project = ProjectUtilities::get_project($_GET['projId']);
        $experiments = ProjectUtilities::get_experiments_in_project($project->projectID);
    ?>
    <h1>Project Summary
        @if( !isset($dashboard))
        <small><a href="{{ URL::to('/') }}/project/summary?projId={{ $project->projectID }}"
                  title="Refresh"><span class="glyphicon glyphicon-refresh refresh-exp"></span></a></small>
        @endif
    </h1>
    <div>
        <div>
            <h3>{{ $project->name }}
                <a href="edit?projId={{ $project->projectID }}" title="Edit">
                    <span class="glyphicon glyphicon-pencil"></span>
                </a>
            </h3>
            <p>{{ $project->description }}</p>
        </div>
        <div class="table-responsive">
            <table class="table">
                <tr>

                    <th>Name</th>
                    <th>Owner</th>
                    <th>Application</th>
                    <th>Compute Resource</th>
                    <th>Last Modified Time</th>
                    <th>Experiment Status</th>
                    <th>Job Status</th>

                </tr>
                <?php

                foreach ($experiments as $experiment) {
                    $expValues = ExperimentUtilities::get_experiment_values($experiment, true);
                    $expValues["jobState"] = ExperimentUtilities::get_job_status($experiment);
                    $applicationInterface = AppUtilities::get_application_interface($experiment->executionId);

                    try {
                        $cr = CRUtilities::get_compute_resource($experiment->userConfigurationData->computationalResourceScheduling->resourceHostId);
                        if (!empty($cr)) {
                            $resourceName = $cr->hostName;
                        }
                    } catch (Exception $ex) {
                        $resourceName = 'Error while retrieving the CR';
                    }
                    ?>

                <tr>
                    <td>
                        <a href="{{URL::to('/')}}/experiment/summary?expId={{$experiment->experimentId}}">
                        {{ $experiment->experimentName }}
                        </a>
                        @if( $expValues['editable'])
                            <a href="{{URL::to('/')}}/experiment/edit?expId={{$experiment->experimentId}}" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                        @endif
                    </td>
                    <td>{{ $experiment->userName }}</td>
                    <td>
                        @if( $applicationInterface != null )
                            {{ $applicationInterface->applicationName }}
                        @else
                            <span class='text-danger'>Removed</span>
                        @endif
                    </td>

                    <td>{{ $resourceName }}</td>
                    <td class="time" unix-time="{{$expValues["experimentTimeOfStateChange"]}}"></td>
                    <td>
                        <div class="{{ExperimentUtilities::get_status_color_class( $expValues["experimentStatusString"])}}">
                            {{ $expValues["experimentStatusString"] }}
                        </div>
                    </td>

                    <td>
                    @if (isset($expValues["jobState"]) )
                        <div class="{{ ExperimentUtilities::get_status_color_class( $expValues["jobState"]) }}">
                            {{ $expValues["jobState"] }}
                        </div>
                    @endif
                    </td>
                </tr>
                <?php
                }
                ?>
            </table>
        </div>
    </div>
    <div style="margin-top: 10%;">
        @include('partials/sharing-display-body', array('form' => false))
    </div>
</div>

@stop
@section('scripts')
@parent
{{ HTML::script('js/time-conversion.js')}}
<script>
    var users = {{ $users }};
</script>
{{ HTML::script('js/sharing/sharing_utils.js') }}
{{ HTML::script('js/sharing/share.js') }}
@stop
