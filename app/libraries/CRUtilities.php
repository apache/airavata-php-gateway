<?php


//Airavata classes - loaded from app/libraries/Airavata
//Compute Resource classes
use Airavata\Model\AppCatalog\ComputeResource\BatchQueue;
use Airavata\Model\AppCatalog\ComputeResource\ComputeResourceDescription;
use Airavata\Model\AppCatalog\ComputeResource\DataMovementProtocol;
use Airavata\Model\AppCatalog\ComputeResource\FileSystems;
use Airavata\Model\AppCatalog\ComputeResource\GridFTPDataMovement;
use Airavata\Model\AppCatalog\ComputeResource\JobManagerCommand;
use Airavata\Model\AppCatalog\ComputeResource\JobSubmissionProtocol;
use Airavata\Model\AppCatalog\ComputeResource\LOCALDataMovement;
use Airavata\Model\AppCatalog\ComputeResource\LOCALSubmission;
use Airavata\Model\AppCatalog\ComputeResource\MonitorMode;
use Airavata\Model\AppCatalog\ComputeResource\ResourceJobManager;
use Airavata\Model\AppCatalog\ComputeResource\ResourceJobManagerType;
use Airavata\Model\AppCatalog\ComputeResource\SCPDataMovement;
use Airavata\Model\AppCatalog\ComputeResource\SecurityProtocol;
use Airavata\Model\AppCatalog\ComputeResource\SSHJobSubmission;
use Airavata\Model\AppCatalog\ComputeResource\UnicoreDataMovement;
use Airavata\Model\AppCatalog\ComputeResource\UnicoreJobSubmission;
use Airavata\Model\AppCatalog\GatewayProfile\ComputeResourcePreference;
use Airavata\Model\AppCatalog\GatewayProfile\GatewayResourceProfile;

//Gateway Classes


class CRUtilities
{
    /**
     * Basic utility functions
     */

//define('ROOT_DIR', __DIR__);

    /**
     * Define configuration constants
     */
    public static function register_or_update_compute_resource($computeDescription, $update = false)
    {
        if ($update) {
            $computeResourceId = $computeDescription->computeResourceId;
            if (Config::get('pga_config.airavata')['enable-app-catalog-cache']) {
                if (Cache::has('CR-' . $computeResourceId)) {
                    Cache::forget('CR-' . $computeResourceId);
                }
            }

            if (Airavata::updateComputeResource($computeResourceId, $computeDescription)) {
                $computeResource = Airavata::getComputeResource($computeResourceId);
                return $computeResource;
            } else
                print_r("Something went wrong while updating!");
            exit;
        } else {
            /*
            $fileSystems = new FileSystems();
            foreach( $fileSystems as $fileSystem)
                $computeDescription["fileSystems"][$fileSystem] = "";
            */
            $cd = new ComputeResourceDescription($computeDescription);
            $computeResourceId = Airavata::registerComputeResource($cd);
        }

        $computeResource = Airavata::getComputeResource($computeResourceId);
        return $computeResource;

    }

    /*
     * Getting data for Compute resource inputs
    */

    public static function getEditCRData()
    {
        $files = new FileSystems();
        $jsp = new JobSubmissionProtocol();
        $rjmt = new ResourceJobManagerType();
        $sp = new SecurityProtocol();
        $dmp = new DataMovementProtocol();
        $jmc = new JobManagerCommand();
        $mm = new MonitorMode();
        return array(
            "fileSystemsObject" => $files,
            "fileSystems" => $files::$__names,
            "jobSubmissionProtocolsObject" => $jsp,
            "jobSubmissionProtocols" => $jsp::$__names,
            "resourceJobManagerTypesObject" => $rjmt,
            "resourceJobManagerTypes" => $rjmt::$__names,
            "securityProtocolsObject" => $sp,
            "securityProtocols" => $sp::$__names,
            "dataMovementProtocolsObject" => $dmp,
            "dataMovementProtocols" => $dmp::$__names,
            "jobManagerCommands" => $jmc::$__names,
            "monitorModes" => $mm::$__names
        );
    }


    public static function createQueueObject($queue)
    {
        $queueObject = new BatchQueue($queue);
        return $queueObject;
    }

    public static function deleteQueue($computeResourceId, $queueName)
    {
        Airavata::deleteBatchQueue($computeResourceId, $queueName);
    }


    /*
     * Creating Job Submission Interface.
    */

    public static function create_or_update_JSIObject($inputs, $update = false)
    {

        $computeResource = CRUtilities::get_compute_resource($inputs["crId"]);


        $jsiId = null;
        if (isset($inputs["jsiId"]))
            $jsiId = $inputs["jsiId"];

        if ($inputs["jobSubmissionProtocol"] == JobSubmissionProtocol::LOCAL) {

            //print_r( $jsiObject->resourceJobManager->resourceJobManagerId);
            $resourceManager = new ResourceJobManager(array(
                "resourceJobManagerType" => $inputs["resourceJobManagerType"],
                "pushMonitoringEndpoint" => $inputs["pushMonitoringEndpoint"],
                "jobManagerBinPath" => $inputs["jobManagerBinPath"],
                "jobManagerCommands" => $inputs["jobManagerCommands"]
            ));

            //$rmId = $jsiObject->resourceJobManager->resourceJobManagerId;
            //$rm = $airavataclient->updateResourceJobManager($rmId, $resourceManager);
            //print_r( $rm); exit;
            $localJobSubmission = new LOCALSubmission(array(
                    "resourceJobManager" => $resourceManager
                )
            );

            if ($update) //update Local JSP
            {
                $jsiObject = Airavata::getLocalJobSubmission($jsiId);
                $localSub = Airavata::updateResourceJobManager($jsiObject->resourceJobManager->resourceJobManagerId, $resourceManager);
                //$localSub = $airavataclient->updateLocalSubmissionDetails( $jsiId, $localJobSubmission);
            } else // create Local JSP
            {
                $localSub = Airavata::addLocalSubmissionDetails($computeResource->computeResourceId, 0, $localJobSubmission);
                return $localSub;
            }

        } else if ($inputs["jobSubmissionProtocol"] == JobSubmissionProtocol::SSH) /* SSH */ {
            $resourceManager = new ResourceJobManager(array(
                "resourceJobManagerType" => $inputs["resourceJobManagerType"],
                "pushMonitoringEndpoint" => $inputs["pushMonitoringEndpoint"],
                "jobManagerBinPath" => $inputs["jobManagerBinPath"],
                "jobManagerCommands" => $inputs["jobManagerCommands"]
            ));
            $sshJobSubmission = new SSHJobSubmission(array
                (
                    "securityProtocol" => intval($inputs["securityProtocol"]),
                    "resourceJobManager" => $resourceManager,
                    "alternativeSSHHostName" => $inputs["alternativeSSHHostName"],
                    "sshPort" => intval($inputs["sshPort"]),
                    "monitorMode" => intval($inputs["monitorMode"])
                )
            );
            //var_dump( $sshJobSubmission); exit;
            if ($update) //update Local JSP
            {
                $jsiObject = Airavata::getSSHJobSubmission($jsiId);

                //first update resource job manager
                $rmjId = $jsiObject->resourceJobManager->resourceJobManagerId;
                Airavata::updateResourceJobManager($rmjId, $resourceManager);
                $jsiObject = Airavata::getSSHJobSubmission($jsiId);

                $jsiObject->securityProtocol = intval($inputs["securityProtocol"]);
                $jsiObject->alternativeSSHHostName = $inputs["alternativeSSHHostName"];
                $jsiObject->sshPort = intval($inputs["sshPort"]);
                $jsiObject->monitorMode = intval($inputs["monitorMode"]);
                $jsiObject->resourceJobManager = Airavata::getresourceJobManager($rmjId);
                //var_dump( $jsiObject); exit;
                //add updated resource job manager to ssh job submission object.
                //$sshJobSubmission->resourceJobManager->resourceJobManagerId = $rmjId;
                $localSub = Airavata::updateSSHJobSubmissionDetails($jsiId, $jsiObject);
            } else {
                $sshSub = Airavata::addSSHJobSubmissionDetails($computeResource->computeResourceId, 0, $sshJobSubmission);
            }
            return;
        } else if ($inputs["jobSubmissionProtocol"] == JobSubmissionProtocol::UNICORE) {
            $unicoreJobSubmission = new UnicoreJobSubmission(array
                (
                    "securityProtocol" => intval($inputs["securityProtocol"]),
                    "unicoreEndPointURL" => $inputs["unicoreEndPointURL"]
                )
            );
            if ($update) {
                $jsiObject = Airavata::getUnicoreJobSubmission($jsiId);
                $jsiObject->securityProtocol = intval($inputs["securityProtocol"]);
                $jsiObject->unicoreEndPointURL = $inputs["unicoreEndPointURL"];

                $unicoreSub = Airavata::updateUnicoreJobSubmissionDetails($jsiId, $jsiObject);
            } else {
                $unicoreSub = Airavata::addUNICOREJobSubmissionDetails($computeResource->computeResourceId, 0, $unicoreJobSubmission);
            }
        } else /* Globus does not work currently */ {
            print_r("Whoops! We haven't coded for this Job Submission Protocol yet. Still working on it. Please click <a href='" . URL::to('/') . "/cr/edit'>here</a> to go back to edit page for compute resource.");
        }
    }

    /*
     * Creating Data Movement Interface Object.
    */
    public static function create_or_update_DMIObject($inputs, $update = false)
    {

        $computeResource = CRUtilities::get_compute_resource($inputs["crId"]);
        if ($inputs["dataMovementProtocol"] == DataMovementProtocol::LOCAL) /* LOCAL */ {
            $localDataMovement = new LOCALDataMovement();
            $localdmp = Airavata::addLocalDataMovementDetails($computeResource->computeResourceId, 0, $localDataMovement);

            if ($localdmp)
                print_r("The Local Data Movement has been added. Edit UI for the Local Data Movement Interface is yet to be made.
                Please click <a href='" . URL::to('/') . "/cr/edit'>here</a> to go back to edit page for compute resource.");
        } else if ($inputs["dataMovementProtocol"] == DataMovementProtocol::SCP) /* SCP */ {
            //var_dump( $inputs); exit;
            $scpDataMovement = new SCPDataMovement(array(
                    "securityProtocol" => intval($inputs["securityProtocol"]),
                    "alternativeSCPHostName" => $inputs["alternativeSSHHostName"],
                    "sshPort" => intval($inputs["sshPort"])
                )

            );

            if ($update)
                $scpdmp = Airavata::updateSCPDataMovementDetails($inputs["dmiId"], $scpDataMovement);
            else
                $scpdmp = Airavata::addSCPDataMovementDetails($computeResource->computeResourceId, 0, $scpDataMovement);
        } else if ($inputs["dataMovementProtocol"] == DataMovementProtocol::GridFTP) /* GridFTP */ {
            $gridFTPDataMovement = new GridFTPDataMovement(array(
                "securityProtocol" => $inputs["securityProtocol"],
                "gridFTPEndPoints" => $inputs["gridFTPEndPoints"]
            ));
            if ($update)
                $gridftpdmp = Airavata::updateGridFTPDataMovementDetails($inputs["dmiId"], $gridFTPDataMovement);
            else
                $gridftpdmp = Airavata::addGridFTPDataMovementDetails($computeResource->computeResourceId, 0, $gridFTPDataMovement);
        } else if ($inputs["dataMovementProtocol"] == DataMovementProtocol::UNICORE_STORAGE_SERVICE) /* Unicore Storage Service */ {
            $unicoreDataMovement = new UnicoreDataMovement(array
                (
                    "securityProtocol" => intval($inputs["securityProtocol"]),
                    "unicoreEndPointURL" => $inputs["unicoreEndPointURL"]
                )
            );
            if ($update)
                $unicoredmp = Airavata::updateUnicoreDataMovementDetails($inputs["dmiId"], $unicoreDataMovement);
            else
                $unicoredmp = Airavata::addUnicoreDataMovementDetails($computeResource->computeResourceId, 0, $unicoreDataMovement);
        } else /* other data movement protocols */ {
            print_r("Whoops! We haven't coded for this Data Movement Protocol yet. Still working on it. Please click <a href='" . URL::to('/') . "/cr/edit'>here</a> to go back to edit page for compute resource.");
        }
    }

    public static function getAllCRObjects($onlyName = false)
    {
        $crNames = Airavata::getAllComputeResourceNames();
        if ($onlyName)
            return $crNames;
        else {
            $crObjects = array();
            foreach ($crNames as $id => $crName) {
                $crObjects[] = Airavata::getComputeResource($id);
            }
            return $crObjects;
        }

    }

    public static function getBrowseCRData()
    {
        $appDeployments = Airavata::getAllApplicationDeployments(Session::get("gateway_id"));

        return array('crObjects' => CRUtilities::getAllCRObjects(true),
            'appDeployments' => $appDeployments
        );
    }

    public static function getJobSubmissionDetails($jobSubmissionInterfaceId, $jsp)
    {
        //jsp = job submission protocol type
        if ($jsp == JobSubmissionProtocol::LOCAL)
            return Airavata::getLocalJobSubmission($jobSubmissionInterfaceId);
        else if ($jsp == JobSubmissionProtocol::SSH)
            return Airavata::getSSHJobSubmission($jobSubmissionInterfaceId);
        else if ($jsp == JobSubmissionProtocol::UNICORE)
            return Airavata::getUnicoreJobSubmission($jobSubmissionInterfaceId);
        else if ($jsp == JobSubmissionProtocol::CLOUD)
            return Airavata::getCloudJobSubmission($jobSubmissionInterfaceId);

        //globus get function not present ??
    }

    public static function getDataMovementDetails($dataMovementInterfaceId, $dmi)
    {
        //jsp = job submission protocol type
        if ($dmi == DataMovementProtocol::LOCAL)
            return Airavata::getLocalDataMovement($dataMovementInterfaceId);
        else if ($dmi == DataMovementProtocol::SCP)
            return Airavata::getSCPDataMovement($dataMovementInterfaceId);
        else if ($dmi == DataMovementProtocol::GridFTP)
            return Airavata::getGridFTPDataMovement($dataMovementInterfaceId);
        else if ($dmi == DataMovementProtocol::UNICORE_STORAGE_SERVICE)
            return Airavata::getUnicoreDataMovement($dataMovementInterfaceId);
        /*
        else if( $dmi == JobSubmissionProtocol::CLOUD)
            return $airavataclient->getCloudJobSubmission( $dataMovementInterfaceId);
        */

        //globus get function not present ??
    }

    public static function deleteActions($inputs)
    {
        if (isset($inputs["jsiId"]))
            if (Airavata::deleteJobSubmissionInterface($inputs["crId"], $inputs["jsiId"]))
                return 1;
            else
                return 0;
        else if (isset($inputs["dmiId"]))
            if (Airavata::deleteDataMovementInterface($inputs["crId"], $inputs["dmiId"]))
                return 1;
            else
                return 0;
        elseif (isset($inputs["del-crId"]))
            if (Airavata::deleteComputeResource($inputs["del-crId"]))
                return 1;
            else
                return 0;
    }

    public static function create_or_update_gateway_profile($inputs, $update = false)
    {

        $computeResourcePreferences = array();
        if (isset($input["crPreferences"]))
            $computeResourcePreferences = $input["crPreferences"];

        $gatewayProfile = new GatewayResourceProfile(array(
                "gatewayName" => $inputs["gatewayName"],
                "gatewayDescription" => $inputs["gatewayDescription"],
                "computeResourcePreferences" => $computeResourcePreferences
            )
        );

        if ($update) {
            $gatewayProfile = new GatewayResourceProfile(array(
                    "gatewayName" => $inputs["gatewayName"],
                    "gatewayDescription" => $inputs["gatewayDescription"]
                )
            );
            $gatewayProfileId = Airavata::updateGatewayResourceProfile($inputs["edit-gpId"], $gatewayProfile);
        } else
            $gatewayProfileId = Airavata::registerGatewayResourceProfile($gatewayProfile);
    }

    public static function getAllGatewayProfilesData()
    {

        if (Session::has("scigap_admin"))
            $gateways = Airavata::getAllGateways();
        else {
            $gateways[0] = Airavata::getGateway(Session::get("gateway_id"));
        }

        $gatewayProfiles = Airavata::getAllGatewayComputeResources();
        //$gatewayProfileIds = array("GatewayTest3_57726e98-313f-4e7c-87a5-18e69928afb5", "GatewayTest4_4fd9fb28-4ced-4149-bdbd-1f276077dad8");
        foreach ($gateways as $key => $gw) {
            $gateways[$key]->profile = array();
            foreach ((array)$gatewayProfiles as $index => $gp) {

                if ($gw->gatewayId == $gp->gatewayID) {
                    foreach ((array)$gp->computeResourcePreferences as $i => $crp) {
                        $gatewayProfiles[$index]->computeResourcePreferences[$i]->crDetails = Airavata::getComputeResource($crp->computeResourceId);
                    }
                    $gateways[$key]->profile = $gatewayProfiles[$index];
                }
            }
        }
        //var_dump( $gatewayProfiles[0]->computeResourcePreferences[0]->crDetails); exit;

        return $gateways;
    }

    public static function add_or_update_CRP($inputs)
    {
        $computeResourcePreferences = new computeResourcePreference($inputs);

        //var_dump( $inputs); exit;
        return Airavata::addGatewayComputeResourcePreference($inputs["gatewayId"], $inputs["computeResourceId"], $computeResourcePreferences);

    }

    public static function deleteGP($gpId)
    {
        return Airavata::deleteGatewayResourceProfile($gpId);
    }

    public static function deleteCR($inputs)
    {
        if (Config::get('pga_config.airavata')['enable-app-catalog-cache']) {
            $id = $inputs["rem-crId"];
            if (Cache::has('CR-' . $id)) {
                Cache::forget('CR-' . $id);
            }
        }
        return Airavata::deleteGatewayComputeResourcePreference($inputs["gpId"], $inputs["rem-crId"]);
    }

    /**
     * Get the ComputeResourceDescription with the given ID
     * @param $id
     * @return null
     */
    public static function get_compute_resource($id)
    {
        $computeResource = null;

        try {
            if (Config::get('pga_config.airavata')['enable-app-catalog-cache']) {
                if (Cache::has('CR-' . $id)) {
                    return Cache::get('CR-' . $id);
                } else {
                    $computeResource = Airavata::getComputeResource($id);
                    Cache::put('CR-' . $id, $computeResource, Config::get('pga_config.airavata')['app-catalog-cache-duration']);
                    return $computeResource;
                }
            } else {
                return $computeResource = Airavata::getComputeResource($id);
            }

        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('<p>There was a problem getting the compute resource.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('<p>There was a problem getting the compute resource.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Airavata Client Exception: ' . $ace->getMessage() . '</p>');
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('<p>There was a problem getting the compute resource.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Airavata System Exception: ' . $ase->getMessage() . '</p>');
        }
    }


    /**
     * Create a select input and populate it with compute resources
     * available for the given application ID
     * @param $applicationId
     * @param $resourceHostId
     */
    public static function create_compute_resources_select($applicationId, $resourceHostId)
    {
        return CRUtilities::get_available_app_interface_compute_resources($applicationId);
    }

    /**
     * Get a list of compute resources available for the given application ID
     * @param $id
     * @return null
     */
    public static function get_available_app_interface_compute_resources($id)
    {
        $computeResources = null;

        try {
            $computeResources = Airavata::getAvailableAppInterfaceComputeResources($id);
        } catch (InvalidRequestException $ire) {
            CommonUtilities::print_error_message('<p>There was a problem getting compute resources.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>InvalidRequestException: ' . $ire->getMessage() . '</p>');
        } catch (AiravataClientException $ace) {
            CommonUtilities::print_error_message('<p>There was a problem getting compute resources.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Airavata Client Exception: ' . $ace->getMessage() . '</p>');
        } catch (AiravataSystemException $ase) {
            CommonUtilities::print_error_message('<p>There was a problem getting compute resources.
            Please try again later or submit a bug report using the link in the Help menu.</p>' .
                '<p>Airavata System Exception: ' . $ase->getMessage() . '</p>');
        }

        return $computeResources;
    }

}

?>