<!-- Jira Issue Collector - Report Issue -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

<!-- Jira Issue Collector - Request Feature -->
@if( isset( Config::get('pga_config.portal')['jira-help']))
<script type="text/javascript"
        src="{{ Config::get('pga_config.portal')['jira-help']['report-issue-script'] }}"></script>


<script type="text/javascript"
        src="{{ Config::get('pga_config.portal')['jira-help']['request-feature-script'] }}"></script>


<script type="text/javascript">
    window.ATL_JQ_PAGE_PROPS = $.extend(window.ATL_JQ_PAGE_PROPS, {
        "{{ Config::get('pga_config.portal')['jira-help']['report-issue-collector-id'] }}": {
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
        "{{ Config::get('pga_config.portal')['jira-help']['request-feature-collector-id'] }}": {
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
</script>
@endif
<script type="text/javascript">

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