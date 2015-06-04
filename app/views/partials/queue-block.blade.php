<div class="form-group">
    <label class="control-label">Queue Description</label>
    <textarea class="form-control" maxlength="255" name="qdesc" placeholder="Queue Description">@if( isset( $queueData)
        ){{ $queueData->queueDescription }}@endif</textarea>
</div>
<div class="form-group">
    <label class="control-label">Queue Max Run Time
        <small> (In Minutes)</small>
    </label>
    <input type="number" min="0" class="form-control"
           value="@if( isset( $queueData) ){{ $queueData->maxRunTime }}@endif" maxlength="30" name="qmaxruntime"
           placeholder="Queue Max Run Time"/>
</div>
<div class="form-group">
    <label class="control-label">Queue Max Nodes</label>
    <input type="number" min="0" class="form-control" value="@if( isset( $queueData) ){{ $queueData->maxNodes }}@endif"
           maxlength="30" name="qmaxnodes" placeholder="Queue Max Nodes"/>
</div>
<div class="form-group">
    <label class="control-label">Queue Max Processors</label>
    <input type="number" min="0" class="form-control"
           value="@if( isset( $queueData) ){{ $queueData->maxProcessors }}@endif" maxlength="30" name="qmaxprocessors"
           placeholder="Queue Max Processors"/>
</div>
<div class="form-group">
    <label class="control-label">Max Jobs in Queue</label>
    <input type="number" min="0" class="form-control"
           value="@if( isset( $queueData) ){{ $queueData->maxJobsInQueue }}@endif" maxlength="30" name="qmaxjobsinqueue"
           placeholder="Max Jobs In Queue"/>
</div>
<div class="form-group">
    <label class="control-label">Max Memory For Queue( In MB )</label>
    <input type="number" min="0" class="form-control" value="@if( isset( $queueData) ){{ $queueData->maxMemory }}@endif"
           maxlength="30" name="qmaxmemoryinqueue" placeholder="Max Memory For Queue"/>
</div>