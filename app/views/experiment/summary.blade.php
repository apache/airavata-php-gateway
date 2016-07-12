@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')
@include('partials/experiment-info')
<div class="row">
@include('partials/sharing-display-body')
</div>
@stop


@section('scripts')
@parent
<script>
    @if( isset( $autoRefresh) )
        var autoRefresh = true;
    @else
        var autoRefresh = false;
    @endif
    setInterval(function () {
        if (($.trim($(".exp-status").html()) != "COMPLETED" && $.trim($(".exp-status").html()) != "FAILED"
                && $.trim($(".exp-status").html()) != "CANCELLED") && autoRefresh) {
            $.ajax({
                type: "GET",
                url: "{{URL::to('/') }}/experiment/summary",
                data: {expId: "{{ Input::get('expId') }}", isAutoRefresh : autoRefresh },
                success: function (data) {
                    data = $.parseJSON( data);
                    //if ($.trim($("#expObj").val()) != $.trim(exp)) {
                    if ($.trim($("#lastModifiedTime").val()) != $.trim( data.expVal["experimentTimeOfStateChange"])) {
                        $(".refresh-exp").click();
                    }
                }
            });
       }
    }, 3000);

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
