@extends('layout.basic')

@section('page-header')
    @parent
@stop

@section('content')
<div class="col-md-offset-3 col-md-6">
    
    <h1>Create a new experiment</h1>
    <form action="{{URL::to('/')}}/experiment/create" method="POST" role="form" enctype="multipart/form-data">

        <input type="hidden" name="experiment-name" value="{{$experimentName}}">
        <input type="hidden" name="experiment-description" value="{{$experimentDescription}}">
        <input type="hidden" name="project" value="{{$project}}">
        <input type="hidden" name="application" value="{{$application}}">
        
        <div class="form-group required">
            <label for="experiment-name" class="control-label">Experiment Name</label>
            <input type="text" class="form-control" name="experiment-name" id="experiment-name" placeholder="Enter experiment name" autofocus required="required" {{ $disabled }} value="{{ $experimentName }}">
        </div>
        <div class="form-group">
            <label for="experiment-description">Experiment Description</label>
            <textarea class="form-control" name="experiment-description" id="experiment-description" placeholder="Optional: Enter a short description of the experiment" {{ $disabled }}>{{ $experimentDescription }}</textarea>
        </div>
        <div class="form-group required">
            <label for="project" class="control-label">Project</label>
            {{ Utilities::create_project_select($project, !$disabled) }}
        </div>
            <div class="form-group">
            <label for="application">Application</label>
            {{ Utilities::create_application_select($application, !$disabled) }}
        </div>

       <div class="panel panel-default">
            <div class="panel-heading">Application configuration</div>
            <div class="panel-body">
                <label>Application input</label>
                <div class="well">
                    <input type="hidden" id="allowedFileSize" value="{{$allowedFileSize}}"/>
                    {{ Utilities::create_inputs($application, true) }}
                </div>
                <div class="form-group">
                    <label for="compute-resource">Compute Resource</label>';
                    {{ Utilities::create_compute_resources_select($application, null) }}
                </div>
                <div class="form-group">
                    <label for="node-count">Node Count</label>
                    <input type="number" class="form-control" name="node-count" id="node-count" value="1" min="1">
                </div>
                <div class="form-group">
                    <label for="cpu-count">Total Core Count</label>
                    <input type="number" class="form-control" name="cpu-count" id="cpu-count" value="4" min="1">
                </div>
                <div class="form-group">
                    <label for="wall-time">Wall Time Limit</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="wall-time" id="wall-time" value="30" min="0">
                        <span class="input-group-addon">minutes</span>
                    </div>
                </div>
            </div>
        </div>
        <h3>Notifications</h3>
        <div class="form-group well">
        	<label for=""></label>
        	<input type="checkbox" id="enableEmail" name="enableEmailNotification" value="1">Do you want to receive email notifications for status changes in the experiment?<br/>
    		<div class="emailSection hide">
    			<h4>Enter Email Address here.</h4>
    			<div class="emailAddresses">
    				<input type="email" id="emailAddresses" class="form-control" name="emailAddresses[]" placeholder="Email"/>
    			</div>
    			<button type="button" class="addEmail btn btn-default">Add another Email</button>
    		</div>
    	</div>


        <div class="btn-toolbar">
            <div class="btn-group">
                <button name="save" type="submit" class="btn btn-primary" value="Save">Save</button>
                <button name="launch" type="submit" class="btn btn-success" id="expLaunch" value="Save and launch">Save and launch</button>
            </div>
            
            <a href="{{URL::to('/')}}/experiment/create" class="btn btn-default" role="button">Start over</a>
        </div>
        
    </form>
        

</div>


@stop

@section('scripts')
    @parent
    <script>
    $('.file-input').bind('change', function() {

        var inputFileSize = Math.round( this.files[0].size/(1024*1024) );
        if( inputFileSize > $("#allowedFileSize").val())
        {
            alert( "The input file size is greater than the allowed file size (" + $("#allowedFileSize").val() + " MB) in a form. Please upload another file.");
            $(this).val("");
        }

    });

    $("#enableEmail").change( function(){
    	if( this.checked)
        {
            $("#emailAddresses").attr("required", "required");
    		$(this).parent().children(".emailSection").removeClass("hide");
        }
    	else
        {
    		$(this).parent().children(".emailSection").addClass("hide");
            $("#emailAddresses").removeAttr("required");
        }

    });

    $(".addEmail").click( function(){
    	var emailInput = $(this).parent().find("#emailAddresses").clone();
    	emailInput.removeAttr("id").removeAttr("required").val("").appendTo(".emailAddresses");
    });
    </script>
@stop