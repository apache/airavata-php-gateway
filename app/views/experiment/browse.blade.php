@extends('layout.basic')

@section('content')

<div class="container" style="max-width: 750px;">
<h1>Browse Experiments</h1>

<?php

if (isset( $expContainer))
{
?>
    <!-- Pagination handling-->
    <form id="paginationForm" action="{{URL::to('/')}}/experiment/browse" method="post" class="form-inline" role="form">
        <div class="pull-right btn-toolbar" style="padding-bottom: 5px">
            <?php
            if($pageNo!=1){
                echo '<input class="btn btn-primary btn-xs" type="submit" style="cursor: pointer" name="prev" value="Previous"/>';
            }
            if(sizeof($expContainer)>0){
                echo '<input class="btn btn-primary btn-xs" type="submit" style="cursor: pointer" name="next" value="Next"/>';
            }
            ?>
        </div>
        <div class="pull-left">
            <?php if (sizeof($expContainer) != 0) echo 'Showing results from ' . strval(($pageNo-1)*$limit + 1)
                . ' to ' . strval(min($pageNo*$limit, ($pageNo-1)*$limit + sizeof($expContainer))); ?>
        </div>
        <input type="hidden" name="pageNo" value="<?php echo($pageNo) ?>"/>
        <div style="clear: both"></div>
    </form>

<?php
    if (sizeof($expContainer) == 0)
    {
        if($pageNo==1){
            Utilities::print_warning_message('No results found. Please try again.');
        }else{
            Utilities::print_warning_message('No more results found.');
        }
    }
    else
    {
?>
    <div id="re" class="table-responsive">
        <table class="table">
            <tr>
                <th>Name</th>
                <th>Application</th>
                <th>Description</th>
                <!--<th>Resource</th>-->
                <th>Creation Time</th>
                <th>
                    <select class="form-control select-status">
                        <option value="ALL">Status</option>
                    @foreach( $expStates as $index => $state)
                        <option value="{{ $state }}">{{ $state }}</option>
                    @endforeach
                    </select>
                </th>
            </tr>
    

<?php
        foreach ($expContainer as $experiment)
        {
            $description = $experiment['experiment']->description;
            if (strlen($description) > 17) // 17 is arbitrary
            {
                $description = substr($experiment['experiment']->description, 0, 17) . '<span class="text-muted">...</span>';
            }

            echo '<tr>';
            $addEditOption="";
            if( $experiment['expValue']['editable'])
                $addEditOption = '<a href="'. URL::to('/') . '/experiment/edit?expId=' . $experiment['experiment']->experimentID . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';

            echo '<td>' . $experiment['experiment']->name .  $addEditOption . '</td>';

            echo '<td>' . $experiment['expValue']['applicationInterface']->applicationName . '</td>';

            echo '<td>' . $description . '</td>';

            //echo "<td>$computeResource->hostName</td>";
            echo '<td>' . date('Y-m-d H:i:s', $experiment['experiment']->creationTime/1000) . '</td>';


            switch ($experiment['expValue']['experimentStatusString'])
            {
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

        ?>
            <td>
                <a class="<?php echo $textClass; ?>" href="{{ URL::to('/') }}/experiment/summary?expId=<?php echo $experiment['experiment']->experimentID; ?>">
                    <?php echo $experiment['expValue']['experimentStatusString']; ?>
                </a>
            </td>

            </tr>

        <?php            
        }

        echo '
            </table>
            </div>
            ';
    }
}
?>
</div>

@stop

@section('scripts')
    @parent

    <script type="text/javascript">

        $(document).ready( function(){

            /* script to make status select work on the UI side itself. */

            $(".select-status").on("change", function(){
                selectedStatus = this.value;

                if( selectedStatus == "ALL")
                {
                    $("table tr").slideDown();
                }
                else
                {
                    $("table tr").each(function(index) {
                        if (index != 0) {

                            $row = $(this);

                            var status = $.trim( $row.find("td:last").text() );
                            if (status == selectedStatus )
                            {
                                $(this).slideDown();
                            }
                            else {
                                $(this).slideUp();
                            }
                        }
                    });
                }
            });
        });

    function changeInputVisibility( selectedStatus)
    {
        if( selectedStatus == "creation-time")
        {
            $(".search-text-block").addClass("hide");
            $(".select-dates").removeClass("hide");
            $("#search-value").removeAttr("required");

        }
        else
        {
            $(".search-text-block").removeClass("hide");
            $(".select-dates").addClass("hide");
            $("#search-value").attr("required");
        }
    }
    </script>
@stop