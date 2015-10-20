<input type="hidden" id="queue-array" value="{{ htmlentities( json_encode( $queues ) ) }}"/>
<div class="form-group required">
    @if( count( $queues) > 0 )
    <label class="control-label" for="node-count">Select a Queue</label>
    <select name="queue-name" class="form-control" id="select-queue" @if(isset($expVal) ) @if(!$expVal['editable']){{
    "disabled" }} @endif @endif required>
    @foreach( $queues as $queue)
    <option value="{{$queue->queueName}}"
    @if(isset($expVal) ) @if( $expVal['scheduling']->queueName == $queue->queueName ) selected @endif @endif
    >
    {{$queue->queueName}}
    </option>
    @endforeach
    </select>
    @else
    <div class="alert alert-warning">
        This resources has no queues available at the moment. Please contact the administrator.
    </div>
    @endif
</div>

<div class="queue-data @if(! isset($expVal) ) hide @endif">
    <div class="form-group">
        <label for="node-count">Node Count <span>( Max Allowed Nodes - <span
                    class="node-count alert-warning"></span>)</span></label>
        <input type="number" class="form-control" name="node-count" id="node-count" min="1"
               value="@if(isset($expVal) ){{ $expVal['scheduling']->nodeCount }}@else{{$queueDefaults['nodeCount']}}@endif"
               required
        @if(isset($expVal) ) @if(!$expVal['editable']){{"disabled"}} @endif @endif>
    </div>
    <div class="form-group">
        <label for="cpu-count">Total Core Count <span>( Max Allowed Cores - <span
                    class="cpu-count alert-warning"></span>)</span></label>
        <input type="number" class="form-control" name="cpu-count" id="cpu-count" min="1"
               value="@if(isset($expVal) ){{ $expVal['scheduling']->totalCPUCount }}@else{{$queueDefaults['cpuCount']}}@endif"
               required
        @if(isset($expVal)) @if(!$expVal['editable']){{"disabled"}} @endif @endif>
    </div>
    <div class="form-group">
        <label for="wall-time">Wall Time Limit <span>( Max Allowed Wall Time - <span
                    class="walltime-count alert-warning"></span>)</span></label>

        <div class="input-group">
            <input type="number" class="form-control" name="wall-time" id="wall-time" min="1"
                   value="@if(isset($expVal)){{$expVal['scheduling']->wallTimeLimit}}@else{{$queueDefaults['wallTimeLimit']}}@endif"
                   required
            @if(isset($expVal)) @if(!$expVal['editable']){{"disabled"}} @endif @endif>
            <span class="input-group-addon">minutes</span>
        </div>
    </div>
    <div class="form-group">
        <label for="physical-memory">Total Physical Memory <span>( Max Allowed Memory - <span
                    class="memory-count alert-warning"></span>)</span></label>

        <div class="input-group">
            <input type="number" class="form-control" name="total-physical-memory" id="memory-count" min="0"
                   value="@if(isset($expVal) ){{ $expVal['scheduling']->totalPhysicalMemory }}@endif"
            @if(isset($expVal)) @if(!$expVal['editable']){{"disabled"}} @endif @endif>
            <span class="input-group-addon">MB</span>
        </div>
    </div>
    <div class="form-group">
        <label for="static-working-dir">Static Working Directory<span
                        class="static-working-dir alert-warning"></span></label>
        <input type="text" class="form-control" name="static-working-dir" id="static-working-dir"
               value="@if(isset($expVal) ){{ $expVal['scheduling']->staticWorkingDir }}@endif"
        @if(isset($expVal)) @if(!$expVal['editable']){{"disabled"}} @endif @endif>
    </div>
</div>


<script>
    //To work work with experiment create (Ajax)
    var selectedQueue = $("#select-queue").val();
    getQueueData(selectedQueue);
    $("#select-queue").change(function () {
        var selectedQueue = $(this).val();
        getQueueData(selectedQueue);
    });

    function getQueueData(selectedQueue) {
        var queues = $.parseJSON($("#queue-array").val());
        console.log(queues);
        for (var i = 0; i < queues.length; i++) {
            if (queues[i]['queueName'] == selectedQueue) {
                //node-count
                if (queues[i]['maxNodes'] != 0 && queues[i]['maxNodes'] != null) {
                    $("#node-count").attr("max", queues[i]['maxNodes']);
                    $(".node-count").html(queues[i]['maxNodes']);
                    $(".node-count").parent().removeClass("hide");
                }
                else
                    $(".node-count").parent().addClass("hide");


                //core-count
                if (queues[i]['maxProcessors'] != 0 && queues[i]['maxProcessors'] != null) {
                    $("#cpu-count").attr("max", queues[i]['maxProcessors']);
                    $(".cpu-count").html(queues[i]['maxProcessors']);
                    $(".cpu-count").parent().removeClass("hide");
                }
                else
                    $(".cpu-count").parent().addClass("hide");

                //walltime-count
                if (queues[i]['maxRunTime'] != null && queues[i]['maxRunTime'] != 0) {
                    $("#wall-time").attr("max", queues[i]['maxRunTime']);
                    $(".walltime-count").html(queues[i]['maxRunTime']);
                    $(".walltime-count").parent().removeClass("hide");
                }
                else
                    $(".walltime-count").parent().addClass("hide");

                //memory-count
                if (queues[i]['maxMemory'] != 0 && queues[i]['maxMemory'] != null) {
                    $("#memory-count").attr("max", queues[i]['maxMemory']).val(0);
                    $(".memory-count").html(queues[i]['maxMemory']);
                    $(".memory-count").parent().removeClass("hide");
                }
                else
                    $(".memory-count").parent().addClass("hide");
            }
        }
        $(".queue-data").removeClass("hide");
    }
</script>


@section('scripts')
@parent
<script>
    //To work with experiment edit (Not Ajax)
    $( document ).ready(function() {
        var selectedQueue = $("#select-queue").val();
        getQueueData(selectedQueue);
        $("#select-queue").change(function () {
            var selectedQueue = $(this).val();
            getQueueData(selectedQueue);
        });
    });
</script>
@stop