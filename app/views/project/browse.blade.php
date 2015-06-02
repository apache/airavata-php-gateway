
@extends('layout.basic')

@section('page-header')
    @parent
@stop

@section('content')

    <div class="container" style="max-width: 750px;">

        <h1>Browse Projects</h1>
        <?php

        if (isset( $projects))
        {
        ?>

        <!-- Pagination handling-->
        <form id="paginationForm" action="{{URL::to('/')}}/project/browse" method="post" class="form-inline" role="form">
            <div class="pull-right btn-toolbar" style="padding-bottom: 5px">
                <?php
                if($pageNo!=1){
                    echo '<input class="btn btn-primary btn-xs" type="submit" style="cursor: pointer" name="prev" value="Previous"/>';
                }
                if(sizeof($projects)>0){
                    echo '<input class="btn btn-primary btn-xs" type="submit" style="cursor: pointer" name="next" value="Next"/>';
                }
                ?>
            </div>
            <div class="pull-left">
                <?php if (sizeof($projects) != 0) echo 'Showing results from ' . strval(($pageNo-1)*$limit + 1)
                    . ' to ' . strval(min($pageNo*$limit, ($pageNo-1)*$limit + sizeof($projects))); ?>
            </div>
            <input type="hidden" name="pageNo" value="<?php echo($pageNo) ?>"/>
            <div style="clear: both"></div>
        </form>

        <?php
            /**
             * get results
             */

            /**
             * display results
             */
            if (sizeof($projects) == 0)
            {
                Utilities::print_warning_message('No results found. Please try again.');
            }
            else
            {
            ?>
                <div class="table-responsive">
                    <table class="table">

                        <tr>

                            <th>Name</th>
                            <th>Creation Time</th>
                            <th>Experiments</th>

                        </tr>
            <?php

                foreach ($projects as $project)
                {

            ?>
                    <tr>
                        <td>
                            <?php echo $project->name; ?>
                            <a href="{{URL::to('/')}}/project/edit?projId=<?php echo $project->projectID; ?>" title="Edit">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </a>
                        </td>
                        <td class="time" unix-time="
                            <?php echo $project->creationTime/1000 ?>">
                        </td>
                        <td>
                            <a href="{{URL::to('/')}}/project/summary?projId=<?php echo $project->projectID; ?>">
                                <span class="glyphicon glyphicon-list"></span>
                            </a>
                            <a href="{{URL::to('/')}}/project/summary?projId=<?php echo $project->projectID; ?>"> View</a>
                        </td>
                    </tr>
            <?php

                }

                echo '</table>';
                echo '</div>';
            }

        }
        
        ?>


    </div>

@stop
@section('scripts')
@parent
    {{ HTML::script('js/time-conversion.js')}}
@stop