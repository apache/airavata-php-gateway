<?php

class StorageresourceController extends BaseController
{

    /**
     *    Instantiate a new Compute Resource Controller Instance
     **/

    public function __construct()
    {
        $this->beforeFilter('verifyadmin');
        Session::put("nav-active", "storage-resource");

    }

    public function createView()
    {
        $this->beforeFilter('verifyeditadmin');
        Session::put("admin-nav", "sr-create");
        $data = SRUtilities::getEditSRData();
        return View::make("storage-resource/create", $data);
    }

    public function createSubmit()
    {
        $this->beforeFilter('verifyeditadmin');
        //Compute resource is by default enabled
        $storageDescription = array(
            "hostName" => trim(Input::get("hostname")),
            "storageResourceDescription" => trim(Input::get("hostname")),
            "enabled" => true
        );
        $storageResource = SRUtilities::register_or_update_storage_resource($storageDescription);

        return Redirect::to("sr/edit?srId=" . $storageResource->storageResourceId);
    }

    public function editView()
    {
        $this->beforeFilter('verifyeditadmin');
        $data = SRUtilities::getEditSRData();
        $storageResourceId = "";
        if (Input::has("srId"))
            $storageResourceId = Input::get("srId");
        else if (Session::has("storageResource")) {
            $storageResource = Session::get("storageResource");
            $storageResourceId = $storageResource->storageResourceId;
        }
        if ($storageResourceId != "") {
            $storageResource = SRUtilities::get_storage_resource($storageResourceId);
            $dataMovementInterfaces = array();
            $addedDMI = array();
            //var_dump( CRUtilities::getJobSubmissionDetails( $data["computeResource"]->jobSubmissionInterfaces[0]->jobSubmissionInterfaceId, 1) ); exit;
            if (count($storageResource->dataMovementInterfaces)) {
                foreach ($storageResource->dataMovementInterfaces as $DMI) {
                    $dataMovementInterfaces[] = SRUtilities::getDataMovementDetails($DMI->dataMovementInterfaceId, $DMI->dataMovementProtocol);
                    $addedDMI[] = $DMI->dataMovementProtocol;
                }
            }

            $data["storageResource"] = $storageResource;
            $data["dataMovementInterfaces"] = $dataMovementInterfaces;
            $data["addedDMI"] = $addedDMI;
            return View::make("resource/edit", $data);
        } else{
            Session::put("message", "Unable to retrieve this Storage Resource. Please try again later or submit a bug report using the link in the Help menu.");
            return View::make("storage-resource/browse");
        }

    }

    public function editSubmit()
    {
        $this->beforeFilter('verifyeditadmin');
        $tabName = "";
        if (Input::get("sr-edit") == "resDesc") /* Modify compute Resource description */ {
            $storageResourceDescription = SRUtilities::get_storage_resource(Input::get("srId"));
            $storageResourceDescription->hostName = trim(Input::get("hostname"));
            $storageResourceDescription->resourceDescription = Input::get("description");
            //var_dump( $computeDescription); exit;

            $storageResource = SRUtilities::register_or_update_compute_resource($storageResourceDescription, true);

            $tabName = "#tab-desc";
        }
        /*
        if (Input::get("sr-edit") == "queue"){
            $queue = array("queueName" => Input::get("qname"),
                "queueDescription" => Input::get("qdesc"),
                "maxRunTime" => Input::get("qmaxruntime"),
                "maxNodes" => Input::get("qmaxnodes"),
                "maxProcessors" => Input::get("qmaxprocessors"),
                "maxJobsInQueue" => Input::get("qmaxjobsinqueue"),
                "maxMemory" => Input::get("qmaxmemoryinqueue")
            );

            $storageResourceDescription = SRUtilities::get_storage_resource(Input::get("crId"));
            $storageResourceDescription->batchQueues[] = CRUtilities::createQueueObject($queue);
            $computeResource = CRUtilities::register_or_update_compute_resource($computeDescription, true);
            //var_dump( $computeResource); exit;
            $tabName = "#tab-queues";
        } else if (Input::get("cr-edit") == "delete-queue") {
            CRUtilities::deleteQueue(Input::get("crId"), Input::get("queueName"));
            $tabName = "#tab-queues";
        } else if (Input::get("cr-edit") == "fileSystems") {
            $computeDescription = CRUtilities::get_compute_resource(Input::get("crId"));
            $computeDescription->fileSystems = array_filter(Input::get("fileSystems"), "trim");
            $computeResource = CRUtilities::register_or_update_compute_resource($computeDescription, true);

            $tabName = "#tab-filesystem";
        } else if (Input::get("cr-edit") == "jsp" || Input::get("cr-edit") == "edit-jsp")  {
            $update = false;
            if (Input::get("cr-edit") == "edit-jsp")
                $update = true;

            $jobSubmissionInterface = CRUtilities::create_or_update_JSIObject(Input::all(), $update);

            $tabName = "#tab-jobSubmission";
        } else if (Input::get("cr-edit") == "jsi-priority") {
            $inputs = Input::all();
            $computeDescription = CRUtilities::get_compute_resource(Input::get("crId"));
            foreach ($computeDescription->jobSubmissionInterfaces as $index => $jsi) {
                foreach ($inputs["jsi-id"] as $idIndex => $jsiId) {
                    if ($jsiId == $jsi->jobSubmissionInterfaceId) {
                        $computeDescription->jobSubmissionInterfaces[$index]->priorityOrder = $inputs["jsi-priority"][$idIndex];
                        break;
                    }
                }
            }
            $computeResource = CRUtilities::register_or_update_compute_resource($computeDescription, true);

            return 1; //currently done by ajax.
        } else
        */
        if (Input::get("cr-edit") == "dmp" || Input::get("cr-edit") == "edit-dmi") /* Add / Modify a Data Movement Interface */ {
            $update = false;
            if (Input::get("cr-edit") == "edit-dmi")
                $update = true;
            $dataMovementInterface = SRUtilities::create_or_update_DMIObject(Input::all(), $update);

            $tabName = "#tab-dataMovement";
        } else if (Input::get("cr-edit") == "dmi-priority") {
            $inputs = Input::all();
            $storageDescription = CRUtilities::get_storage_resource(Input::get("srId"));
            foreach ($storageDescription->dataMovementInterfaces as $index => $dmi) {
                foreach ($inputs["dmi-id"] as $idIndex => $dmiId) {
                    if ($dmiId == $dmi->dataMovementInterfaceId) {
                        $storageDescription->dataMovementInterfaces[$index]->priorityOrder = $inputs["dmi-priority"][$idIndex];
                        break;
                    }
                }
            }
            $storageResource = CRUtilities::register_or_update_storage_resource($storageDescription, true);

            return 1; //currently done by ajax.
        }

        return Redirect::to("se/edit?srId=" . Input::get("srId") . $tabName);
    }

    public function viewView()
    {
        $data = CRUtilities::getEditCRData();
        $computeResourceId = "";
        if (Input::has("crId"))
            $computeResourceId = Input::get("crId");
        else if (Session::has("computeResource")) {
            $computeResource = Session::get("computeResource");
            $computeResourceId = $computeResource->computeResourceId;
        }

        if ($computeResourceId != "") {
            $computeResource = CRUtilities::get_compute_resource($computeResourceId);
            $jobSubmissionInterfaces = array();
            $dataMovementInterfaces = array();
            $addedJSP = array();
            $addedDMI = array();
            //var_dump( $computeResource->jobSubmissionInterfaces); exit;
            if (count($computeResource->jobSubmissionInterfaces)) {
                foreach ($computeResource->jobSubmissionInterfaces as $JSI) {
                    $jobSubmissionInterfaces[] = CRUtilities::getJobSubmissionDetails($JSI->jobSubmissionInterfaceId, $JSI->jobSubmissionProtocol);
                    $addedJSP[] = $JSI->jobSubmissionProtocol;
                }
            }
            //var_dump( CRUtilities::getJobSubmissionDetails( $data["computeResource"]->jobSubmissionInterfaces[0]->jobSubmissionInterfaceId, 1) ); exit;
            if (count($computeResource->dataMovementInterfaces)) {
                foreach ($computeResource->dataMovementInterfaces as $DMI) {
                    $dataMovementInterfaces[] = CRUtilities::getDataMovementDetails($DMI->dataMovementInterfaceId, $DMI->dataMovementProtocol);
                    $addedDMI[] = $DMI->dataMovementProtocol;
                }
            }

            $data["computeResource"] = $computeResource;
            $data["jobSubmissionInterfaces"] = $jobSubmissionInterfaces;
            $data["dataMovementInterfaces"] = $dataMovementInterfaces;
            $data["addedJSP"] = $addedJSP;
            $data["addedDMI"] = $addedDMI;
            //var_dump($data["jobSubmissionInterfaces"]); exit;
            return View::make("resource/view", $data);
        } else
            return View::make("resource/browse")->with("login-alert", "Unable to retrieve this Compute Resource. Please report this error to devs.");

    }

    public function deleteActions()
    {
        $this->beforeFilter('verifyeditadmin');
        $result = SRUtilities::deleteActions(Input::all());
        /*
        if (Input::has("jsiId")) {
            return Redirect::to("cr/edit?crId=" . Input::get("crId") . "#tab-jobSubmission")
                ->with("message", "Job Submission Interface was deleted successfully");
        }
        */
        if (Input::has("dmiId")) {
            return Redirect::to("sr/edit?crId=" . Input::get("crId") . "#tab-dataMovement")
                ->with("message", "Data Movement Protocol was deleted successfully");
        } elseif (Input::has("del-srId")) {
            return Redirect::to("sr/browse")->with("message", "The Compute Resource has been successfully deleted.");
        } else
            return $result;
    }

    public function browseView()
    {
        $data = SRUtilities::getBrowseSRData(false);
        $allSRs = $data["srObjects"];

        Session::put("admin-nav", "cr-browse");
        return View::make("storage-resource/browse", array(
            "allSRs" => $allSRs
        ));

    }
}

?>