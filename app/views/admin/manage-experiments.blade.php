@extends('layout.basic')

@section('page-header')
@parent
{{ HTML::style('css/admin.css')}}
{{ HTML::style('css/datetimepicker.css')}}
@stop

@section('content')

<div id="wrapper">
<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
@include( 'partials/dashboard-block')
<div id="page-wrapper">
<div class="col-md-12">
    <h3>Experiments</h3>
</div>
<div class="container-fluid">

<div class="row">
    <!--
        <div class="well col-md-2 text-center">
            Total 500
        </div>
    -->

    <div class="well form-group form-horizontal col-md-12">
        <label class="col-md-3">Enter Experiment Id to View Summary :</label>

        <div class="col-md-6">
            <input type="text" class="form-control experimentId"/>
        </div>
        <button class="col-md-3 btn btn-primary get-experiment">Get</button>
        <div class="loading-img hide text-center"><img src="{{URL::to('/')}}/assets/ajax-loader.gif"/></div>

        <div class="experiment-info col-md-12">
        </div>
    </div>
</div>

<hr/>
<br/>

<div class="dates row">
    <div class="well col-md-12">
        <div class="col-md-10">
            <div class='col-md-5'>
                <div class="form-group">
                        <input type='button' class="oneDayExp form-control btn-primary" value="Get Experiments from Last 24 hours"/>
                </div>
            </div>
            <div class='col-md-5'>
                <div class="form-group">
                        <input type='button' class="oneWeekExp form-control btn-primary" value="Get Experiments from Last Week"/>
                </div>
            </div>

            <div class="col-md-12">
                <h4>Or Select dates between which you want to review experiment statistics.</h4>
            </div>
            <div class='col-md-5'>
                <div class="form-group">
                    <div class='input-group date' id='datetimepicker9'>
                        <input type='text' class="form-control" placeholder="From Date" name="from-date"/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                    </div>
                </div>
            </div>
            <div class='col-md-5'>
                <div class="form-group">
                    <div class='input-group date' id='datetimepicker10'>
                        <input type='text' class="form-control" placeholder="To Date" name="to-date"/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <button name="getStatistics" id="getStatistics" type="submit" class="btn btn-primary"
                        value="GetStatistics"><span
                        class="glyphicon glyphicon-search"></span> Get Statistics
                </button>
            </div>
        </div>
    </div>
    <div class="experiment-statistics"></div>
    <div class="loading-img-statistics hide text-center"><img src="{{URL::to('/')}}/assets/ajax-loader.gif"/></div>
</div>

<!--<div class="row">-->
<!--    <div class="col-lg-12">-->
<!--        <div class="panel panel-primary">-->
<!--            <div class="panel-heading">-->
<!--                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i>Experiment v/s Time Graph</h3>-->
<!--            </div>-->
<!--            <div class="panel-body">-->
<!--                <div class="flot-chart">-->
<!--                    <div class="flot-chart-content" id="flot-line-chart" style="padding: 0px; position: relative;">-->
<!--                        <canvas class="base" width="1596" height="400"></canvas>-->
<!--                        <canvas class="overlay" width="1596" height="400"-->
<!--                                style="position: absolute; left: 0px; top: 0px;"></canvas>-->
<!--                        <div class="tickLabels" style="font-size:smaller">-->
<!--                            <div class="xAxis x1Axis" style="color:#545454">-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:center;left:-33px;top:383px;width:122px">0-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:center;left:97px;top:383px;width:122px">1-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:center;left:228px;top:383px;width:122px">2-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:center;left:358px;top:383px;width:122px">3-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:center;left:488px;top:383px;width:122px">4-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:center;left:619px;top:383px;width:122px">5-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:center;left:749px;top:383px;width:122px">6-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:center;left:879px;top:383px;width:122px">7-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:center;left:1010px;top:383px;width:122px">8-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:center;left:1140px;top:383px;width:122px">9-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:center;left:1270px;top:383px;width:122px">10-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:center;left:1401px;top:383px;width:122px">11-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="yAxis y1Axis" style="color:#545454">-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:right;top:337px;right:1575px;width:21px">-1.0-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:right;top:259px;right:1575px;width:21px">-0.5-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:right;top:182px;right:1575px;width:21px">0.0-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:right;top:104px;right:1575px;width:21px">0.5-->
<!--                                </div>-->
<!--                                <div class="tickLabel"-->
<!--                                     style="position:absolute;text-align:right;top:26px;right:1575px;width:21px">1.0-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="legend">-->
<!--                            <div-->
<!--                                style="position: absolute; width: 45px; height: 34px; top: 9px; right: 9px; opacity: 0.85; background-color: rgb(255, 255, 255);"></div>-->
<!--                            <table style="position:absolute;top:9px;right:9px;;font-size:smaller;color:#545454">-->
<!--                                <tbody>-->
<!--                                <tr>-->
<!--                                    <td class="legendColorBox">-->
<!--                                        <div style="border:1px solid #ccc;padding:1px">-->
<!--                                            <div-->
<!--                                                style="width:4px;height:0;border:5px solid rgb(237,194,64);overflow:hidden"></div>-->
<!--                                        </div>-->
<!--                                    </td>-->
<!--                                    <td class="legendLabel">Canceled Experiments</td>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <td class="legendColorBox">-->
<!--                                        <div style="border:1px solid #ccc;padding:1px">-->
<!--                                            <div-->
<!--                                                style="width:4px;height:0;border:5px solid rgb(175,216,248);overflow:hidden"></div>-->
<!--                                        </div>-->
<!--                                    </td>-->
<!--                                    <td class="legendLabel">Successful Experiments</td>-->
<!--                                </tr>-->
<!--                                </tbody>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!---->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!---->
<!--<div class="row">-->
<!--    <h1 class="text-center well alert alert-danger">Proposed UI to view flow of one experiment.</h1>-->
<!---->
<!--    <div class="tree">-->
<!--        <ul>-->
<!--            <li>-->
<!--                <span><i class="icon-calendar"></i>Experiment 1</span>-->
<!--                <ul>-->
<!--                    <li>-->
<!--                        <span class="badge badge-success"><i class="icon-minus-sign"></i>Pre Processing</span>-->
<!--                        <ul>-->
<!--                            <li>-->
<!--                                <a href=""><span class="alert alert-success"><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash;-->
<!--                                    PGA to Airavata Authentication Successful</a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href=""><span class="alert alert-success"><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash;-->
<!--                                    Airavata to Resource Authentication Successful</a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <span class="badge badge-success"><i class="icon-minus-sign"></i>Input Staging</span>-->
<!--                        <ul>-->
<!--                            <li>-->
<!--                                <span class="alert alert-success"><i-->
<!--                                        class="icon-time"></i>2015-04-17 15:21:21</span> &ndash; <a href="">PGA to
-->
<!--                                    Airavata File Transfer Successful</a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <span class="alert alert-success" abhi><i-->
<!--                                        class="icon-time"></i>2015-04-17 15:21:21</span> &ndash; <a href="">Airavata to
-->
<!--                                    Resource File Transfer Successful</a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <span class="badge badge-warning"><i class="icon-minus-sign"></i>Job Description</span>-->
<!--                        <ul>-->
<!--                            <li>-->
<!--                                <a href=""><span>-->
<!--                                                   Long Script of Job Description / PBS Script <br/>-->
<!--                                                   <br/>-->
<!--                                                    <p>-->
<!--                                                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean-->
<!--                                                        commodo ligula eget dolor. Aenean massa. Cum sociis natoque-->
<!--                                                        penatibus et magnis dis parturient montes, nascetur ridiculus-->
<!--                                                        mus. Donec quam felis, ultricies nec, pellentesque eu, pretium-->
<!--                                                        quis, sem. Nulla consequat massa quis enim. Donec pede justo,-->
<!--                                                        fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo,-->
<!--                                                        rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum-->
<!--                                                        felis eu pede mollis pretium. Integer tincidunt. Cras dapibus.-->
<!--                                                        Vivamus elementum semper nisi. Aenean vulputate eleifend tellus.-->
<!--                                                        Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac,-->
<!--                                                        enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a,-->
<!--                                                        tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque-->
<!--                                                        rutrum. Aenean-->
<!--                                                    </p>-->
<!--                                                 </span></a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <span class="badge badge-important"><i class="icon-minus-sign"></i>Execution</span>-->
<!--                        <ul>-->
<!--                            <li>-->
<!--                                <a href=""><span class="alert alert-success"><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash;-->
<!--                                    Execution of Job Description - No errors</a>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </li>-->
<!---->
<!--                    <li>-->
<!--                        <span class="badge badge-important"><i class="icon-minus-sign"></i>Experiment Complete</span>-->
<!--                        <ul>-->
<!--                            <li>-->
<!--                                <a href=""><span class="alert alert-danger"><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash;-->
<!--                                    Output Transfer from Resource to Airavata UnSuccessful</a>-->
<!--                                <br/>-->
<!--                                <span> Some text about failure</span>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href=""><span class="alert alert-danger"><i class="icon-time"></i>2015-04-17 15:21:21</span> &ndash;-->
<!--                                    Output Transfer from Airavata to PGA UnSuccessful</a>-->
<!--                                <br/>-->
<!--                                <span> Some text about failure</span>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </li>-->
<!---->
<!---->
<!--                </ul>-->
<!--            </li>-->
<!--        </ul>-->
<!--    </div>-->
<!--</div>-->
</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

</div>

@stop


@section('scripts')
@parent
{{ HTML::script('js/gateway.js') }}
{{ HTML::script('js/moment.js')}}
{{ HTML::script('js/datetimepicker.js')}}

<!-- Morris Charts JavaScript -->
<!--
to be uncommented when actually in use.

{{ HTML::script('js/morris/raphael.min.js')}}
{{ HTML::script('js/morris/morris.min.js')}}
{{ HTML::script('js/morris/morris-data.js')}}
-->

<!-- Flot Charts JavaScript -->
{{ HTML::script('js/flot/jquery.flot.js')}}
{{ HTML::script('js/flot/jquery.flot.tooltip.min.js')}}
{{ HTML::script('js/flot/jquery.flot.resize.js')}}
{{ HTML::script('js/flot/jquery.flot.pie.js')}}
{{ HTML::script('js/flot/flot-data.js')}}

{{ HTML::script('js/moment.js')}}
{{ HTML::script('js/datetimepicker.js')}}
{{ HTML::script('js/time-conversion.js')}}
<script>

    //make first tab of accordion open by default.
    //temporary fix
    $("#accordion2").children(".panel").children(".collapse").addClass("in");
    $(".add-tenant").slideUp();

    $(".toggle-add-tenant").click(function () {
        $(".add-tenant").slideDown();
    });

    $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
    $('.tree li.parent_li > span').on('click', function (e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(":visible")) {
            children.hide('fast');
            $(this).attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
        } else {
            children.show('fast');
            $(this).attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
        }
        e.stopPropagation();
    });

    $(".get-experiment").click(function () {
        $(".loading-img").removeClass("hide");
        $.ajax({
            url: 'experiment/summary?expId=' + $(".experimentId").val(),
            type: 'get',
            success: function (data) {
                $(".experiment-info").html(data);
                //from time-conversion.js
                updateTime();
            }
        }).complete(function () {
            $(".loading-img").addClass("hide");
        });
    });

    //Experiment stages are under development.
    $(".tree").parent().addClass("hide");

    /* making datetimepicker work for exp stat search */

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

    $(".oneDayExp").click( function(){
        var todayDate = getCurrentDate();
        var ydayDate = getCurrentDate(1);
        $("#datetimepicker9").find("input").val( ydayDate);
        $("#datetimepicker10").find("input").val( todayDate);
        todayDate = moment(todayDate).utc().format('MM/DD/YYYY hh:mm a');
        ydayDate = moment(ydayDate).utc().format('MM/DD/YYYY hh:mm a');
        getExperiments( ydayDate, todayDate);
    });

    $(".oneWeekExp").click( function(){
        var todayDate = getCurrentDate();
        var ydayDate = getCurrentDate(7);
        $("#datetimepicker9").find("input").val( ydayDate);
        $("#datetimepicker10").find("input").val( todayDate);
        todayDate = moment(todayDate).utc().format('MM/DD/YYYY hh:mm a');
        ydayDate = moment(ydayDate).utc().format('MM/DD/YYYY hh:mm a');
        getExperiments( ydayDate, todayDate);
    })

    $("#getStatistics").click(function () {
        $fromTime = $("#datetimepicker9").find("input").val();
        $fromTime = moment($fromTime).utc().format('MM/DD/YYYY hh:mm a');
        $toTime = $("#datetimepicker10").find("input").val();
        $toTime = moment($toTime).utc().format('MM/DD/YYYY hh:mm a');
        if ($fromTime == '' || $toTime == '') {
            alert("Please Select Valid Date Inputs!");
        } else {
            getExperiments( $fromTime, $toTime);
        }
    });

    function getExperiments( startTime, endTime){
        $(".experiment-statistics").html("");
        $(".loading-img-statistics").removeClass("hide");
            $.ajax({
                url: 'experimentStatistics?fromTime=' + startTime + '&' + 'toTime=' + endTime,
                type: 'get',
                success: function (data) {
                    $(".experiment-statistics").html(data);
                }
            }).complete(function () {
                $(".loading-img-statistics").addClass("hide");
            });
    }

    function getCurrentDate( subtractDaysFromToday){
        var cd =  new Date();
        var hours = cd.getUTCHours();
        month = cd.getMonth() + 1; //getmonth()starts from 0 for some reason
        var timeOfDay = "AM";
        if(hours >= 12)
        {
            timeOfDay = "PM"
            if(hours != 12)
                hours = hours - 12;
        }
        var date = cd.getDate();
        if( subtractDaysFromToday!= null)
            date = date - subtractDaysFromToday;

        var todayDate = month + "/" + date + "/" + cd.getFullYear() + " " + hours + ":" + cd.getUTCMinutes() + " " + timeOfDay;
        return todayDate;
    }
</script>
@stop