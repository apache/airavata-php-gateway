@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')
<div class="col-md-offset-3 col-md-6">

    <h1>Create a new experiment</h1>

    <form action="{{URL::to('/')}}/experiment/create" method="POST" role="form" enctype="multipart/form-data">

        <input type="hidden" name="experiment-name" value="{{$expInputs['experimentName']}}">
        <input type="hidden" name="experiment-description" value="{{$expInputs['experimentDescription']}}">
        <input type="hidden" name="project" value="{{$expInputs['project']}}">
        <input type="hidden" name="application" value="{{$expInputs['application']}}">

        @include('partials/experiment-inputs', array("expInputs" => $expInputs, "queueDefaults" =>
        $expInputs['queueDefaults']) )

        <div class="form-group btn-toolbar">
            <div class="btn-group">
                <button name="save" type="submit" class="btn btn-primary" value="Save">Save</button>
                <button name="launch" type="submit" class="btn btn-success" id="expLaunch" value="Save and launch">Save
                    and launch
                </button>
            </div>

            <a href="{{URL::to('/')}}/experiment/create" class="btn btn-default" role="button">Start over</a>
        </div>

    </form>


</div>


@stop

@section('scripts')
@parent
<script>
    $('.file-input').bind('change', function () {

        var inputFileSize = Math.round(this.files[0].size / (1024 * 1024));
        if (inputFileSize > $("#allowedFileSize").val()) {
            alert("The input file size is greater than the allowed file size (" + $("#allowedFileSize").val() + " MB) in a form. Please upload another file.");
            $(this).val("");
        }

    });

    $("#enableEmail").change(function () {
        if (this.checked) {
            $("#emailAddresses").attr("required", "required");
            $(this).parent().children(".emailSection").removeClass("hide");
        }
        else {
            $(this).parent().children(".emailSection").addClass("hide");
            $("#emailAddresses").removeAttr("required");
        }

    });

    $(".addEmail").click(function () {
        var emailInput = $(this).parent().find("#emailAddresses").clone();
        emailInput.removeAttr("id").removeAttr("required").val("").appendTo(".emailAddresses");
    });

    $("#compute-resource").change(function () {
        var crId = $(this).val();
        $(".loading-img ").removeClass("hide");
        $.ajax({
            url: '../experiment/getQueueView',
            type: 'get',
            data: {crId: crId},
            success: function (data) {
                $(".queue-view").html(data);
                $(".loading-img ").addClass("hide");
            }
        });
    });

    window.onbeforeunload = function() {
        return "Are you sure you want to navigate to other page ? (you will loose all unsaved data)";
    }
</script>
@stop