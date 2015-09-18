<!-- Jira Issue Collector - Report Issue -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

<script type="text/javascript"
        src="https://gateways.atlassian.net/s/31280375aecc888d5140f63e1dc78a93-T/en_USmlc07/6328/46/1.4.13/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=en-US&collectorId=b1572922"></script>

<!-- Jira Issue Collector - Request Feature -->
<script type="text/javascript"
        src="https://gateways.atlassian.net/s/31280375aecc888d5140f63e1dc78a93-T/en_USmlc07/6328/46/1.4.13/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=en-US&collectorId=674243b0"></script>


<script type="text/javascript">
    window.ATL_JQ_PAGE_PROPS = $.extend(window.ATL_JQ_PAGE_PROPS, {
        "b1572922": {
            "triggerFunction": function (showCollectorDialog) {
                //Requries that jQuery is available!
                jQuery("#report-issue").click(function (e) {
                    e.preventDefault();
                    showCollectorDialog();
                });
            },fieldValues: {
                email : email !== 'undefined' ? email : "",
                fullname : fullName !== 'undefined' ? fullName : ""
            }
        },
        "674243b0": {
            "triggerFunction": function (showCollectorDialog) {
                //Requries that jQuery is available!
                jQuery("#request-feature").click(function (e) {
                    e.preventDefault();
                    showCollectorDialog();
                });
            },fieldValues: {
                email : email !== 'undefined' ? email : "",
                fullname : fullName !== 'undefined' ? fullName : ""
            }
        }
    });

    var highest = null;
    $(".nav-tabs a").each(function () {  //find the height of your highest link
        var h = $(this).height();
        if (h > highest) {
            highest = $(this).height();
        }
    });

    $(".nav-tabs a").height(highest);  //set all your links to that height.


    // not letting users to add only spaces in text boxes.
    $("body").on("blur", ".form-control", function () {
        $(this).val($.trim($(this).val()));
    });

    //find users' current time.
    if ("{{ Session::get('user_time') }}".length == 0) {
        var visitortime = new Date();
        var visitortimezone = visitortime.getTimezoneOffset() / 60;
        $.ajax({
            type: "GET",
            url: "{{URL::to('/')}}/setUserTimezone",
            data: 'timezone=' + visitortimezone,
            success: function () {
                //location.reload();
            }
        });
    }
</script>