<?php

use Airavata\API\Error\AiravataClientException;
use Airavata\API\Error\AiravataSystemException;
use Airavata\API\Error\ExperimentNotFoundException;
use Airavata\API\Error\InvalidRequestException;
use Airavata\Facades\Airavata;
use Airavata\Model\Application\Io\DataType;
use Airavata\Model\AppCatalog\AppInterface\ApplicationInterfaceDescription;
use Airavata\Model\Scheduling\ComputationalResourceSchedulingModel;
use Airavata\Model\Experiment\ExperimentModel;
use Airavata\Model\Status\ExperimentState;
use Airavata\Model\Status\ProcessState;
use Airavata\Model\Status\JobState;
use Airavata\Model\Status\TaskState;
use Airavata\Model\Task\TaskTypes;
use Airavata\Model\Experiment\UserConfigurationDataModel;
use Airavata\Model\Data\Replica\DataProductModel;
use Airavata\Model\Data\Replica\DataProductType;
use Airavata\Model\Data\Replica\DataReplicaLocationModel;
use Airavata\Model\Data\Replica\ReplicaLocationCategory;
use Airavata\Model\Data\Replica\ReplicaPersistentType;
use Airavata\Model\Application\Io\InputDataObjectType;
use Airavata\Model\Group\ResourceType;
use Airavata\Model\Group\ResourcePermissionType;

class ExperimentUtilities
{
    private static $experimentPath;

    private static $relativeExperimentDataDir;

    /**
     * Launch the experiment with the given ID
     * @param $expId
     */
    public static function launch_experiment($expId)
    {
        try {
            $gatewayId = Config::get('pga_config.airavata')['gateway-id'];
            Airavata::launchExperiment(Session::get('authz-token'), $expId, $gatewayId);
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('<p>There was a problem launching the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
        } catch (ExperimentNotFoundException $enf) {
            CommonUtilities::print_error_message('<p>There was a problem launching the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>ExperimentNotFoundException: ' . $enf->getMessage() . '</p>');
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('<p>There was a problem launching the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>AiravataClientException: ' . $ace->getMessage() . '</p>');
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('<p>There was a problem launching the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>AiravataSystemException: ' . $ase->getMessage() . '</p>');
        } catch (Exception $e) {
            CommonUtilities::print_error_message('<p>There was a problem launching the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Exception: ' . $e->getMessage() . '</p>');
        }
    }

    /**
     * List the experiment's input files
     * @param $experiment
     */
    public static function list_input_files($experimentInputs)
    {
        //$experimentInputs = $experiment->experimentInputs;

        //showing experiment inputs in the order defined by the admins.
        $order = array();
        foreach ($experimentInputs as $index => $input) {
            $order[$index] = $input->inputOrder;
        }
        array_multisort($order, SORT_ASC, $experimentInputs);

        $optFilesHtml = "";

        if( count( $experimentInputs) > 0 ) {
            foreach ($experimentInputs as $input) {
                $matchingAppInput = null;
                if ($input->type == DataType::URI) {

                    if (strpos($input->value, "airavata-dp") === 0) {
                        $dataProductModel = Airavata::getDataProduct(Session::get('authz-token'), $input->value);
                        $currentInputPath = "";
                        foreach ($dataProductModel->replicaLocations as $rp) {
                            if ($rp->replicaLocationCategory == ReplicaLocationCategory::GATEWAY_DATA_STORE) {
                                $currentInputPath = $rp->filePath;
                                break;
                            }
                        }
                        $fileName = basename($currentInputPath);
                    } else {
                        $fileName = basename($input->value);
                    }

                    echo '<p>' . $input->name . ':&nbsp;<a target="_blank" href="' . URL::to("/") . '/download/?id='
                        . $input->value . '">' . $fileName . ' <span class="glyphicon glyphicon-new-window"></span></a></p>';

                }else if($input->type == DataType::URI_COLLECTION) {
                    $uriList = $input->value;
                    $uriList = preg_split('/,/', $uriList);

                    foreach($uriList as $uri){
                        if (strpos($uri, "airavata-dp") === 0) {
                            $dataProductModel = Airavata::getDataProduct(Session::get('authz-token'), $uri);
                            $currentInputPath = "";
                            foreach ($dataProductModel->replicaLocations as $rp) {
                                if ($rp->replicaLocationCategory == ReplicaLocationCategory::GATEWAY_DATA_STORE) {
                                    $currentInputPath = $rp->filePath;
                                    break;
                                }
                            }
                            $fileName = basename($currentInputPath);
                        } else {
                            $fileName = basename($input->value);
                        }

                        $optFilesHtml = $optFilesHtml . '<a target="_blank" href="' . URL::to("/") . '/download/?id='
                            . $uri . '">' . $fileName . ' <span class="glyphicon glyphicon-new-window"></span></a>&nbsp;';

                    }

                } elseif ($input->type == DataType::STRING || $input->type == DataType::INTEGER
                    || $input->type == DataType::FLOAT) {
                    echo '<p>' . $input->name . ':&nbsp;' . $input->value . '</p>';
                }
            }

            if(strlen($optFilesHtml) > 0)
                echo '<p> Optional File Inputs:&nbsp;' . $optFilesHtml;
        }
    }

    /**
     * List the process's input files
     * @param $experiment
     */
    public static function list_process_input_files($processInputs)
    {
        $order = array();
        foreach ($processInputs as $index => $input) {
            $order[$index] = $input->inputOrder;
        }
        array_multisort($order, SORT_ASC, $processInputs);

        foreach ($processInputs as $input) {
            $matchingAppInput = null;

            if ($input->type == DataType::URI) {
                $dataRoot = Config::get("pga_config.airavata")["experiment-data-absolute-path"];
                if(!ExperimentUtilities::endsWith($dataRoot, "/"))
                    $dataRoot = $dataRoot . "/";
                $filePath = str_replace($dataRoot, "", parse_url($input->value, PHP_URL_PATH));
                echo '<p>' . $input->name . ':&nbsp;<a target="_blank" href="' . URL::to("/")
                    . '/download/?path=' . $filePath . '">' . basename($filePath) . ' <span class="glyphicon glyphicon-new-window"></span></a></p>';
            }elseif ($input->type == DataType::STRING || $input->type == DataType::INTEGER
                || $input->type == DataType::FLOAT) {
                echo '<p>' . $input->name . ': ' . $input->value . '</p>';
            }
        }
    }

    /**
     * List the process's output files
     * @param $experiment
     */
    public static function list_process_output_files($outputs, $status){
        if ($status != ProcessState::COMPLETED)
            echo "Process hasn't completed. Process Status is : " . ProcessState::$__names[ $status] . '<br/>';

        foreach ((array)$outputs as $output) {
            if ($output->type == DataType::URI || $output->type == DataType::STDOUT || $output->type == DataType::STDERR) {
                $dataRoot = Config::get("pga_config.airavata")["experiment-data-absolute-path"];
                if(!ExperimentUtilities::endsWith($dataRoot, "/"))
                    $dataRoot = $dataRoot . "/";
                $filePath = str_replace($dataRoot, "", parse_url($output->value, PHP_URL_PATH));
                echo '<p>' . $output->name . ':&nbsp;<a target="_blank" href="' . URL::to("/")
                    . '/download/?path=' . $filePath . '">' . basename($filePath) . ' <span class="glyphicon glyphicon-new-window"></span></a></p>';
            }
            elseif ($output->type == DataType::STRING) {
                echo '<p>' . $output->value . '</p>';
            }
            else
                echo 'output : '. $output;
        }
    }

    private  static function endsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    /**
     * Get the experiment with the given ID
     * @param $expId
     * @return null
     */
    public static function get_experiment($expId)
    {

        try {
            return Airavata::getExperiment(Session::get('authz-token'), $expId);
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
        } catch (ExperimentNotFoundException $enf) {
            CommonUtilities::print_error_message('<p>ExperimentNotFoundException: ' . $enf->getMessage() . '</p>');
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('AiravataClientException: ' . $ace->getMessage() . '</p>');
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('AiravataSystemException: ' . $ase->getMessage() . '</p>');
        } catch (TTransportException $tte) {
            CommonUtilities::print_error_message('TTransportException: ' . $tte->getMessage() . '</p>');
        } catch (Exception $e) {
            CommonUtilities::print_error_message('Exception: ' . $e->getMessage() . '</p>');
        }

    }

    /**
     * Get the detailed tree of an experiment with the given ID
     * @param $expId
     * @return null
     */
    public static function get_detailed_experiment($expId)
    {

        try {
            return Airavata::getDetailedExperimentTree(Session::get('authz-token'), $expId);
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('<p>There was a problem getting the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
        } catch (ExperimentNotFoundException $enf) {
            CommonUtilities::print_error_message('<p>There was a problem getting the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>ExperimentNotFoundException: ' . $enf->getMessage() . '</p>');
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('<p>There was a problem getting the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>AiravataClientException: ' . $ace->getMessage() . '</p>');
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('<p>There was a problem getting the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>AiravataSystemException: ' . $ase->getMessage() . '</p>');
        } catch (TTransportException $tte) {
            CommonUtilities::print_error_message('<p>There was a problem getting the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>TTransportException: ' . $tte->getMessage() . '</p>');
        } catch (Exception $e) {
            CommonUtilities::print_error_message('<p>There was a problem getting the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Exception: ' . $e->getMessage() . '</p>');
        }

    }

    /**
     * Create and configure a new Experiment
     * @return Experiment
     */
    public static function assemble_experiment()
    {
        $experimentInputs = array();

        $scheduling = new ComputationalResourceSchedulingModel();
        $scheduling->totalCPUCount = $_POST['cpu-count'];
        $scheduling->nodeCount = $_POST['node-count'];
        $scheduling->queueName = $_POST['queue-name'];
        $scheduling->wallTimeLimit = $_POST['wall-time'];
        $scheduling->totalPhysicalMemory = $_POST['total-physical-memory'];
        $scheduling->resourceHostId = $_POST['compute-resource'];
//        $scheduling->staticWorkingDir = $_POST['static-working-dir'];

        $userConfigData = new UserConfigurationDataModel();
        $userConfigData->computationalResourceScheduling = $scheduling;
        $userConfigData->storageId =  Config::get('pga_config.airavata')['gateway-data-store-resource-id'];
        $userConfigData->airavataAutoSchedule = isset($_POST['enable-auto-scheduling']) ? true : false;
        if (isset($_POST["userDN"])) {
            $userConfigData->generateCert = 1;
            $userConfigData->userDN = $_POST["userDN"];
        }

        $applicationInputs = AppUtilities::get_application_inputs($_POST['application']);
        $experimentInputs = ExperimentUtilities::process_inputs($_POST['project'], $_POST['experiment-name'], $applicationInputs, $experimentInputs);

        if (ExperimentUtilities::$experimentPath == null) {
            ExperimentUtilities::create_experiment_folder_path($_POST['project'], $_POST['experiment-name']);
        }
        $userConfigData->experimentDataDir = ExperimentUtilities::$experimentPath;

        $experiment = new ExperimentModel();
        // required
        $experiment->projectId = $_POST['project'];
        $experiment->userName = Session::get('username');
        $experiment->name = $_POST['experiment-name'];
        $experiment->gatewayId = Config::get('pga_config.airavata')['gateway-id'];
        $experiment->experimentName = $_POST['experiment-name'];

        // optional
        $experiment->description = $_POST['experiment-description'];
        $experiment->executionId = $_POST['application'];
        $experiment->userConfigurationData = $userConfigData;
        $experiment->experimentInputs = $experimentInputs;
        if (isset($_POST["enableEmailNotification"])) {
            $experiment->enableEmailNotification = intval($_POST["enableEmailNotification"]);
            $experiment->emailAddresses = array_unique(array_filter($_POST["emailAddresses"], "trim"));
        }

        // adding default experiment outputs for now till prepoulated experiment template is not implemented.
        $experiment->experimentOutputs = AppUtilities::get_application_outputs($_POST["application"]);

        if ($experimentInputs) {
            return $experiment;
        }
    }


    /**
     * @param $applicationInputs
     * @param $experimentInputs
     * @internal param $environmentPath
     * @return array
     */
    public static function process_inputs($projectId, $experimentName, $applicationInputs, $experimentInputs)
    {
        $experimentAssemblySuccessful = true;
        $newExperimentInputs = array();

        ExperimentUtilities::create_experiment_folder_path($projectId, $experimentName);

        //sending application inputs in the order defined by the admins.
        $order = array();
        foreach ($applicationInputs as $index => $input) {
            $order[$index] = $input->inputOrder;
        }
        array_multisort($order, SORT_ASC, $applicationInputs);

        foreach ($applicationInputs as $applicationInput) {

            $experimentInput = $applicationInput;

            if (($applicationInput->type == DataType::STRING) ||
                ($applicationInput->type == DataType::INTEGER) ||
                ($applicationInput->type == DataType::FLOAT)
            ) {
                if (isset($_POST[$applicationInput->name]) && (trim($_POST[$applicationInput->name]) != '')) {
                    $experimentInput->value = $_POST[$applicationInput->name];
                    $experimentInput->type = $applicationInput->type;

                } else // use previous value
                {
                    $index = -1;
                    for ($i = 0; $i < sizeof($experimentInputs); $i++) {
                        if ($experimentInputs[$i]->name == $applicationInput->name) {
                            $index = $i;
                        }
                    }

                    if ($index >= 0) {
                        $experimentInput->value = $experimentInputs[$index]->value;
                        $experimentInput->type = $applicationInput->type;
                    }
                }
            } elseif ($applicationInput->type == DataType::URI) {
                if ($_FILES[$applicationInput->name]['name']) {
                    $file = $_FILES[$applicationInput->name];

                    //
                    // move file to experiment data directory
                    //
                    if (!empty($applicationInput->value)) {
                        $filePath = ExperimentUtilities::$experimentPath . $applicationInput->value;
                    } else {
                        $filePath = ExperimentUtilities::$experimentPath . $file['name'];
                    }

                    // check if file already exists
                    if (is_file($filePath)) {
                        unlink($filePath);

                        CommonUtilities::print_warning_message('Uploaded file already exists! Overwriting...');
                    }

                    $moveFile = move_uploaded_file($file['tmp_name'], $filePath);

                    if (!$moveFile) {
                        CommonUtilities::print_error_message('<p>Error moving uploaded file ' . $file['name'] . '!
                        Please try again later or report a bug using the link in the Help menu.</p>');
                        $experimentAssemblySuccessful = false;
                    }

                    $experimentInput->type = $applicationInput->type;
                    $dataProductModel = new DataProductModel();
                    $dataProductModel->gatewayId = Config::get("pga_config.airavata")["gateway-id"];
                    $dataProductModel->ownerName = Session::get("username");
                    $dataProductModel->productName = basename($filePath);
                    $dataProductModel->dataProductType = DataProductType::FILE;

                    $dataReplicationModel = new DataReplicaLocationModel();
                    $dataReplicationModel->storageResourceId = Config::get("pga_config.airavata")["gateway-data-store-resource-id"];
                    $dataReplicationModel->replicaName = basename($filePath) . " gateway data store copy";
                    $dataReplicationModel->replicaLocationCategory = ReplicaLocationCategory::GATEWAY_DATA_STORE;
                    $dataReplicationModel->replicaPersistentType = ReplicaPersistentType::TRANSIENT;
                    $hostName = $_SERVER['SERVER_NAME'];
                    $dataReplicationModel->filePath = "file://" . $hostName . ":" . $filePath;

                    $dataProductModel->replicaLocations[] = $dataReplicationModel;
                    $uri = Airavata::registerDataProduct(Session::get('authz-token'), $dataProductModel);
                    $experimentInput->value = $uri;
                } else {
                    $index = -1;
                    for ($i = 0; $i < sizeof($experimentInputs); $i++) {
                        if ($experimentInputs[$i]->name == $applicationInput->name) {
                            $index = $i;
                        }
                    }

                    if ($index >= 0) {
                        $experimentInput->value = $experimentInputs[$index]->value;
                        $experimentInput->type = $applicationInput->type;
                    }
                }
            } else {
                CommonUtilities::print_error_message('I cannot accept this input type yet!');
            }
            $newExperimentInputs[] = $experimentInput;
        }

        if(isset($_FILES['optInputFiles'])){
            $uriList = "";
            for($i=0; $i < count($_FILES['optInputFiles']['name']); $i++){
                if(!empty($_FILES['optInputFiles']['name'][$i])){
                    $filePath = ExperimentUtilities::$experimentPath . $_FILES['optInputFiles']['name'][$i];

                    // check if file already exists
                    if (is_file($filePath)) {
                        unlink($filePath);

                        CommonUtilities::print_warning_message('Uploaded file already exists! Overwriting...');
                    }

                    $moveFile = move_uploaded_file($_FILES['optInputFiles']['tmp_name'][$i], $filePath);

                    if (!$moveFile) {
                        CommonUtilities::print_error_message('<p>Error moving uploaded file ' . $_FILES['optInputFiles']['name'][$i] . '!
                        Please try again later or report a bug using the link in the Help menu.</p>');
                        $experimentAssemblySuccessful = false;
                    }

                    $dataProductModel = new DataProductModel();
                    $dataProductModel->gatewayId = Config::get("pga_config.airavata")["gateway-id"];
                    $dataProductModel->ownerName = Session::get("username");
                    $dataProductModel->productName = basename($filePath);
                    $dataProductModel->dataProductType = DataProductType::FILE;

                    $dataReplicationModel = new DataReplicaLocationModel();
                    $dataReplicationModel->storageResourceId = Config::get("pga_config.airavata")["gateway-data-store-resource-id"];
                    $dataReplicationModel->replicaName = basename($filePath) . " gateway data store copy";
                    $dataReplicationModel->replicaLocationCategory = ReplicaLocationCategory::GATEWAY_DATA_STORE;
                    $dataReplicationModel->replicaPersistentType = ReplicaPersistentType::TRANSIENT;
                    $hostName = $_SERVER['SERVER_NAME'];
                    $dataReplicationModel->filePath = "file://" . $hostName . ":" . $filePath;

                    $dataProductModel->replicaLocations[] = $dataReplicationModel;
                    $uri = Airavata::registerDataProduct(Session::get('authz-token'), $dataProductModel);
                    $uriList = $uriList . $uri . ",";
                }
            }

            if(strlen($uriList) > 0){
                $uriList = substr($uriList,0, strlen($uriList) - 1);
                $optInput = new InputDataObjectType();
                $optInput->name = "Optional-File-Input-List";
                $optInput->type = DataType::URI_COLLECTION;
                $optInput->value = $uriList;
                $newExperimentInputs[] = $optInput;
            }
        }

        if ($experimentAssemblySuccessful) {
            return $newExperimentInputs;
        } else {
            return false;
        }
    }


    public static function create_experiment_folder_path($projectId, $experimentName)
    {
        do {
            $projectId = substr($projectId, 0, -37);
            $projectId = preg_replace('/[^a-zA-Z0-9]+/', '_', $projectId);
            $experimentName = preg_replace('/[^a-zA-Z0-9]+/', '_', $experimentName);

            ExperimentUtilities::$relativeExperimentDataDir = "/" . Session::get('username') . "/" . $projectId . "/"
                        . $experimentName . time() . '/';
            ExperimentUtilities::$experimentPath = Config::get('pga_config.airavata')['experiment-data-absolute-path'] .
                ExperimentUtilities::$relativeExperimentDataDir;
        } while (is_dir(ExperimentUtilities::$experimentPath)); // if dir already exists, try again
        // create upload directory
        $old_umask = umask(0);
        if (!mkdir(ExperimentUtilities::$experimentPath, 0777, true)) {
            CommonUtilities::print_error_message('<p>Error creating upload directory!
            Please try again later or report a bug using the link in the Help menu.</p>');
            $experimentAssemblySuccessful = false;
        }
        umask($old_umask);
    }

    /**
     * Check the uploaded files for errors
     */
    public static function file_upload_successful()
    {
        $uploadSuccessful = true;

        foreach ($_FILES as $file) {
            //var_dump($file);
            if (!is_array($file) and $file['name']) {
                if ($file['error'] > 0) {
                    $uploadSuccessful = false;
                    CommonUtilities::print_error_message('<p>Error uploading file ' . $file['name'] . ' !
                    Please try again later or report a bug using the link in the Help menu.');
                }
            }else if(is_array($file) and $file['name']){
                for($i =0 ; $i< count($file['name']); $i++){
                    if ($file['error'][$i] > 0) {
                        $uploadSuccessful = false;
                        CommonUtilities::print_error_message('<p>Error uploading file ' . $file['name'][$i] . ' !
                    Please try again later or report a bug using the link in the Help menu.');
                    }
                }
            }


        }

        return $uploadSuccessful;
    }


    /**
     * Update the experiment with the given ID
     * @param $expId
     * @param $updatedExperiment
     */
    public static function update_experiment($expId, $updatedExperiment)
    {
        try {
            Airavata::updateExperiment(Session::get('authz-token'), $expId, $updatedExperiment);
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('<p>There was a problem updating the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
        } catch (ExperimentNotFoundException $enf) {
            CommonUtilities::print_error_message('<p>There was a problem updating the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>ExperimentNotFoundException: ' . $enf->getMessage() . '</p>');
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('<p>There was a problem updating the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>AiravataClientException: ' . $ace->getMessage() . '</p>');
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('<p>There was a problem updating the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>AiravataSystemException: ' . $ase->getMessage() . '</p>');
        }
    }


    /**
     * Clone the experiment with the given ID
     * @param $expId
     */
    public static function clone_experiment($expId)
    {
        try {
            //create new experiment to receive the clone
            $experiment = Airavata::getExperiment(Session::get('authz-token'), $expId);
            $cloneId = Airavata::cloneExperiment(Session::get('authz-token'), $expId, 'Clone of ' . $experiment->experimentName);
            //updating the experiment inputs and output path
            $experiment = Airavata::getExperiment(Session::get('authz-token'), $cloneId);
            $experimentInputs = $experiment->experimentInputs;

            ExperimentUtilities::create_experiment_folder_path($experiment->projectId, $experiment->experimentName);

            $hostName = $_SERVER['SERVER_NAME'];

            foreach ($experimentInputs as $experimentInput) {
                if ($experimentInput->type == DataType::URI) {
                    $hostPathConstant = 'file://' . $hostName . ':';
                    $dataProductModel = Airavata::getDataProduct(Session::get('authz-token'), $experimentInput->value);
                    $currentInputPath = "";
                    foreach ($dataProductModel->replicaLocations as $rp) {
                        if($rp->replicaLocationCategory == ReplicaLocationCategory::GATEWAY_DATA_STORE){
                            $currentInputPath = $rp->filePath;
                            break;
                        }
                    }
                    $currentInputPath = str_replace($hostPathConstant, '', $currentInputPath);
                    $parts = explode('/', rtrim($currentInputPath, '/'));
                    $fileName = array_pop($parts);
                    $newInputPath = ExperimentUtilities::$experimentPath . $fileName;
                    copy($currentInputPath, $newInputPath);

                    $dataProductModel = new DataProductModel();
                    $dataProductModel->gatewayId = Config::get("pga_config.airavata")["gateway-id"];
                    $dataProductModel->ownerName = Session::get("username");
                    $dataProductModel->productName = basename($newInputPath);
                    $dataProductModel->dataProductType = DataProductType::FILE;

                    $dataReplicationModel = new DataReplicaLocationModel();
                    $dataReplicationModel->storageResourceId = Config::get("pga_config.airavata")["gateway-data-store-resource-id"];
                    $dataReplicationModel->replicaName = basename($newInputPath) . " gateway data store copy";
                    $dataReplicationModel->replicaLocationCategory = ReplicaLocationCategory::GATEWAY_DATA_STORE;
                    $dataReplicationModel->replicaPersistentType = ReplicaPersistentType::TRANSIENT;
                    $hostName = $_SERVER['SERVER_NAME'];
                    $dataReplicationModel->filePath = "file://" . $hostName . ":" . $newInputPath;

                    $dataProductModel->replicaLocations[] = $dataReplicationModel;
                    $uri = Airavata::registerDataProduct(Session::get('authz-token'), $dataProductModel);
                    $experimentInput->value = $uri;
                }
            }
            $experiment->userConfigurationData->experimentDataDir = ExperimentUtilities::$experimentPath;
            Airavata::updateExperiment(Session::get('authz-token'), $cloneId, $experiment);
            return $cloneId;
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('<p>There was a problem cloning the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
        } catch (ExperimentNotFoundException $enf) {
            CommonUtilities::print_error_message('<p>There was a problem cloning the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>ExperimentNotFoundException: ' . $enf->getMessage() . '</p>');
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('<p>There was a problem cloning the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>AiravataClientException: ' . $ace->getMessage() . '</p>');
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('<p>There was a problem cloning the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>AiravataSystemException: ' . $ase->getMessage() . '</p>');
        } catch (TTransportException $tte) {
            CommonUtilities::print_error_message('<p>There was a problem cloning the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>TTransportException: ' . $tte->getMessage() . '</p>');
        }
    }

    /**
     * Cancel the experiment with the given ID
     * Cancel the experiment with the given ID
     * @param $expId
     */
    public static function cancel_experiment($expId)
    {

        try {
            Airavata::terminateExperiment(Session::get('authz-token'), $expId, Config::get('pga_config.airavata')["gateway-id"]);

            CommonUtilities::print_success_message("Experiment canceled!");
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('<p>There was a problem canceling the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
        } catch (ExperimentNotFoundException $enf) {
            CommonUtilities::print_error_message('<p>There was a problem canceling the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>ExperimentNotFoundException: ' . $enf->getMessage() . '</p>');
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('<p>There was a problem canceling the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>AiravataClientException: ' . $ace->getMessage() . '</p>');
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('<p>There was a problem canceling the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>AiravataSystemException: ' . $ase->getMessage() . '</p>');
        } catch (TTransportException $tte) {
            CommonUtilities::print_error_message('<p>There was a problem canceling the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>TTransportException: ' . $tte->getMessage() . '</p>');
        } catch (Exception $e) {
            CommonUtilities::print_error_message('<p>There was a problem canceling the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Exception: ' . $e->getMessage() . '</p>');
        }
    }

    /**
     * Create form inputs to accept the inputs to the given application
     * @param $id
     * @param $isRequired
     * @internal param $required
     */
    public static function create_inputs($id, $isRequired)
    {
        $inputs = AppUtilities::get_application_inputs($id);

        $required = $isRequired ? ' required' : '';

        //var_dump( $inputs);  echo "<br/>after sort<br/>";
        //arranging inputs in ascending order.
        foreach ($inputs as $index => $input) {
            $order[$index] = $input->inputOrder;
        }
        if($inputs != null){
            array_multisort($order, SORT_ASC, $inputs);
        }

        //var_dump( $inputs); exit;
        foreach ($inputs as $input) {
            switch ($input->type) {
                case DataType::STRING:
                    echo '<div class="form-group">
                    <label for="experiment-input">' . $input->name . '</label>
                    <input value="' . $input->value . '" type="text" class="form-control" name="' . $input->name .
                        '" id="' . $input->name .
                        '" placeholder="' . $input->userFriendlyDescription . '"' . $required . '>
                    </div>';
                    break;
                case DataType::INTEGER:
                    echo '<div class="form-group">
                    <label for="experiment-input">' . $input->name . '</label>
                    <input value="' . $input->value . '" type="number" class="form-control" name="' . $input->name .
                        '" id="' . $input->name .
                        '" placeholder="' . $input->userFriendlyDescription . '"' . $required . '>
                    </div>';
                    break;
                case DataType::FLOAT:
                    echo '<div class="form-group">
                    <label for="experiment-input">' . $input->name . '</label>
                    <input value="' . $input->value . '" type="number" step="0.01" class="form-control" name="' . $input->name .
                        '" id="' . $input->name .
                        '" placeholder="' . $input->userFriendlyDescription . '"' . $required . '>
                    </div>';
                    break;
                case DataType::URI:
                    if(!empty($input->metaData) && json_decode($input->metaData)->location == "remote"){

                        echo '<div class="form-group">
                            <label for="experiment-input">' . $input->name . '</label>
                            <input class="form-control" type="text" name="' . $input->name .
                                    '" id="' . $input->name . '" ' . $required . '>
                            <p class="help-block">' . $input->userFriendlyDescription . '</p>
                            </div>';
                        break;
                    }else{
                        echo '<div class="form-group">
                            <label for="experiment-input">' . $input->name . '</label>
                            <div data-file-id="' . $input->name . '" class="readBytesButtons btn btn-default btn-xs"
                             data-toggle="modal" style="float: right">view file</div>
                            <input class="file-input" type="file" name="' . $input->name .
                                    '" id="' . $input->name . '" ' . $required . '>
                            <p class="help-block">' . $input->userFriendlyDescription . '</p>
                            </div>';
                        break;
                    }

                default:
                    CommonUtilities::print_error_message('Input data type not supported!
                    Please file a bug report using the link in the Help menu.');
                    break;
            }
        }

        $appInterface = AppUtilities::get_application_interface($id);
        if($appInterface->hasOptionalFileInputs){
            echo '<div>
                <label>Optional Input Files</label>
                <input type="file" id="optInputFiles" name="optInputFiles[]" multiple onchange="javascript:updateList()" >
                <div id="optFileList"></div>
            </div>';
        }
    }


    /**
     * Create a new experiment from the values submitted in the form
     * @return null
     */
    public static function create_experiment()
    {

        $experiment = ExperimentUtilities::assemble_experiment();
        $expId = null;

        $share = $_POST['share-settings'];

        try {
            if ($experiment) {
                $expId = Airavata::createExperiment(Session::get('authz-token'), Session::get("gateway_id"), $experiment);
            }

            if ($expId) {
                /*
                CommonUtilities::print_success_message("Experiment {$_POST['experiment-name']} created!" .
                    ' <a href="experiment_summary.php?expId=' . $expId . '">Go to experiment summary page</a>');
                */
            } else {
                CommonUtilities::print_error_message("Error creating experiment {$_POST['experiment-name']}!");
            }
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('InvalidRequestException!<br><br>' . $ire->getMessage());
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('AiravataClientException!<br><br>' . $ace->getMessage());
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('AiravataSystemException!<br><br>' . $ase->getMessage());
        }

        ExperimentUtilities::share_experiment($expId, json_decode($share));

        return $expId;
    }

    /*
     * Required in Experiment Sumamry page.
     *
    */

    public static function list_output_files($outputs, $status, $process)
    {
        if ( $status != ExperimentState::COMPLETED)
            echo "Experiment hasn't completed. Experiment Status is : " .  ExperimentState::$__names[ $status] . '<br/>';

        foreach ((array)$outputs as $output) {
            if ($output->type == DataType::URI || $output->type == DataType::STDOUT || $output->type == DataType::STDERR) {
                if(!empty($output->value) && filter_var($output->value, FILTER_VALIDATE_URL)){

                    if(strpos($output->value, "airavata-dp") === 0){
                        $dataProductModel = Airavata::getDataProduct(Session::get('authz-token'), $output->value);
                        $currentOutputPath = "";
                        foreach ($dataProductModel->replicaLocations as $rp) {
                            if($rp->replicaLocationCategory == ReplicaLocationCategory::GATEWAY_DATA_STORE){
                                $currentOutputPath = $rp->filePath;
                                break;
                            }
                        }
                        $fileName = basename($currentOutputPath);
                    }else{
                        $fileName = basename($output->value);
                    }
                    echo '<p>' . $output->name . ':&nbsp;<a target="_blank" href="' . URL::to("/")
                        . '/download/?id=' . urlencode($output->value) . '">' . $fileName
                        . ' <span class="glyphicon glyphicon-new-window"></span></a></p>';

                }
            } elseif ($output->type == DataType::STRING) {
                echo '<p>' . $output->value . '</p>';
            } else
                echo 'output : ' . $output;
        }
    }

    /*
    public static function get_experiment_summary_values($experimentSummary, $forSearch = false)
    {
//        var_dump( $experimentSummary); exit;
        $expVal = array();
        $expVal["experimentStatusString"] = "";
        $expVal["experimentTimeOfStateChange"] = "";
        $expVal["experimentCreationTime"] = "";

        $expVal["experimentStatusString"] = $experimentSummary->experimentStatus;
        $expVal["experimentTimeOfStateChange"] = $experimentSummary->statusUpdateTime / 1000; // divide by 1000 since timeOfStateChange is in ms
        $expVal["experimentCreationTime"] = $experimentSummary->creationTime / 1000; // divide by 1000 since creationTime is in ms

        if (!$forSearch) {
            $userConfigData = $experimentSummary->userConfigurationData;
            $scheduling = $userConfigData->computationalResourceScheduling;
            $expVal['scheduling'] = $scheduling;
            try {
                $expVal["computeResource"] = CRUtilities::get_compute_resource($scheduling->resourceHostId);
            } catch (Exception $ex) {
                //Error while retrieving CR
                $expVal["computeResource"] = "";
            }
        }

        try{
            $expVal["applicationInterface"] = AppUtilities::get_application_interface($experimentSummary->executionId);
        }catch (Exception $ex){
            //Failed retrieving Application Interface (May be it's deleted) Fix for Airavata-1801
            $expVal["applicationInterface"] = new ApplicationInterfaceDescription();
            $expVal["applicationInterface"]->applicationName = substr($experimentSummary->executionId, -8);
        }


        switch ($experimentSummary->experimentStatus) {
            case 'CREATED':
            case 'VALIDATED':
            case 'SCHEDULED':
            case 'FAILED':
                $expVal["editable"] = true;
                break;
            default:
                $expVal["editable"] = false;
                break;
        }

        switch ($experimentSummary->experimentStatus) {
            case 'CREATED':
            case 'VALIDATED':
            case 'SCHEDULED':
            case 'LAUNCHED':
            case 'EXECUTING':
                $expVal["cancelable"] = true;
                break;
            default:
                $expVal["cancelable"] = false;
                break;
        }

        return $expVal;

    }
    */

    public static function get_experiment_values($experiment, $forSearch = false)
    {
        $expVal = array();
        //$expVal["experimentStatusString"] = "";
        $expVal["experimentTimeOfStateChange"] = "";
        $expVal["experimentCreationTime"] = "";

        $expVal["experimentStates"] = ExperimentState::$__names;
        $expVal["processStates"] = ProcessState::$__names;
        $expVal["jobStates"] = JobState::$__names;
        $expVal["taskStates"] = TaskState::$__names;
        $expVal["taskTypes"] = TaskTypes::$__names;


        if( is_object( $experiment->experimentStatus ) )
            $experimentStatusString = $expVal["experimentStates"][$experiment->experimentStatus->state];
        else
            $experimentStatusString = $experiment->experimentStatus;

        $expVal["experimentStatusString"] = $experimentStatusString;
        if ( $experimentStatusString == ExperimentState::FAILED)
            $expVal["editable"] = false;

        $expVal["cancelable"] = false;
        if ( $experimentStatusString == ExperimentState::LAUNCHED
            || $experimentStatusString == ExperimentState::EXECUTING)
            $expVal["cancelable"] = true;


        if ($experiment->experimentStatus != null) {
            $experimentStatus = $experiment->experimentStatus;

            if( is_object( $experiment->experimentStatus ) )
                $expVal["experimentTimeOfStateChange"] = $experimentStatus->timeOfStateChange / 1000; // divide by 1000 since timeOfStateChange is in ms
            $expVal["experimentCreationTime"] = $experiment->creationTime / 1000; // divide by 1000 since creationTime is in ms
        }

        if (!$forSearch && is_object( $experiment->experimentStatus) ){
            $userConfigData = $experiment->userConfigurationData;
            $scheduling = $userConfigData->computationalResourceScheduling;

            $expVal['scheduling'] = $scheduling;
            try {
                $expVal["computeResource"] = CRUtilities::get_compute_resource($scheduling->resourceHostId);
            } catch (Exception $ex) {
                //Error while retrieving CR
                $expVal["computeResource"] = "";
            }
        }

        try{
            $expVal["applicationInterface"] = AppUtilities::get_application_interface($experiment->executionId);
        }catch (Exception $ex){
            //Failed retrieving Application Interface (May be it's deleted) Fix for Airavata-1801
            $expVal["applicationInterface"] = new ApplicationInterfaceDescription();
            $expVal["applicationInterface"]->applicationName = substr($experiment->executionId, -8);
        }

        //editable statuses
        switch ( $experimentStatusString) {
            case 'CREATED':
            case 'VALIDATED':
            case 'SCHEDULED':
                $expVal["editable"] = true;
                break;
            default:
                $expVal["editable"] = false;
                break;
        }

        //cancelable statuses
        switch ( $experimentStatusString) {
            case 'VALIDATED':
            case 'SCHEDULED':
            case 'LAUNCHED':
            case 'EXECUTING':
                $expVal["cancelable"] = true;
                break;
            default:
                $expVal["cancelable"] = false;
                break;
        }

        return $expVal;

    }

    /**
     * Method to get the job status of an experiment
     * @param $experiment
     * @return null
     */
    public static function get_job_status(ExperimentModel $experiment)
    {
        $jobStatus = Airavata::getJobStatuses(Session::get('authz-token'), $experiment->experimentId);
        //TODO - implement following logic with new data model.
/*        if(!empty($experiment->workflowNodeDetailsList)){
            if(!empty($experiment->workflowNodeDetailsList[0]->taskDetailsList)){
                if(!empty($experiment->workflowNodeDetailsList[0]->taskDetailsList[0]->jobDetailsList)){
                    $jobStatus = $experiment->workflowNodeDetailsList[0]->taskDetailsList[0]->jobDetailsList[0]->jobStatus;
                }
            }
        }*/
        if (isset($jobStatus) && count($jobStatus) > 0) {
            $jobState = JobState::$__names[array_values($jobStatus)[0]->jobState];
        } else {
            $jobState = null;
        }

        return $jobState;
    }


    /**
     * Create options for the search key select input
     * @param $values
     * @param $labels
     * @param $disabled
     */
    public static function create_options($values, $labels, $disabled)
    {
        for ($i = 0; $i < sizeof($values); $i++) {
            $selected = '';

            // if option was previously selected, mark it as selected
            if (isset($_POST['search-key'])) {
                if ($values[$i] == $_POST['search-key']) {
                    $selected = 'selected';
                }
            }

            echo '<option value="' . $values[$i] . '" ' . $disabled[$i] . ' ' . $selected . '>' . $labels[$i] . '</option>';
        }
    }

    /**
     * Get results of the user's search of experiments with pagination
     * @return array|null
     */
    public static function get_expsearch_results_with_pagination($inputs, $limit, $offset)
    {
        $experiments = array();

        try {
            $filters = array();
            if(!empty($inputs["status-type"])){
                if ($inputs["status-type"] != "ALL") {
                    $filters[\Airavata\Model\Experiment\ExperimentSearchFields::STATUS] = $inputs["status-type"];
                }
            }
            if(!empty($inputs["search-key"])){
                switch ($inputs["search-key"]) {
                    case 'experiment-name':
                        $filters[\Airavata\Model\Experiment\ExperimentSearchFields::EXPERIMENT_NAME] = $inputs["search-value"];
                        break;
                    case 'experiment-description':
                        $filters[\Airavata\Model\Experiment\ExperimentSearchFields::EXPERIMENT_DESC] = $inputs["search-value"];
                        break;
                    case 'application':
                        $filters[\Airavata\Model\Experiment\ExperimentSearchFields::APPLICATION_ID] = $inputs["search-value"];
                        break;
                    case 'creation-time':
                        $timeDifference = Session::get("user_timezone");
                        $addOrSubtract = "-";
                        if( $timeDifference > 0)
                            $addOrSubtract = "+";
                        $filters[\Airavata\Model\Experiment\ExperimentSearchFields::FROM_DATE] = strtotime( $addOrSubtract . " " . Session::get("user_timezone") . " hours", strtotime($inputs["from-date"]) ) * 1000;
                        $filters[\Airavata\Model\Experiment\ExperimentSearchFields::TO_DATE] = strtotime( $addOrSubtract . " " . Session::get("user_timezone") . " hours", strtotime($inputs["to-date"]) ) * 1000;
                        break;
                    case '':
                }
            }
            $experiments = Airavata::searchExperiments(Session::get('authz-token'),
                Session::get('gateway_id'), Session::get('username'), $filters, $limit, $offset);
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('InvalidRequestException!<br><br>' . $ire->getMessage());
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('AiravataClientException!<br><br>' . $ace->getMessage());
        } catch (AiravataSystemException $ase) {
            if ($ase->airavataErrorType == 2) // 2 = INTERNAL_ERROR
            {
                CommonUtilities::print_info_message('<p>You have not created any experiments yet, so no results will be returned!</p>
                                <p>Click <a href="create_experiment.php">here</a> to create an experiment, or
                                <a href="create_project.php">here</a> to create a new project.</p>');
            } else {
                CommonUtilities::print_error_message('There was a problem with Airavata. Please try again later or report a bug using the link in the Help menu.');
                //print_error_message('AiravataSystemException!<br><br>' . $ase->airavataErrorType . ': ' . $ase->getMessage());
            }
        } catch (TTransportException $tte) {
            CommonUtilities::print_error_message('TTransportException!<br><br>' . $tte->getMessage());
        }

        //get values of all experiments
        $expContainer = array();
        $expNum = 0;
        foreach ($experiments as $experiment) {
            $expValue = ExperimentUtilities::get_experiment_values($experiment, true);
            $expContainer[$expNum]['experiment'] = $experiment;
            if ($expValue["experimentStatusString"] == "FAILED")
                $expValue["editable"] = false;
            $expContainer[$expNum]['expValue'] = $expValue;
            $expNum++;
        }

        return $expContainer;
    }

    /**
     * Get results of the user's all experiments with pagination.
     * Results are ordered creation time DESC
     * @return array|null
     */
    public static function get_all_user_experiments_with_pagination($limit, $offset)
    {
        $experiments = array();

        try {
            $experiments = Airavata::getUserExperiments(Session::get('authz-token'),
                Session::get('gateway_id'), Session::get('username'), $limit, $offset
            );
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('InvalidRequestException!<br><br>' . $ire->getMessage());
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('AiravataClientException!<br><br>' . $ace->getMessage());
        } catch (AiravataSystemException $ase) {
            if ($ase->airavataErrorType == 2) // 2 = INTERNAL_ERROR
            {
                CommonUtilities::print_info_message('<p>You have not created any experiments yet, so no results will be returned!</p>
                                <p>Click <a href="create_experiment.php">here</a> to create an experiment, or
                                <a href="create_project.php">here</a> to create a new project.</p>');
            } else {
                CommonUtilities::print_error_message('There was a problem with Airavata. Please try again later or report a bug using the link in the Help menu.');
                //print_error_message('AiravataSystemException!<br><br>' . $ase->airavataErrorType . ': ' . $ase->getMessage());
            }
        } catch (TTransportException $tte) {
            CommonUtilities::print_error_message('TTransportException!<br><br>' . $tte->getMessage());
        }

        //get values of all experiments
        $expContainer = array();
        $expNum = 0;
        foreach ($experiments as $experiment) {
            $expValue = ExperimentUtilities::get_experiment_values($experiment, true);
            $expContainer[$expNum]['experiment'] = $experiment;
            if ($expValue["experimentStatusString"] == "FAILED")
                $expValue["editable"] = false;
            $expContainer[$expNum]['expValue'] = $expValue;
            $expNum++;
        }

        return $expContainer;
    }

    public static function getExpStates()
    {
        $states = ExperimentState::$__names;
        //removing UNKNOWN and SUSPENDED states. (AIRAVATA-1756)
        $index = array_search('UNKNOWN',$states);
        if($index !== FALSE){
            unset($states[$index]);
        }
        $index = array_search('SUSPENDED',$states);
        if($index !== FALSE){
            unset($states[$index]);
        }

        return $states;
    }

    public static function apply_changes_to_experiment($experiment, $input)
    {
        $experiment->experimentName = $input['experiment-name'];
        $experiment->description = rtrim($input['experiment-description']);
//        $experiment->projectId = $input['project'];
//        $experiment->applicationId = $_POST['application'];
//        $experiment->executionId = $_POST['application'];

        $userConfigDataUpdated = $experiment->userConfigurationData;
        $schedulingUpdated = $userConfigDataUpdated->computationalResourceScheduling;

        $schedulingUpdated->resourceHostId = $input['compute-resource'];
        $schedulingUpdated->nodeCount = $input['node-count'];
        $schedulingUpdated->queueName = $_POST['queue-name'];
        $schedulingUpdated->totalCPUCount = $input['cpu-count'];
        //$schedulingUpdated->numberOfThreads = $input['threads'];
        $schedulingUpdated->wallTimeLimit = $input['wall-time'];
        //$schedulingUpdated->totalPhysicalMemory = $input['memory'];

        /*
        switch ($_POST['compute-resource'])
        {
            case 'trestles.sdsc.edu':
                $schedulingUpdated->ComputationalProjectAccount = 'sds128';
                break;
            case 'stampede.tacc.xsede.org':
            case 'lonestar.tacc.utexas.edu':
                $schedulingUpdated->ComputationalProjectAccount = 'TG-STA110014S';
                break;
            default:
                $schedulingUpdated->ComputationalProjectAccount = 'admin';
        }
        */

        $userConfigDataUpdated->computationalResourceScheduling = $schedulingUpdated;
        $userConfigDataUpdated->airavataAutoSchedule = isset($_POST['enable-auto-scheduling']) ? true : false;
        if (isset($input["userDN"])) {
            $userConfigDataUpdated->generateCert = 1;
            $userConfigDataUpdated->userDN = $input["userDN"];
        }

        $experiment->userConfigurationData = $userConfigDataUpdated;

        $applicationInputs = AppUtilities::get_application_inputs($experiment->executionId);

        $experimentInputs = $experiment->experimentInputs; // get current inputs
        $experimentInputs = ExperimentUtilities::process_inputs( $experiment->projectId, $input['experiment-name'], $applicationInputs, $experimentInputs); // get new inputs

        if ($experimentInputs) {
            $experiment->experimentInputs = $experimentInputs;
            //var_dump($experiment);
            return $experiment;
        }
    }

    public static function get_status_color_class( $status)
    {
        switch ( $status) {
            case 'CANCELING':
            case 'CANCELED':
            case 'UNKNOWN':
                $statusClass = 'text-warning';
                break;
            case 'FAILED':
                $statusClass = 'text-danger';
                break;
            case 'COMPLETED':
                $statusClass = 'text-success';
                break;
            case 'COMPLETE':
                $statusClass = 'text-success';
                break;
            default:
                $statusClass = 'text-info';
                break;
        }
        return $statusClass;
    }

    public static function get_job_details($experimentId)
    {
        return Airavata::getJobDetails(Session::get('authz-token'), $experimentId);
    }

    public static function get_transfer_details($experimentId)
    {
        return Airavata::getDataTransferDetails(Session::get('authz-token'), $experimentId);
    }

    public static function getQueueDatafromResourceId($crId)
    {
        $resourceObject = Airavata::getComputeResource(Session::get('authz-token'), $crId);
        return $resourceObject->batchQueues;
    }

    /**
     * Create a select input and populate it with applications options
     * @param null $id
     * @param bool $editable
     */
    public static function create_application_select($id = null, $editable = true)
    {
        $disabled = $editable ? '' : 'disabled';

        $applicationIds = AppUtilities::get_all_applications();
        uksort($applicationIds, 'strcasecmp');
        echo '<select class="form-control" name="application" id="application" required ' . $disabled . '>';

        if (count($applicationIds)) {
            foreach ((array)$applicationIds as $applicationId => $applicationName) {
                $selected = ($applicationId == $id) ? 'selected' : '';

                echo '<option value="' . $applicationId . '" ' . $selected . '>' . $applicationName . '</option>';
            }
        }

        echo '</select>';
    }

    private static function share_experiment($expId, $users) {
        $wadd = array();
        $wrevoke = array();
        $radd = array();
        $rrevoke = array();

        foreach ($users as $user => $perms) {
            if ($perms['write']) {
                $wadd[$user] = ResourcePermissionType::WRITE;
            }
            else {
                $wrevoke[$user] = ResourcePermissionType::WRITE;
            }

            if ($perms['read']) {
                $radd[$user] = ResourcePermissionType::READ;
            }
            else {
                $rrevoke[$user] = ResourcePermissionType::READ;
            }
        }

        GrouperUtilities::shareResourceWithUsers($expId, ResourceType::Experiment, $wadd);
        GrouperUtilities::revokeSharingOfResourceFromUsers($expId, ResourceType::Experiment, $wrevoke);

        GrouperUtilities::shareResourceWithUsers($expId, ResourceType::Experiment, $radd);
        GrouperUtilities::revokeSharingOfResourceFromUsers($expId, ResourceType::Experiment, $rrevoke);
    }


}
