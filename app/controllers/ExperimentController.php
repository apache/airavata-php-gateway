<?php

use Airavata\Model\Status\JobState;
use Airavata\Model\Group\ResourceType;

class ExperimentController extends BaseController
{

    /**
     * Limit used in fetching paginated results
     * @var int
     */
    var $limit = 20;

    /**
     *    Instantiate a new ExperimentController Instance
     **/

    public function __construct()
    {
        $this->beforeFilter('verifylogin');
        $this->beforeFilter('verifyauthorizeduser');
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


            $clonedExp = false; $savedExp = false;
            if( Input::has("clonedExp"))
                $clonedExp = true;
            if( Input::has("savedExp"))
                $savedExp = true;

            // Condition added to deal with php ini default value set for post_max_size issue.
            $allowedFileSize = Config::get('pga_config.airavata')["server-allowed-file-size"];
            $serverLimit = intval( ini_get( 'post_max_size') );
            if( $serverLimit < $allowedFileSize)
                $allowedFileSize = $serverLimit;


            $experimentInputs = array(
                "clonedExp" => $clonedExp,
                "savedExp" => $savedExp,
                "disabled" => ' disabled',
                "experimentName" => $_POST['experiment-name'],
                "experimentDescription" => $_POST['experiment-description'] . ' ',
                "project" => $_POST['project'],
                "application" => $_POST['application'],
                "echo" => ($_POST['application'] == 'Echo') ? ' selected' : '',
                "wrf" => ($_POST['application'] == 'WRF') ? ' selected' : '',
                "queueDefaults" => $queueDefaults,
                "advancedOptions" => Config::get('pga_config.airavata')["advanced-experiment-options"],
                "computeResources" => $computeResources,
                "resourceHostId" => null,
                "advancedOptions" => Config::get('pga_config.airavata')["advanced-experiment-options"],
                "allowedFileSize" => $allowedFileSize
            );

            if(Config::get('pga_config.airavata')["data-sharing-enabled"]){
                $users = SharingUtilities::getProfilesForSharedUsers($_POST['project'], ResourceType::PROJECT);
                $owner = array();

                return View::make("experiment/create-complete", array("expInputs" => $experimentInputs,
                    "users" => json_encode($users), "owner" => json_encode($owner),
                    "canEditSharing" => true, "updateSharingViaAjax" => false));
            }else{
                return View::make("experiment/no-sharing-create-complete", array("expInputs" => $experimentInputs));
            }

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
        if(isset($_GET['isAutoRefresh']) && $_GET['isAutoRefresh'] == 'true'){
            $autoRefresh = true;
        }else{
            $autoRefresh = false;
        }
        if ($experiment != null) {
            //viewing experiments of other gateways is not allowed if user is not super admin
            if( $experiment->gatewayId != Session::get("gateway_id") && !Session::has("super-admin")){
                Session::put("permissionDenied", true);
                CommonUtilities::print_error_message('It seems that you do not have permissions to view this experiment or it belongs to another gateway.');
                if (Input::has("dashboard"))
                    return View::make("partials/experiment-info", array("invalidExperimentId" => 1, "users" => json_encode(array())));
                else
                    return View::make("experiment/summary", array("invalidExperimentId" => 1, "users" => json_encode(array())));
            }
            else
                Session::forget("permissionDenied");


            $project = null;
            if(Config::get('pga_config.airavata')["data-sharing-enabled"]){
                if (SharingUtilities::userCanRead(Session::get("username"), $experiment->projectId, ResourceType::PROJECT)) {
                    $project = ProjectUtilities::get_project($experiment->projectId);
                }
            } else {
                $project = ProjectUtilities::get_project($experiment->projectId);
            }
            $expVal = ExperimentUtilities::get_experiment_values($experiment);
            $jobDetails = ExperimentUtilities::get_job_details($experiment->experimentId);
//            var_dump( $jobDetails); exit;
            foreach( $jobDetails as $index => $jobDetail){
                if(isset($jobDetail->jobStatuses)){
                      $jobDetails[ $index]->jobStatuses[0]->jobStateName = JobState::$__names[$jobDetail->jobStatuses[0]->jobState];
                }
                else{
                    $jobDetails[ $index]->jobStatuses = [new stdClass()];
                    $jobDetails[ $index]->jobStatuses[0]->jobStateName = null;
                }
            }
            $expVal["jobDetails"] = $jobDetails;

            $writeableProjects = ProjectUtilities::get_all_user_writeable_projects(Session::get("gateway_id"), Session::get("username"));

            $data = array(
                "expId" => Input::get("expId"),
                "experiment" => $experiment,
                "project" => $project,
                "jobDetails" => $jobDetails,
                "expVal" => $expVal,
                "autoRefresh"=> $autoRefresh,
                "writeableProjects" => $writeableProjects
            );
            if(Config::get('pga_config.airavata')["data-sharing-enabled"]){
                $users = SharingUtilities::getProfilesForSharedUsers(Input::get("expId"), ResourceType::EXPERIMENT);

                $owner = array();
                $projectOwner = array();
                if (strcmp(Session::get("username"), $experiment->userName) !== 0) {
                    $owner[$experiment->userName] = $users[$experiment->userName];
                    $users = array_diff_key($users, $owner);
                }
                // TODO: figure out the owner of the project using sharing API
                if ($project != null && strcmp(Session::get("username"), $project->owner) !== 0) {
                    $projectOwner[$project->owner] = $users[$project->owner];
                    $users = array_diff_key($users, $projectOwner);
                }
                // Only allow editing sharing on the summary page if the owner
                // and the experiment isn't editable. If the experiment is
                // editable, the sharing can be edited on the edit page.
                $canEditSharing = $this->isExperimentOwner($experiment, Session::get("username")) && !$expVal["editable"];
                $data['can_write'] = SharingUtilities::userCanWrite(Session::get("username"), $experiment->experimentId, ResourceType::EXPERIMENT);
                $data["users"] = json_encode($users);
                $data["owner"] = json_encode($owner);
                $data["projectOwner"] = json_encode($projectOwner);
                $data["canEditSharing"] = $canEditSharing;
                // The summary page has it's own Update Sharing button
                $data["updateSharingViaAjax"] = true;
            }

            if( Input::has("dashboard"))
            {
                $detailedExperiment = ExperimentUtilities::get_detailed_experiment( $_GET['expId']);
                $data["detailedExperiment"] = $detailedExperiment;
            }

            if (Request::ajax()) {
                //admin wants to see an experiment summary
                if (Input::has("dashboard")) {
                    $data["dashboard"] = true;
                    return View::make("partials/experiment-info", $data);
                } else
                    return json_encode($data);
            } else {
                return View::make("experiment/summary", $data);
            }
        } else {
            if (Input::has("dashboard"))
                return View::make("partials/experiment-info", array("invalidExperimentId" => 1, "users" => json_encode(array())));
            else
                return View::make("experiment/summary", array("invalidExperimentId" => 1, "users" => json_encode(array())));
        }
    }

    public function expChange()
    {
        //var_dump( Input::all() ); exit;
        $experiment = ExperimentUtilities::get_experiment(Input::get('expId'));
        $expVal = ExperimentUtilities::get_experiment_values($experiment);
        $expVal["jobState"] = ExperimentUtilities::get_job_status($experiment);
        /*if (isset($_POST['save']))
        {
            $updatedExperiment = CommonUtilities::apply_changes_to_experiment($experiment);

            CommonUtilities::update_experiment($experiment->experimentId, $updatedExperiment);
        }*/
        if (isset($_POST['launch'])) {
            ExperimentUtilities::launch_experiment($experiment->experimentId);
            return Redirect::to('experiment/summary?expId=' . $experiment->experimentId);
        } elseif (isset($_POST['cancel'])) {
            ExperimentUtilities::cancel_experiment($experiment->experimentId);
            return Redirect::to('experiment/summary?expId=' . $experiment->experimentId);
        } elseif (isset($_POST['update-sharing'])) {
            if(Config::get('pga_config.airavata')["data-sharing-enabled"]){
                $share = $_POST['share-settings'];
                ExperimentUtilities::update_experiment_sharing($experiment->experimentId, json_decode($share));
            }
            return Redirect::to('experiment/summary?expId=' . $experiment->experimentId);
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
        $expVal = ExperimentUtilities::get_experiment_values($experiment);
        $expVal["jobState"] = ExperimentUtilities::get_job_status($experiment);

        $computeResources = CRUtilities::create_compute_resources_select($experiment->executionId, $expVal['scheduling']->resourceHostId);

        $userComputeResourcePreferences = URPUtilities::get_all_user_compute_resource_prefs();
        $userHasComputeResourcePreference = array_key_exists($expVal['scheduling']->resourceHostId, $userComputeResourcePreferences);

        $clonedExp = false; $savedExp = false;
        if( Input::has("clonedExp"))
            $clonedExp = true;
        if( Input::has("savedExp"))
            $savedExp = true;

        $experimentInputs = array(
            "clonedExp" => $clonedExp,
            "savedExp" => $savedExp,
            "disabled" => ' ',
            "experimentName" => $experiment->experimentName,
            "experimentDescription" => $experiment->description,
            "application" => $experiment->executionId,
            "autoSchedule" => $experiment->userConfigurationData->airavataAutoSchedule,
            "userDN" => $experiment->userConfigurationData->userDN,
            "userHasComputeResourcePreference" => $userHasComputeResourcePreference,
            "useUserCRPref" => $experiment->userConfigurationData->useUserCRPref,
            "allowedFileSize" => Config::get('pga_config.airavata')["server-allowed-file-size"],
            'experiment' => $experiment,
            "queueDefaults" => $queueDefaults,
            'computeResources' => $computeResources,
            "resourceHostId" => $expVal['scheduling']->resourceHostId,
            'project' => $experiment->projectId,
            'expVal' => $expVal,
            'cloning' => true,
            'advancedOptions' => Config::get('pga_config.airavata')["advanced-experiment-options"]
        );

        if(Config::get('pga_config.airavata')["data-sharing-enabled"]){
            if (SharingUtilities::userCanWrite(Session::get("username"), $_GET['expId'], ResourceType::EXPERIMENT) === true) {
                $users = SharingUtilities::getProfilesForSharedUsers($_GET['expId'], ResourceType::EXPERIMENT);

                $owner = array();
                if (strcmp(Session::get("username"), $experiment->userName) !== 0) {
                    $owner[$experiment->userName] = $users[$experiment->userName];
                    $users = array_diff_key($users, $owner);
                }
                $canEditSharing = $this->isExperimentOwner($experiment, Session::get('username'));

                return View::make("experiment/edit", array("expInputs" => $experimentInputs,
                    "users" => json_encode($users), "owner" => json_encode($owner),
                    "canEditSharing" => $canEditSharing,
                    "updateSharingViaAjax" => false
                ));
            }
            else {
                Redirect::to("experiment/summary?expId=" . $experiment->experimentId)->with("error", "You do not have permission to edit this experiment");
            }
        }else {
            return View::make("experiment/no-sharing-edit", array("expInputs" => $experimentInputs));
        }
    }

    public function cloneExperiment()
    {
        try{
            $cloneId = ExperimentUtilities::clone_experiment(Input::get('expId'), Input::get('projectId'));
            return Redirect::to('experiment/edit?expId=' . $cloneId . "&clonedExp=true");
        }catch (Exception $ex){
            return Redirect::to("experiment/summary?expId=" . Input::get('expId'))
                ->with("cloning-error", "Failed to clone experiment: " . $ex->getMessage());
        }
    }

    public function editSubmit()
    {
        $experiment = ExperimentUtilities::get_experiment(Input::get('expId')); // update local experiment variable
        $updatedExperiment = ExperimentUtilities::apply_changes_to_experiment($experiment, Input::all());

        if(Config::get('pga_config.airavata')["data-sharing-enabled"]){
            if (SharingUtilities::userCanWrite(Session::get("username"), Input::get('expId'), ResourceType::EXPERIMENT)) {
                if (isset($_POST['save']) || isset($_POST['launch'])) {

                    ExperimentUtilities::update_experiment($experiment->experimentId, $updatedExperiment);

                    if (isset($_POST['save'])) {
                        $experiment = ExperimentUtilities::get_experiment(Input::get('expId')); // update local experiment variable
                    }
                    if (isset($_POST['launch'])) {
                        ExperimentUtilities::launch_experiment($experiment->experimentId);
                    }

                    return Redirect::to('experiment/summary?expId=' . $experiment->experimentId);
                } else
                    return View::make("home");
            }
        }else{
            if (isset($_POST['save']) || isset($_POST['launch'])) {

                ExperimentUtilities::update_experiment($experiment->experimentId, $updatedExperiment);

                if (isset($_POST['save'])) {
                    $experiment = ExperimentUtilities::get_experiment(Input::get('expId')); // update local experiment variable
                }
                if (isset($_POST['launch'])) {
                    ExperimentUtilities::launch_experiment($experiment->experimentId);
                }

                return Redirect::to('experiment/summary?expId=' . $experiment->experimentId);
            } else
                return View::make("home");
        }
    }

    public function getQueueView()
    {
        $computeResourceId = Input::get("crId");
        $queues = ExperimentUtilities::getQueueDatafromResourceId($computeResourceId);
        $queueDefaults = array("queueName" => Config::get('pga_config.airavata')["queue-name"],
            "nodeCount" => Config::get('pga_config.airavata')["node-count"],
            "cpuCount" => Config::get('pga_config.airavata')["total-cpu-count"],
            "wallTimeLimit" => Config::get('pga_config.airavata')["wall-time-limit"]
        );

        $userComputeResourcePreferences = URPUtilities::get_all_user_compute_resource_prefs();
        $userHasComputeResourcePreference = array_key_exists($computeResourceId, $userComputeResourcePreferences);
        if ($userHasComputeResourcePreference)
        {
            $queueDefaults["queueName"] = $userComputeResourcePreferences[$computeResourceId]->preferredBatchQueue;
        }
        return View::make("partials/experiment-queue-block", array("queues" => $queues, "queueDefaults" => $queueDefaults,
            "useUserCRPref" => $userHasComputeResourcePreference,
            "userHasComputeResourcePreference" => $userHasComputeResourcePreference));
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

        if(Config::get('pga_config.airavata')["data-sharing-enabled"]){
            $can_write = array();
            foreach ($expContainer as $experiment) {
                $can_write[$experiment['experiment']->experimentId] = SharingUtilities::userCanWrite(Session::get("username"), $experiment['experiment']->experimentId, ResourceType::EXPERIMENT);
            }

            return View::make('experiment/browse', array(
                'input' => Input::all(),
                'pageNo' => $pageNo,
                'limit' => $this->limit,
                'expStates' => $experimentStates,
                'expContainer' => $expContainer,
                'can_write' => $can_write
            ));
        }else{
            return View::make('experiment/no-sharing-browse', array(
                'input' => Input::all(),
                'pageNo' => $pageNo,
                'limit' => $this->limit,
                'expStates' => $experimentStates,
                'expContainer' => $expContainer
            ));
        }
    }

    /**
     * Generate JSON containing permissions information for this project.
     *
     * This function retrieves the user profile and permissions for every user
     * other than the client that has access to the project. In the event that
     * the project does not exist, return an error message.
     */
    public function sharedUsers()
    {
        if (Session::has("authz-token") && array_key_exists('resourceId', $_GET)) {
            return Response::json(SharingUtilities::getProfilesForSharedUsers($_GET['resourceId'], ResourceType::EXPERIMENT));
        }
        else {
            return Response::json(array("error" => "Error: No project specified"));
        }
    }

    public function unsharedUsers()
    {
        if (Session::has("authz-token") && array_key_exists('resourceId', $_GET)) {
            return Response::json(SharingUtilities::getProfilesForUnsharedUsers($_GET['resourceId'], ResourceType::EXPERIMENT));
        }
        else {
            return Response::json(array("error" => "Error: No experiment specified"));
        }
    }

    public function updateSharing()
    {
        try{
            // Convert the JSON array to an object
            $sharing_info = json_decode(json_encode(Input::json()->all()));
            ExperimentUtilities::update_experiment_sharing(Input::get('expId'), $sharing_info);
            return Response::json(array("success" => true));
        }catch (Exception $ex){
            Log::error("failed to update sharing for experiment", array(Input::all()));
            Log::error($ex);
            return Response::json(array("success" => false, "error" => "Error: failed to update sharing: " . $ex->getMessage()));
        }
    }

    private function isExperimentOwner($experiment, $username)
    {
        return strcmp($username, $experiment->userName) === 0;
    }
}

?>
