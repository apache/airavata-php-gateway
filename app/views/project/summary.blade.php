@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')

<div class="container" style="max-width: 80%;">
    <?php
        $project = ProjectUtilities::get_project($_GET['projId']);
    ?>
    <h1>Project Summary
        @if( !isset($dashboard))
        <small><a href="{{ URL::to('/') }}/project/summary?projId={{ $project->projectID }}"
                  title="Refresh"><span class="glyphicon glyphicon-refresh refresh-exp"></span></a></small>
        @endif
    </h1>
    <?php

    echo '<div>';

    echo '<div>';
    echo '<h3>' . $project->name . ' <a href="edit?projId=' .
        $project->projectID .
        '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a></h3>';
    echo "<p>$project->description</p>";
    echo '</div>';

    $experiments = ProjectUtilities::get_experiments_in_project($project->projectID);

    echo '<div class="table-responsive">';
    echo '<table class="table">';

    echo '<tr>';

    echo '<th>Name</th>';
    echo '<th>Application</th>';
    echo '<th>Compute Resource</th>';
    echo '<th>Last Modified Time</th>';
    echo '<th>Experiment Status</th>';
    echo '<th>Job Status</th>';

    echo '</tr>';

    foreach ($experiments as $experiment) {
        $expValues = ExperimentUtilities::get_experiment_values($experiment, $project, true);
        $expValues["jobState"] = ExperimentUtilities::get_job_status($experiment);
        $applicationInterface = AppUtilities::get_application_interface($experiment->executionId);

        switch ($expValues["experimentStatusString"]) {
            case 'CANCELING':
            case 'CANCELED':
            case 'UNKNOWN':
                $expStatustextClass = 'text-warning';
                break;
            case 'FAILED':
                $expStatustextClass = 'text-danger';
                break;
            case 'COMPLETED':
                $expStatustextClass = 'text-success';
                break;
            default:
                $expStatustextClass = 'text-info';
                break;
        }
        switch ($expValues["jobState"]) {
            case 'CANCELING':
            case 'CANCELED':
            case 'UNKNOWN':
                $jobStatustextClass = 'text-warning';
                break;
            case 'FAILED':
                $jobStatustextClass = 'text-danger';
                break;
            case 'COMPLETED':
                $jobStatustextClass = 'text-success';
                break;
            case 'COMPLETE':
                $jobStatustextClass = 'text-success';
                break;
            default:
                $jobStatustextClass = 'text-info';
                break;
        }

        echo '<tr>';

        echo '<td>';
        switch ($expValues["experimentStatusString"]) {
            case 'SCHEDULED':
            case 'LAUNCHED':
            case 'EXECUTING':
            case 'CANCELING':
            case 'COMPLETED':
                echo '<a href="' . URL::to('/') . '/experiment/summary?expId=' .
                        $experiment->experimentId . '">' . $experiment->experimentName . '</a>';
                break;
            default:
                echo '<a href="' . URL::to('/') . '/experiment/summary?expId=' .
                        $experiment->experimentId . '">' . $experiment->experimentName . '</a>' .
                        ' <a href="' . URL::to('/') . '/experiment/edit?expId=' .
                        $experiment->experimentId .
                        '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';
                break;
        }
        echo '</td>';

        echo "<td>$applicationInterface->applicationName</td>";

        echo '<td>';
        try {
            $cr = CRUtilities::get_compute_resource($experiment->userConfigurationData
                ->computationalResourceScheduling->resourceHostId);
            if (!empty($cr)) {
                echo $cr->hostName;
            }
        } catch (Exception $ex) {
            //Error while retrieving the CR
        }
        echo '</td>';
        echo '<td class="time" unix-time="' . $expValues["experimentTimeOfStateChange"] . '"></td>';


        echo '<td><div class="' . $expStatustextClass . '">' . $expValues["experimentStatusString"] . '</div></td>';

        if (isset($expValues["jobState"])) echo '
            <td><div class="' . $jobStatustextClass . '">' . $expValues["jobState"] . '</div></td>';
        else
            echo '<td></td>';
        echo '</tr>';
    }

    echo '</table>';
    echo '</div>';
    echo '</div>';

    ?>


</div>
@stop
@section('scripts')
@parent
{{ HTML::script('js/time-conversion.js')}}
@stop