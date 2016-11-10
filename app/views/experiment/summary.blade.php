@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')
@include('partials/experiment-info')
{{ HTML::image("assets/Profile_avatar_placeholder_large.png", 'placeholder image', array('class' => 'baseimage')) }}
@stop


@section('scripts')
@parent
<script>
    @if( isset( $autoRefresh) )
        var autoRefresh = true;
    @else
        var autoRefresh = false;
    @endif

    var currentJobStatuses = {};
    @foreach( $expVal["jobDetails"] as $index => $jobDetail)
    currentJobStatuses["{{$jobDetail->jobId}}"] = "{{ $jobDetail->jobStatuses[0]->jobStateName}}";
    @endforeach

    var isStatusChanged = function(experimentTimeOfStateChange, jobStatuses) {

        if ($.trim($("#lastModifiedTime").val()) != experimentTimeOfStateChange) {
            // console.log("Detected lastModifiedTime changed");
            return true;
        }
        for (var jobId in jobStatuses) {
            if (jobId in currentJobStatuses) {
                if (currentJobStatuses[jobId] !== jobStatuses[jobId]){
                    // console.log("Detected job status changed", jobId, currentJobStatuses[jobId], jobStatuses[jobId]);
                    return true;
                }
            } else {
                // console.log("Found a new job", jobId, jobStatuses[jobId]);
                return true; // if job not in currentJobStatuses
            }
        }
        return false;
    }

    // Check for a status change at most once every 3 seconds
    var checkForStatusChange = function () {
        if (($.trim($(".exp-status").html()) != "COMPLETED" && $.trim($(".exp-status").html()) != "FAILED"
                && $.trim($(".exp-status").html()) != "CANCELLED") && autoRefresh) {
            $.ajax({
                type: "GET",
                url: "{{URL::to('/') }}/experiment/summary",
                data: {expId: "{{ Input::get('expId') }}", isAutoRefresh : autoRefresh },
                success: function (data) {
                    data = $.parseJSON( data);

                    // Convert jobDetails to a map of jobStatuses
                    var jobStatuses = {};
                    var jobDetails = data["jobDetails"];
                    for (var jobIndex in jobDetails){
                        if (jobDetails.hasOwnProperty(jobIndex)) {
                            var jobDetail = jobDetails[jobIndex];
                            // Assuming only one job status per job
                            jobStatuses[jobDetail["jobId"]] = jobDetail["jobStatuses"]["0"]["jobStateName"];
                        }
                    }

                    if (isStatusChanged(data.expVal["experimentTimeOfStateChange"], jobStatuses)) {
                        $(".refresh-exp").click();
                    } else {
                        setTimeout(checkForStatusChange, 3000);
                    }
                },
                // In case of some spurious error, keep trying to check for status change
                error: function() {
                    setTimeout(checkForStatusChange, 3000);
                }
            });
        }
    };
    setTimeout(checkForStatusChange, 3000);

    $('.btn-toggle').click(function() {
        if(autoRefresh){
            autoRefresh = false;
        }else{
            autoRefresh = true;
        }

        $(this).find('.btn').toggleClass('active');
        if ($(this).find('.btn-primary').size()>0) {
            $(this).find('.btn').toggleClass('btn-primary');
        }
        $(this).find('.btn').toggleClass('btn-default');
    });

    $('#refresh-experiment').click(function() {
        console.log(autoRefresh);
        window.location.replace("{{URL::to('/') }}/experiment/summary?" + "expId=" + "{{ Input::get('expId') }}"+"&"+ "isAutoRefresh=" + autoRefresh);
    });
</script>
@stop
