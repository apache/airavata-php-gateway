@extends('layout.basic')

@section('page-header')
@parent
{{ HTML::style('css/datetimepicker.css')}}

@stop

@section('content')

<div class="container" style="max-width: 750px;">
    <h1>Search for Experiments</h1>

    <form action="{{URL::to('/')}}/experiment/search" method="post" class="form-inline" role="form">
        <div class="form-group">
            <label for="search-key">Search by</label>
            <select class="form-control" name="search-key" id="search-key">
                <?php

                // set up options for select input
                $values = array('experiment-name', 'experiment-description', 'application', 'creation-time');
                $labels = array('Experiment Name', 'Experiment Description', 'Application', 'Creation Time');
                $disabled = array('', '', '', '');

                ExperimentUtilities::create_options($values, $labels, $disabled);

                ?>
            </select>
        </div>

        <div class="form-group search-text-block">
            <label for="search-value">for</label>
            <input type="search" class="form-control" name="search-value" id="search-value" placeholder="value" required
                   value="<?php if (isset($_POST['search-value'])) echo $_POST['search-value'] ?>">
        </div>

        <!--        <select name="status-type" class="form-control select-status">-->
        <!--            <option value="ALL">Status</option>-->
        <!--            --><?php
        //            foreach ($expStates as $index => $state) {
        //                if (isset($input) && $state == $input["status-type"]) {
        //                    echo '<option value="' . $state . '" selected>' . $state . '</option>';
        //                } else {
        //                    echo '<option value="' . $state . '">' . $state . '</option>';
        //                }
        //            }
        //
        ?>
        <!--        </select>-->

        <div class="container select-dates hide">
            <div class="col-md-12">
                Select dates between which you want to search for experiments.
            </div>
            <div class="col-sm-8" style="height:75px;">
                <div class='col-md-6'>
                    <div class="form-group">
                        <div class='input-group date' id='datetimepicker9'>
                            <input type='text' class="form-control" placeholder="From Date" name="from-date"
                                   value="<?php if (isset($_POST['from-date'])) echo $_POST['from-date'] ?>"/>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        </div>
                    </div>
                </div>
                <div class='col-md-6'>
                    <div class="form-group">
                        <div class='input-group date' id='datetimepicker10'>
                            <input type='text' class="form-control" placeholder="To Date" name="to-date"
                                   value="<?php if (isset($_POST['to-date'])) echo $_POST['to-date'] ?>"/>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button name="search" type="submit" class="btn btn-primary pull-right" value="Search"><span
                class="glyphicon glyphicon-search"></span> Search
        </button>
        <p class="help-block">You can use * as a wildcard character. Tip: search for * alone to retrieve all of your
            experiments.</p>

        <!-- Pagination Handling -->
        <?php
        if (isset($expContainer)) {
            ?>
            <div class="pull-right btn-toolbar" style="padding-bottom: 5px">
                <?php
                if ($pageNo != 1) {
                    echo '<input class="btn btn-primary btn-xs" type="submit" style="cursor: pointer" name="prev" value="Previous"/>';
                }
                if (sizeof($expContainer) > 0) {
                    echo '<input class="btn btn-primary btn-xs" type="submit" style="cursor: pointer" name="next" value="Next"/>';
                }
                ?>
            </div>
            <div class="pull-left">
                <?php if (sizeof($expContainer) != 0) echo 'Showing results from ' . strval(($pageNo - 1) * $limit + 1)
                    . ' to ' . strval(min($pageNo * $limit, ($pageNo - 1) * $limit + sizeof($expContainer))); ?>
            </div>
            <input type="hidden" name="pageNo" value="<?php echo($pageNo) ?>"/>
            <div style="clear: both"></div>
        <?php
        }
        ?>
    </form>




    <?php

    if (isset($expContainer))
    {
    if (sizeof($expContainer) == 0)
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

    <div id="re" class="table-responsive">
        <table class="table">
            <tr>
                <th>Name</th>
                <th>Application</th>
                <th>Description</th>
                <!--<th>Resource</th>-->
                <th>Creation Time</th>
                <!--                <th>Status</th>-->
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
            foreach ($expContainer as $experiment) {
                $description = $experiment['experiment']->description;
                if (strlen($description) > 17) // 17 is arbitrary
                {
                    $description = substr($experiment['experiment']->description, 0, 17) . '<span class="text-muted">...</span>';
                }

                echo '<tr>';
                $addEditOption = "";
                if ($experiment['expValue']['editable'])
                    $addEditOption = '<a href="' . URL::to('/') . '/experiment/edit?expId=' . $experiment['experiment']->experimentID . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';

                echo '<td>' . $experiment['experiment']->name . $addEditOption . '</td>';

                echo '<td>' . $experiment['expValue']['applicationInterface']->applicationName . '</td>';

                echo '<td>' . $description . '</td>';

                //echo "<td>$computeResource->hostName</td>";
                echo '<td class="time" unix-time="' . $experiment['experiment']->creationTime / 1000 . '"></td>';


                switch ($experiment['expValue']['experimentStatusString']) {
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
                    <a class="<?php echo $textClass; ?>"
                       href="{{ URL::to('/') }}/experiment/summary?expId=<?php echo $experiment['experiment']->experimentID; ?>">
                        <?php echo $experiment['expValue']['experimentStatusString']; ?>
                    </a>
                </td>

                </tr>

            <?php
            }
            }
            }
            ?>
        </table>
    </div>
</div>

@stop

@section('scripts')
@parent
{{ HTML::script('js/time-conversion.js')}}
{{ HTML::script('js/moment.js')}}
{{ HTML::script('js/datetimepicker.js')}}

<script type="text/javascript">

    $(document).ready(function () {

        /* script to make status select work on the UI side itself. */

        $(".select-status").on("change", function () {
            selectedStatus = this.value;

            if (selectedStatus == "ALL") {
                $("table tr").slideDown();
            }
            else {
                $("table tr").each(function (index) {
                    if (index != 0) {

                        $row = $(this);

                        var status = $.trim($row.find("td:last").text());
                        if (status == selectedStatus) {
                            $(this).slideDown();
                        }
                        else {
                            $(this).slideUp();
                        }
                    }
                });
            }
        });

        /* making datetimepicker work for exp search */

        $('#datetimepicker9').datetimepicker({
            pick12HourFormat: false
        });
        $('#datetimepicker10').datetimepicker({
            pick12HourFormat: false
        });
        $("#datetimepicker9").on("dp.change", function (e) {
            $('#datetimepicker10').data("DateTimePicker").setMinDate(e.date);
        });
        $("#datetimepicker10").on("dp.change", function (e) {
            $('#datetimepicker9').data("DateTimePicker").setMaxDate(e.date);
        });

        /* selecting creation time */
        $("#search-key").on("change", function () {
            if (this.value == "creation-time") {
                $(".search-text-block").addClass("hide");
                $(".select-dates").removeClass("hide");
                $("#search-value").removeAttr("required");

            }
            else {
                $(".search-text-block").removeClass("hide");
                $(".select-dates").addClass("hide");
                $("#search-value").attr("required");
            }
        });

        changeInputVisibility($("#search-key").val());

    });

    function changeInputVisibility(selectedStatus) {
        if (selectedStatus == "creation-time") {
            $(".search-text-block").addClass("hide");
            $(".select-dates").removeClass("hide");
            $("#search-value").removeAttr("required");

        }
        else {
            $(".search-text-block").removeClass("hide");
            $(".select-dates").addClass("hide");
            $("#search-value").attr("required");
        }
    }
</script>
@stop