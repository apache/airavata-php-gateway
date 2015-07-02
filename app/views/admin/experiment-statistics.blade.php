<!-- high level statistics -->
<div class="high-level-values row tex-center">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$expStatistics->allExperimentCount}}</div>
                        <div>Total Experiments</div>
                    </div>
                </div>
            </div>
            <a id="getAllExperiments" href="#experiment-container">
            <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><span class="glyphicon glyphicon-arrow-right"></span></span>

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$expStatistics->completedExperimentCount}}</div>
                        <div>Successful Experiments</div>
                    </div>
                </div>
            </div>
            <a id="getCompletedExperiments" href="#experiment-container">
            <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><span class="glyphicon glyphicon-arrow-right"></span></i></span>

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$expStatistics->cancelledExperimentCount}}</div>
                        <div>Canceled Experiments</div>
                    </div>
                </div>
            </div>
            <a id="getCancelledExperiments" href="#experiment-container">
            <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><span class="glyphicon glyphicon-arrow-right"></span></i></span>

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$expStatistics->failedExperimentCount}}</div>
                        <div>Failed Experiments</div>
                    </div>
                </div>
            </div>
            <a id="getFailedExperiments" href="#experiment-container">
            <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><span class="glyphicon glyphicon-arrow-right"></span></span>

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>

<div id="experiment-container" style="margin: 20px" class="experiment-container"></div>

<script>
    $("#getAllExperiments").click(function () {
        //These are coming from manage-experiments.blade.php
        $fromTime = $("#datetimepicker9").find("input").val();
        $toTime = $("#datetimepicker10").find("input").val();
        if ($fromTime == '' || $toTime == '') {
            alert("Please Select Valid Date Inputs!");
        } else {
            $.ajax({
                type: 'GET',
                url: "{{URL::to('/')}}/admin/dashboard/experimentsOfTimeRange",
                data: {
                    'status-type': 'ALL',
                    'search-key': 'creation-time',
                    'from-date': $fromTime,
                    'to-date': $toTime
                },
                async: false,
                success: function (data) {
                    $(".experiment-container").html(data);
                    //from time-conversion.js
                    updateTime();
                }
            });
        }
    });

    $("#getCompletedExperiments").click(function () {
        //These are coming from manage-experiments.blade.php
        $fromTime = $("#datetimepicker9").find("input").val();
        $toTime = $("#datetimepicker10").find("input").val();
        if ($fromTime == '' || $toTime == '') {
            alert("Please Select Valid Date Inputs!");
        } else {
            $.ajax({
                type: 'GET',
                url: "{{URL::to('/')}}/admin/dashboard/experimentsOfTimeRange",
                data: {
                    'status-type': 'COMPLETED',
                    'search-key': 'creation-time',
                    'from-date': $fromTime,
                    'to-date': $toTime
                },
                async: false,
                success: function (data) {
                    $(".experiment-container").html(data);
                    //from time-conversion.js
                    updateTime();
                }
            });
        }
    });

    $("#getCancelledExperiments").click(function () {
        //These are coming from manage-experiments.blade.php
        $fromTime = $("#datetimepicker9").find("input").val();
        $toTime = $("#datetimepicker10").find("input").val();
        if ($fromTime == '' || $toTime == '') {
            alert("Please Select Valid Date Inputs!");
        } else {
            $.ajax({
                type: 'GET',
                url: "{{URL::to('/')}}/admin/dashboard/experimentsOfTimeRange",
                data: {
                    'status-type': 'CANCELED',
                    'search-key': 'creation-time',
                    'from-date': $fromTime,
                    'to-date': $toTime
                },
                async: false,
                success: function (data) {
                    $(".experiment-container").html(data);
                    //from time-conversion.js
                    updateTime();
                }
            });
        }
    });

    $("#getFailedExperiments").click(function () {
        //These are coming from manage-experiments.blade.php
        $fromTime = $("#datetimepicker9").find("input").val();
        $toTime = $("#datetimepicker10").find("input").val();
        if ($fromTime == '' || $toTime == '') {
            alert("Please Select Valid Date Inputs!");
        } else {
            $.ajax({
                type: 'GET',
                url: "{{URL::to('/')}}/admin/dashboard/experimentsOfTimeRange",
                data: {
                    'status-type': 'FAILED',
                    'search-key': 'creation-time',
                    'from-date': $fromTime,
                    'to-date': $toTime
                },
                async: false,
                success: function (data) {
                    $(".experiment-container").html(data);
                    //from time-conversion.js
                    updateTime();
                }
            });
        }
    });
</script>