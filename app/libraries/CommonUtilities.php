<?php

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
        if (Session::has('loggedin') && (Session::has('authorizeduser') || Session::has('admin')
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

//            if (Session::has("admin")) {
//                $menus['Compute Resource'] = array
//                (
//                    array('label' => 'Register', 'url' => URL::to('/') . '/cr/create', "nav-active" => "compute-resource"),
//                    array('label' => 'Browse', 'url' => URL::to('/') . '/cr/browse', "nav-active" => "compute-resource")
//                );
//                $menus['App Catalog'] = array
//                (
//                    array('label' => 'Module', 'url' => URL::to('/') . '/app/module', "nav-active" => "app-catalog"),
//                    array('label' => 'Interface', 'url' => URL::to('/') . '/app/interface', "nav-active" => "app-catalog"),
//                    array('label' => 'Deployment', 'url' => URL::to('/') . '/app/deployment', "nav-active" => "app-catalog")
//                );
//            }

            $menus['Help'] = array
            (
                array('label' => 'Report Issue', 'url' => '#', "nav-active", ""),
                array('label' => 'Request Feature', 'url' => '#', "nav-active", "")
            );
        }

        echo '<nav class="navbar navbar-inverse navbar-static-top" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                       <span class="sr-only">Toggle navigation</span>
                       <span class="icon-bar"></span>
                       <span class="icon-bar"></span>
                       <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="' . URL::to('home') . '" title="PHP Gateway with Airavata">PGA</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
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


        echo '</ul>

        <ul class="nav navbar-nav navbar-right">';

        // right-aligned content

        if (Session::has('loggedin')) {
            $active = "";
            if (Session::has("nav-active")) {
                if ("user-console" == Session::get("nav-active"))
                    $active = " active ";
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
}

