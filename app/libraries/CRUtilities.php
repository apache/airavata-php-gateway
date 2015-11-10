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

            if (Airavata::updateComputeResource(Session::get('authz-token'), $computeResourceId, $computeDescription)) {
                $computeResource = Airavata::getComputeResource(Session::get('authz-token'), $computeResourceId);
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
            $computeResourceId = Airavata::registerComputeResource(Session::get('authz-token'), $cd);
        }

        $computeResource = Airavata::getComputeResource(Session::get('authz-token'), $computeResourceId);
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
        if (Config::get('pga_config.airavata')['enable-app-catalog-cache']) {
            if (Cache::has('CR-' . $computeResourceId)) {
                Cache::forget('CR-' . $computeResourceId);
            }
        }
        Airavata::deleteBatchQueue(Session::get('authz-token'), $computeResourceId, $queueName);
    }


    /*
     * Creating Job Submission Interface.
    */

    public static function create_or_update_JSIObject($inputs, $update = false)
    {

        $computeResource = CRUtilities::get_compute_resource($inputs["crId"]);

        if (Config::get('pga_config.airavata')['enable-app-catalog-cache']) {
            if (Cache::has('CR-' . $inputs["crId"])) {
                Cache::forget('CR-' . $inputs["crId"]);
            }
        }

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
                $jsiObject = Airavata::getLocalJobSubmission(Session::get('authz-token'), $jsiId);
                $localSub = Airavata::updateResourceJobManager(Session::get('authz-token'), $jsiObject->resourceJobManager->resourceJobManagerId, $resourceManager);
                //$localSub = $airavataclient->updateLocalSubmissionDetails( $jsiId, $localJobSubmission);
            } else // create Local JSP
            {
                $localSub = Airavata::addLocalSubmissionDetails(Session::get('authz-token'), $computeResource->computeResourceId, 0, $localJobSubmission);
                return $localSub;
            }

        } else if ($inputs["jobSubmissionProtocol"] == JobSubmissionProtocol::SSH) {
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
                    "monitorMode" => MonitorMode::JOB_EMAIL_NOTIFICATION_MONITOR
                )
            );
            //var_dump( $sshJobSubmission); exit;
            if ($update) //update Local JSP
            {
                $jsiObject = Airavata::getSSHJobSubmission(Session::get('authz-token'), $jsiId);

                //first update resource job manager
                $rmjId = $jsiObject->resourceJobManager->resourceJobManagerId;
                Airavata::updateResourceJobManager(Session::get('authz-token'), $rmjId, $resourceManager);
                $jsiObject = Airavata::getSSHJobSubmission(Session::get('authz-token'), $jsiId);

                $jsiObject->securityProtocol = intval($inputs["securityProtocol"]);
                $jsiObject->alternativeSSHHostName = $inputs["alternativeSSHHostName"];
                $jsiObject->sshPort = intval($inputs["sshPort"]);
                $jsiObject->monitorMode = intval($inputs["monitorMode"]);
                $jsiObject->resourceJobManager = Airavata::getresourceJobManager(Session::get('authz-token'), $rmjId);
                //var_dump( $jsiObject); exit;
                //add updated resource job manager to ssh job submission object.
                //$sshJobSubmission->resourceJobManager->resourceJobManagerId = $rmjId;
                $localSub = Airavata::updateSSHJobSubmissionDetails(Session::get('authz-token'), $jsiId, $jsiObject);
            } else {
                $sshSub = Airavata::addSSHJobSubmissionDetails(Session::get('authz-token'), $computeResource->computeResourceId, 0, $sshJobSubmission);
            }
            return;
        } else if ($inputs["jobSubmissionProtocol"] == JobSubmissionProtocol::SSH_FORK) {
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
                    "monitorMode" => MonitorMode::FORK
                )
            );
            //var_dump( $sshJobSubmission); exit;
            if ($update) //update Local JSP
            {
                $jsiObject = Airavata::getSSHJobSubmission(Session::get('authz-token'), $jsiId);

                //first update resource job manager
                $rmjId = $jsiObject->resourceJobManager->resourceJobManagerId;
                Airavata::updateResourceJobManager(Session::get('authz-token'), $rmjId, $resourceManager);
                $jsiObject = Airavata::getSSHJobSubmission(Session::get('authz-token'), $jsiId);

                $jsiObject->securityProtocol = intval($inputs["securityProtocol"]);
                $jsiObject->alternativeSSHHostName = $inputs["alternativeSSHHostName"];
                $jsiObject->sshPort = intval($inputs["sshPort"]);
                $jsiObject->monitorMode = intval($inputs["monitorMode"]);
                $jsiObject->resourceJobManager = Airavata::getresourceJobManager(Session::get('authz-token'), $rmjId);
                //var_dump( $jsiObject); exit;
                //add updated resource job manager to ssh job submission object.
                //$sshJobSubmission->resourceJobManager->resourceJobManagerId = $rmjId;
                $localSub = Airavata::updateSSHJobSubmissionDetails(Session::get('authz-token'), $jsiId, $jsiObject);
            } else {
                $sshSub = Airavata::addSSHForkJobSubmissionDetails(Session::get('authz-token'), $computeResource->computeResourceId, 0, $sshJobSubmission);
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
                $jsiObject = Airavata::getUnicoreJobSubmission(Session::get('authz-token'), $jsiId);
                $jsiObject->securityProtocol = intval($inputs["securityProtocol"]);
                $jsiObject->unicoreEndPointURL = $inputs["unicoreEndPointURL"];

                $unicoreSub = Airavata::updateUnicoreJobSubmissionDetails(Session::get('authz-token'), $jsiId, $jsiObject);
            } else {
                $unicoreSub = Airavata::addUNICOREJobSubmissionDetails(Session::get('authz-token'), $computeResource->computeResourceId, 0, $unicoreJobSubmission);
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

        if (Config::get('pga_config.airavata')['enable-app-catalog-cache']) {
            if (Cache::has('CR-' . $inputs["crId"])) {
                Cache::forget('CR-' . $inputs["crId"]);
            }
        }

        if ($inputs["dataMovementProtocol"] == DataMovementProtocol::LOCAL) /* LOCAL */ {
            $localDataMovement = new LOCALDataMovement();
            $localdmp = Airavata::addLocalDataMovementDetails(Session::get('authz-token'), $computeResource->computeResourceId, 0, $localDataMovement);

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
                $scpdmp = Airavata::updateSCPDataMovementDetails(Session::get('authz-token'), $inputs["dmiId"], $scpDataMovement);
            else
                $scpdmp = Airavata::addSCPDataMovementDetails(Session::get('authz-token'), $computeResource->computeResourceId, 0, $scpDataMovement);
        } else if ($inputs["dataMovementProtocol"] == DataMovementProtocol::GridFTP) /* GridFTP */ {
            $gridFTPDataMovement = new GridFTPDataMovement(array(
                "securityProtocol" => $inputs["securityProtocol"],
                "gridFTPEndPoints" => $inputs["gridFTPEndPoints"]
            ));
            if ($update)
                $gridftpdmp = Airavata::updateGridFTPDataMovementDetails(Session::get('authz-token'), $inputs["dmiId"], $gridFTPDataMovement);
            else
                $gridftpdmp = Airavata::addGridFTPDataMovementDetails(Session::get('authz-token'), $computeResource->computeResourceId, 0, $gridFTPDataMovement);
        } else if ($inputs["dataMovementProtocol"] == DataMovementProtocol::UNICORE_STORAGE_SERVICE) /* Unicore Storage Service */ {
            $unicoreDataMovement = new UnicoreDataMovement(array
                (
                    "securityProtocol" => intval($inputs["securityProtocol"]),
                    "unicoreEndPointURL" => $inputs["unicoreEndPointURL"]
                )
            );
            if ($update)
                $unicoredmp = Airavata::updateUnicoreDataMovementDetails(Session::get('authz-token'), $inputs["dmiId"], $unicoreDataMovement);
            else
                $unicoredmp = Airavata::addUnicoreDataMovementDetails(Session::get('authz-token'), $computeResource->computeResourceId, 0, $unicoreDataMovement);
        } else /* other data movement protocols */ {
            print_r("Whoops! We haven't coded for this Data Movement Protocol yet. Still working on it. Please click <a href='" . URL::to('/') . "/cr/edit'>here</a> to go back to edit page for compute resource.");
        }
    }

    public static function getAllCRObjects($onlyName = false)
    {
        $crNames = Airavata::getAllComputeResourceNames(Session::get('authz-token'));
        if ($onlyName)
            return $crNames;
        else {
            $crObjects = array();
            foreach ($crNames as $id => $crName) {
                array_push($crObjects, Airavata::getComputeResource(Session::get('authz-token'), $id));
            }
            return $crObjects;
        }

    }

    public static function getBrowseCRData($onlyNames)
    {
        $appDeployments = Airavata::getAllApplicationDeployments(Session::get('authz-token'), Session::get("gateway_id"));

        return array('crObjects' => CRUtilities::getAllCRObjects($onlyNames),
            'appDeployments' => $appDeployments
        );
    }

    public static function getJobSubmissionDetails($jobSubmissionInterfaceId, $jsp)
    {
        //jsp = job submission protocol type
        if ($jsp == JobSubmissionProtocol::LOCAL)
            return Airavata::getLocalJobSubmission(Session::get('authz-token'), $jobSubmissionInterfaceId);
        else if ($jsp == JobSubmissionProtocol::SSH || $jsp == JobSubmissionProtocol::SSH_FORK)
            return Airavata::getSSHJobSubmission(Session::get('authz-token'), $jobSubmissionInterfaceId);
        else if ($jsp == JobSubmissionProtocol::UNICORE)
            return Airavata::getUnicoreJobSubmission(Session::get('authz-token'), $jobSubmissionInterfaceId);
        else if ($jsp == JobSubmissionProtocol::CLOUD)
            return Airavata::getCloudJobSubmission(Session::get('authz-token'), $jobSubmissionInterfaceId);

        //globus get function not present ??
    }

    public static function getDataMovementDetails($dataMovementInterfaceId, $dmi)
    {
        //jsp = job submission protocol type
        if ($dmi == DataMovementProtocol::LOCAL)
            return Airavata::getLocalDataMovement(Session::get('authz-token'), $dataMovementInterfaceId);
        else if ($dmi == DataMovementProtocol::SCP)
            return Airavata::getSCPDataMovement(Session::get('authz-token'), $dataMovementInterfaceId);
        else if ($dmi == DataMovementProtocol::GridFTP)
            return Airavata::getGridFTPDataMovement(Session::get('authz-token'), $dataMovementInterfaceId);
        else if ($dmi == DataMovementProtocol::UNICORE_STORAGE_SERVICE)
            return Airavata::getUnicoreDataMovement(Session::get('authz-token'), $dataMovementInterfaceId);
        /*
        else if( $dmi == JobSubmissionProtocol::CLOUD)
            return $airavataclient->getCloudJobSubmission( $dataMovementInterfaceId);
        */

        //globus get function not present ??
    }

    public static function deleteActions($inputs)
    {
        if (isset($inputs["crId"])) {
            if (Config::get('pga_config.airavata')['enable-app-catalog-cache']) {
                if (Cache::has('CR-' . $inputs["crId"])) {
                    Cache::forget('CR-' . $inputs["crId"]);
                }
            }
        } elseif (isset($inputs["del-crId"])) {
            if (Config::get('pga_config.airavata')['enable-app-catalog-cache']) {
                if (Cache::has('CR-' . $inputs["del-crId"])) {
                    Cache::forget('CR-' . $inputs["del-crId"]);
                }
            }
        }

        if (isset($inputs["jsiId"]))
            if (Airavata::deleteJobSubmissionInterface(Session::get('authz-token'), $inputs["crId"], $inputs["jsiId"]))
                return 1;
            else
                return 0;
        else if (isset($inputs["dmiId"]))
            if (Airavata::deleteDataMovementInterface(Session::get('authz-token'), $inputs["crId"], $inputs["dmiId"]))
                return 1;
            else
                return 0;
        elseif (isset($inputs["del-crId"]))
            if (Airavata::deleteComputeResource(Session::get('authz-token'), $inputs["del-crId"]))
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
            $gatewayProfileId = Airavata::updateGatewayResourceProfile(Session::get('authz-token'), $inputs["edit-gpId"], $gatewayProfile);
        } else
            $gatewayProfileId = Airavata::registerGatewayResourceProfile(Session::get('authz-token'), $gatewayProfile);
    }

    public static function getAllGatewayProfilesData()
    {

        if (Session::has("scigap_admin"))
            $gateways = Airavata::getAllGateways(Session::get('authz-token'));
        else {
            $gateways[0] = Airavata::getGateway(Session::get('authz-token'), Session::get("gateway_id"));
        }

        $gatewayProfiles = Airavata::getAllGatewayResourceProfiles(Session::get('authz-token'));
        //var_dump( $gatewayProfiles); exit;
        //$gatewayProfileIds = array("GatewayTest3_57726e98-313f-4e7c-87a5-18e69928afb5", "GatewayTest4_4fd9fb28-4ced-4149-bdbd-1f276077dad8");
        foreach ($gateways as $key => $gw) {
            $gateways[$key]->profile = array();
            foreach ((array)$gatewayProfiles as $index => $gp) {

                if ($gw->gatewayId == $gp->gatewayID) {
                    foreach ((array)$gp->computeResourcePreferences as $i => $crp) {
                        $gatewayProfiles[$index]->computeResourcePreferences[$i]->crDetails = Airavata::getComputeResource(Session::get('authz-token'), $crp->computeResourceId);
                    }
                    $gateways[$key]->profile = $gatewayProfiles[$index];
                }
            }
        }
        //var_dump( $gatewayProfiles[0]->computeResourcePreferences[0]->crDetails); exit;

        return $gateways;
    }

    public static function updateGatewayProfile( $data){
        $gatewayResourceProfile = Airavata::getGatewayResourceProfile( Session::get('authz-token'), $data["gateway_id"]);
        $gatewayResourceProfile->credentialStoreToken = $data["cst"];
        return Airavata::updateGatewayResourceProfile( Session::get('authz-token'), $data["gateway_id"], $gatewayResourceProfile); 
    }

    public static function add_or_update_CRP($inputs)
    {
        $computeResourcePreferences = new computeResourcePreference($inputs);

        if (Config::get('pga_config.airavata')['enable-app-catalog-cache']) {
            if (Cache::has('CR-' . $inputs["computeResourceId"])) {
                Cache::forget('CR-' . $inputs["computeResourceId"]);
            }
        }

        //var_dump( $inputs); exit;
        return Airavata::addGatewayComputeResourcePreference(Session::get('authz-token'), $inputs["gatewayId"], $inputs["computeResourceId"], $computeResourcePreferences);

    }

    public static function deleteGP($gpId)
    {
        return Airavata::deleteGatewayResourceProfile(Session::get('authz-token'), $gpId);
    }

    public static function deleteCR($inputs)
    {
        if (Config::get('pga_config.airavata')['enable-app-catalog-cache']) {
            $id = $inputs["rem-crId"];
            if (Cache::has('CR-' . $id)) {
                Cache::forget('CR-' . $id);
            }
        }

        return Airavata::deleteGatewayComputeResourcePreference(Session::get('authz-token'), $inputs["gpId"], $inputs["rem-crId"]);
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
                    $computeResource = Airavata::getComputeResource(Session::get('authz-token'), $id);
                    Cache::put('CR-' . $id, $computeResource, Config::get('pga_config.airavata')['app-catalog-cache-duration']);
                    return $computeResource;
                }
            } else {
                return $computeResource = Airavata::getComputeResource(Session::get('authz-token'), $id);
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
            $computeResources = Airavata::getAvailableAppInterfaceComputeResources(Session::get('authz-token'), $id);
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