<?php

use Airavata\API\Error\AiravataClientException;
use Airavata\API\Error\AiravataSystemException;
use Airavata\API\Error\ExperimentNotFoundException;
use Airavata\API\Error\InvalidRequestException;
use Airavata\Facades\Airavata;
use Airavata\Model\AppCatalog\AppInterface\DataType;
use Airavata\Model\AppCatalog\AppInterface\InputDataObjectType;
use Airavata\Model\Workspace\Experiment\AdvancedOutputDataHandling;
use Airavata\Model\Workspace\Experiment\ComputationalResourceScheduling;
use Airavata\Model\Workspace\Experiment\Experiment;
use Airavata\Model\Workspace\Experiment\ExperimentState;
use Airavata\Model\Workspace\Experiment\JobState;
use Airavata\Model\Workspace\Experiment\UserConfigurationData;

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
            $hardCodedToken = Config::get('pga_config.airavata')['credential-store-token'];
            Airavata::launchExperiment($expId, $hardCodedToken);
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
    public static function list_input_files($experiment)
    {
        $applicationInputs = AppUtilities::get_application_inputs($experiment->applicationId);

        $experimentInputs = $experiment->experimentInputs;


        //showing experiment inputs in the order defined by the admins.
        $order = array();
        foreach ($experimentInputs as $index => $input) {
            $order[$index] = $input->inputOrder;
        }
        array_multisort($order, SORT_ASC, $experimentInputs);

        foreach ($experimentInputs as $input) {
            $matchingAppInput = null;

            foreach ($applicationInputs as $applicationInput) {
                if ($input->name == $applicationInput->name) {
                    $matchingAppInput = $applicationInput;
                }
            }
            //var_dump($matchingAppInput);

            if ($matchingAppInput->type == DataType::URI) {
                $explode = explode('/', $input->value);
                echo '<p><a target="_blank"
                        href="' . URL::to("/") . Config::get('pga_config.airavata')['experiment-data-dir'] . $explode[sizeof($explode) - 2] . '/' . $explode[sizeof($explode) - 1] . '">' .
                    $explode[sizeof($explode) - 1] . '
                <span class="glyphicon glyphicon-new-window"></span></a></p>';
            } elseif ($matchingAppInput->type == DataType::STRING) {
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
            return Airavata::getExperiment($expId);
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

        $scheduling = new ComputationalResourceScheduling();
        $scheduling->totalCPUCount = $_POST['cpu-count'];
        $scheduling->nodeCount = $_POST['node-count'];
        $scheduling->queueName = $_POST['queue-name'];
        $scheduling->wallTimeLimit = $_POST['wall-time'];
        $scheduling->totalPhysicalMemory = $_POST['total-physical-memory'];
        $scheduling->resourceHostId = $_POST['compute-resource'];

        $userConfigData = new UserConfigurationData();
        $userConfigData->computationalResourceScheduling = $scheduling;
        if (isset($_POST["userDN"])) {
            $userConfigData->generateCert = 1;
            $userConfigData->userDN = $_POST["userDN"];
        }

        $applicationInputs = AppUtilities::get_application_inputs($_POST['application']);
        $experimentInputs = ExperimentUtilities::process_inputs($applicationInputs, $experimentInputs);

        if (ExperimentUtilities::$experimentPath == null) {
            ExperimentUtilities::create_experiment_folder_path();
        }

        $advHandling = new AdvancedOutputDataHandling();
        $sshUser = "root";
        $hostName = $_SERVER['SERVER_NAME'];
        $expPathConstant = 'file://' . $sshUser . '@' . $hostName . ':' . Config::get('pga_config.airavata')['experiment-data-absolute-path'];

        $advHandling->outputDataDir = Config::get('pga_config.airavata')['experiment-data-absolute-path'];
        $userConfigData->advanceOutputDataHandling = $advHandling;

        //TODO: replace constructor with a call to airvata to get a prepopulated experiment template
        $experiment = new Experiment();

        // required
        $experiment->projectID = $_POST['project'];
        $experiment->userName = Session::get('username');
        $experiment->name = $_POST['experiment-name'];

        // optional
        $experiment->description = $_POST['experiment-description'];
        $experiment->applicationId = $_POST['application'];
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
                //var_dump($_FILES[$applicationInput->name]->name);
                if ($_FILES[$applicationInput->name]['name']) {
                    $file = $_FILES[$applicationInput->name];


                    //
                    // move file to experiment data directory
                    //
                    $filePath = ExperimentUtilities::$experimentPath . $file['name'];

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

                    $experimentInput->value = Config::get('pga_config.airavata')['experiment-data-absolute-path'];
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
            Airavata::updateExperiment($expId, $updatedExperiment);
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
            $experiment = Airavata::getExperiment($expId);

            $cloneId = Airavata::cloneExperiment($expId, 'Clone of ' . $experiment->name);

            CommonUtilities::print_success_message("<p>Experiment cloned!</p>" .
                '<p>You will be redirected to the edit page shortly, or you can
                <a href="edit_experiment.php?expId=' . $cloneId . '">go directly</a> to the edit experiment page.</p>');
            //redirect('edit_experiment.php?expId=' . $cloneId);
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
            Airavata::terminateExperiment($expId, Config::get('pga_config.airavata')["credential-store-token"]);

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
        array_multisort($order, SORT_ASC, $inputs);
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
                    echo '<div class="form-group">
                    <label for="experiment-input">' . $input->name . '</label>
                    <input class="file-input" type="file" name="' . $input->name .
                        '" id="' . $input->name . '" ' . $required . '>
                    <p class="help-block">' . $input->userFriendlyDescription . '</p>
                    </div>';
                    break;
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
                $expId = Airavata::createExperiment(Session::get("gateway_id"), $experiment);
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

    public static function list_output_files($experiment, $expStatus)
    {

        $expStatusVal = array_search($expStatus, ExperimentState::$__names);

        if ($expStatusVal == ExperimentState::COMPLETED) {
            $experimentOutputs = $experiment->experimentOutputs;

            foreach ((array)$experimentOutputs as $output) {
                if ($output->type == DataType::URI || $output->type == DataType::STDOUT || $output->type == DataType::STDERR) {
                    $explode = explode('/', $output->value);
                    //echo '<p>' . $output->key .  ': <a href="' . $output->value . '">' . $output->value . '</a></p>';
                    $outputPath = str_replace(Config::get('pga_config.airavata')['experiment-data-absolute-path'],
                        Config::get('pga_config.airavata')['experiment-data-dir'], $output->value);
                    $outputPathArray = explode("/", $outputPath);

                    echo '<p>' . $output->name . ' : ' . '<a target="_blank"
                            href="' . URL::to("/") . "/.." . str_replace(Config::get('pga_config.airavata')['experiment-data-absolute-path'],
                            Config::get('pga_config.airavata')['experiment-data-dir'], $output->value) . '">' .
                        $outputPathArray[sizeof($outputPathArray) - 1] . ' <span class="glyphicon glyphicon-new-window"></span></a></p>';
                } elseif ($output->type == DataType::STRING) {
                    echo '<p>' . $output->value . '</p>';
                }
            }
        } else
            echo "Experiment hasn't completed. Experiment Status is : " . $expStatus;
    }

    public static function get_experiment_values($experiment, $project, $forSearch = false)
    {
        //var_dump( $experiment); exit;
        $expVal = array();
        $expVal["experimentStatusString"] = "";
        $expVal["experimentTimeOfStateChange"] = "";
        $expVal["experimentCreationTime"] = "";

        if ($experiment->experimentStatus != null) {
            $experimentStatus = $experiment->experimentStatus;
            $experimentState = $experimentStatus->experimentState;
            $experimentStatusString = ExperimentState::$__names[$experimentState];
            $expVal["experimentStatusString"] = $experimentStatusString;
            $expVal["experimentTimeOfStateChange"] = $experimentStatus->timeOfStateChange / 1000; // divide by 1000 since timeOfStateChange is in ms
            $expVal["experimentCreationTime"] = $experiment->creationTime / 1000; // divide by 1000 since creationTime is in ms
        }

        if (!$forSearch) {
            $userConfigData = $experiment->userConfigurationData;
            $scheduling = $userConfigData->computationalResourceScheduling;
            $expVal['scheduling'] = $scheduling;
            $expVal["computeResource"] = CRUtilities::get_compute_resource($scheduling->resourceHostId);
        }
        $expVal["applicationInterface"] = AppUtilities::get_application_interface($experiment->applicationId);


        switch ($experimentStatusString) {
            case 'CREATED':
            case 'VALIDATED':
            case 'SCHEDULED':
            case 'CANCELED':
            case 'FAILED':
                $expVal["editable"] = true;
                break;
            default:
                $expVal["editable"] = false;
                break;
        }

        switch ($experimentStatusString) {
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
    public static function get_job_status(Experiment $experiment)
    {
        //$jobStatus = Airavata::getJobStatuses($experiment->experimentID);
        if(!empty($experiment->workflowNodeDetailsList)){
            if(!empty($experiment->workflowNodeDetailsList[0]->taskDetailsList)){
                if(!empty($experiment->workflowNodeDetailsList[0]->taskDetailsList[0]->jobDetailsList)){
                    $jobStatus = $experiment->workflowNodeDetailsList[0]->taskDetailsList[0]->jobDetailsList[0]->jobStatus;
                }
            }
        }
        if (isset($jobStatus)) {
            $jobState = JobState::$__names[$jobStatus->jobState];
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
            if ($inputs["status-type"] != "ALL") {
                $filters[\Airavata\Model\Workspace\Experiment\ExperimentSearchFields::STATUS] = $inputs["status-type"];
            }
            switch ($inputs["search-key"]) {
                case 'experiment-name':
                    $filters[\Airavata\Model\Workspace\Experiment\ExperimentSearchFields::EXPERIMENT_NAME] = $inputs["search-value"];
                    break;
                case 'experiment-description':
                    $filters[\Airavata\Model\Workspace\Experiment\ExperimentSearchFields::EXPERIMENT_DESC] = $inputs["search-value"];
                    break;
                case 'application':
                    $filters[\Airavata\Model\Workspace\Experiment\ExperimentSearchFields::APPLICATION_ID] = $inputs["search-value"];
                    break;
                case 'creation-time':
                    $filters[\Airavata\Model\Workspace\Experiment\ExperimentSearchFields::FROM_DATE] = strtotime($inputs["from-date"]) * 1000;
                    $filters[\Airavata\Model\Workspace\Experiment\ExperimentSearchFields::TO_DATE] = strtotime($inputs["to-date"]) * 1000;
                    break;
                case '':
            }
            $experiments = Airavata::searchExperiments(
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
            $expValue = ExperimentUtilities::get_experiment_values($experiment, ProjectUtilities::get_project($experiment->projectID), true);
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
                    $experiments = Airavata::searchExperimentsByName(Session::get('gateway_id'), Session::get('username'), $inputs["search-value"]);
                    break;
                case 'experiment-description':
                    $experiments = Airavata::searchExperimentsByDesc(Session::get('gateway_id'), Session::get('username'), $inputs["search-value"]);
                    break;
                case 'application':
                    $experiments = Airavata::searchExperimentsByApplication(Session::get('gateway_id'), Session::get('username'), $inputs["search-value"]);
                    break;
                case 'creation-time':
                    $experiments = Airavata::searchExperimentsByCreationTime(Session::get('gateway_id'), Session::get('username'), strtotime($inputs["from-date"]) * 1000, strtotime($inputs["to-date"]) * 1000);
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
            $expValue = ExperimentUtilities::get_experiment_values($experiment, ProjectUtilities::get_project($experiment->projectID), true);
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
            $experiments = Airavata::getAllUserExperimentsWithPagination(
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
            $expValue = ExperimentUtilities::get_experiment_values($experiment, ProjectUtilities::get_project($experiment->projectID), true);
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
        return ExperimentState::$__names;
    }


    public static function apply_changes_to_experiment($experiment, $input)
    {
        $experiment->name = $input['experiment-name'];
        $experiment->description = rtrim($input['experiment-description']);
        $experiment->projectID = $input['project'];
        //$experiment->applicationId = $_POST['application'];

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
        if (isset($input["userDN"])) {
            $userConfigDataUpdated->generateCert = 1;
            $userConfigDataUpdated->userDN = $input["userDN"];
        }

        $experiment->userConfigurationData = $userConfigDataUpdated;

        $applicationInputs = AppUtilities::get_application_inputs($experiment->applicationId);

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
        return Airavata::getJobDetails($experimentId);
    }

    public static function get_transfer_details($experimentId)
    {
        return Airavata::getDataTransferDetails($experimentId);
    }

    public static function getQueueDatafromResourceId($crId)
    {
        $resourceObject = Airavata::getComputeResource($crId);
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