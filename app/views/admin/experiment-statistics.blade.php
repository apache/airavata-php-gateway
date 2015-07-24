<!-- high level statistics -->
<div class="high-level-values row tex-center">
    <div class="col-lg-2 col-md-4">
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
            <div class="panel-footer" style="height: 80px">
                    <span class="pull-left">All</span>
<!--                    <span class="pull-right"><span class="glyphicon glyphicon-arrow-right"></span></span>-->

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-2 col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$expStatistics->createdExperimentCount}}</div>
                        <div>Created Experiments</div>
                    </div>
                </div>
            </div>
            <a id="getCreatedExperiments" href="#experiment-container">
                <div class="panel-footer" style="height: 80px">
                    <span class="pull-left">CREATED VALIDATED &nbsp; &nbsp; &nbsp; &nbsp; </span>
<!--                    <span class="pull-right"><span class="glyphicon glyphicon-arrow-right"></span></span>-->

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-2 col-md-4">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$expStatistics->runningExperimentCount}}</div>
                        <div>Running Experiments</div>
                    </div>
                </div>
            </div>
            <a id="getRunningExperiments" href="#experiment-container">
                <div class="panel-footer" style="height: 80px">
                    <span class="pull-left">SCHEDULED LAUNCHED EXECUTING</span>
<!--                    <span class="pull-right"><span class="glyphicon glyphicon-arrow-right"></span></span>-->

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-2 col-md-4">
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
            <div class="panel-footer" style="height: 80px">
                    <span class="pull-left">COMPLETED</span>
<!--                    <span class="pull-right"><span class="glyphicon glyphicon-arrow-right"></span></i></span>-->

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-2 col-md-4">
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
            <div class="panel-footer" style="height: 80px">
                    <span class="pull-left">CANCELLING CANCELLED</span>
<!--                    <span class="pull-right"><span class="glyphicon glyphicon-arrow-right"></span></i></span>-->

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-2 col-md-4">
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
            <div class="panel-footer" style="height: 80px">
                    <span class="pull-left">FAILED</span>
<!--                    <span class="pull-right"><span class="glyphicon glyphicon-arrow-right"></span></span>-->

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>

<div id="experiment-container" style="margin: 20px" class="experiment-container"></div>

<script>

    function convertToUtc(str) {

        var date = new Date(str);
        var year = date.getUTCFullYear();
        var month = date.getUTCMonth()+1;
        var dd = date.getUTCDate();
        var hh = date.getUTCHours();
        var mi = date.getUTCMinutes();
        var sec = date.getUTCSeconds();

        // 2010-11-12T13:14:15Z

        theDate = year + "-" + (month [1] ? month : "0" + month [0]) + "-" +
            (dd[1] ? dd : "0" + dd[0]);
        theTime = (hh[1] ? hh : "0" + hh[0]) + ":" + (mi[1] ? mi : "0" + mi[0]);
        return [ theDate, theTime ].join("T");
    }

    $("#getAllExperiments").click(function () {
        //These are coming from manage-experiments.blade.php
        $fromTime = $("#datetimepicker9").find("input").val();
        $fromTime = convertToUtc($fromTime);
        $toTime = $("#datetimepicker10").find("input").val();
        $toTime - convertToUtc($toTime);
        if ($fromTime == '' || $toTime == '') {
            alert("Please Select Valid Date Inputs!");
        } else {
            $(".loading-img-statistics").removeClass("hide");
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
            }).complete(function () {
                $(".loading-img-statistics").addClass("hide");
            });
        }
    });

    $("#getCreatedExperiments").click(function () {
        //These are coming from manage-experiments.blade.php
        $fromTime = $("#datetimepicker9").find("input").val();
        $fromTime = convertToUtc($fromTime);
        $toTime = $("#datetimepicker10").find("input").val();
        $toTime - convertToUtc($toTime);

        if ($fromTime == '' || $toTime == '') {
            alert("Please Select Valid Date Inputs!");
        } else {
            $(".loading-img-statistics").removeClass("hide");
            $.ajax({
                type: 'GET',
                url: "{{URL::to('/')}}/admin/dashboard/experimentsOfTimeRange",
                data: {
                    'status-type': 'CREATED',
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
            }).complete(function () {
                $(".loading-img-statistics").addClass("hide");
            });
        }
    });

    $("#getRunningExperiments").click(function () {
        //These are coming from manage-experiments.blade.php
        $fromTime = $("#datetimepicker9").find("input").val();
        $fromTime = convertToUtc($fromTime);
        $toTime = $("#datetimepicker10").find("input").val();
        $toTime - convertToUtc($toTime);
        if ($fromTime == '' || $toTime == '') {
            alert("Please Select Valid Date Inputs!");
        } else {
            $(".loading-img-statistics").removeClass("hide");
            $.ajax({
                type: 'GET',
                url: "{{URL::to('/')}}/admin/dashboard/experimentsOfTimeRange",
                data: {
                    'status-type': 'RUNNING',
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
            }).complete(function () {
                $(".loading-img-statistics").addClass("hide");
            });
        }
    });

    $("#getCompletedExperiments").click(function () {
        //These are coming from manage-experiments.blade.php
        $fromTime = $("#datetimepicker9").find("input").val();
        $fromTime = convertToUtc($fromTime);
        $toTime = $("#datetimepicker10").find("input").val();
        $toTime - convertToUtc($toTime);
        if ($fromTime == '' || $toTime == '') {
            alert("Please Select Valid Date Inputs!");
        } else {
            $(".loading-img-statistics").removeClass("hide");
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
            }).complete(function () {
                $(".loading-img-statistics").addClass("hide");
            });
        }
    });

    $("#getCancelledExperiments").click(function () {
        //These are coming from manage-experiments.blade.php
        $fromTime = $("#datetimepicker9").find("input").val();
        $fromTime = convertToUtc($fromTime);
        $toTime = $("#datetimepicker10").find("input").val();
        $toTime - convertToUtc($toTime);
        if ($fromTime == '' || $toTime == '') {
            alert("Please Select Valid Date Inputs!");
        } else {
            $(".loading-img-statistics").removeClass("hide");
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
            }).complete(function () {
                $(".loading-img-statistics").addClass("hide");
            });
        }
    });

    $("#getFailedExperiments").click(function () {
        //These are coming from manage-experiments.blade.php
        $fromTime = $("#datetimepicker9").find("input").val();
        $fromTime = convertToUtc($fromTime);
        $toTime = $("#datetimepicker10").find("input").val();
        $toTime - convertToUtc($toTime);
        if ($fromTime == '' || $toTime == '') {
            alert("Please Select Valid Date Inputs!");
        } else {
            $(".loading-img-statistics").removeClass("hide");
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
            }).complete(function () {
                $(".loading-img-statistics").addClass("hide");
            });
        }
    });
</script>