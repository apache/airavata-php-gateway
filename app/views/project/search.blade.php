@extends('layout.basic')

@section('page-header')
@parent
@stop

@section('content')

<div class="container" style="max-width: 750px;">

    <h1>Search for Projects</h1>

    <form action="{{ URL::to('/') }}/project/search" method="post" class="form-inline" role="form">
        <div class="form-group">
            <label for="search-key">Search by</label>
            <select class="form-control" name="search-key" id="search-key">
                <option value="project-name">Project Name</option>
                <option value="project-description">Project description</option>
            </select>
        </div>

        <div class="form-group">
            <label for="search-value">for</label>
            <input type="search" class="form-control" name="search-value" id="search-value" placeholder="value" required
                   value="<?php if (isset($_POST['search-value'])) echo $_POST['search-value'] ?>">
        </div>

        <button name="search" type="submit" class="btn btn-primary" value="Search"><span
                class="glyphicon glyphicon-search"></span> Search
        </button>
        <p class="help-block">You can use * as a wildcard character. Tip: search for * alone to retrieve all of your
            projects.</p>

        <!-- Pagination Handling -->
        <?php
        if (isset($projects)) {
            ?>
            <div class="pull-right btn-toolbar" style="padding-bottom: 5px">
                <?php
                if ($pageNo != 1) {
                    echo '<input class="btn btn-primary btn-xs" type="submit" style="cursor: pointer" name="prev" value="Previous"/>';
                }
                if (sizeof($projects) > 0) {
                    echo '<input class="btn btn-primary btn-xs" type="submit" style="cursor: pointer" name="next" value="Next"/>';
                }
                ?>
            </div>
            <div class="pull-left">
                <?php if (sizeof($projects) != 0) echo 'Showing results from ' . strval(($pageNo - 1) * $limit + 1)
                    . ' to ' . strval(min($pageNo * $limit, ($pageNo - 1) * $limit + sizeof($projects))); ?>
            </div>
            <input type="hidden" name="pageNo" value="<?php echo($pageNo) ?>"/>
            <div style="clear: both"></div>
        <?php
        }
        ?>
    </form>





    <?php

    if (isset($projects))
    {
    /**
     * get results
     */

    /**
     * display results
     */
    if (sizeof($projects) == 0)
    {
        if ($pageNo == 1) {
            CommonUtilities::print_warning_message('No results found. Please try again.');
        } else {
            CommonUtilities::print_warning_message('No more results found.');
        }
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

            foreach ($projects as $project) {

                ?>
                <tr>
                    <td>
                        <?php echo $project->name; ?>
                        <a href="{{URL::to('/')}}/project/edit?projId=<?php echo $project->projectID; ?>" title="Edit">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                    </td>
                    <td class="time" unix-time="
                            <?php echo $project->creationTime / 1000 ?>">
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