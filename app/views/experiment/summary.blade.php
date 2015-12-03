@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')
@include('partials/experiment-info')
@stop


@section('scripts')
@parent
<script>
    var $continue = true;
    setInterval(function () {
        //if ($.trim($(".exp-status").html()) != "COMPLETED" && $continue) {
            $.ajax({
                type: "GET",
                url: "{{URL::to('/') }}/experiment/summary",
                data: {expId: "{{ Input::get('expId') }}" },
                success: function (data) {
                    data = $.parseJSON( data);
                    //if ($.trim($("#expObj").val()) != $.trim(exp)) {
                    if ($.trim($("#lastModifiedTime").val()) != $.trim( data.expVal["experimentTimeOfStateChange"])) {
                        $continue = false;
                        $(".refresh-exp").click();
                    }
                }
            });
       // }
    }, 3000);
</script>
@stop