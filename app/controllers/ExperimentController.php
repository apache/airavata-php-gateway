<?php

class ExperimentController extends BaseController
{

    /**
     * Limit used in fetching paginated results
     * @var int
     */
    var $limit = 10;

    /**
     *    Instantiate a new ExperimentController Instance
     **/

    public function __construct()
    {
        $this->beforeFilter('verifylogin');
        Session::put("nav-active", "experiment");
    }

    public function createView()
    {
        Session::forget('exp_create_continue');
        return View::make('experiment/create');
    }

    public function createSubmit()
    {
        if (isset($_POST['continue'])) {
            Session::put('exp_create_continue', true);

            $computeResources = CRUtilities::create_compute_resources_select($_POST['application'], null);

            $queueDefaults = array("queueName" => Config::get('pga_config.airavata')["queue-name"],
                "nodeCount" => Config::get('pga_config.airavata')["node-count"],
                "cpuCount" => Config::get('pga_config.airavata')["total-cpu-count"],
                "wallTimeLimit" => Config::get('pga_config.airavata')["wall-time-limit"]
            );

            $experimentInputs = array(
                "disabled" => ' disabled',
                "experimentName" => $_POST['experiment-name'],
                "experimentDescription" => $_POST['experiment-description'] . ' ',
                "project" => $_POST['project'],
                "application" => $_POST['application'],
                "allowedFileSize" => Config::get('pga_config.airavata')["server-allowed-file-size"],
                "echo" => ($_POST['application'] == 'Echo') ? ' selected' : '',
                "wrf" => ($_POST['application'] == 'WRF') ? ' selected' : '',
                "queueDefaults" => $queueDefaults,
                "advancedOptions" => Config::get('pga_config.airavata')["advanced-experiment-options"],
                "computeResources" => $computeResources,
                "resourceHostId" => null,
                "advancedOptions" => Config::get('pga_config.airavata')["advanced-experiment-options"]
            );

            return View::make("experiment/create-complete", array("expInputs" => $experimentInputs));
        } else if (isset($_POST['save']) || isset($_POST['launch'])) {
            $expId = ExperimentUtilities::create_experiment();

            if (isset($_POST['launch']) && $expId) {
                ExperimentUtilities::launch_experiment($expId);
            }
            /* Not required.
            else
            {
                CommonUtilities::print_success_message("<p>Experiment {$_POST['experiment-name']} created!</p>" .
                    '<p>You will be redirected to the summary page shortly, or you can
                    <a href=' . URL::to('/') . '"/experiment/summary?expId=' . $expId . '">go directly</a> to experiment summary page.</p>');

            }*/
            return Redirect::to('experiment/summary?expId=' . $expId);
        } else
            return Redirect::to("home")->with("message", "Something went wrong here. Please file a bug report using the link in the Help menu.");
    }

    public function summary()
    {
        $experiment = ExperimentUtilities::get_experiment($_GET['expId']);
        if ($experiment != null) {
            $project = ProjectUtilities::get_project($experiment->projectID);
            $expVal = ExperimentUtilities::get_experiment_values($experiment, $project);
            $expVal["jobState"] = ExperimentUtilities::get_job_status($experiment);
            $jobDetails = ExperimentUtilities::get_job_details($experiment->experimentID);
            $transferDetails = ExperimentUtilities::get_transfer_details($experiment->experimentID);
            //var_dump( $jobDetails); exit;
            // User should not clone or edit a failed experiment. Only create clones of it.
            if ($expVal["experimentStatusString"] == "FAILED")
                $expVal["editable"] = false;

            $expVal["cancelable"] = false;
            if ($expVal["experimentStatusString"] == "LAUNCHED" || $expVal["experimentStatusString"] == "EXECUTING")
                $expVal["cancelable"] = true;

            $data = array(
                "expId" => Input::get("expId"),
                "experiment" => $experiment,
                "project" => $project,
                "jobDetails" => $jobDetails,
                "expVal" => $expVal
            );

            if (Request::ajax()) {
                //admin wants to see an experiment summary
                if (Input::has("dashboard")) {
                    $data["dashboard"] = true;
                    return View::make("partials/experiment-info", $data);
                } else
                    return json_encode($experiment);
            } else {
                return View::make("experiment/summary", $data);
            }
        } else {
            if (Input::has("dashboard"))
                return View::make("partials/experiment-info", array("invalidExperimentId" => 1));
            else
                return View::make("experiment/summary", array("invalidExperimentId" => 1));
        }
    }

    public function expCancel()
    {
        ExperimentUtilities::cancel_experiment(Input::get("expId"));

        return Redirect::to('experiment/summary?expId=' . Input::get("expId"));
    }

    public function expChange()
    {
        //var_dump( Input::all() ); exit;
        $experiment = ExperimentUtilities::get_experiment(Input::get('expId'));
        $project = ProjectUtilities::get_project($experiment->projectID);

        $expVal = ExperimentUtilities::get_experiment_values($experiment, $project);
        $expVal["jobState"] = ExperimentUtilities::get_job_status($experiment);
        /*if (isset($_POST['save']))
        {
            $updatedExperiment = CommonUtilities::apply_changes_to_experiment($experiment);

            CommonUtilities::update_experiment($experiment->experimentID, $updatedExperiment);
        }*/
        if (isset($_POST['launch'])) {
            ExperimentUtilities::launch_experiment($experiment->experimentID);
            return Redirect::to('experiment/summary?expId=' . $experiment->experimentID);
        } elseif (isset($_POST['cancel'])) {
            ExperimentUtilities::cancel_experiment($experiment->experimentID);
            return Redirect::to('experiment/summary?expId=' . $experiment->experimentID);

        }
    }

    public function editView()
    {
        $queueDefaults = array("queueName" => Config::get('pga_config.airavata')["queue-name"],
            "nodeCount" => Config::get('pga_config.airavata')["node-count"],
            "cpuCount" => Config::get('pga_config.airavata')["total-cpu-count"],
            "wallTimeLimit" => Config::get('pga_config.airavata')["wall-time-limit"]
        );

        $experiment = ExperimentUtilities::get_experiment($_GET['expId']);
        $project = ProjectUtilities::get_project($experiment->projectID);

        $expVal = ExperimentUtilities::get_experiment_values($experiment, $project);
        $expVal["jobState"] = ExperimentUtilities::get_job_status($experiment);

        $computeResources = CRUtilities::create_compute_resources_select($experiment->applicationId, $expVal['scheduling']->resourceHostId);

        $experimentInputs = array(
            "disabled" => ' ',
            "experimentName" => $experiment->name,
            "experimentDescription" => $experiment->description,
            "application" => $experiment->applicationId,
            "allowedFileSize" => Config::get('pga_config.airavata')["server-allowed-file-size"],
            'experiment' => $experiment,
            "queueDefaults" => $queueDefaults,
            'project' => $project,
            'expVal' => $expVal,
            'cloning' => true,
            'advancedOptions' => Config::get('pga_config.airavata')["advanced-experiment-options"],
            'computeResources' => $computeResources,
            "resourceHostId" => $expVal['scheduling']->resourceHostId,
            'project' => $project,
            'expVal' => $expVal,
            'cloning' => true,
            'advancedOptions' => Config::get('pga_config.airavata')["advanced-experiment-options"]
        );
        return View::make("experiment/edit", array("expInputs" => $experimentInputs));
    }

    public function cloneExperiment()
    {
        if (isset($_GET['expId'])) {
            $cloneId = ExperimentUtilities::clone_experiment($_GET['expId']);
            $experiment = ExperimentUtilities::get_experiment($cloneId);
            $project = ProjectUtilities::get_project($experiment->projectID);

            $expVal = ExperimentUtilities::get_experiment_values($experiment, $project);
            $expVal["jobState"] = ExperimentUtilities::get_job_status($experiment);

            return Redirect::to('experiment/edit?expId=' . $cloneId);
        }
    }

    public function editSubmit()
    {
        if (isset($_POST['save']) || isset($_POST['launch'])) {
            $experiment = ExperimentUtilities::get_experiment(Input::get('expId')); // update local experiment variable
            $updatedExperiment = ExperimentUtilities::apply_changes_to_experiment($experiment, Input::all());

            ExperimentUtilities::update_experiment($experiment->experimentID, $updatedExperiment);

            if (isset($_POST['save'])) {
                $experiment = ExperimentUtilities::get_experiment(Input::get('expId')); // update local experiment variable
            }
            if (isset($_POST['launch'])) {
                ExperimentUtilities::launch_experiment($experiment->experimentID);
            }

            return Redirect::to('experiment/summary?expId=' . $experiment->experimentID);
        } else
            return View::make("home");
    }

    public function getQueueView()
    {
        $queues = ExperimentUtilities::getQueueDatafromResourceId(Input::get("crId"));
        $queueDefaults = array("queueName" => Config::get('pga_config.airavata')["queue-name"],
            "nodeCount" => Config::get('pga_config.airavata')["node-count"],
            "cpuCount" => Config::get('pga_config.airavata')["total-cpu-count"],
            "wallTimeLimit" => Config::get('pga_config.airavata')["wall-time-limit"]
        );
        return View::make("partials/experiment-queue-block", array("queues" => $queues, "queueDefaults" => $queueDefaults));
    }

    public function browseView()
    {
        $pageNo = Input::get('pageNo');
        $prev = Input::get('prev');
        $isSearch = Input::get('search');
        if (empty($pageNo) || isset($isSearch) ) {
            $pageNo = 1;
        } else {
            if (isset($prev)) {
                $pageNo -= 1;
            } else {
                $pageNo += 1;
            }
        }

        $expContainer = ExperimentUtilities::get_expsearch_results_with_pagination(Input::all(), $this->limit,
            ($pageNo - 1) * $this->limit);
        $experimentStates = ExperimentUtilities::getExpStates();

        return View::make('experiment/browse', array(
            'input' => Input::all(),
            'pageNo' => $pageNo,
            'limit' => $this->limit,
            'expStates' => $experimentStates,
            'expContainer' => $expContainer
        ));
    }
}

?>
