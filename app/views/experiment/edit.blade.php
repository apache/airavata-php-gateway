@extends('layout.basic')

@section('page-header')
    @parent
@stop

@section('content')

<?php
//$echoResources = array('localhost', 'trestles.sdsc.edu', 'lonestar.tacc.utexas.edu');
//$wrfResources = array('trestles.sdsc.edu');

//$appResources = array('Echo' => $echoResources, 'WRF' => $wrfResources);
?>


<div class="container">

<h1>Edit Experiment</h1>

<form action="{{URL::to('/')}}/experiment/edit" method="POST" role="form" enctype="multipart/form-data">
    <input type="hidden" name="expId" value="<?php echo Input::get('expId');?>"/>

    <div class="form-group">
        <label for="experiment-name">Experiment Name</label>
        <input type="text"
               class="form-control"
               name="experiment-name"
               id="experiment-name"
               value="<?php echo $experiment->name; ?>"
            <?php if(!$expVal['editable']) echo 'disabled' ?>>
    </div>
    <div class="form-group">
        <label for="experiment-description">Experiment Description</label>
        <textarea class="form-control"
                  name="experiment-description"
                  id="experiment-description"
                <?php if(!$expVal['editable']) echo 'disabled' ?>><?php echo $experiment->description ?>
        </textarea>
    </div>
    <div class="form-group">
        <label for="project">Project</label>
        <?php Utilities::create_project_select($experiment->projectID, $expVal['editable']); ?>
    </div>
    <div class="form-group">
        <label for="application">Application</label>
        <?php Utilities::create_application_select($experiment->applicationId, false); ?>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Application configuration</div>
        <div class="panel-body">
            <label>Application input</label>
            <div class="well">
                <div class="form-group">
                    <p><strong>Current inputs</strong></p>
                    <?php Utilities::list_input_files($experiment); ?>
                </div>
                <?php Utilities::create_inputs($experiment->applicationId, false); ?>
            </div>

        <div class="form-group">
            <label for="compute-resource">Compute Resource</label>
            <?php Utilities::create_compute_resources_select($experiment->applicationId, $expVal['scheduling']->resourceHostId); ?>
        </div>

    <div class="form-group">
        <label for="node-count">Node Count</label>
        <input type="number"
               class="form-control"
               name="node-count"
               id="node-count"
               min="1"
               value="<?php echo $expVal['scheduling']->nodeCount ?>"
            <?php if(!$expVal['editable']) echo 'disabled' ?>>
    </div>
    <div class="form-group">
        <label for="cpu-count">Total Core Count</label>
        <input type="number"
               class="form-control"
               name="cpu-count"
               id="cpu-count"
               min="1"
               value="<?php echo $expVal['scheduling']->totalCPUCount ?>"
            <?php if(!$expVal['editable']) echo 'disabled' ?>>
    </div>
    <!--
    <div class="form-group">
        <label for="threads">Number of Threads</label>
        <input type="number"
               class="form-control"
               name="threads"
               id="threads"
               min="0"
               value="<?php //echo $expVal['scheduling']->numberOfThreads; ?>"
            <?php //if(!$expVal['editable']) echo 'disabled'; ?>>
    </div>
    -->
    <div class="form-group">
        <label for="wall-time">Wall Time Limit</label>
        <div class="input-group">
            <input type="number"
                   class="form-control"
                   name="wall-time"
                   id="wall-time"
                   min="0"
                   value="<?php echo $expVal['scheduling']->wallTimeLimit ?>"
                <?php if(!$expVal['editable']) echo 'disabled' ?>>
            <span class="input-group-addon">minutes</span>
        </div>
    </div>
    <!--
    <div class="form-group">
        <label for="memory">Total Physical Memory</label>
        <div class="input-group">
            <input type="number"
                   class="form-control"
                   name="memory"
                   id="memory"
                   min="0"
                   value="<?php //echo $expVal['scheduling']->totalPhysicalMemory; ?>"
                <?php //if(!$expVal['editable']) echo 'disabled'; ?>>
            <span class="input-group-addon">kB</span>
        </div>
    </div>
    -->
    </div>
    </div>

    <div class="btn-toolbar">
        <div class="btn-group">
            <input name="save" type="submit" class="btn btn-primary" value="Save" <?php if(!$expVal['editable']) echo 'disabled'  ?>>
            <input name="launch" type="submit" class="btn btn-success" value="Save and launch" <?php if(!$expVal['editable']) echo 'disabled'  ?>>
        </div>
    </div>


</form>


</div>

@stop