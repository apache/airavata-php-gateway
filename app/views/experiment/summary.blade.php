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
    setInterval( function(){
        if( $.trim( $(".exp-status").html() ) != "COMPLETED")
        {
            $.ajax({
                type:"GET",
                url: "{{URL::to('/') }}/experiment/summary",
                data: {expId: "{{ Input::get('expId') }}" },
                success: function( exp){
                    if( $.trim( $("#expObj").val() ) != $.trim( exp) )
                       $(".refresh-exp").click();

                }
            });
        }
    }, 3000);
    </script>
@stop