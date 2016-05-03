<?php
use Airavata\Model\Workspace\Notification;
use Airavata\Model\Workspace\NotificationPriority;

class CommonUtilities
{

    /**
     * Print success message
     * @param $message
     */
    public static function print_success_message($message)
    {
        echo '<div class="alert alert-success">' . $message . '</div>';
    }

    /**
     * Print warning message
     * @param $message
     */
    public static function print_warning_message($message)
    {
        echo '<div class="alert alert-warning">' . $message . '</div>';
    }

    /**
     * Print error message
     * @param $message
     */
    public static function print_error_message($message)
    {
        echo '<div class="alert alert-danger">' . $message . '</div>';
    }

    /**
     * Print info message
     * @param $message
     */
    public static function print_info_message($message)
    {
        echo '<div class="alert alert-info">' . $message . '</div>';
    }

    /**
     * Redirect to the given url
     * @param $url
     */
    public static function redirect($url)
    {
        echo '<meta http-equiv="Refresh" content="0; URL=' . $url . '">';
    }

    /**
     * Return true if the form has been submitted
     * @return bool
     */
    public static function form_submitted()
    {
        return isset($_POST['Submit']);
    }

    /**
     * Store username in session variables
     * @param $username
     */
    public static function store_id_in_session($username)
    {
        Session::put('username', $username);
        Session::put('loggedin', true);
    }

    /**
     * Return true if the username stored in the session
     * @return bool
     */
    public static function id_in_session()
    {
        if (Session::has("username") && Session::has('loggedin'))
            return true;
        else
            return false;
    }

    /**
     * Verify if the user is already logged in. If not, redirect to the home page.
     */
    public static function verify_login()
    {
        if (CommonUtilities::id_in_session()) {
            return true;
        } else {
            CommonUtilities::print_error_message('User is not logged in!');
            return false;
        }
    }

    /**
     * Create navigation bar
     * Used for all pages
     */
    public static function create_nav_bar()
    {
        $menus = array();
        if ( Session::has('loggedin') && (Session::has('authorized-user') || Session::has('admin')
                || Session::has('admin-read-only'))) {
            $menus = array
            (
                'Project' => array
                (
                    array('label' => 'Create', 'url' => URL::to('/') . '/project/create', "nav-active" => "project"),
                    array('label' => 'Browse', 'url' => URL::to('/') . '/project/browse', "nav-active" => "project")
                ),
                'Experiment' => array
                (
                    array('label' => 'Create', 'url' => URL::to('/') . '/experiment/create', "nav-active" => "experiment"),
                    array('label' => 'Browse', 'url' => URL::to('/') . '/experiment/browse', "nav-active" => "experiment")
                )
            );

            if( isset( Config::get('pga_config.portal')['jira-help']))
            {
                $menus['Help'] = array();
                if( Config::get('pga_config.portal')['jira-help']['report-issue-script'] != '' 
                    && Config::get('pga_config.portal')['jira-help']['report-issue-collector-id'] != '')
                {
                    $menus['Help'][] = array('label' => 'Report Issue', 'url' => '#', "nav-active", "");
                }  
    //                array('label' => 'Forgot Password?', 'url' => URL::to('/') . '/forgot-password', "nav-active" => "")
                if( Config::get('pga_config.portal')['jira-help']['request-feature-script'] != '' 
                    && Config::get('pga_config.portal')['jira-help']['request-feature-collector-id'] != '')
                {
                    $menus['Help'][] = array('label' => 'Request Feature', 'url' => '#', "nav-active", "");
                }

                if( count( $menus['Help'] ) == 0 )
                    unset( $menus['Help']);
            }
        }

        echo '<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                       <span class="sr-only">Toggle navigation</span>
                       <span class="icon-bar"></span>
                       <span class="icon-bar"></span>
                       <span class="icon-bar"></span>
                    </button>
                    <!--
                    <a class="navbar-brand" href="' . URL::to('home') . '" title="PHP Gateway with Airavata">PGA</a>
                    -->
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                    <ul class="nav navbar-nav">';


        foreach ($menus as $label => $options) {
            Session::has('loggedin') ? $disabled = '' : $disabled = ' class="disabled"';

            $active = "";
            if (Session::has("nav-active") && isset($options[0]['nav-active'])) {
                if ($options[0]['nav-active'] == Session::get("nav-active"))
                    $active = " active ";
            }
            echo '<li class="dropdown ' . $active . '">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $label . '<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">';

            if (Session::has('loggedin')) {
                foreach ($options as $option) {
                    $id = strtolower(str_replace(' ', '-', $option['label']));

                    echo '<li' . $disabled . '><a href="' . $option['url'] . '" id=' . $id . '>' . $option['label'] . '</a></li>';
                }
            }

            echo '</ul>
        </li>';
        }

        $active = "";
        if(Session::has('loggedin') && (Session::has('authorized-user') || Session::has('admin')
                || Session::has('admin-read-only'))){
            if( Session::get("nav-active") == "storage")
                $active = "active";
            echo '<li class="' . $active . '"><a href="' . URL::to("/") . '/files/browse"><span class="glyphicon glyphicon-folder-close"></span> Storage</a></li>';
        }
        echo '</ul>

        <ul class="nav navbar-nav navbar-right">';

        // right-aligned content

        if (Session::has('loggedin')) {
            $active = "";
            if (Session::has("nav-active")) {
                if ("user-console" == Session::get("nav-active"))
                    $active = " active ";
            }

            if( Session::has('authorized-user') || Session::has('admin') || Session::has('admin-read-only')){
                //notification bell
                $notices = array();
                $notices = CommonUtilities::get_all_notices();
                echo CommonUtilities::get_notices_ui( $notices);
            }


            if (Session::has("admin") || Session::has("admin-read-only"))
                echo '<li class="' . $active . '"><a href="' . URL::to("/") . '/admin/dashboard"><span class="glyphicon glyphicon-user"></span>Admin Dashboard</a></li>';
//            else
//                echo '<li><a href="' . URL::to("/") . '/user/profile"><span class="glyphicon glyphicon-user"></span> Profile</a></li>';

            echo '<li class="dropdown">

                <a href="#" class="dropdown-toggle" data-toggle="dropdown">' . Session::get("username") . ' <span class="caret"></span></a>';
            echo '<ul class="dropdown-menu" role="menu">';

            echo '<li><a href="' . URL::to('/') . '/logout"><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>';
            echo '</ul></li></ul>';
        } else {
            echo '<li><a href="' . URL::to('/') . '/create"><span class="glyphicon glyphicon-user"></span> Create account</a></li>';
            echo '<li><a href="' . URL::to('/') . '/login"><span class="glyphicon glyphicon-log-in"></span> Log in</a></li>';
            echo '</ul>';

        }

        echo '</div></div></nav>';
    }

    public static function get_notices_ui( $notices){
        $notifVisibility = "";

        $publishedNoticesCount = 0;
        $currentTime = floatval( time()*1000);
        $noticesUI = "";
        foreach( $notices as $notice){
            $endTime = $notice->expirationTime;
            if( $endTime == null)
                $endTime = $currentTime;
            if( $currentTime >= $notice->publishedTime && $currentTime <= $endTime)
            {
                $publishedNoticesCount++;
                $textColor = "text-info";
                if( $notice->priority == NotificationPriority::LOW)
                    $textColor = "text-primary";
                elseif( $notice->priority ==NotificationPriority::NORMAL)
                    $textColor = "text-warning";
                elseif( $notice->priority == NotificationPriority::HIGH)
                    $textColor = "text-danger";
                $noticesUI .= '
                <div class="notification">
                    <div class="notification-title ' . $textColor . '">' . $notice->title . '</div>
                    <div class="notification-description"><strong></strong>' . $notice->notificationMessage . '</div>
                    <div class="notification-ago">' . date("m/d/Y h:i:s A T", $notice->publishedTime/1000) . '</div>
                    <div class="notification-icon"></div>
                </div> <!-- / .notification -->
                ';
            }
        }

        $countOfNotices = $publishedNoticesCount;
        $newNotices = 0;
        if( Session::has("notice-count")){
            $newNotices = $countOfNotices - Session::get("notice-count");
        }
        else
            $newNotices = $countOfNotices;

        if( !$newNotices)
            $notifVisibility = "hide";

        $noticesUI = '<li clas="dropdown" style="color:#fff; relative">' .
                        '<a href="#" class="dropdown-toggle notif-link" data-toggle="dropdown">' .
                        '<span class="glyphicon glyphicon-bell notif-bell"></span>' .
                        '<span class="notif-num ' . $notifVisibility . '" data-total-notices="' . $countOfNotices . '">' . $newNotices . '</span>'.
                        '<div class="dropdown-menu widget-notifications no-padding" style="width: 300px"><div class="slimScrollDiv" style="position: relative; overflow-y: scroll; overflow-x:hidden; width: auto; max-height: 250px;"><div class="notifications-list" id="main-navbar-notifications" style=" width: auto; max-height: 250px;">'

                    . $noticesUI;

        
        $noticesUI .= '
        </div><div class="slimScrollBar" style="width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 195.925px; background: rgb(0, 0, 0);"></div>

        <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; opacity: 0.2; z-index: 90; right: 1px; background: rgb(51, 51, 51);"></div></div> <!-- / .notifications-list -->
        <a href="#" class="notifications-link"><!--MORE NOTIFICATIONS--></a>
        </div>'.
        '</a>'.
            '</li>';

        return $noticesUI;
    }   

    /**
     * Add attributes to the HTTP header.
     */
    public static function create_http_header()
    {
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }

    /**
     * Open the XML file containing the community token
     * @param $tokenFilePath
     * @throws Exception
     */
    public static function open_tokens_file($tokenFilePath)
    {
        if (file_exists($tokenFilePath)) {
            $tokenFile = simplexml_load_file($tokenFilePath);
        } else {
            throw new Exception('Error: Cannot connect to tokens database!');
        }


        if (!$tokenFile) {
            throw new Exception('Error: Cannot open tokens database!');
        }
    }

    /**
     * Get All Notifications for a gateway
     * @param 
     * 
     */
    public static function get_all_notices(){
        return Airavata::getAllNotifications( Session::get('authz-token'), Session::get("gateway_id"));
    }

    public static function get_notice_priorities(){
        return NotificationPriority::$__names;
    }
}

