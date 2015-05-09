<?php

//Airavata classes - loaded from app/libraries/Airavata
use Airavata\API\Error\InvalidRequestException;
use Airavata\API\Error\AiravataClientException;
use Airavata\API\Error\AiravataSystemException;
use Airavata\API\Error\ExperimentNotFoundException;
use Airavata\Model\Workspace\Experiment\ComputationalResourceScheduling;
use Airavata\Model\AppCatalog\AppInterface\InputDataObjectType;
use Airavata\Model\Workspace\Experiment\UserConfigurationData;
use Airavata\Model\Workspace\Experiment\AdvancedOutputDataHandling;
use Airavata\Model\Workspace\Experiment\Experiment;
use Airavata\Model\Workspace\Experiment\ExperimentState;
use Airavata\Model\AppCatalog\AppInterface\DataType;
use Airavata\Model\Workspace\Experiment\JobState;

class ExpUtilities{

/**
 * Launch the experiment with the given ID
 * @param $expId
 */
public static function launch_experiment($expId)
{
    //global $tokenFilePath;
    try
    {
        /* temporarily using hard-coded token
        open_tokens_file($tokenFilePath);

        $communityToken = $tokenFile->tokenId;


        $token = isset($_SESSION['tokenId'])? $_SESSION['tokenId'] : $communityToken;

        $airavataclient->launchExperiment($expId, $token);

        $tokenString = isset($_SESSION['tokenId'])? 'personal' : 'community';

        Utilities::print_success_message('Experiment launched using ' . $tokenString . ' allocation!');
        */

        $hardCodedToken = 'bdc612fe-401e-4684-88e9-317f99409c45';
        $airavataclient->launchExperiment($expId, $hardCodedToken);

        /*
        Utilities::print_success_message('Experiment launched!');
        Utilities::print_success_message("<p>Experiment launched!</p>" .
            '<p>You will be redirected to the summary page shortly, or you can
            <a href="experiment_summary.php?expId=' . $expId . '">go directly</a> to the experiment summary page.</p>');
        redirect('experiment_summary.php?expId=' . $expId);
        */
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('<p>There was a problem launching the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
    }
    catch (ExperimentNotFoundException $enf)
    {
        Utilities::print_error_message('<p>There was a problem launching the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>ExperimentNotFoundException: ' . $enf->getMessage() . '</p>');
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('<p>There was a problem launching the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>AiravataClientException: ' . $ace->getMessage() . '</p>');
    }
    catch (AiravataSystemException $ase)
    {
        Utilities::print_error_message('<p>There was a problem launching the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>AiravataSystemException: ' . $ase->getMessage() . '</p>');
    }
    catch (Exception $e)
    {
        Utilities::print_error_message('<p>There was a problem launching the experiment.
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
    $applicationInputs = Utilities::get_application_inputs($experiment->applicationId);

    $experimentInputs = $experiment->experimentInputs;


    //showing experiment inputs in the order defined by the admins.
    $order = array();
    foreach ($experimentInputs as $index => $input)
    {
        $order[$index] = $input->inputOrder;
    }
    array_multisort($order, SORT_ASC, $experimentInputs);
    
    foreach ($experimentInputs as $input)
    {
        $matchingAppInput = null;

        foreach($applicationInputs as $applicationInput)
        {
            if ($input->name == $applicationInput->name)
            {
                $matchingAppInput = $applicationInput;
            }
        }
        //var_dump($matchingAppInput);

        if ($matchingAppInput->type == DataType::URI)
        {
            $explode = explode('/', $input->value);
            echo '<p><a target="_blank"
                        href="' . URL::to("/") . "/../../" . Constant::EXPERIMENT_DATA_ROOT . $explode[sizeof($explode)-2] . '/' . $explode[sizeof($explode)-1] . '">' .
                $explode[sizeof($explode)-1] . '
                <span class="glyphicon glyphicon-new-window"></span></a></p>';
        }
        elseif ($matchingAppInput->type == DataType::STRING)
        {
            echo '<p>' . $input->name . ': ' . $input->value . '</p>';
        }
    }
}


/**
 * Get a list of the inputs for the application with the given ID
 * @param $id
 * @return null
 */
public static function get_application_inputs($id)
{
    $inputs = null;

    try
    {
        $inputs = Airavata::getApplicationInputs($id);
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('<p>There was a problem getting application inputs.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('<p>There was a problem getting application inputs.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>Airavata Client Exception: ' . $ace->getMessage() . '</p>');
    }
    catch (AiravataSystemException $ase)
    {
        Utilities::print_error_message('<p>There was a problem getting application inputs.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>Airavata System Exception: ' . $ase->getMessage() . '</p>');
    }

    return $inputs;
}


/**
 * Get a list of the outputs for the application with the given ID
 * @param $id
 * @return null
 */
public static function get_application_outputs($id)
{
    $outputs = null;

    try
    {
        $outputs = Airavata::getApplicationOutputs($id);
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('<p>There was a problem getting application outputs.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('<p>There was a problem getting application outputs.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>Airavata Client Exception: ' . $ace->getMessage() . '</p>');
    }
    catch (AiravataSystemException $ase)
    {
        Utilities::print_error_message('<p>There was a problem getting application outputs.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>Airavata System Exception: ' . $ase->getMessage() . '</p>');
    }

    return $outputs;
}


/**
 * Get the experiment with the given ID
 * @param $expId
 * @return null
 */
public static function get_experiment($expId)
{
    try
    {
        return Airavata::getExperiment($expId);
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('<p>There was a problem getting the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
    }
    catch (ExperimentNotFoundException $enf)
    {
        Utilities::print_error_message('<p>There was a problem getting the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>ExperimentNotFoundException: ' . $enf->getMessage() . '</p>');
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('<p>There was a problem getting the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>AiravataClientException: ' . $ace->getMessage() . '</p>');
    }
    catch (AiravataSystemException $ase)
    {
        Utilities::print_error_message('<p>There was a problem getting the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>AiravataSystemException: ' . $ase->getMessage() . '</p>');
    }
    catch (TTransportException $tte)
    {
        Utilities::print_error_message('<p>There was a problem getting the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>TTransportException: ' . $tte->getMessage() . '</p>');
    }
    catch (Exception $e)
    {
        Utilities::print_error_message('<p>There was a problem getting the experiment.
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
    $utility = new Utilities();
    $experimentInputs = array();

    $scheduling = new ComputationalResourceScheduling();
    $scheduling->totalCPUCount = $_POST['cpu-count'];
    $scheduling->nodeCount = $_POST['node-count'];
    $scheduling->queueName = $_POST['queue-name'];
    //$scheduling->numberOfThreads = $_POST['threads'];
    //$scheduling->queueName = 'normal';
    $scheduling->wallTimeLimit = $_POST['wall-time'];
    //$scheduling->totalPhysicalMemory = $_POST['memory'];
    $scheduling->resourceHostId = $_POST['compute-resource'];

    $userConfigData = new UserConfigurationData();
    $userConfigData->computationalResourceScheduling = $scheduling;

    $applicationInputs = Utilities::get_application_inputs($_POST['application']);
    $experimentInputs = Utilities::process_inputs($applicationInputs, $experimentInputs);
    //var_dump($experimentInputs);

    if( Utilities::$experimentPath != null){
        $advHandling = new AdvancedOutputDataHandling();

        $advHandling->outputDataDir = str_replace( base_path() . Constant::EXPERIMENT_DATA_ROOT, Utilities::$pathConstant , Utilities::$experimentPath);
        $userConfigData->advanceOutputDataHandling = $advHandling;
    }

    //TODO: replace constructor with a call to airvata to get a prepopulated experiment template
    $experiment = new Experiment();

    // required
    $experiment->projectID = $_POST['project'];
    $experiment->userName = Session::get( 'username');
    $experiment->name = $_POST['experiment-name'];

    // optional
    $experiment->description = $_POST['experiment-description'];
    $experiment->applicationId = $_POST['application'];
    $experiment->userConfigurationData = $userConfigData;
    $experiment->experimentInputs = $experimentInputs;
    if( isset( $_POST["emailNotification"]))
    {
        $experiment->emailNotification = intval( $_POST["emailNotification"] );
        $experiment->emailAddresses = array_unique( array_filter( $_POST["emailAddresses"], "trim") );
    }

    // adding default experiment outputs for now till prepoulated experiment template is not implemented.
    $experiment->experimentOutputs = Utilities::get_application_outputs( $_POST["application"]);

    if ($experimentInputs)
    {
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
    $utility = new Utilities();
    $experimentAssemblySuccessful = true;
    $newExperimentInputs = array();

    //var_dump($_FILES);

    if (sizeof($_FILES) > 0)
    {
        if (Utilities::file_upload_successful())
        {
            // construct unique path
            do
            {
                Utilities::$experimentPath = base_path() . Constant::EXPERIMENT_DATA_ROOT . str_replace(' ', '', Session::get('username') ) . md5(rand() * time()) . '/';
            }
            while (is_dir( Utilities::$experimentPath)); // if dir already exists, try again

            //var_dump( Utilities::$experimentPath ); exit;
            // create upload directory
            if (!mkdir( Utilities::$experimentPath))
            {
                Utilities::print_error_message('<p>Error creating upload directory!
                    Please try again later or report a bug using the link in the Help menu.</p>');
                $experimentAssemblySuccessful = false;
            }
        }
        else
        {
            $experimentAssemblySuccessful = false;
        }
    }

    //sending application inputs in the order defined by the admins.
    $order = array();
    foreach ($applicationInputs as $index => $input)
    {
        $order[$index] = $input->inputOrder;
    }
    array_multisort($order, SORT_ASC, $applicationInputs);
    
    foreach ($applicationInputs as $applicationInput)
    {
        $experimentInput = new InputDataObjectType();
        $experimentInput = $applicationInput;
        //$experimentInput->name = $applicationInput->name;
        //$experimentInput->metaData = $applicationInput->metaData;


        //$experimentInput->type = $applicationInput->type;
        //$experimentInput->type = DataType::STRING;


        if(($applicationInput->type == DataType::STRING) ||
            ($applicationInput->type == DataType::INTEGER) ||
            ($applicationInput->type == DataType::FLOAT))
        {
            if (isset($_POST[$applicationInput->name]) && (trim($_POST[$applicationInput->name]) != ''))
            {
                $experimentInput->value = $_POST[$applicationInput->name];
                $experimentInput->type = $applicationInput->type;

            }
            else // use previous value
            {
                $index = -1;
                for ($i = 0; $i < sizeof($experimentInputs); $i++)
                {
                    if ($experimentInputs[$i]->name == $applicationInput->name)
                    {
                        $index = $i;
                    }
                }

                if ($index >= 0)
                {
                    $experimentInput->value = $experimentInputs[$index]->value;
                    $experimentInput->type = $applicationInput->type;
                }
            }
        }
        elseif ($applicationInput->type == DataType::URI)
        {
            //var_dump($_FILES[$applicationInput->name]->name);
            if ($_FILES[$applicationInput->name]['name'])
            {
                $file = $_FILES[$applicationInput->name];


                //
                // move file to experiment data directory
                //
                $filePath = Utilities::$experimentPath . $file['name'];

                // check if file already exists
                if (is_file($filePath))
                {
                    unlink($filePath);

                    Utilities::print_warning_message('Uploaded file already exists! Overwriting...');
                }

                $moveFile = move_uploaded_file($file['tmp_name'], $filePath);

                if ($moveFile)
                {
                    Utilities::print_success_message('Upload: ' . $file['name'] . '<br>' .
                        'Type: ' . $file['type'] . '<br>' .
                        'Size: ' . ($file['size']/1024) . ' kB');//<br>' .
                        //'Stored in: ' . $experimentPath . $file['name']);
                }
                else
                {
                    Utilities::print_error_message('<p>Error moving uploaded file ' . $file['name'] . '!
                    Please try again later or report a bug using the link in the Help menu.</p>');
                    $experimentAssemblySuccessful = false;
                }

                $experimentInput->value = str_replace(base_path() . Constant::EXPERIMENT_DATA_ROOT, Utilities::$pathConstant , $filePath);
                $experimentInput->type = $applicationInput->type;
                
            }
            else
            {
                $index = -1;
                for ($i = 0; $i < sizeof($experimentInputs); $i++)
                {
                    if ($experimentInputs[$i]->name == $applicationInput->name)
                    {
                        $index = $i;
                    }
                }

                if ($index >= 0)
                {
                    $experimentInput->value = $experimentInputs[$index]->value;
                    $experimentInput->type = $applicationInput->type;
                }
            }

        }
        else
        {
            Utilities::print_error_message('I cannot accept this input type yet!');
        }







        //$experimentInputs[] = $experimentInput;
        /*
        $index = -1;
        for ($i = 0; $i < sizeof($experimentInputs); $i++)
        {
            if ($experimentInputs[$i]->key == $experimentInput->key)
            {
                $index = $i;
            }
        }

        if ($index >= 0)
        {
            unset($experimentInputs[$index]);
        }
        */
        //$experimentInputs[] = $experimentInput;





        $newExperimentInputs[] = $experimentInput;


    }

    if ($experimentAssemblySuccessful)
    {
        return $newExperimentInputs;
    }
    else
    {
        return false;
    }

}


/**
 * Check the uploaded files for errors
 */
public static function file_upload_successful()
{
    $uploadSuccessful = true;

    foreach ($_FILES as $file)
    {
        //var_dump($file);
        if($file['name'])
        {
            if ($file['error'] > 0)
            {
                $uploadSuccessful = false;
                Utilities::print_error_message('<p>Error uploading file ' . $file['name'] . ' !
                    Please try again later or report a bug using the link in the Help menu.');
            }/*
            elseif ($file['type'] != 'text/plain')
            {
                $uploadSuccessful = false;
                Utilities::print_error_message('Uploaded file ' . $file['name'] . ' type not supported!');
            }
            elseif (($file['size'] / 1024) > 20)
            {
                $uploadSuccessful = false;
                Utilities::print_error_message('Uploaded file ' . $file['name'] . ' must be smaller than 10 MB!');
            }*/
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
    try
    {
        Airavata::updateExperiment($expId, $updatedExperiment);

        /*
        Utilities::print_success_message("<p>Experiment updated!</p>" .
            '<p>Click
            <a href="' . URL::to('/') . '/experiment/summary?expId=' . $expId . '">here</a> to visit the experiment summary page.</p>');
        */
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('<p>There was a problem updating the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
    }
    catch (ExperimentNotFoundException $enf)
    {
        Utilities::print_error_message('<p>There was a problem updating the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>ExperimentNotFoundException: ' . $enf->getMessage() . '</p>');
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('<p>There was a problem updating the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>AiravataClientException: ' . $ace->getMessage() . '</p>');
    }
    catch (AiravataSystemException $ase)
    {
        Utilities::print_error_message('<p>There was a problem updating the experiment.
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
    try
    {
        //create new experiment to receive the clone
        $experiment = Airavata::getExperiment($expId);

        $cloneId = Airavata::cloneExperiment($expId, 'Clone of ' . $experiment->name);

        Utilities::print_success_message("<p>Experiment cloned!</p>" .
            '<p>You will be redirected to the edit page shortly, or you can
            <a href="edit_experiment.php?expId=' . $cloneId . '">go directly</a> to the edit experiment page.</p>');
        //redirect('edit_experiment.php?expId=' . $cloneId);
        return $cloneId;
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('<p>There was a problem cloning the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
    }
    catch (ExperimentNotFoundException $enf)
    {
        Utilities::print_error_message('<p>There was a problem cloning the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>ExperimentNotFoundException: ' . $enf->getMessage() . '</p>');
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('<p>There was a problem cloning the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>AiravataClientException: ' . $ace->getMessage() . '</p>');
    }
    catch (AiravataSystemException $ase)
    {
        Utilities::print_error_message('<p>There was a problem cloning the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>AiravataSystemException: ' . $ase->getMessage() . '</p>');
    }
    catch (TTransportException $tte)
    {
        Utilities::print_error_message('<p>There was a problem cloning the experiment.
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
    try
    {
        Airavata::terminateExperiment($expId);

        Utilities::print_success_message("Experiment canceled!");
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('<p>There was a problem canceling the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
    }
    catch (ExperimentNotFoundException $enf)
    {
        Utilities::print_error_message('<p>There was a problem canceling the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>ExperimentNotFoundException: ' . $enf->getMessage() . '</p>');
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('<p>There was a problem canceling the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>AiravataClientException: ' . $ace->getMessage() . '</p>');
    }
    catch (AiravataSystemException $ase)
    {
        Utilities::print_error_message('<p>There was a problem canceling the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>AiravataSystemException: ' . $ase->getMessage() . '</p>');
    }
    catch (TTransportException $tte)
    {
        Utilities::print_error_message('<p>There was a problem canceling the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>TTransportException: ' . $tte->getMessage() . '</p>');
    }
    catch (Exception $e)
    {
        Utilities::print_error_message('<p>There was a problem canceling the experiment.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>Exception: ' . $e->getMessage() . '</p>');
    }
}


/**
 * Create a new experiment from the values submitted in the form
 * @return null
 */
public static function create_experiment()
{
    $experiment = Utilities::assemble_experiment();
    //var_dump($experiment); exit;
    $expId = null;

    try
    {
        if($experiment)
        {
            $expId = Airavata::createExperiment( Session::get("gateway_id"), $experiment);
        }

        if ($expId)
        {
            /*
            Utilities::print_success_message("Experiment {$_POST['experiment-name']} created!" .
                ' <a href="experiment_summary.php?expId=' . $expId . '">Go to experiment summary page</a>');
            */
        }
        else
        {
            Utilities::print_error_message("Error creating experiment {$_POST['experiment-name']}!");
        }
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('InvalidRequestException!<br><br>' . $ire->getMessage());
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('AiravataClientException!<br><br>' . $ace->getMessage());
    }
    catch (AiravataSystemException $ase)
    {
        Utilities::print_error_message('AiravataSystemException!<br><br>' . $ase->getMessage());
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

    if( $expStatusVal == ExperimentState::COMPLETED )
    {
        $utility = new Utilities();
        $experimentOutputs = $experiment->experimentOutputs;
        foreach ((array)$experimentOutputs as $output)
        {
            if ($output->type == DataType::URI || $output->type == DataType::STDOUT || $output->type == DataType::STDERR )
            {
                //echo '<p>' . $output->key .  ': <a href="' . $output->value . '">' . $output->value . '</a></p>';
                $outputPath = str_replace(Utilities::$experimentDataPathAbsolute, Constant::EXPERIMENT_DATA_ROOT, $output->value);
                $outputPathArray = explode("/", $outputPath);

                echo '<p>' . $output->name  . ' : ' . '<a target="_blank"
                            href="' . URL::to("/") . "/.." . str_replace(Utilities::$experimentDataPathAbsolute, Constant::EXPERIMENT_DATA_ROOT, $output->value) . '">' . 
                            $outputPathArray[ sizeof( $outputPathArray) - 1] . ' <span class="glyphicon glyphicon-new-window"></span></a></p>';
            }
            elseif ($output->type == DataType::STRING)
            {
                echo '<p>' . $output->value . '</p>';
            }
        }
    }
    else
        echo "Experiment hasn't completed. Experiment Status is : " . $expStatus;

}
public static function get_experiment_values( $experiment, $project, $forSearch = false)
{
    $expVal = array();
    $expVal["experimentStatusString"] = "";
    $expVal["experimentTimeOfStateChange"] = "";
    $expVal["experimentCreationTime"] = "";

    if( $experiment->experimentStatus != null)
    {
        $experimentStatus = $experiment->experimentStatus;
        $experimentState = $experimentStatus->experimentState;
        $experimentStatusString = ExperimentState::$__names[$experimentState];
        $expVal["experimentStatusString"] = $experimentStatusString;
        $expVal["experimentTimeOfStateChange"] = date('Y-m-d H:i:s', $experimentStatus->timeOfStateChange/1000); // divide by 1000 since timeOfStateChange is in ms
        $expVal["experimentCreationTime"] = date('Y-m-d H:i:s', $experiment->creationTime/1000); // divide by 1000 since creationTime is in ms
    }
    $jobStatus = Airavata::getJobStatuses($experiment->experimentID);

    if ($jobStatus)
    {
        $jobName = array_keys($jobStatus);
        $jobState = JobState::$__names[$jobStatus[$jobName[0]]->jobState];
    }
    else
    {
        $jobState = null;
    }

    $expVal["jobState"] = $jobState;
    
    if(! $forSearch)
    {
        $userConfigData = $experiment->userConfigurationData;
        $scheduling = $userConfigData->computationalResourceScheduling;
        $expVal['scheduling'] = $scheduling;
        $expVal["computeResource"] = Utilities::get_compute_resource($scheduling->resourceHostId);
    }
    $expVal["applicationInterface"] = Utilities::get_application_interface($experiment->applicationId);


    switch ($experimentStatusString)
    {
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

    switch ($experimentStatusString)
    {
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
 * Get results of the user's search of experiments
 * @return array|null
 */
public static function get_expsearch_results( $inputs)
{
    $experiments = array();

    try
    {
        switch ( $inputs["search-key"])
        {
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
                $experiments = Airavata::searchExperimentsByCreationTime(Session::get('gateway_id'), Session::get('username'), strtotime( $inputs["from-date"])*1000, strtotime( $inputs["to-date"])*1000 );
                break;
            case '':
        }
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('InvalidRequestException!<br><br>' . $ire->getMessage());
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('AiravataClientException!<br><br>' . $ace->getMessage());
    }
    catch (AiravataSystemException $ase)
    {
        if ($ase->airavataErrorType == 2) // 2 = INTERNAL_ERROR
        {
            Utilities::print_info_message('<p>You have not created any experiments yet, so no results will be returned!</p>
                                <p>Click <a href="create_experiment.php">here</a> to create an experiment, or
                                <a href="create_project.php">here</a> to create a new project.</p>');
        }
        else
        {
            Utilities::print_error_message('There was a problem with Airavata. Please try again later or report a bug using the link in the Help menu.');
            //print_error_message('AiravataSystemException!<br><br>' . $ase->airavataErrorType . ': ' . $ase->getMessage());
        }
    }
    catch (TTransportException $tte)
    {
        Utilities::print_error_message('TTransportException!<br><br>' . $tte->getMessage());
    }

    //get values of all experiments
    $expContainer = array();
    $expNum = 0;
    foreach( $experiments as $experiment)
    {
        $expValue = Utilities::get_experiment_values( $experiment, Utilities::get_project($experiment->projectID), true );
        $expContainer[$expNum]['experiment'] = $experiment;
        if( $expValue["experimentStatusString"] == "FAILED")
            $expValue["editable"] = false;
        $expContainer[$expNum]['expValue'] = $expValue;
        $expNum++;
    }

    return $expContainer;
}

public static function getExpStates(){
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
    $schedulingUpdated->wallTimeLimit = $input['wall-time'];

    $userConfigDataUpdated->computationalResourceScheduling = $schedulingUpdated;
    $experiment->userConfigurationData = $userConfigDataUpdated;




    $applicationInputs = Utilities::get_application_inputs($experiment->applicationId);

    $experimentInputs = $experiment->experimentInputs; // get current inputs
    $experimentInputs = Utilities::process_inputs($applicationInputs, $experimentInputs); // get new inputs
    
    if ($experimentInputs)
    {
        $experiment->experimentInputs = $experimentInputs;
        //var_dump($experiment);
        return $experiment;
    }
}



}

?>
