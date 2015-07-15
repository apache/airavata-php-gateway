@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')

<div class="container" style="max-width: 750px;">
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
    echo '<th>Creation Time</th>';
    echo '<th>Experiment Status</th>';
    echo '<th>Job Status</th>';

    echo '</tr>';

    foreach ($experiments as $experiment) {
        $expValues = ExperimentUtilities::get_experiment_values($experiment, ProjectUtilities::get_project($experiment->projectID), true);
        $expValues["jobState"] = ExperimentUtilities::get_job_status($experiment);
        $applicationInterface = AppUtilities::get_application_interface($experiment->applicationId);

        echo '<tr>';

        echo '<td>';


        switch ($expValues["experimentStatusString"]) {
            case 'CANCELING':
            case 'CANCELED':
            case 'UNKNOWN':
                $textClass = 'text-warning';
                break;
            case 'FAILED':
                $textClass = 'text-danger';
                break;
            case 'COMPLETED':
                $textClass = 'text-success';
                break;
            default:
                $textClass = 'text-info';
                break;
        }

        switch ($expValues["experimentStatusString"]) {
            case 'SCHEDULED':
            case 'LAUNCHED':
            case 'EXECUTING':
            case 'CANCELING':
            case 'COMPLETED':
                echo '<a class="' . $textClass . '" href="' . URL::to('/') . '/experiment/summary?expId=' .
                    $experiment->experimentID . '">' . $experiment->name . '</a>';
                break;
            default:
                echo '<a class="' . $textClass . '" href="' . URL::to('/') . '/experiment/summary?expId=' .
                    $experiment->experimentID . '">' . $experiment->name . '</a>' .
                    ' <a href="' . URL::to('/') . '/experiment/edit?expId=' .
                    $experiment->experimentID .
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

        echo '<td>'. $expValues["experimentStatusString"] . '</td>';

        if ($expValues["jobState"]) echo '
            <td>' . $expValues["jobState"] . '</td>';
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