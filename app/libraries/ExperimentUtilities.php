<?php

use Airavata\API\Error\AiravataClientException;
use Airavata\API\Error\AiravataSystemException;
use Airavata\API\Error\ExperimentNotFoundException;
use Airavata\API\Error\InvalidRequestException;
use Airavata\Facades\Airavata;
use Airavata\Model\Application\Io\DataType;
use Airavata\Model\AppCatalog\AppInterface\ApplicationInterfaceDescription;
use Airavata\Model\Application\Io\InputDataObjectType;
use Airavata\Model\Scheduling\ComputationalResourceSchedulingModel;
use Airavata\Model\Experiment\ExperimentModel;
use Airavata\Model\Status\ExperimentState;
use Airavata\Model\Status\ProcessState;
use Airavata\Model\Status\JobState;
use Airavata\Model\Status\TaskState;
use Airavata\Model\Task\TaskTypes;
use Airavata\Model\Experiment\UserConfigurationDataModel;

class ExperimentUtilities
{
    private static $experimentPath;

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

        foreach ($experimentInputs as $input) {
            $matchingAppInput = null;

            if ($input->type == DataType::URI && empty($input->metaData)) {
                $inputArray = explode('/', $input->value);
                echo '<p><a target="_blank"
                        href="' . URL::to("/") . '/download/' . $inputArray[ count($inputArray)-2] . '/' . 
                $inputArray[ count($inputArray)-1] . '">' .
                    $inputArray[ count($inputArray)-1] . '
                <span class="glyphicon glyphicon-new-window"></span></a></p>';
            }elseif($input->type == DataType::URI && !empty($input->metaData)
                && json_decode($input->metaData)->location=="remote"){
                echo '<p>' . $input->name . ': ' . $input->value . '</p>';
            }elseif ($input->type == DataType::STRING || $input->type == DataType::INTEGER
                || $input->type == DataType::FLOAT) {
                echo '<p>' . $input->name . ': ' . $input->value . '</p>';
            }
        }
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
        $scheduling->staticWorkingDir = $_POST['static-working-dir'];

        $userConfigData = new UserConfigurationDataModel();
        $userConfigData->computationalResourceScheduling = $scheduling;
        $userConfigData->airavataAutoSchedule = isset($_POST['enable-auto-scheduling']) ? true : false;
        if (isset($_POST["userDN"])) {
            $userConfigData->generateCert = 1;
            $userConfigData->userDN = $_POST["userDN"];
        }

        $applicationInputs = AppUtilities::get_application_inputs($_POST['application']);
        $experimentInputs = ExperimentUtilities::process_inputs($applicationInputs, $experimentInputs);

        if (ExperimentUtilities::$experimentPath == null) {
            ExperimentUtilities::create_experiment_folder_path();
        }

//        $advHandling = new AdvancedOutputDataHandling();
        $hostName = $_SERVER['SERVER_NAME'];
        $expPathConstant = 'file://' . Config::get('pga_config.airavata')['ssh-user'] . '@' . $hostName . ':' . Config::get('pga_config.airavata')['experiment-data-absolute-path'];

//        $advHandling->outputDataDir = str_replace(Config::get('pga_config.airavata')['experiment-data-absolute-path'],
//            $expPathConstant, ExperimentUtilities::$experimentPath);
//        $userConfigData->advanceOutputDataHandling = $advHandling;

        //TODO: replace constructor with a call to airvata to get a prepopulated experiment template
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
    public static function process_inputs($applicationInputs, $experimentInputs)
    {
        $experimentAssemblySuccessful = true;
        $newExperimentInputs = array();

        //var_dump($_FILES);

        if (sizeof($_FILES) > 0) {
            if (ExperimentUtilities::file_upload_successful()) {
                // construct unique path
                ExperimentUtilities::create_experiment_folder_path();
            } else {
                $experimentAssemblySuccessful = false;
            }
        }

        //sending application inputs in the order defined by the admins.
        $order = array();
        foreach ($applicationInputs as $index => $input) {
            $order[$index] = $input->inputOrder;
        }
        array_multisort($order, SORT_ASC, $applicationInputs);

        foreach ($applicationInputs as $applicationInput) {
            $experimentInput = new InputDataObjectType();
            $experimentInput = $applicationInput;
            //$experimentInput->name = $applicationInput->name;
            //$experimentInput->metaData = $applicationInput->metaData;


            //$experimentInput->type = $applicationInput->type;
            //$experimentInput->type = DataType::STRING;


            if (($applicationInput->type == DataType::STRING) ||
                ($applicationInput->type == DataType::INTEGER) ||
                ($applicationInput->type == DataType::FLOAT) ||
                ($applicationInput->type == DataType::URI && !empty($applicationInput->metaData)
                    && json_decode($applicationInput->metaData)->location=="remote")
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
                //var_dump($_FILES[$applicationInput->name]->name);
                if ($_FILES[$applicationInput->name]['name']) {
                    $file = $_FILES[$applicationInput->name];


                    //
                    // move file to experiment data directory
                    //
                    if(!empty($applicationInput->value)){
                        $filePath = ExperimentUtilities::$experimentPath . $applicationInput->value;
                    }else{
                        $filePath = ExperimentUtilities::$experimentPath . $file['name'];
                    }

                    // check if file already exists
                    if (is_file($filePath)) {
                        unlink($filePath);

                        CommonUtilities::print_warning_message('Uploaded file already exists! Overwriting...');
                    }

                    $moveFile = move_uploaded_file($file['tmp_name'], $filePath);

                    if ($moveFile) {
                        CommonUtilities::print_success_message('Upload: ' . $file['name'] . '<br>' .
                            'Type: ' . $file['type'] . '<br>' .
                            'Size: ' . ($file['size'] / 1024) . ' kB');
                        //<br>' .
                        //'Stored in: ' . $experimentPath . $file['name']);
                    } else {
                        CommonUtilities::print_error_message('<p>Error moving uploaded file ' . $file['name'] . '!
                    Please try again later or report a bug using the link in the Help menu.</p>');
                        $experimentAssemblySuccessful = false;
                    }
                    $hostName = $_SERVER['SERVER_NAME'];
                    $experimentInput->value = 'file://' . Config::get('pga_config.airavata')['ssh-user'] . '@' . $hostName . ':' . $filePath;
                    $experimentInput->type = $applicationInput->type;

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

        if ($experimentAssemblySuccessful) {
            return $newExperimentInputs;
        } else {
            return false;
        }
    }


    public static function create_experiment_folder_path()
    {
        do {
            ExperimentUtilities::$experimentPath = Config::get('pga_config.airavata')['experiment-data-absolute-path'] .
                "/" . str_replace(' ', '', Session::get('username')) . md5(rand() * time()) . '/';
        } while (is_dir(ExperimentUtilities::$experimentPath)); // if dir already exists, try again
        // create upload directory
        if (!mkdir(ExperimentUtilities::$experimentPath)) {
            CommonUtilities::print_error_message('<p>Error creating upload directory!
            Please try again later or report a bug using the link in the Help menu.</p>');
            $experimentAssemblySuccessful = false;
        }
    }

    /**
     * Check the uploaded files for errors
     */
    public static function file_upload_successful()
    {
        $uploadSuccessful = true;

        foreach ($_FILES as $file) {
            //var_dump($file);
            if ($file['name']) {
                if ($file['error'] > 0) {
                    $uploadSuccessful = false;
                    CommonUtilities::print_error_message('<p>Error uploading file ' . $file['name'] . ' !
                    Please try again later or report a bug using the link in the Help menu.');
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
            ExperimentUtilities::create_experiment_folder_path();
            $hostName = $_SERVER['SERVER_NAME'];
            $expPathConstant = 'file://' . Config::get('pga_config.airavata')['ssh-user'] . '@' . $hostName . ':' . Config::get('pga_config.airavata')['experiment-data-absolute-path'];
            $outputDataDir = str_replace(Config::get('pga_config.airavata')['experiment-data-absolute-path'],
                $expPathConstant, ExperimentUtilities::$experimentPath);
            //$experiment->userConfigurationData->advanceOutputDataHandling->outputDataDir = $outputDataDir;

            foreach ($experimentInputs as $experimentInput) {
                if ($experimentInput->type == DataType::URI) {
                    $currentInputPath = $experimentInput->value;
                    $hostPathConstant = 'file://' . Config::get('pga_config.airavata')['ssh-user'] . '@' . $hostName . ':';
                    $currentInputPath = str_replace($hostPathConstant, '', $currentInputPath);
                    $parts = explode('/', rtrim($currentInputPath, '/'));
                    $fileName = array_pop($parts);
                    $newInputPath = ExperimentUtilities::$experimentPath . $fileName;
                    copy($currentInputPath, $newInputPath);
                    $experimentInput->value = $hostPathConstant . $newInputPath;
                }
            }
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
    }


    /**
     * Create a new experiment from the values submitted in the form
     * @return null
     */
    public static function create_experiment()
    {

        $experiment = ExperimentUtilities::assemble_experiment();
        $expId = null;

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

        return $expId;
    }

    /*
     * Required in Experiment Sumamry page.
     *
    */

    public static function list_output_files($outputs, $status, $process)
    {
        if( $process)
        {
            $processStatusVal = array_search($status, ProcessState::$__names);
            if ($status != ProcessState::COMPLETED)
                echo "Process hasn't completed. Process Status is : " . ProcessState::$__names[ $status] . '<br/>';
        }
        else
        {
            $expStatusVal = array_search($status, ExperimentState::$__names);
            if ( $status != ExperimentState::COMPLETED)
                echo "Experiment hasn't completed. Experiment Status is : " .  ExperimentState::$__names[ $status] . '<br/>';
        }
        //$outputs = $experiment->experimentOutputs;
        //print_r( $outputs); exit;
        foreach ((array)$outputs as $output) {
            if ($output->type == DataType::URI || $output->type == DataType::STDOUT || $output->type == DataType::STDERR) {
                $explode = explode('/', $output->value);
                //echo '<p>' . $output->key .  ': <a href="' . $output->value . '">' . $output->value . '</a></p>';
                $outputPath = str_replace(Config::get('pga_config.airavata')['experiment-data-absolute-path'], Config::get('pga_config.airavata')['experiment-data-dir'], $output->value);
                //print_r( $output->value); 
                if(file_exists(str_replace('//','/',$output->value))){
                    $outputPathArray = explode("/", $outputPath);

                    echo '<p>' . $output->name . ' : ' . '<a target="_blank"
                            href="' . URL::to("/") . '/download/' . $outputPathArray[ count($outputPathArray)-2] . '/' . 
            $outputPathArray[ count($outputPathArray)-1] . '">' .
                        $outputPathArray[sizeof($outputPathArray) - 1] . ' <span class="glyphicon glyphicon-new-window"></span></a></p>';
                }
                else
                    echo 'Output paths are not correctly defined for : <br/>' . $output->name . '<br/><br/> Please report this issue to the admin<br/><br/>';
            
            } 
            elseif ($output->type == DataType::STRING) {
                echo '<p>' . $output->value . '</p>';
            }
            else
                echo 'output : '. $output;
            //echo 'output-type : ' . $output->type;
        }
    }

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


    public static function get_experiment_values($experiment, $project, $forSearch = false)
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

        $experimentStatusString = $expVal["experimentStates"][$experiment->experimentStatus->state];
        $expVal["experimentStatusString"] = $experimentStatusString;
        if ( $experimentStatusString == ExperimentState::FAILED)
            $expVal["editable"] = false;

        $expVal["cancelable"] = false;
        if ( $experimentStatusString == ExperimentState::LAUNCHED 
            || $experimentStatusString == ExperimentState::EXECUTING)
            $expVal["cancelable"] = true;


        if ($experiment->experimentStatus != null) {
            $experimentStatus = $experiment->experimentStatus;
            /*
            $experimentState = $experimentStatus->state;
            $experimentStatusString = ExperimentState::$__names[$experimentState];
            $expVal["experimentStatusString"] = $experimentStatusString;
            */
            $expVal["experimentTimeOfStateChange"] = $experimentStatus->timeOfStateChange / 1000; // divide by 1000 since timeOfStateChange is in ms
            $expVal["experimentCreationTime"] = $experiment->creationTime / 1000; // divide by 1000 since creationTime is in ms
        }

        if (!$forSearch) {
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


        switch (ExperimentState::$__names[$experiment->experimentStatus->state]) {
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

        switch (ExperimentState::$__names[$experiment->experimentStatus->state]) {
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
                        $filters[\Airavata\Model\Experiment\ExperimentSearchFields::FROM_DATE] = strtotime($inputs["from-date"]) * 1000;
                        $filters[\Airavata\Model\Experiment\ExperimentSearchFields::TO_DATE] = strtotime($inputs["to-date"]) * 1000;
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
            $expValue = ExperimentUtilities::get_experiment_summary_values($experiment, true);
            $expContainer[$expNum]['experiment'] = $experiment;
            if ($expValue["experimentStatusString"] == "FAILED")
                $expValue["editable"] = false;
            $expContainer[$expNum]['expValue'] = $expValue;
            $expNum++;
        }

        return $expContainer;
    }

    /**
     * Get results of the user's search of experiments
     * @return array|null
     */
    public static function get_expsearch_results($inputs)
    {
        $experiments = array();

        try {
            switch ($inputs["search-key"]) {
                case 'experiment-name':
                    $experiments = Airavata::searchExperimentsByName(Session::get('authz-token'), Session::get('gateway_id'), Session::get('username'), $inputs["search-value"]);
                    break;
                case 'experiment-description':
                    $experiments = Airavata::searchExperimentsByDesc(Session::get('authz-token'), Session::get('gateway_id'), Session::get('username'), $inputs["search-value"]);
                    break;
                case 'application':
                    $experiments = Airavata::searchExperimentsByApplication(Session::get('authz-token'), Session::get('gateway_id'), Session::get('username'), $inputs["search-value"]);
                    break;
                case 'creation-time':
                    $experiments = Airavata::searchExperimentsByCreationTime(Session::get('authz-token'), Session::get('gateway_id'), Session::get('username'), strtotime($inputs["from-date"]) * 1000, strtotime($inputs["to-date"]) * 1000);
                    break;
                case '':
            }
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
            $expValue = ExperimentUtilities::get_experiment_search_values($experiment, ProjectUtilities::get_project($experiment->projectId), true);
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
            $expValue = ExperimentUtilities::get_experiment_summary_values($experiment, true);
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
        $experiment->projectId = $input['project'];
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
        //var_dump($experimentInputs);
        $experimentInputs = ExperimentUtilities::process_inputs($applicationInputs, $experimentInputs); // get new inputs
        //var_dump($experimentInputs);

        if ($experimentInputs) {
            $experiment->experimentInputs = $experimentInputs;
            //var_dump($experiment);
            return $experiment;
        }
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


}