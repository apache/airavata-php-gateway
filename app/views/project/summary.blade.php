@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')

<div class="container" style="max-width: 750px;">


    <h1>Project Summary</h1>



    <?php

    $project = ProjectUtilities::get_project($_GET['projId']);


    echo '<div class="panel panel-default">';

    echo '<div class="panel-heading">';
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
    echo '<th>Time</th>';
    echo '<th>Status</th>';

    echo '</tr>';

    foreach ($experiments as $experiment) {
        $expValues = ExperimentUtilities::get_experiment_values($experiment, ProjectUtilities::get_project($experiment->projectID), true);
        $applicationInterface = AppUtilities::get_application_interface($experiment->applicationId);

        echo '<tr>';

        echo '<td>';


        switch ($expValues["experimentStatusString"]) {
            case 'SCHEDULED':
            case 'LAUNCHED':
            case 'EXECUTING':
            case 'CANCELING':
            case 'COMPLETED':
                echo $experiment->name;
                break;
            default:
                echo $experiment->name .
                    ' <a href="edit?expId=' .
                    $experiment->experimentID .
                    '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';
                break;
        }


        echo '</td>';

        echo "<td>$applicationInterface->applicationName</td>";

        echo '<td>' . CRUtilities::get_compute_resource($experiment->userConfigurationData
                ->computationalResourceScheduling->resourceHostId)->hostName . '</td>';
        echo '<td class="time" unix-time="' . $expValues["experimentTimeOfStateChange"] . '"></td>';


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


        echo '<td><a class="' .
            $textClass .
            '" href="' . URL::to('/') . '/experiment/summary?expId=' .
            $experiment->experimentID .
            '">' .
            $expValues["experimentStatusString"] .
            '</a></td>';

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