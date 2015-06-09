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
use Airavata\Model\Workspace\Project;
use Airavata\Model\Workspace\Experiment\JobState;



class Utilities{
/**
 * Basic utility functions
 */

/*

************* IMPORTANT ************

READ :: ALL CONSTANTS ARE NOW BEING CALLED FROM app/models/Constant.php. 

************* IMPORTANT ************
*/
private $tokenFilePath = 'tokens.xml';
private $tokenFile = null;

private $sshUser;
private $hostName;
private static $pathConstant;
private static $experimentPath;
private static $experimentDataPathAbsolute;

function __construct(){
	$this->sshUser = "root";
	$this->hostName = $_SERVER['SERVER_NAME'];

    self::$experimentDataPathAbsolute = base_path() . Constant::EXPERIMENT_DATA_ROOT;
	self::$pathConstant = 'file://'.$this->sshUser.'@'.$this->hostName.':' . self::$experimentDataPathAbsolute;
	self::$experimentPath = null;
}

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
    Session::put('username', $username );
    Session::put('loggedin', true);
}

/**
 * Return true if the username stored in the session
 * @return bool
 */
public static function id_in_session()
{
    if( Session::has("username") && Session::has('loggedin') )
        return true;
    else
        return false;
}

/**
 * Verify if the user is already logged in. If not, redirect to the home page.
 */
public static function verify_login()
{
    if (Utilities::id_in_session())
    {
        return true;
    }
    else
    {
        Utilities::print_error_message('User is not logged in!');
        return false;
    }
}

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

        $hardCodedToken = Config::get('pga_config.airavata')['credential-store-token'];
        Airavata::launchExperiment($expId, $hardCodedToken);

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
 * Get all projects owned by the given user
 * @param $username
 * @return null
 */
public static function get_all_user_projects($gatewayId, $username)
{
    $userProjects = null;

    try
    {
        $userProjects = Airavata::getAllUserProjects($gatewayId, $username);
        //var_dump( $userProjects); exit;
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('<p>There was a problem getting the user\'s projects.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>InvalidRequestException: ' . $ire->getMessage(). '</p>');
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('<p>There was a problem getting the user\'s projects.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>Airavata Client Exception: ' . $ace->getMessage(). '</p>');
    }
    catch (AiravataSystemException $ase)
    {
        if ($ase->airavataErrorType == 2) // 2 = INTERNAL_ERROR
        {
            Utilities::print_warning_message('<p>You must create a project before you can create an experiment.
                Click <a href="' . URL::to('/') . '/project/create">here</a> to create a project.</p>');
        }
        else
        {
            Utilities::print_error_message('<p>There was a problem getting the user\'s projects.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>AiravataSystemException: ' . $ase->getMessage() . '</p>');
        }
    }

    return $userProjects;
}


/**
 * Get all available applications
 * @return null
 */
public static function get_all_applications()
{
    $applications = null;

    try
    {
        $applications = Airavata::getAllApplicationInterfaceNames( Session::get("gateway_id"));
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('<p>There was a problem getting all applications.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('<p>There was a problem getting all applications.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>Airavata Client Exception: ' . $ace->getMessage() . '</p>');
    }
    catch (AiravataSystemException $ase)
    {
        Utilities::print_warning_message('<p>You must create an application module, interface and deployment space before you can create an experiment.
                Click <a href="' . URL::to('/') . '/app/module">here</a> to create an application.</p>');
        /*
        Utilities::print_error_message('<p>There was a problem getting all applications.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>Airavata System Exception: ' . $ase->getMessage() . '</p>');
            */
    }

    if( count( $applications) == 0)
        Utilities::print_warning_message('<p>You must create an application module, interface and deployment space before you can create an experiment.
                Click <a href="' . URL::to('/') . '/app/module">here</a> to create an application.</p>');
        

    return $applications;
}


/**
 * Get the interface for the application with the given ID
 * @param $id
 * @return null
 */
public static function get_application_interface($id)
{
    $applicationInterface = null;

    try
    {
        $applicationInterface = Airavata::getApplicationInterface($id);
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('<p>There was a problem getting the application interface.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>InvalidRequestException: ' . $ire->getMessage(). '</p>');
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('<p>There was a problem getting the application interface.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>Airavata Client Exception: ' . $ace->getMessage() . '</p>');
    }
    catch (AiravataSystemException $ase)
    {
        Utilities::print_error_message('<p>There was a problem getting the application interface.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>Airavata System Exception: ' . $ase->getMessage() . '</p>');
    }

    return $applicationInterface;
}


/**
 * Get a list of compute resources available for the given application ID
 * @param $id
 * @return null
 */
public static function get_available_app_interface_compute_resources($id)
{
    $computeResources = null;

    try
    {
        $computeResources = Airavata::getAvailableAppInterfaceComputeResources($id);
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('<p>There was a problem getting compute resources.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('<p>There was a problem getting compute resources.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>Airavata Client Exception: ' . $ace->getMessage() . '</p>');
    }
    catch (AiravataSystemException $ase)
    {
        Utilities::print_error_message('<p>There was a problem getting compute resources.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>Airavata System Exception: ' . $ase->getMessage() . '</p>');
    }

    return $computeResources;
}


/**
 * Get the ComputeResourceDescription with the given ID
 * @param $id
 * @return null
 */
public static function get_compute_resource($id)
{
    $computeResource = null;

    try
    {
        $computeResource = Airavata::getComputeResource($id);
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('<p>There was a problem getting the compute resource.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('<p>There was a problem getting the compute resource.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>Airavata Client Exception: ' . $ace->getMessage() . '</p>');
    }
    catch (AiravataSystemException $ase)
    {
        Utilities::print_error_message('<p>There was a problem getting the compute resource.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>Airavata System Exception: ' . $ase->getMessage() . '</p>');
    }

    return $computeResource;
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
 * Get the project with the given ID
 * @param $projectId
 * @return null
 */
public static function get_project($projectId)
{

    try
    {
        return Airavata::getProject($projectId);
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('<p>There was a problem getting the project.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('<p>There was a problem getting the project.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>AiravataClientException: ' . $ace->getMessage() . '</p>');
    }
    catch (AiravataSystemException $ase)
    {
        Utilities::print_error_message('<p>There was a problem getting the project.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
            '<p>AiravataSystemException!<br><br>' . $ase->getMessage() . '</p>');
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
    $scheduling->wallTimeLimit = $_POST['wall-time'];    
    $scheduling->totalPhysicalMemory = $_POST['total-physical-memory'];
    $scheduling->resourceHostId = $_POST['compute-resource'];

    $userConfigData = new UserConfigurationData();
    $userConfigData->computationalResourceScheduling = $scheduling;
    if( isset( $_POST["userDN"]) )
    {        
        $userConfigData->generateCert = 1;
        $userConfigData->userDN = $_POST["userDN"];
    }

    $applicationInputs = Utilities::get_application_inputs($_POST['application']);
    $experimentInputs = Utilities::process_inputs($applicationInputs, $experimentInputs);

    if( Utilities::$experimentPath == null){
        Utilities::create_experiment_folder_path();
    }

    $advHandling = new AdvancedOutputDataHandling();

    $advHandling->outputDataDir = str_replace( base_path() . Constant::EXPERIMENT_DATA_ROOT, Utilities::$pathConstant , Utilities::$experimentPath);
    $userConfigData->advanceOutputDataHandling = $advHandling;

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
    if( isset( $_POST["enableEmailNotification"]))
    {
        $experiment->enableEmailNotification = intval( $_POST["enableEmailNotification"] );
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
            Utilities::create_experiment_folder_path();
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


public static function create_experiment_folder_path()
{
    do
    {
        Utilities::$experimentPath = base_path() . Constant::EXPERIMENT_DATA_ROOT . str_replace(' ', '', Session::get('username') ) . md5(rand() * time()) . '/';
    }
    while (is_dir( Utilities::$experimentPath)); // if dir already exists, try again
    // create upload directory
    if (!mkdir( Utilities::$experimentPath))
    {
        Utilities::print_error_message('<p>Error creating upload directory!
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
        Airavata::terminateExperiment($expId, Config::get('pga_config.airavata')["credential-store-token"]);

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
 * Create a select input and populate it with project options from the database
 */
public static function create_project_select($projectId = null, $editable = true)
{
    $editable? $disabled = '' : $disabled = 'disabled';
    $userProjects = Utilities::get_all_user_projects( Session::get("gateway_id"), Session::get('username') );

    echo '<select class="form-control" name="project" id="project" required ' . $disabled . '>';
    if (sizeof($userProjects) > 0)
    {
        foreach ($userProjects as $project)
        {
            if ($project->projectID == $projectId)
            {
                $selected = 'selected';
            }
            else
            {
                $selected = '';
            }

            echo '<option value="' . $project->projectID . '" ' . $selected . '>' . $project->name . '</option>';
        }
    }
    echo '</select>';
    if( sizeof($userProjects) == 0 )
    {
        Utilities::print_warning_message('<p>You must create a project before you can create an experiment.
                Click <a href="' . URL::to('/') . '/project/create">here</a> to create a project.</p>');
    }
}


/**
 * Create a select input and populate it with applications options
 * @param null $id
 * @param bool $editable
 */
public static function create_application_select($id = null, $editable = true)
{
    $disabled = $editable? '' : 'disabled';

    $applicationIds = Utilities::get_all_applications();

    echo '<select class="form-control" name="application" id="application" required ' . $disabled . '>';

    if( count( $applicationIds))
    {
        foreach ( (array) $applicationIds as $applicationId => $applicationName)
        {
            $selected = ($applicationId == $id) ? 'selected' : '';
    
            echo '<option value="' . $applicationId . '" ' . $selected . '>' . $applicationName . '</option>';
        }
    }

    echo '</select>';
}


/**
 * Create a select input and populate it with compute resources
 * available for the given application ID
 * @param $applicationId
 * @param $resourceHostId
 */
public static function create_compute_resources_select($applicationId, $resourceHostId)
{
    return Utilities::get_available_app_interface_compute_resources($applicationId);
}


/**
 * Create form inputs to accept the inputs to the given application
 * @param $id
 * @param $isRequired
 * @internal param $required
 */
public static function create_inputs($id, $isRequired)
{
    $inputs = Utilities::get_application_inputs($id);

    $required = $isRequired? ' required' : '';

    //var_dump( $inputs);  echo "<br/>after sort<br/>";
    //arranging inputs in ascending order.
    foreach ($inputs as $index => $input)
    {
        $order[$index] = $input->inputOrder;
    }
    array_multisort($order, SORT_ASC, $inputs);
    //var_dump( $inputs); exit;
    foreach ($inputs as $input)
    {
        switch ($input->type)
        {
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
                Utilities::print_error_message('Input data type not supported!
                    Please file a bug report using the link in the Help menu.');
                break;
        }
    }
}


/**
 * Create navigation bar
 * Used for all pages
 */
public static function create_nav_bar()
{
	$menus = array();
/*
	if( Session::has('loggedin'))
	{
	    $menus = array
	    (
	        'Project' => array
	        (
	            array('label' => 'Create Project', 'url' => URL::to('/') . '/project/create'),
	            array('label' => 'Search Projects', 'url' => URL::to('/') . '/project/search')
	        ),
	        'Experiment' => array
	        (
	            array('label' => 'Create Experiment', 'url' => URL::to('/') . '/experiment/create'),
	            array('label' => 'Search Experiments', 'url' => URL::to('/') . '/experiment/search')
	        ),
	        'Compute Resource' => array
	        (
	            array('label' => 'Register', 'url' => URL::to('/') . '/cr/create'),
	            array('label' => 'Browse', 'url' => URL::to('/') . '/cr/browse')
	        ),
	        'App Catalog' => array
	        (
	            array('label' => 'Module', 'url' => URL::to('/') . '/app/module'),
	            array('label' => 'Interface', 'url' => URL::to('/') . '/app/interface'),
	            array('label' => 'Deployment', 'url' => URL::to('/') . '/app/deployment')
	        ),
	        'Help' => array
	        (
	            array('label' => 'Report Issue', 'url' => '#'),
	            array('label' => 'Request Feature', 'url' => '#')
	        )
	    );
	}
*/
	if( Session::has('loggedin'))
	{
	    $menus = array
	    (
	        'Project' => array
	        (
	            array('label' => 'Create', 'url' => URL::to('/') . '/project/create', "nav-active" => "project"),
	            array('label' => 'Search', 'url' => URL::to('/') . '/project/search', "nav-active"=> "project"),
                array('label' => 'Browse', 'url' => URL::to('/') . '/project/browse', "nav-active" => "project")
	        ),
	        'Experiment' => array
	        (
	            array('label' => 'Create', 'url' => URL::to('/') . '/experiment/create', "nav-active" => "experiment"),
	            array('label' => 'Search', 'url' => URL::to('/') . '/experiment/search', "nav-active" => "experiment"),
                array('label' => 'Browse', 'url' => URL::to('/') . '/experiment/browse', "nav-active" => "experiment")
	        )
	    );

	    if( Session::has("admin"))
	    {
	    	$menus['Compute Resource'] = array
	        (
	            array('label' => 'Register', 'url' => URL::to('/') . '/cr/create', "nav-active" => "compute-resource"),
	            array('label' => 'Browse', 'url' => URL::to('/') . '/cr/browse', "nav-active" => "compute-resource")
	        );
	        $menus['App Catalog'] = array
	        (
	            array('label' => 'Module', 'url' => URL::to('/') . '/app/module', "nav-active" => "app-catalog"),
	            array('label' => 'Interface', 'url' => URL::to('/') . '/app/interface', "nav-active" => "app-catalog"),
	            array('label' => 'Deployment', 'url' => URL::to('/') . '/app/deployment', "nav-active" => "app-catalog")
	        );
            /*
            $menus['Gateway Profile'] = array
            (

                array('label' => 'Register', 'url' => URL::to('/') . '/gp/create', "nav-active" => "gateway-profile"),
                array('label' => 'Browse', 'url' => URL::to('/') . '/gp/browse', "nav-active" => "gateway-profile")
            );
            */
               
	    }
        
        $menus['Help'] = array
        (
            array('label' => 'Report Issue', 'url' => '#', "nav-active", ""),
            array('label' => 'Request Feature', 'url' => '#', "nav-active", "")
        );
	}

    $selfExplode = explode('/', $_SERVER['PHP_SELF']);



    // nav bar and left-aligned content

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


    foreach ($menus as $label => $options)
    {
        Session::has('loggedin') ? $disabled = '' : $disabled = ' class="disabled"';

        $active = "";
        if( Session::has("nav-active") && isset( $options[0]['nav-active'] ) )
        {
	        if( $options[0]['nav-active'] == Session::get("nav-active"))
	        	$active = " active ";
        }
        echo '<li class="dropdown ' . $active . '">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $label . '<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">';

        if( Session::has('loggedin'))
        {
	        foreach ($options as $option)
	        {
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

    if ( Session::has('loggedin') )
    {
        $active = "";
        if( Session::has("nav-active") )
        {
            if( "user-console" == Session::get("nav-active"))
                $active = " active ";
        }
        if( Session::has("admin"))
            echo '<li><a href="' . URL::to("/") . '/admin/dashboard"><span class="glyphicon glyphicon-user"></span> Dashboard</a></li>';
        else
            echo '<li><a href="' . URL::to("/") . '/user/profile"><span class="glyphicon glyphicon-user"></span> Profile</a></li>';

        echo '<li class="dropdown ' . $active . '">

                <a href="#" class="dropdown-toggle" data-toggle="dropdown">' . Session::get("username") . ' <span class="caret"></span></a>';
        echo '<ul class="dropdown-menu" role="menu">';

        echo '<li><a href="' . URL::to('/') . '/logout"><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>';
        echo    '</ul></li></ul>';
    }
    else
    {
        echo '<li><a href="' . URL::to('/') . '/create"><span class="glyphicon glyphicon-user"></span> Create account</a></li>';
        echo '<li><a href="' . URL::to('/') . '/login"><span class="glyphicon glyphicon-log-in"></span> Log in</a></li>';
        echo '</ul>';

    }

    echo '</div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
    </nav>';
}

/**
* Add attributes to the HTTP header.
*/
public static function create_http_header()
{
   header( 'Cache-Control: no-store, no-cache, must-revalidate' );
   header( 'Cache-Control: post-check=0, pre-check=0', false );
   header( 'Pragma: no-cache' );
}

/**
 * Open the XML file containing the community token
 * @param $tokenFilePath
 * @throws Exception
 */
public static function open_tokens_file($tokenFilePath)
{
    if (file_exists( $tokenFilePath ))
    {
        $tokenFile = simplexml_load_file( $tokenFilePath );
    }
    else
    {
        throw new Exception('Error: Cannot connect to tokens database!');
    }


    if (!$tokenFile)
    {
        throw new Exception('Error: Cannot open tokens database!');
    }
}


/**
 * Write the new token to the XML file
 * @param $tokenId
 */
public static function write_new_token($tokenId)
{    // write new tokenId to tokens file
    $tokenFile->tokenId = $tokenId;

    //Format XML to save indented tree rather than one line
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML( $tokenFile->asXML());
    $dom->save( $tokenFilePath );
}


//moved from create project view.

public static function create_project()
{
    $project = new Project();
    $project->owner = Session::get('username');
    $project->name = $_POST['project-name'];
    $project->description = $_POST['project-description'];


    $projectId = null;

    try
    {
        $projectId = Airavata::createProject(Config::get('pga_config.airavata')['gateway-id'], $project);

        if ($projectId)
        {
            Utilities::print_success_message("<p>Project {$_POST['project-name']} created!</p>" .
                '<p>You will be redirected to the summary page shortly, or you can
                <a href="project/summary?projId=' . $projectId . '">go directly</a> to the project summary page.</p>');
        }
        else
        {
            Utilities::print_error_message("Error creating project {$_POST['project-name']}!");
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

    return $projectId;
}

/**
 * Get experiments in project
 * @param $projectId
 * @return array|null
 */
public static function get_experiments_in_project($projectId)
{

    $experiments = array();

    try
    {
        $experiments = Airavata::getAllExperimentsInProject($projectId);
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
    catch (TTransportException $tte)
    {
        Utilities::print_error_message('TTransportException!<br><br>' . $tte->getMessage());
    }

    return $experiments;
}

public static function update_project($projectId, $projectDetails)
{

    $updatedProject = new Project();
    $updatedProject->owner = $projectDetails["owner"];
    $updatedProject->name = $projectDetails["name"];
    $updatedProject->description = $projectDetails["description"];

    try
    {
        Airavata::updateProject($projectId, $updatedProject);

        //Utilities::print_success_message('Project updated! Click <a href="project_summary.php?projId=' . $projectId . '">here</a> to view the project summary.');
    }
    catch (InvalidRequestException $ire)
    {
        Utilities::print_error_message('InvalidRequestException!<br><br>' . $ire->getMessage());
    }
    catch (ProjectNotFoundException $pnfe)
    {
        Utilities::print_error_message('ProjectNotFoundException!<br><br>' . $pnfe->getMessage());
    }
    catch (AiravataClientException $ace)
    {
        Utilities::print_error_message('AiravataClientException!<br><br>' . $ace->getMessage());
    }
    catch (AiravataSystemException $ase)
    {
        Utilities::print_error_message('AiravataSystemException!<br><br>' . $ase->getMessage());
    }
}


/**
 * Create a new experiment from the values submitted in the form
 * @return null
 */
public static function create_experiment()
{

    $experiment = Utilities::assemble_experiment();
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

    if($expStatusVal == ExperimentState::COMPLETED )
    {
        $utility = new Utilities();
        $experimentOutputs = $experiment->experimentOutputs;

        foreach ((array)$experimentOutputs as $output)
        {   
            if ($output->type == DataType::URI || $output->type == DataType::STDOUT || $output->type == DataType::STDERR )
            {
                $explode = explode('/', $output->value);
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
    //var_dump( $experiment); exit;
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
        $expVal["experimentTimeOfStateChange"] = $experimentStatus->timeOfStateChange/1000; // divide by 1000 since timeOfStateChange is in ms
        $expVal["experimentCreationTime"] = $experiment->creationTime/1000; // divide by 1000 since creationTime is in ms
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

    public static function get_all_user_projects_with_pagination($limit, $offset)
    {

        $projects = array();

        try
        {
            $projects = Airavata::getAllUserProjectsWithPagination( Session::get("gateway_id"),
                Session::get("username"), $limit, $offset);
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
                Utilities::print_info_message('<p>You have not created any projects yet, so no results will be returned!</p>
                                <p>Click <a href="create_project.php">here</a> to create a new project.</p>');
            }
            else
            {
                Utilities::print_error_message('There was a problem with Airavata. Please try again later, or report a bug using the link in the Help menu.');
                //print_error_message('AiravataSystemException!<br><br>' . $ase->airavataErrorType . ': ' . $ase->getMessage());
            }
        }
        catch (TTransportException $tte)
        {
            Utilities::print_error_message('TTransportException!<br><br>' . $tte->getMessage());
        }

        return $projects;
    }



    public static function get_projsearch_results_with_pagination( $searchKey, $searchValue, $limit, $offset)
    {

        $projects = array();

        try
        {
            switch ( $searchKey)
            {
                case 'project-name':
                    $projects = Airavata::searchProjectsByProjectNameWithPagination( Session::get("gateway_id"),
                        Session::get("username"), $searchValue, $limit, $offset);
                    break;
                case 'project-description':
                    $projects = $airavataclient->searchProjectsByProjectDescWithPagination( Session::get("gateway_id"),
                        Session::get("username"), $searchValue, $limit, $offset);
                    break;
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
                Utilities::print_info_message('<p>You have not created any projects yet, so no results will be returned!</p>
                                <p>Click <a href="create_project.php">here</a> to create a new project.</p>');
            }
            else
            {
                Utilities::print_error_message('There was a problem with Airavata. Please try again later, or report a bug using the link in the Help menu.');
                //print_error_message('AiravataSystemException!<br><br>' . $ase->airavataErrorType . ': ' . $ase->getMessage());
            }
        }
        catch (TTransportException $tte)
        {
            Utilities::print_error_message('TTransportException!<br><br>' . $tte->getMessage());
        }

        return $projects;
    }


public static function get_projsearch_results( $searchKey, $searchValue)
{

    $projects = array();

    try
    {
        switch ( $searchKey)
        {
            case 'project-name':
                $projects = Airavata::searchProjectsByProjectName( Session::get("gateway_id"), Session::get("username"), $searchValue);
                break;
            case 'project-description':
                $projects = $airavataclient->searchProjectsByProjectDesc( Session::get("gateway_id"), Session::get("username"), $searchValue);
                break;
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
            Utilities::print_info_message('<p>You have not created any projects yet, so no results will be returned!</p>
                                <p>Click <a href="create_project.php">here</a> to create a new project.</p>');
        }
        else
        {
            Utilities::print_error_message('There was a problem with Airavata. Please try again later, or report a bug using the link in the Help menu.');
            //print_error_message('AiravataSystemException!<br><br>' . $ase->airavataErrorType . ': ' . $ase->getMessage());
        }
    }
    catch (TTransportException $tte)
    {
        Utilities::print_error_message('TTransportException!<br><br>' . $tte->getMessage());
    }

    return $projects;
}


/**
 * Create options for the search key select input
 * @param $values
 * @param $labels
 * @param $disabled
 */
public static function create_options($values, $labels, $disabled)
{
    for ($i = 0; $i < sizeof($values); $i++)
    {
        $selected = '';

        // if option was previously selected, mark it as selected
        if (isset($_POST['search-key']))
        {
            if ($values[$i] == $_POST['search-key'])
            {
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
    public static function get_expsearch_results_with_pagination( $inputs, $limit, $offset)
    {
        $experiments = array();

        try
        {
            $filters = array();
            if($inputs["status-type"] != "ALL"){
                $filters[\Airavata\Model\Workspace\Experiment\ExperimentSearchFields::STATUS] = $inputs["status-type"];
            }
            switch ( $inputs["search-key"])
            {
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
                    $filters[\Airavata\Model\Workspace\Experiment\ExperimentSearchFields::FROM_DATE] = strtotime( $inputs["from-date"])*1000;
                    $filters[\Airavata\Model\Workspace\Experiment\ExperimentSearchFields::TO_DATE] = strtotime( $inputs["to-date"])*1000;
                    break;
                case '':
            }
            $experiments = Airavata::searchExperiments(
                Session::get('gateway_id'), Session::get('username'), $filters, $limit, $offset);
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

    /**
     * Get results of the user's all experiments with pagination.
     * Results are ordered creation time DESC
     * @return array|null
     */
    public static function get_all_user_experiments_with_pagination($limit, $offset)
    {
        $experiments = array();

        try
        {
            $experiments = Airavata::getAllUserExperimentsWithPagination(
                Session::get('gateway_id'), Session::get('username'), $limit, $offset
            );
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
    if( isset( $input["userDN"]) )
    {
        $userConfigDataUpdated->generateCert = 1;
        $userConfigDataUpdated->userDN = $input["userDN"];
    }

    $experiment->userConfigurationData = $userConfigDataUpdated;

    $applicationInputs = Utilities::get_application_inputs($experiment->applicationId);

    $experimentInputs = $experiment->experimentInputs; // get current inputs
    //var_dump($experimentInputs);
    $experimentInputs = Utilities::process_inputs($applicationInputs, $experimentInputs); // get new inputs
    //var_dump($experimentInputs);

    if ($experimentInputs)
    {
        $experiment->experimentInputs = $experimentInputs;
        //var_dump($experiment);
        return $experiment;
    }
}

public static function get_job_details( $experimentId){
    return Airavata::getJobDetails( $experimentId);
}

public static function get_transfer_details( $experimentId){
    return Airavata::getDataTransferDetails( $experimentId);
}

public static function getQueueDatafromResourceId( $crId){
    $resourceObject = Airavata::getComputeResource( $crId);
    return $resourceObject->batchQueues;
}

}

?>
